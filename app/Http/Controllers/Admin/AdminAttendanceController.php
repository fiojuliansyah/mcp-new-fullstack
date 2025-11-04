<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Schedule;

class AdminAttendanceController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search');

        $schedules = Schedule::with(['classroom.subject', 'classroom.user', 'attendances'])
            ->when($search, function ($query, $search) {
                $query->whereHas('classroom.subject', function ($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%");
                })
                ->orWhereHas('classroom.user', function ($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%");
                });
            })
            ->orderBy('time', 'desc')
            ->paginate(10);

        return view('admin.attendances.index', compact('schedules', 'search'));
    }
}
