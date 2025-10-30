<?php

namespace App\Http\Controllers\Admin;

use App\Models\User;
use App\Models\Subject;
use App\Models\Schedule;
use App\Models\Classroom;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class AdminClassroomController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search');

        $classrooms = Classroom::with(['subject', 'user', 'schedules'])
            ->when($search, function ($query, $search) {
                $query->where('name', 'like', "%{$search}%")
                    ->orWhereHas('subject', fn($q) => $q->where('name', 'like', "%{$search}%"))
                    ->orWhereHas('user', fn($q) => $q->where('name', 'like', "%{$search}%"));
            })
            ->latest()
            ->paginate(10)
            ->appends(['search' => $search]);

        return view('admin.classrooms.index', compact('classrooms', 'search'));
    }

    public function create()
    {
        $subjects = Subject::all();
        $tutors = User::all();
        return view('admin.classrooms.create', compact('subjects', 'tutors'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'nullable|string|max:255',
            'subject_id' => 'required|exists:subjects,id',
            'user_id' => 'required|exists:users,id',
            'date' => 'nullable|date',
            'time' => 'nullable',
            'status' => 'required|in:active,deactive',
        ]);

        Classroom::create($request->all());

        return redirect()->route('admin.classrooms.index')->with('success', 'Classroom created successfully.');
    }

    public function edit(Classroom $classroom)
    {
        $subjects = Subject::all();
        $tutors = User::all();
        return view('admin.classrooms.edit', compact('classroom', 'subjects', 'tutors'));
    }

    public function update(Request $request, Classroom $classroom)
    {
        $request->validate([
            'name' => 'nullable|string|max:255',
            'subject_id' => 'required|exists:subjects,id',
            'user_id' => 'required|exists:users,id',
            'date' => 'nullable|date',
            'time' => 'nullable',
            'status' => 'required|in:active,deactive',
        ]);

        $classroom->update($request->all());

        return redirect()->route('admin.classrooms.index')->with('success', 'Classroom updated successfully.');
    }

    public function show(Classroom $classroom)
    {
        $schedules = Schedule::where('classroom_id', $classroom->id)->paginate(10);
        return view('admin.classrooms.show', compact('classroom', 'schedules'));
    }

    public function destroy(Classroom $classroom)
    {
        $classroom->delete();
        return redirect()->route('admin.classrooms.index')->with('success', 'Classroom deleted successfully.');
    }
}
