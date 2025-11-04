<?php

namespace App\Http\Controllers\Auth;

use App\Models\Otp;
use App\Models\User;
use App\Mail\OtpMail;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;

class OtpController extends Controller
{
    public function showForm()
    {
        return view('auth.verify-otp');
    }

    public function verify(Request $request)
    {
        $request->validate([
            'otp' => 'required|digits:6',
        ]);

        $userId = session('otp_user_id');
        if (!$userId) {
            return redirect()->route('login')->withErrors(['session' => 'Session expired, please login again.']);
        }

        $user = User::find($userId);
        if (!$user) {
            return back()->withErrors(['otp' => 'User not found.']);
        }

        $otp = Otp::where('user_id', $user->id)
                ->where('code', $request->otp)
                ->where('expires_at', '>', now())
                ->latest()
                ->first();

        if (!$otp) {
            return back()->withErrors(['otp' => 'OTP is incorrect or has expired.']);
        }

        $otp->delete();
        $user->update(['email_verified_at' => now()]);

        Auth::login($user);

        session()->forget(['otp_user_id', 'otp_email']);


        if (!$user->account_type) {
            return redirect()->route('account.choose');
        }

        if ($user->account_type === 'student') {
            return redirect()->route('student.dashboard');
        }

        if ($user->account_type === 'parent') {
            return redirect()->route('parent.dashboard');
        }

        if ($user->account_type === 'tutor') {
            return redirect()->route('tutor.dashboard');
        }

        if ($user->account_type === 'admin') {
            return redirect()->route('admin.dashboard');
        }
    }

    public function resend(Request $request)
    {
        $request->validate(['email' => 'required|email']);
        $user = User::where('email', $request->email)->first();

        if (!$user) {
            return back()->withErrors(['email' => 'Email Not Found']);
        }

        $otpCode = rand(100000, 999999);

        Otp::create([
            'user_id' => $user->id,
            'code' => $otpCode,
            'expires_at' => now()->addMinutes(5),
        ]);

        Mail::to($user->email)->send(new OtpMail($otpCode));

        return back()->with('status', 'New OTP has been sent!');
    }
}