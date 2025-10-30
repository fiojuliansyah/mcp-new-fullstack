<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class StudentDashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        $userFormId = $user->form_id;

        $subscriptions = $user->subscriptions()
            ->with([
                'classrooms.schedules' => function($query) use ($userFormId) {
                    $query->where('form_id', $userFormId)
                        ->with(['replays', 'materials', 'quizzes']);
                }
            ])
            ->get();

        return view('student.dashboard', compact('subscriptions'));
    }
}
