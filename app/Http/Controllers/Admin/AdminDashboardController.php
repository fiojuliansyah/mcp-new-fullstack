<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Schedule;
use App\Models\User;
use App\Models\Subscription;
use Carbon\Carbon;
use App\Models\QuizAttempt;
use App\Models\Attendance;
use App\Models\Subject;
use Illuminate\Support\Facades\DB;

class AdminDashboardController extends Controller
{
    public function index()
    {
        $timeThreshold = Carbon::now()->subHours(4);

        $schedules = Schedule::whereDate('time', Carbon::today())->paginate(5);
        $studentCount = User::where('account_type', 'student')->count();
        $parentCount = User::where('account_type', 'parent')->count();
        $tutorCount = User::where('account_type', 'tutor')->count();
        $subsCount = Subscription::where('status', 'active')->count();

        $pendingMaterialsSchedules = Schedule::where('time', '<', $timeThreshold)
            ->doesntHave('materials')
            ->with(['classroom', 'form'])
            ->take(5)
            ->get();

        $pendingReplaysSchedules = Schedule::where('time', '<', $timeThreshold)
            ->doesntHave('replays')
            ->with(['classroom', 'form'])
            ->take(5)
            ->get();

        $pendingUploads = collect();
        foreach ($pendingMaterialsSchedules as $schedule) {
            $pendingUploads->push([
                'schedule' => $schedule,
                'missing_type' => 'Material'
            ]);
        }
        foreach ($pendingReplaysSchedules as $schedule) {
            $pendingUploads->push([
                'schedule' => $schedule,
                'missing_type' => 'Replay'
            ]);
        }

        $avgQuizScore = QuizAttempt::avg('score') ?? 0;

        $subjectsPerformance = DB::table('quiz_attempts')
            ->join('quizzes', 'quiz_attempts.quiz_id', '=', 'quizzes.id')
            ->join('schedules', 'quizzes.schedule_id', '=', 'schedules.id')
            ->join('classrooms', 'schedules.classroom_id', '=', 'classrooms.id')
            ->join('subjects', 'classrooms.subject_id', '=', 'subjects.id')
            ->select('subjects.name as subject_name', DB::raw('AVG(quiz_attempts.score) as avg_score'))
            ->groupBy('subjects.name')
            ->get();

        $subjectsUnder60 = $subjectsPerformance->where('avg_score', '<', 60)->count();

        $lowAttendanceStudents = Attendance::where('status', 'absent')
            ->distinct('user_id')
            ->count();

        $performance = [
            'avgQuizScore' => round($avgQuizScore, 2),
            'subjectsUnder60' => $subjectsUnder60,
            'lowAttendanceStudents' => $lowAttendanceStudents,
            'subjectsPerformance' => $subjectsPerformance,
        ];

        return view('admin.dashboard', compact(
            'schedules',
            'studentCount',
            'parentCount',
            'tutorCount',
            'subsCount',
            'pendingUploads',
            'performance'
        ));
    }

}
