<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Plan;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class AdminPlanController extends Controller
{
    public function index()
    {
        $plans = Plan::orderBy('created_at', 'desc')->paginate(10);
        return view('admin.plans.index', compact('plans'));
    }

    public function create()
    {
        return view('admin.plans.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'price' => 'required|string|max:255',
            'description' => 'nullable|string',
            'duration' => 'required|in:week,month,year',
            'duration_value' => 'required|string',
            'device_limit' => 'nullable|string',
            'is_weekly_live_classes' => 'nullable|string',
            'is_materials' => 'nullable|string',
            'is_quizzes' => 'nullable|string',
            'replay_day' => 'nullable|string',
            'status' => 'nullable|string|in:active,inactive',
        ]);

        $validated['slug'] = Str::slug($validated['name']);

        Plan::create($validated);

        return redirect()->route('admin.plans.index')->with('success', 'Plan successfully created!');
    }

    public function edit($id)
    {
        $plan = Plan::findOrFail($id);
        return view('admin.plans.edit', compact('plan'));
    }

    public function update(Request $request, $id)
    {
        $plan = Plan::findOrFail($id);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'price' => 'required|string|max:255',
            'description' => 'nullable|string',
            'duration' => 'required|in:week,month,year',
            'duration_value' => 'required|string',
            'device_limit' => 'nullable|string',
            'is_weekly_live_classes' => 'nullable|string',
            'is_materials' => 'nullable|string',
            'is_quizzes' => 'nullable|string',
            'replay_day' => 'nullable|string',
            'status' => 'required|string|in:active,inactive',
        ]);

        $validated['slug'] = Str::slug($validated['name']);

        $plan->update($validated);

        return redirect()->route('admin.plans.index')->with('success', 'Plan successfully updated!');
    }

    public function destroy($id)
    {
        $plan = Plan::findOrFail($id);
        $plan->delete();

        return redirect()->route('admin.plans.index')->with('success', 'Plan deleted successfully.');
    }

    public function getPrice($id)
    {
        $plan = Plan::find($id);
        if (!$plan) {
            return response()->json(['error' => 'Plan not found'], 404);
        }

        return response()->json(['price' => $plan->price]);
    }
}
