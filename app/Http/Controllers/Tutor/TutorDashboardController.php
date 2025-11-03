<?php

namespace App\Http\Controllers\Tutor;

use Carbon\Carbon;
use App\Models\User;
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

        $classrooms = Classroom::with(['subject', 'schedules.quizzes.attempts', 'schedules.attendances'])
            ->where('user_id', $user->id)
            ->where('subject_id', $selectedSubject->id)
            ->get();

        $subjects = Subject::all();

        $coloredDates = [];
        $classroomIds = $classrooms->pluck('id')->toArray();
        $allSchedules = collect();

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
                
                $allSchedules->push($schedule);
            }
        }

        $scheduleIds = $allSchedules->pluck('id')->unique()->toArray();
        $replayVideoIds = $allSchedules->pluck('replay_video_id')->filter()->unique()->toArray();
        $quizIds = $allSchedules->flatMap(fn($s) => $s->quizzes->pluck('id'))->unique()->toArray();

        $subscriptions = Subscription::with([
            'user',
            'classroom'
        ])
        ->whereIn('classroom_id', $classroomIds)
        ->get()
        ->groupBy('user_id');

        $studentIds = $subscriptions->keys()->toArray();
        
        $studentsData = User::with([
            'replayViews' => fn($q) => $q->whereIn('replay_video_id', $replayVideoIds),
            'attendances' => fn($q) => $q->whereIn('schedule_id', $scheduleIds),
            'quizAttempts' => fn($q) => $q->whereIn('quiz_id', $quizIds),
        ])
        ->whereIn('id', $studentIds)
        ->get();

        $students = [];
        $totalSchedules = $allSchedules->count();
        
        foreach ($studentsData as $student) {
            $attendedCount = $student->attendances->count();
            $replayCount = $student->replayViews->count();

            $allScores = $student->quizAttempts->pluck('score');
            $avgScore = $allScores->avg() ?? 0;

            $attendanceRate = $totalSchedules > 0 ? round(($attendedCount / $totalSchedules) * 100, 1) : 0;
            $replayRate = $totalSchedules > 0 ? round(($replayCount / $totalSchedules) * 100, 1) : 0;

            $students[] = [
                'user' => $student,
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

        $subsQuery = Subscription::whereIn('classroom_id', $classroomIds);

        $allCount = $studentsData->count();
        $activeCount = (clone $subsQuery)->whereDate('end_date', '>', $today)->count();
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
            'overview',
            'students'
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
