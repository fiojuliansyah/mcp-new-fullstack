<?php

namespace App\Http\Controllers\Student;

use App\Models\Schedule;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class StudentScheduleController extends Controller
{
    public function class($id)
    {
        $schedule = Schedule::findOrFail($id);
        return view('student.class-join', compact('schedule'));
    }

    public function embed($id)
    {
        $schedule = Schedule::findOrFail($id);
        return view('student.class-embed', compact('schedule'));
    }

    public function recordJoin(Request $request, $scheduleId)
    {
        $userId = Auth::id();
        $attendance = Attendance::updateOrCreate(
            [
                'user_id' => $userId,
                'schedule_id' => $scheduleId,
                'out_time' => null,
            ],
            [
                'join_time' => now(),
                'status' => 'Joined',
            ]
        );

        return response()->json([
            'status' => 'success',
            'attendance_id' => $attendance->id,
            'message' => 'Join time recorded.',
        ]);
    }

    public function recordOut(Request $request, $scheduleId)
    {
        $userId = Auth::id();

        $attendance = Attendance::where('user_id', $userId)
            ->where('schedule_id', $scheduleId)
            ->whereNull('out_time')
            ->latest()
            ->first();

        if ($attendance && $attendance->join_time) {
            $outTime = now();
            $joinTime = \Carbon\Carbon::parse($attendance->join_time);
            $duration = $outTime->diffInSeconds($joinTime);

            $attendance->update([
                'out_time' => $outTime,
                'duration_joined' => $duration,
                'status' => 'Completed',
            ]);

            return response()->json([
                'status' => 'success',
                'duration' => $duration,
                'message' => 'Out time and duration recorded.',
            ]);
        }

        return response()->json(['status' => 'error', 'message' => 'No active join session found.'], 404);
    }
}
