<?php

namespace App\Http\Controllers\Tutor;

use Carbon\Carbon;
use App\Models\Subject;
use App\Models\Schedule;
use App\Models\Classroom;
use App\Models\Subscription;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;

class TutorDashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        $subjects = Subject::all();
        $coloredDates = [];

        return view('tutor.dashboard', compact('subjects', 'coloredDates'));
    }

    public function show($slug)
    {
        $user = Auth::user();

        $selectedSubject = Subject::where('slug', $slug)->firstOrFail();

        $classrooms = Classroom::with(['subject', 'schedules.quizzes.attempts', 'schedules.attendances', 'schedules.replayViews'])
            ->where('user_id', $user->id)
            ->where('subject_id', $selectedSubject->id)
            ->get();

        $subjects = Subject::all();

        $coloredDates = [];
        $scheduleIds = [];

        foreach ($classrooms as $classroom) {
            foreach ($classroom->schedules as $schedule) {
                $date = Carbon::parse($schedule->time)->format('Y-m-d');
                $status = strtolower($schedule->status);

                if ($status === 'cancelled') $status = 'canceled';
                elseif ($status === 'upcoming') $status = 'scheduled';

                $coloredDates[$date] = match ($status) {
                    'live' => '#22c55e',
                    'scheduled' => '#13EFD9',
                    'completed' => '#f5f5f5',
                    'canceled' => '#B70000',
                    default => '#737373',
                };

                $scheduleIds[] = $schedule->id;
            }
        }

        $scheduleIds = array_unique($scheduleIds);

        $subscriptions = Subscription::with([
            'user',
            'schedule.quizzes.attempts',
            'schedule.attendances',
            'schedule.replayViews'
        ])
        ->whereIn('schedule_id', $scheduleIds)
        ->get()
        ->groupBy('user_id');

        $students = [];
        foreach ($subscriptions as $userId => $subs) {
            $userSub = $subs->first()->user;
            $userSchedules = $subs->pluck('schedule')->filter();
            $totalSchedules = $userSchedules->count();

            $allScores = $userSchedules->flatMap(fn($schedule) =>
                $schedule->quizzes->flatMap(fn($quiz) =>
                    $quiz->attempts->where('user_id', $userId)->pluck('score')
                )
            );

            $avgScore = $allScores->avg() ?? 0;
            $attendedCount = $userSchedules->sum(fn($schedule) => $schedule->attendances->where('user_id', $userId)->count());
            $replayCount = $userSchedules->sum(fn($schedule) => $schedule->replayViews->where('user_id', $userId)->count());

            $attendanceRate = $totalSchedules > 0 ? round(($attendedCount / $totalSchedules) * 100, 1) : 0;
            $replayRate = $totalSchedules > 0 ? round(($replayCount / $totalSchedules) * 100, 1) : 0;

            $students[] = [
                'user' => $userSub,
                'avgScore' => round($avgScore, 1),
                'attendanceRate' => $attendanceRate,
                'replayRate' => $replayRate,
            ];
        }

        $overview = [
            'avgScore' => $students ? round(collect($students)->avg('avgScore'), 1) : 0,
            'attendanceRate' => $students ? round(collect($students)->avg('attendanceRate'), 1) : 0,
            'replayRate' => $students ? round(collect($students)->avg('replayRate'), 1) : 0,
        ];

        $today = Carbon::now();
        $in7days = $today->copy()->addDays(7);

        $subsQuery = Subscription::whereIn('schedule_id', $scheduleIds);

        $allCount = (clone $subsQuery)->distinct('user_id')->count('user_id');
        $activeCount = (clone $subsQuery)->whereDate('end_date', '>', $in7days)->count();
        $expiringSoonCount = (clone $subsQuery)->whereDate('end_date', '>', $today)->whereDate('end_date', '<=', $in7days)->count();
        $expiredCount = (clone $subsQuery)->whereDate('end_date', '<=', $today)->count();

        return view('tutor.dashboard', compact(
            'subjects',
            'selectedSubject',
            'classrooms',
            'coloredDates',
            'allCount',
            'activeCount',
            'expiringSoonCount',
            'expiredCount',
            'overview'
        ));
    }

    public function getSchedules($slug, $date)
    {
        $user = Auth::user();

        $selectedSubject = Subject::where('slug', $slug)->firstOrFail();

        $schedules = Schedule::with(['classroom.subject'])
            ->whereDate('time', $date)
            ->whereHas('classroom', function ($q) use ($user, $selectedSubject) {
                $q->where('user_id', $user->id)
                ->where('subject_id', $selectedSubject->id);
            })
            ->get()
            ->map(fn($s) => [
                'class_name' => $s->classroom->name,
                'subject' => strtoupper($s->classroom->subject->name),
                'topic' => $s->topic ?? 'TBD',
                'time' => \Carbon\Carbon::parse($s->time)->format('d M · g:i A'),
                'status' => strtolower($s->status),
            ]);

        return response()->json(['schedules' => $schedules]);
    }

}
