<?php

namespace App\Http\Controllers\Student;

use Carbon\Carbon;
use App\Models\Plan;
use App\Models\Coupon;
use App\Models\Schedule;
use Carbon\CarbonPeriod;
use App\Models\Classroom;
use App\Models\Subscription;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;

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
            'subtotal' => $totalSubscriptionPrice,
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
            ->route('student.enrollment.timetable', $subscription->id)
            ->with('success', 'Subscription created successfully. Proceed to payment.');
    }

    public function timetable($id)
    {
        $subscription = Subscription::with('plan')->findOrFail($id);

        $classroomIds = json_decode($subscription->classroom_id, true) ?? [];
        $scheduleDays = json_decode($subscription->schedule_day, true) ?? [];

        $classrooms = Classroom::with(['subject', 'user', 'schedules' => function ($q) {
            $q->select('id', 'classroom_id', 'time');
        }])
            ->whereIn('id', $classroomIds)
            ->get(['id', 'name', 'subject_id', 'user_id']);

        $start = Carbon::parse($subscription->start_date);
        $end = Carbon::parse($subscription->end_date);
        $period = CarbonPeriod::create($start, $end);

        $calendarEvents = [];

        foreach ($classrooms as $classroom) {
            foreach ($classroom->schedules as $schedule) {
                $scheduleDay = Carbon::parse($schedule->time)->format('l');

                if (!in_array($scheduleDay, $scheduleDays)) {
                    continue;
                }

                foreach ($period as $date) {
                    if ($date->format('l') === $scheduleDay) {
                        $scheduleTime = Carbon::parse($schedule->time);
                        $startDateTime = $date->format('Y-m-d') . ' ' . $scheduleTime->format('H:i:s');
                        $endDateTime = Carbon::parse($startDateTime)->addHour()->format('Y-m-d H:i:s');

                        $calendarEvents[] = [
                            'title' => $classroom->subject?->name . ' - ' . $classroom->name,
                            'start' => $startDateTime,
                            'end' => $endDateTime,
                            'backgroundColor' => '#161616',
                            'borderColor' => '#2563eb',
                            'extendedProps' => [
                                'classroom' => $classroom->name,
                                'subject' => $classroom->subject?->name,
                                'user' => $classroom->user?->name,
                            ]
                        ];
                    }
                }
            }
        }

        return view('student.enrollments.timetable', compact(
            'subscription',
            'classroomIds',
            'scheduleDays',
            'classrooms',
            'calendarEvents'
        ));
    }

    public function summary($id)
    {
        $subscription = Subscription::with('plan')->findOrFail($id);

        $plans = Plan::all();

        return view('student.enrollments.summary', compact('subscription','plans'));
    }

    protected function calculateSubtotal(Subscription $subscription)
    {
        $total = $subscription->total ?? 0;
        $subtotal = $total;

        if ($subscription->coupon) {
            $coupon = $subscription->coupon;
            if ($coupon->type === 'percentage') {
                $subtotal = $subtotal * (1 - $coupon->value / 100);
            } else {
                $subtotal = $subtotal - $coupon->value;
            }
        }

        if ($subscription->plusianCoupon) {
            $subtotal = $subtotal - $subscription->plusianCoupon->value;
        }

        $subscription->subtotal = max(0, $subtotal);
        $subscription->save();

        return $subscription->subtotal;
    }

    public function applyVoucher(Request $request, $id)
    {
        $request->validate(['coupon_code'=>'required|string|max:255']);
        $subscription = Subscription::findOrFail($id);

        $coupon = Coupon::where('code', $request->coupon_code)
                        ->where('type', '!=', 'plusian')
                        ->first();

        if (!$coupon) {
            return response()->json(['success'=>false,'message'=>'Voucher code not found']);
        }

        if ($coupon->limit !== null && $coupon->times_used >= $coupon->limit) {
            return response()->json(['success'=>false,'message'=>'This voucher has reached its usage limit']);
        }

        $userUsage = $subscription->user->subscriptions()
                        ->where('coupon_id', $coupon->id)->count();
        if ($coupon->max_uses_per_user !== null && $userUsage >= $coupon->max_uses_per_user) {
            return response()->json(['success'=>false,'message'=>'You have reached the maximum usage for this voucher']);
        }

        if ($coupon->min_purchase_amount !== null && $subscription->total < $coupon->min_purchase_amount) {
            return response()->json(['success'=>false,'message'=>'Your purchase does not meet the minimum amount for this voucher']);
        }

        $subscription->coupon_id = $coupon->id;
        $coupon->times_used += 1;
        $coupon->save();

        $subtotal = $this->calculateSubtotal($subscription);

        return response()->json([
            'success'=>true,
            'message'=>'Voucher applied successfully',
            'subtotal'=>$subtotal,
            'coupon_code'=>$coupon->code,
            'coupon_type'=>$coupon->type,
            'coupon_value'=>$coupon->value,
            'total'=>$subscription->total
        ]);
    }

    public function applyPlusian(Request $request, $id)
    {
        $request->validate(['coupon_code'=>'required|string|max:255']);
        $subscription = Subscription::findOrFail($id);

        $coupon = Coupon::where('code', $request->coupon_code)
                        ->where('type','plusian')
                        ->first();

        if (!$coupon) {
            return response()->json(['success'=>false,'message'=>'Plusian code not found']);
        }

        if ($coupon->limit !== null && $coupon->times_used >= $coupon->limit) {
            return response()->json(['success'=>false,'message'=>'This Plusian has reached its usage limit']);
        }

        $userUsage = $subscription->user->subscriptions()
                        ->where('plusian_coupon_id', $coupon->id)->count();
        if ($coupon->max_uses_per_user !== null && $userUsage >= $coupon->max_uses_per_user) {
            return response()->json(['success'=>false,'message'=>'You have reached the maximum usage for this Plusian']);
        }

        if ($coupon->min_purchase_amount !== null && $subscription->total < $coupon->min_purchase_amount) {
            return response()->json(['success'=>false,'message'=>'Your purchase does not meet the minimum amount for this Plusian']);
        }

        $subscription->plusian_coupon_id = $coupon->id;
        $coupon->times_used += 1;
        $coupon->save();

        $subtotal = $this->calculateSubtotal($subscription);

        return response()->json([
            'success'=>true,
            'coupon_code'=>$coupon->code,
            'coupon_value'=>$coupon->value,
            'subtotal'=>$subtotal,
            'total'=>$subscription->total
        ]);
    }

    public function removeVoucher($id)
    {
        $subscription = Subscription::findOrFail($id);

        if ($subscription->coupon_id) {
            $coupon = Coupon::find($subscription->coupon_id);
            if ($coupon && $coupon->times_used > 0) {
                $coupon->times_used -= 1;
                $coupon->save();
            }
            $subscription->coupon_id = null;
        }

        $subtotal = $this->calculateSubtotal($subscription);

        return response()->json([
            'success'=>true,
            'message'=>'Voucher removed successfully',
            'subtotal'=>$subtotal,
            'total'=>$subscription->total
        ]);
    }

    public function removePlusian($id)
    {
        $subscription = Subscription::findOrFail($id);

        if ($subscription->plusian_coupon_id) {
            $coupon = Coupon::find($subscription->plusian_coupon_id);
            if ($coupon && $coupon->times_used > 0) {
                $coupon->times_used -= 1;
                $coupon->save();
            }
            $subscription->plusian_coupon_id = null;
        }
        $subtotal = $this->calculateSubtotal($subscription);

        return response()->json([
            'success'=>true,
            'message'=>'Plusian removed successfully',
            'subtotal'=>$subtotal,
            'total'=>$subscription->total
        ]);
    }

    public function updatePlan(Request $request, $id)
    {
        $request->validate(['plan_id'=>'required|exists:plans,id']);
        $subscription = Subscription::findOrFail($id);
        $plan = Plan::findOrFail($request->plan_id);

        $classesCount = count(json_decode($subscription->classroom_id, true) ?? []);
        $subscription->plan_id = $plan->id;
        $subscription->total = $plan->price * $classesCount;

        $subtotal = $this->calculateSubtotal($subscription);

        return response()->json([
            'success'=>true,
            'message'=>'Plan updated successfully',
            'plan'=>$plan,
            'total'=>$subscription->total,
            'subtotal'=>$subtotal
        ]);
    }

}
