<?php

namespace App\Http\Controllers\Admin;

use Carbon\Carbon;
use App\Models\User;
use App\Models\Classroom;
use App\Models\Subscription;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class AdminTutorController extends Controller
{
    public function index()
    {
        $count = User::where('account_type', 'tutor')->count();

        $users = User::where('account_type', 'tutor')
            ->with([
                'classrooms' => function ($query) {
                    $query->with([
                        'subject',
                        'schedules.attendances',
                    ]);
                }
            ])
            ->paginate(10);

        $users->getCollection()->transform(function ($user) {
            $totalSubjects = $user->classrooms
                ->map(fn($c) => $c->subject->id ?? null)
                ->filter()
                ->unique()
                ->count();

            $classroomIds = $user->classrooms->pluck('id')->toArray();

            $studentIds = \App\Models\Subscription::where(function ($q) use ($classroomIds) {
                foreach ($classroomIds as $cid) {
                    $q->orWhereJsonContains('classroom_id', (string) $cid);
                }
            })
            ->pluck('user_id')
            ->unique();

            $totalStudents = $studentIds->count();

            $totalAttendanceRate = 0;
            $totalSchedulesWithAttendance = 0;

            foreach ($user->classrooms as $classroom) {
                foreach ($classroom->schedules as $schedule) {
                    $totalStudentsInClass = $totalStudents;
                    $totalAttendanceRecords = $schedule->attendances
                        ->whereIn('status', ['present', 'late'])
                        ->count();

                    if ($totalStudentsInClass > 0) {
                        $attendanceRate = ($totalAttendanceRecords / $totalStudentsInClass) * 100;
                        $totalAttendanceRate += $attendanceRate;
                        $totalSchedulesWithAttendance++;
                    }
                }
            }

            $avgAttendance = $totalSchedulesWithAttendance > 0
                ? round($totalAttendanceRate / $totalSchedulesWithAttendance)
                : 0;

            $user->total_subjects = $totalSubjects;
            $user->total_students = $totalStudents;
            $user->avg_attendance = $avgAttendance;

            return $user;
        });

        return view('admin.users.tutors.index', compact('users', 'count'));
    }

    public function create()
    {
        return view('admin.users.tutors.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:6',
            'gender' => 'nullable|in:male,female',
        ]);

        User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => bcrypt($validated['password']),
            'account_type' => 'tutor',
            'gender' => $validated['gender'],
        ]);

        return redirect()->route('admin.users.tutors.index')->with('success', 'Tutor added successfully.');
    }

    public function edit($id)
    {
        $tutor = User::where('account_type', 'tutor')->findOrFail($id);
        return view('admin.users.tutors.edit', compact('tutor'));
    }

    public function update(Request $request, $id)
    {
        $tutor = User::where('account_type', 'tutor')->findOrFail($id);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $tutor->id,
            'password' => 'nullable|min:6',
            'gender' => 'nullable|in:male,female',
        ]);

        $tutor->update([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'gender' => $validated['gender'],
            'password' => $validated['password'] ? bcrypt($validated['password']) : $tutor->password,
        ]);

        return redirect()->route('admin.users.tutors.index')->with('success', 'Tutor updated successfully.');
    }


    public function show($id)
    {
        $tutor = User::where('account_type', 'tutor')
            ->with([
                'classrooms.subject',
                'classrooms.schedules.replays',
            ])
            ->findOrFail($id);

        $subscriptions = Subscription::all();

        foreach ($tutor->classrooms as $classroom) {
            $classroom->status = $classroom->schedules->last()->status ?? 'pending';

            $studentCount = $subscriptions
                ->filter(function ($sub) use ($classroom) {
                    $classroomIds = is_string($sub->classroom_id)
                        ? json_decode($sub->classroom_id, true)
                        : ($sub->classroom_id ?? []);
                    return in_array($classroom->id, $classroomIds);
                })
                ->unique('user_id')
                ->count();
            $classroom->total_students = $studentCount;

            $classroom->pending_replays = $classroom->schedules
                ->flatMap->replays
                ->where('status', 'pending')
                ->count();
        }

        return view('admin.users.tutors.show', compact('tutor'));
    }

    public function detail($classroomId)
    {
        $classroom = Classroom::with([
                'subject',
                'schedules.replays',
                'schedules.attendances',
            ])
            ->findOrFail($classroomId);

        $studentCount = Subscription::all()
            ->filter(function ($sub) use ($classroom) {
                $classroomIds = is_string($sub->classroom_id)
                    ? json_decode($sub->classroom_id, true)
                    : ($sub->classroom_id ?? []);
                return in_array($classroom->id, $classroomIds);
            })
            ->unique('user_id')
            ->count();
        $classroom->total_students = $studentCount;

        foreach ($classroom->schedules as $schedule) {
            $replays = $schedule->replays ?? collect();
            $schedule->replay_status = $replays->isEmpty()
                ? 'pending'
                : ($replays->every(fn($r) => $r->status === 'approved') ? 'uploaded' : 'pending');

            if ($schedule->replay_status === 'pending') {
                $scheduleDate = Carbon::parse($schedule->date);
                $schedule->pending_days = max(0, now()->diffInDays($scheduleDate));
            } else {
                $schedule->pending_days = 0;
            }

            $totalStudents = $classroom->total_students ?: 0;
            $attended = $schedule->attendances
                ->whereIn('status', ['present', 'late'])
                ->count();
            $schedule->attendance_rate = $totalStudents > 0
                ? round(($attended / $totalStudents) * 100)
                : 0;
        }

        return view('admin.users.tutors.detail', compact('classroom'));
    }

}
