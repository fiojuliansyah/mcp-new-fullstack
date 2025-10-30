<?php

namespace App\Http\Controllers\Tutor;

use App\Models\Form;
use App\Models\Subject;
use App\Models\Classroom;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class TutorClassController extends Controller
{
    public function index(Request $request, $slug)
    {
        $user = Auth::user();
        $selectedSubject = Subject::where('slug', $slug)->firstOrFail();
        $forms = Form::all();

        $query = Classroom::with([
            'latestSchedule.latestReplay',
            'latestSchedule.latestQuiz',
            'latestSchedule.latestMaterial'
        ])->where('user_id', $user->id)
        ->where('subject_id', $selectedSubject->id);

        if ($request->filled('form_id')) {
            $query->whereHas('latestSchedule', function($q) use ($request) {
                $q->where('form_id', $request->form_id);
            });
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                ->orWhereHas('latestSchedule', function($sq) use ($search) {
                    $sq->where('topic', 'like', "%{$search}%");
                });
            });
        }

        $classes = $query->paginate(10)->withQueryString();

        return view('tutor.classrooms.index', compact('selectedSubject', 'classes', 'user', 'forms'));
    }

    public function show(Classroom $classroom, Request $request)
    {
        $user = Auth::user();

        if ($classroom->user_id !== $user->id) {
            abort(403, 'Unauthorized');
        }

        $query = $classroom->schedules()->with([
            'form',
            'latestReplay',
            'latestQuiz',
            'latestMaterial'
        ]);

        if ($request->filled('form_id')) {
            $query->where('form_id', $request->form_id);
        }

        $schedules = $query->orderBy('time', 'desc')->paginate(10)->withQueryString();

        $form = $schedules->first()?->form->name ?? '-';

        return view('tutor.classrooms.show', compact('classroom', 'schedules', 'form'));
    }




}
