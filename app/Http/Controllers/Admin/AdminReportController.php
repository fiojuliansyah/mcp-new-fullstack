<?php

namespace App\Http\Controllers\Admin;

use Carbon\Carbon;
use App\Models\User;
use App\Models\Schedule;
use App\Models\Attendance;
use App\Models\ReplayView;
use App\Models\QuizAttempt;
use App\Models\Subscription;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;

class AdminReportController extends Controller
{
    public function index()
    {
        $totalRevenue = Subscription::sum('subtotal') ?? 0;
        $todayRevenue = Subscription::whereDate('created_at', Carbon::today())->sum('subtotal');

        $avgQuizScore = QuizAttempt::avg('score') ?? 0;

        $totalSchedules = Schedule::count();
        $attendedSchedules = Schedule::whereHas('attendances', function ($q) {
            $q->whereIn('status', ['present', 'late']);
        })->count();
        $subjectProgress = $totalSchedules > 0 ? round(($attendedSchedules / $totalSchedules) * 100) : 0;

        $totalReplayVideos = DB::table('replay_videos')->count();
        $totalViews = ReplayView::count();
        $replayUsage = $totalReplayVideos > 0 ? round(($totalViews / $totalReplayVideos) * 100) : 0;

        $attendanceByClass = Schedule::with(['classroom', 'form'])
            ->select('classroom_id', 'form_id', DB::raw('COUNT(attendances.id) as total'))
            ->join('attendances', 'attendances.schedule_id', '=', 'schedules.id')
            ->whereIn('attendances.status', ['present', 'late'])
            ->groupBy('classroom_id', 'form_id')
            ->orderByDesc('total')
            ->get();

        $topClass = $attendanceByClass->first();
        $lowClass = $attendanceByClass->last();

        return view('admin.reports.index', [
            'totalRevenue' => $totalRevenue,
            'todayRevenue' => $todayRevenue,
            'avgQuizScore' => round($avgQuizScore),
            'subjectProgress' => $subjectProgress,
            'replayUsage' => $replayUsage,
            'topClass' => $topClass,
            'lowClass' => $lowClass,
        ]);
    }

    public function subscription()
    {
        $subscriptions = Subscription::orderBy('created_at', 'desc')->paginate(10);
        return view('admin.reports.subscription', compact('subscriptions'));
    }

    public function performance()
    {
        $students = User::where('account_type', 'student')
            ->with([
                'subscriptions.plan',
                'subscriptions.classrooms.subject',
                'subscriptions.classrooms.user',
                'subscriptions.classrooms.schedules.attendances',
                'subscriptions.classrooms.schedules.quizzes.attempts',
                'subscriptions.classrooms.schedules.replays.replayVideos.views',
                'subscriptions.classrooms.schedules.replays.replayVideos',
            ])
            ->paginate(10);

        $count = $students->total();

        return view('admin.reports.performance', compact('students', 'count'));
    }

    public function replay(Request $request)
    {
        $search = $request->input('search');

        $schedules = Schedule::with(['classroom', 'form', 'replays'])
            ->when($search, function ($query, $search) {
                $query->where('name', 'like', "%{$search}%")
                      ->orWhereHas('classroom', fn($q) => $q->where('name', 'like', "%{$search}%"));
            })
            ->latest()
            ->paginate(10)
            ->appends(['search' => $search]);

        return view('admin.reports.replay', compact('schedules', 'search'));
    }
}
