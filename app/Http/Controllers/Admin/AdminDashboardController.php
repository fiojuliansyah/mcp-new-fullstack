<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Schedule;
use App\Models\User;
use App\Models\Subscription;
use Carbon\Carbon;

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

        return view('admin.dashboard', compact('schedules', 'studentCount', 'parentCount', 'tutorCount', 'subsCount', 'pendingUploads'));
    }
}
