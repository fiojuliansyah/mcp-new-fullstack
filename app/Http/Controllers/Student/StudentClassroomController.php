<?php

namespace App\Http\Controllers\Student;

use Carbon\Carbon;
use App\Models\Classroom;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class StudentClassroomController extends Controller
{
    public function index(Classroom $classroom, Request $request)
    {
        $user = auth()->user();
        $userFormId = $user->form_id;
        $today = Carbon::today();

        $hasAccess = $user->subscriptions()
            ->whereHas('classrooms', function($q) use ($classroom) {
                $q->where('classrooms.id', $classroom->id);
            })
            ->exists();

        if (!$hasAccess) {
            abort(403, 'You do not have access to this classroom.');
        }

        $topicFilter = $request->input('topic');
        $search = $request->input('search');
        $sort = $request->input('sort', 'latest');

        $replaySchedulesQuery = $classroom->schedules()
            ->where('form_id', $userFormId)
            ->whereHas('replays') 
            ->with(['replays', 'materials.materialFiles', 'quizzes']);

        if ($topicFilter) {
            $replaySchedulesQuery->where('topic', $topicFilter);
        }

        if ($search) {
            $replaySchedulesQuery->where('topic', 'like', "%{$search}%");
        }

        $replaySchedulesQuery->orderBy('time', $sort === 'latest' ? 'desc' : 'asc');

        $replaySchedules = $replaySchedulesQuery->get();

        $schedules = $classroom->schedules()
            ->where('form_id', $userFormId)
            ->orderBy('time', 'asc')
            ->with(['replays', 'materials.materialFiles', 'quizzes'])
            ->get();

        $schedulesToday = $classroom->schedules()
            ->where('form_id', $userFormId)
            ->whereDate('time', $today)
            ->orderBy('time', 'asc')
            ->get();

        $subscriptions = $user->subscriptions()
            ->with(['classrooms.schedules' => function($query) use ($userFormId) {
                $query->where('form_id', $userFormId)
                    ->with(['replays', 'materials.materialFiles', 'quizzes']);
            }])
            ->get();

        return view('student.classrooms.index', compact(
            'classroom',
            'schedulesToday',
            'schedules',
            'replaySchedules',
            'today',
            'subscriptions',
            'topicFilter',
            'search',
            'sort'
        ));
    }

    public function show(Classroom $classroom, Request $request)
    {
        $user = auth()->user();
        $userFormId = $user->form_id;

        $topicFilter = $request->input('topic');
        $dateFilter = $request->input('date');
        $today = Carbon::today();

        $hasAccess = $user->subscriptions()
            ->whereHas('classrooms', function ($q) use ($classroom) {
                $q->where('classrooms.id', $classroom->id);
            })
            ->exists();

        if (!$hasAccess) {
            abort(403, 'You do not have access to this classroom.');
        }

        $schedulesTodayQuery = $classroom->schedules()
            ->where('form_id', $userFormId)
            ->when($dateFilter, fn($q) => $q->whereDate('time', $dateFilter))
            ->when(!$dateFilter, fn($q) => $q->whereDate('time', $today))
            ->when($topicFilter, fn($q) => $q->where('topic', $topicFilter));

        $schedulesToday = $schedulesTodayQuery->orderBy('time', 'asc')->get();

        $schedulesQuery = $classroom->schedules()
            ->where('form_id', $userFormId)
            ->when($dateFilter, fn($q) => $q->whereDate('time', $dateFilter))
            ->when($topicFilter, fn($q) => $q->where('topic', $topicFilter));

        $schedules = $schedulesQuery->orderBy('time', 'asc')->get();

        $subscriptions = $user->subscriptions()
            ->with([
                'classrooms.schedules' => function ($query) use ($userFormId, $topicFilter, $dateFilter) {
                    $query->where('form_id', $userFormId)
                        ->when($topicFilter, fn($q) => $q->where('topic', $topicFilter))
                        ->when($dateFilter, fn($q) => $q->whereDate('time', $dateFilter))
                        ->with(['replays.replayVideos', 'materials.materialFiles', 'quizzes']);
                }
            ])
            ->get();

        return view('student.classrooms.show', compact(
            'classroom',
            'schedulesToday',
            'schedules',
            'today',
            'subscriptions',
            'topicFilter',
            'dateFilter'
        ));
    }

}
