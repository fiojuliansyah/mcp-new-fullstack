<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Models\Otp;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\View\View;

class AuthenticatedSessionController extends Controller
{
    public function started(): View
    {
        return view('auth.started');
    }

    public function create(): View
    {
        return view('auth.login');
    }

    public function store(LoginRequest $request): RedirectResponse
    {
        $credentials = $request->only('email', 'password');

        if (!Auth::validate($credentials)) {
            return back()->withErrors(['email' => 'Email atau password salah.']);
        }

        $user = User::where('email', $request->email)->first();

        $otp = rand(100000, 999999);

        Otp::updateOrCreate(
            ['user_id' => $user->id],
            ['code' => $otp, 'expires_at' => now()->addMinutes(5)]
        );

        Mail::raw("Kode OTP Anda adalah: {$otp}", function ($message) use ($user) {
            $message->to($user->email)->subject('Kode OTP Login');
        });

        session(['otp_user_id' => $user->id]);

        return redirect()->route('verify.otp')->with('email', $user->email)->with('status', 'The OTP code has been sent to your email.');
    }

    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/');
    }
}
