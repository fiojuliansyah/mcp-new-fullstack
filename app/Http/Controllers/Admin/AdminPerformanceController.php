<?php

namespace App\Http\Controllers\Admin;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class AdminPerformanceController extends Controller
{
    public function index()
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

        return view('admin.performance.index', compact('students', 'count'));
    }

    public function show($id)
    {
        $student = User::with([
            'parent',
            'subscriptions.plan',
            'subscriptions.classrooms' => fn($q) => $q->with([
                'subject', 
                'user',
                'schedules' => fn($q) => $q->with([
                    'attendances' => fn($q2) => $q2->where('user_id', $id)->whereIn('status', ['present', 'late']),
                    'quizzes.attempts' => fn($q2) => $q2->where('user_id', $id),
                    'replays.replayVideos.views' => fn($q2) => $q2->where('user_id', $id),
                    'replays.replayVideos'
                ])
            ])
        ])->findOrFail($id);
        
        return view('admin.performance.show', compact('student')); 
    }

}
