<?php

namespace App\Http\Controllers\Student;

use Carbon\Carbon;
use App\Models\Plan;
use App\Models\Schedule;
use App\Models\Classroom;
use App\Models\Subscription;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class StudentEnrollmentController extends Controller
{
    public function classType()
    {
        return view('student.enrollments.class-type');
    }

    public function checkout()
    {
        $plans = Plan::select('id', 'name', 'price', 'is_weekly_live_classes', 'is_materials', 'is_quizzes', 'replay_day')->get();

        $classrooms = Classroom::all();
        $schedules = Schedule::with('classroom.subject', 'classroom.user')
                    ->get()
                    ->map(function ($schedule) {
                    $schedule->formatted_time = \Carbon\Carbon::parse($schedule->time)->format('D, h:i A');
                    return $schedule;
    });
        return view('student.enrollments.checkout', compact('plans','classrooms' , 'schedules'));
    }

    public function store(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'plan_id' => 'required|exists:plans,id',
            'schedule_day' => 'nullable|json',
            'total_subjects' => 'required|integer|min:1',
            'payment_method' => 'nullable|string|max:50',
        ]);

        $selectedScheduleIds = json_decode($request->input('schedule_day'), true);

        if (!is_array($selectedScheduleIds) || count($selectedScheduleIds) === 0) {
            return back()->withErrors(['schedules' => 'Please select at least one class to enroll.'])->withInput();
        }

        $plan = Plan::findOrFail($request->plan_id);
        $totalClasses = count($selectedScheduleIds);

        if ($totalClasses > $request->total_subjects) {
            return back()->withErrors([
                'schedules' => "You selected {$totalClasses} classes, but chose a maximum of {$request->total_subjects} subjects."
            ])->withInput();
        }

        $pricePerClass = $plan->price;
        $totalSubscriptionPrice = $pricePerClass * $totalClasses;

        $startDate = now();
        $endDate = $startDate->copy()->addMonth();

        $classroomIds = Schedule::whereIn('id', $selectedScheduleIds)
            ->pluck('classroom_id')
            ->unique()
            ->values()
            ->toArray();
            
        $classroomIds = array_map('strval', $classroomIds);

        $scheduleDays = Schedule::whereIn('id', $selectedScheduleIds)
            ->get()
            ->map(function ($schedule) {
                return \Carbon\Carbon::parse($schedule->time)->format('l');
            })
            ->unique()
            ->values()
            ->toArray();

        $subscriptionData = [
            'user_id' => $user->id,
            'plan_id' => $plan->id,
            'classroom_id' => json_encode($classroomIds),
            'price' => $pricePerClass,
            'total' => $totalSubscriptionPrice,
            'payment_method' => $request->payment_method ?? 'unspecified',
            'invoice_number' => 'INV' . date('YmdHis') . '-' . strtoupper(uniqid()),
            'status' => 'pending',
            'payment_status' => 'unpaid',
            'start_date' => $startDate,
            'end_date' => $endDate,
            'schedule_day' => json_encode($scheduleDays),
        ];

        $subscription = Subscription::create($subscriptionData);

        return redirect()
            ->route('student.enrollment.payment', $subscription->id)
            ->with('success', 'Subscription created successfully. Proceed to payment.');
    }
}
