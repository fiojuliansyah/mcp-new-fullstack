<?php

namespace App\Http\Controllers\Admin;

use App\Models\Plan;
use App\Models\User;
use App\Models\Classroom;
use App\Models\Subscription;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\Http\Controllers\Controller;

class AdminSubscriptionController extends Controller
{
    public function index()
    {
        $subscriptions = Subscription::orderBy('created_at', 'desc')->paginate(10);
        return view('admin.subscriptions.index', compact('subscriptions'));
    }

    public function create()
    {
        $plans = Plan::all();
        $users = User::where('account_type', 'student')->get();
        $classrooms = Classroom::with('subject')->get();
        return view('admin.subscriptions.create', compact('plans', 'users', 'classrooms'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'plan_id' => 'required|exists:plans,id',
            'classroom_id' => 'required|array',
            'classroom_id.*' => 'exists:classrooms,id',
            'price' => 'required|numeric|min:0',
            'payment_method' => 'required|string|max:50',
            'total' => 'required|numeric|min:0',
            'status' => ['required', Rule::in(['pending', 'active', 'cancelled', 'expired', 'on_hold'])],
            'payment_status' => ['required', Rule::in(['unpaid', 'paid', 'failed', 'refunded'])],
            'start_date' => 'required|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
        ]);

        $validated['invoice_number'] = 'INV' . date('YmdHis') . '-' . strtoupper(uniqid());

        $classroomIds = $validated['classroom_id'];
        unset($validated['classroom_id']);

        $subscription = Subscription::create($validated);
        $subscription->classrooms()->sync($classroomIds);

        return redirect()->route('admin.subscriptions.index')
            ->with('success', 'Subscription successfully created!');
    }


    public function edit($id)
    {
        $subscription = Subscription::findOrFail($id);
        $users = User::where('account_type', 'student')->get();
        $plans = Plan::all();
        $classrooms = Classroom::with('subject')->get();
        return view('admin.subscriptions.edit', compact('subscription', 'users', 'plans', 'classrooms'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'user_id' => 'required',
            'plan_id' => 'required',
            'price' => 'required|numeric',
            'payment_method' => 'required',
            'payment_status' => 'required',
            'total' => 'required|numeric',
            'status' => 'required',
            'start_date' => 'required|date',
        ]);

        $subscription = Subscription::findOrFail($id);
        $subscription->update([
            'user_id' => $request->user_id,
            'plan_id' => $request->plan_id,
            'price' => $request->price,
            'payment_method' => $request->payment_method,
            'payment_status' => $request->payment_status,
            'total' => $request->total,
            'status' => $request->status,
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
        ]);

        $subscription->classrooms()->sync($request->classroom_id);

        return redirect()->route('admin.subscriptions.index')->with('success', 'Subscription updated successfully.');
    }

    public function destroy($id)
    {
        $subscription = Subscription::findOrFail($id);
        $subscription->delete();

        return redirect()->route('admin.subscriptions.index')->with('success', 'Subscription deleted successfully.');
    }
}
