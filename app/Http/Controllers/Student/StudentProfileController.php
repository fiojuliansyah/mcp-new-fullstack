<?php

namespace App\Http\Controllers\Student;

use App\Models\Plan;
use App\Models\Schedule;
use App\Models\Classroom;
use App\Models\Subscription;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class StudentProfileController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        $subscriptions = Subscription::with(['classrooms.schedules'])
                        ->orderBy('created_at', 'DESC')
                        ->where('user_id', $user->id)
                        ->where('status', 'active')
                        ->get();
        
        $expiredSubscriptions = Subscription::with(['classrooms.schedules'])
                        ->orderBy('created_at', 'DESC')
                        ->where('user_id', $user->id)
                        ->where('status', 'expired')
                        ->get();
        
        $subscribedScheduleIds = $subscriptions->pluck('classrooms.*.schedules.*.id')->flatten()->unique();

        $availableSchedules = Schedule::with(['classroom.user', 'classroom.subject'])
                            ->whereNotIn('id', $subscribedScheduleIds)
                            ->get();

        $plan = Plan::orderBy('price', 'asc')->first();

        return view('student.profile', compact('subscriptions', 'user', 'expiredSubscriptions', 'availableSchedules', 'plan'));
    }

    public function update(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'name' => 'required|string|max:255',
            'ic_number' => 'nullable|string|unique:users,ic_number,' . $user->id,
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
            'avatar_url' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'postal_code' => 'nullable|string|max:10',
            'language' => 'nullable|string|in:default,english',
            'password' => 'nullable|string|min:8|confirmed',
        ]);

        $data = $request->only(['name', 'ic_number', 'email', 'postal_code', 'language']);

        if ($request->hasFile('avatar_url')) {
            $disk = config('filesystems.default') === 's3' ? 's3' : 'public';
            $folder = 'avatars/' . $user->id;

            $oldPath = $user->getRawOriginal('avatar_url'); 
        
            if ($oldPath && Storage::disk($disk)->exists($oldPath)) {
                Storage::disk($disk)->delete($oldPath);
            }

            $file = $request->file('avatar_url');
            $filename = uniqid() . '.' . $file->getClientOriginalExtension();
            $path = $file->storeAs($folder, $filename, $disk);

            $data['avatar_url'] = $path;
        }

        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        }

        $user->update($data);

        return redirect()->route('student.profile')
                        ->with('success', 'Your profile has been successfully updated!');
    }

}
