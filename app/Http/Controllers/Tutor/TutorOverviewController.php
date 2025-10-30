<?php

namespace App\Http\Controllers\Tutor;

use App\Models\Form;
use App\Models\User;
use App\Models\Subject;
use App\Models\Schedule;
use App\Models\Classroom;
use App\Models\Attendance;
use App\Models\ReplayView;
use App\Models\QuizAttempt;
use App\Models\Subscription;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Pagination\LengthAwarePaginator;

class TutorOverviewController extends Controller
{
    public function subscriptionIndex(Request $request, $slug)
    {
        $tutor = Auth::user();
        $tutorId = $tutor->id;

        $selectedSubject = Subject::where('slug', $slug)->firstOrFail();

        $classroomFilter = Classroom::where('user_id', $tutorId)
            ->where('subject_id', $selectedSubject->id)
            ->get();

        $classroomIds = $classroomFilter->pluck('id');

        $latestSubscriptionIds = Subscription::selectRaw('MAX(subscriptions.id) as id')
            ->whereIn('schedule_id', Schedule::whereIn('classroom_id', $classroomIds)->pluck('id'))
            ->groupBy('user_id')
            ->pluck('id');

        $query = Subscription::whereIn('id', $latestSubscriptionIds)
            ->with(['classrooms', 'plan', 'user', 'schedule.form'])
            ->when($request->filled('classroom_id'), function ($q) use ($request) {
                $q->whereHas('classrooms', function ($c) use ($request) {
                    $c->where('classrooms.id', $request->classroom_id);
                });
            })
            ->when($request->filled('form'), function ($q) use ($request) {
                $q->whereHas('schedule', function ($s) use ($request) {
                    $s->where('form_id', $request->form);
                });
            })
            ->when($request->filled('search'), function ($q) use ($request) {
                $q->whereHas('user', function ($u) use ($request) {
                    $u->where('name', 'like', '%' . $request->search . '%');
                });
            })
            ->latest();

        $subscriptions = $query->paginate(10)->withQueryString();

        $formIds = $subscriptions->pluck('schedule.form.id')->filter()->unique();
        $forms = Form::whereIn('id', $formIds)->get();

        return view('tutor.overviews.subscriptions.index', compact(
            'selectedSubject',
            'subscriptions',
            'classroomFilter',
            'forms',
            'tutor'
        ));
    }


    public function subscriptionShow(User $user)
    {
        $subscriptions = Subscription::with(['plan', 'schedule.form'])
            ->where('user_id', $user->id)
            ->latest()
            ->get();

        $latestSubscription = $subscriptions->first(); 

        return view('tutor.overviews.subscriptions.show', compact('user', 'subscriptions', 'latestSubscription'));
    }

    public function performanceIndex($slug, Request $request)
    {
        $selectedSubject = Subject::where('slug', $slug)->firstOrFail();
        $tutor = Auth::user();
        $forms = Form::all();

        $classrooms = Classroom::where('user_id', $tutor->id)
            ->where('subject_id', $selectedSubject->id)
            ->pluck('id');

        $schedules = Schedule::with('classroom')
            ->whereIn('classroom_id', $classrooms)
            ->get();

        if ($request->filled('form_id')) {
            $schedules = $schedules->filter(fn($s) => $s->form_id == $request->form_id);
        }
        if ($request->filled('classroom_id')) {
            $schedules = $schedules->filter(fn($s) => $s->classroom_id == $request->classroom_id);
        }

        $scheduleIds = $schedules->pluck('id');

        $subscriptions = Subscription::with([
            'user',
            'schedule.classroom',
            'schedule.quizzes.attempts' => fn($q) => $q->select('id', 'quiz_id', 'user_id', 'score'),
            'schedule.attendances' => fn($q) => $q->select('id', 'schedule_id', 'user_id'),
            'schedule.replayViews' => fn($q) => $q->select('id', 'schedule_id', 'user_id')
        ])
        ->whereIn('schedule_id', $scheduleIds)
        ->get()
        ->groupBy('user_id');

        $students = [];
        $search = strtolower($request->input('search', ''));
        $type = $request->input('type');

        foreach ($subscriptions as $userId => $subs) {
            $user = $subs->first()->user;
            if ($search && !str_contains(strtolower($user->name), $search)) continue;

            $userSchedules = $subs->pluck('schedule')->filter();
            if ($request->filled('form_id')) $userSchedules = $userSchedules->where('form_id', $request->form_id);
            if ($request->filled('classroom_id')) $userSchedules = $userSchedules->where('classroom_id', $request->classroom_id);
            if ($userSchedules->isEmpty()) continue;

            $allScores = $userSchedules->flatMap(fn($schedule) =>
                $schedule->quizzes->flatMap(fn($quiz) =>
                    $quiz->attempts->where('user_id', $userId)->pluck('score')
                )
            );

            $avgScore = $allScores->avg() ?? 0;
            $totalSchedules = $userSchedules->count();
            $attendedCount = $userSchedules->sum(fn($schedule) => $schedule->attendances->where('user_id', $userId)->count());
            $replayCount = $userSchedules->sum(fn($schedule) => $schedule->replayViews->where('user_id', $userId)->count());

            $attendanceRate = $totalSchedules > 0 ? round(($attendedCount / $totalSchedules) * 100, 1) : 0;
            $replayRate = $totalSchedules > 0 ? round(($replayCount / $totalSchedules) * 100, 1) : 0;

            $note = $avgScore < 70 ? 'Needs practice' : 'Good progress';
            if ($type === 'good' && $note !== 'Good progress') continue;
            if ($type === 'needs' && $note !== 'Needs practice') continue;

            $students[] = [
                'user' => $user,
                'avgScore' => round($avgScore, 1),
                'attendanceRate' => $attendanceRate,
                'replayRate' => $replayRate,
                'notes' => $note,
            ];
        }

        $studentsCollection = collect($students);

        $perPage = 10;
        $page = $request->input('page', 1);
        $paginatedStudents = new LengthAwarePaginator(
            $studentsCollection->forPage($page, $perPage),
            $studentsCollection->count(),
            $perPage,
            $page,
            ['path' => $request->url(), 'query' => $request->query()]
        );

        return view('tutor.overviews.peformances.index', compact(
            'selectedSubject',
            'tutor',
            'paginatedStudents',
            'forms',
            'schedules'
        ));
    }

    public function performanceShow(Request $request, $slug, User $user)
    {
        $selectedSubject = Subject::where('slug', $slug)->firstOrFail();
        $tutor = Auth::user();

        $classroomIds = Classroom::where('user_id', $tutor->id)
            ->where('subject_id', $selectedSubject->id)
            ->pluck('id');

        $scheduleIds = Schedule::whereIn('classroom_id', $classroomIds)->pluck('id');

        $subscriptions = Subscription::with([
            'schedule.form',
            'schedule.classroom',
            'schedule.quizzes.attempts' => function ($q) use ($user) {
                $q->where('user_id', $user->id)->select('id', 'quiz_id', 'user_id', 'score');
            },
            'schedule.attendances' => function ($q) use ($user) {
                $q->where('user_id', $user->id)->select('id', 'schedule_id', 'user_id');
            },
            'schedule.replayViews' => function ($q) use ($user) {
                $q->where('user_id', $user->id)->select('id', 'schedule_id', 'user_id');
            }
        ])
            ->where('user_id', $user->id)
            ->whereIn('schedule_id', $scheduleIds)
            ->get();

        $records = [];
        foreach ($subscriptions as $sub) {
            $schedule = $sub->schedule;
            if (!$schedule) continue;

            $avgScore = $schedule->quizzes->flatMap(function ($quiz) {
                return $quiz->attempts->pluck('score');
            })->avg() ?? 0;

            $records[] = [
                'topic' => $schedule->topic ?? '-',
                'quiz_score' => round($avgScore, 1),
                'attendance' => $schedule->attendances->isNotEmpty(),
                'replay_rate' => $schedule->replayViews->isNotEmpty() ? 100 : 0,
                'note' => $sub->note ?? null,
            ];
        }

        if ($request->filled('search')) {
            $search = strtolower($request->search);
            $records = array_filter($records, function ($r) use ($search) {
                return str_contains(strtolower($r['topic']), $search);
            });
        }

        $overview = [
            'avg_quiz' => collect($records)->avg('quiz_score') ?? 0,
            'attendance_rate' => (collect($records)->where('attendance', true)->count() / max(count($records), 1)) * 100,
            'replay_rate' => (collect($records)->where('replay_rate', '>', 0)->count() / max(count($records), 1)) * 100,
        ];

        $firstSchedule = $subscriptions->first()?->schedule;
        $formName = optional($firstSchedule?->form)->name;
        $groupName = optional($firstSchedule?->classroom)->name;

        return view('tutor.overviews.peformances.show', compact(
            'selectedSubject',
            'user',
            'records',
            'overview',
            'formName',
            'groupName'
        ));
    }

}
