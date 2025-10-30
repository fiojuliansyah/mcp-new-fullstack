<?php

namespace App\Http\Controllers\Auth;

use App\Models\Otp;
use App\Models\Form;
use App\Models\User;
use App\Mail\OtpMail;
use Illuminate\View\View;
use Illuminate\Http\Request;
use Illuminate\Validation\Rules;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Http\RedirectResponse;
use Illuminate\Auth\Events\Registered;

class RegisteredUserController extends Controller
{
    public function create(): View
    {
        return view('auth.register');
    }

    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        $user = User::create([
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        $otpCode = rand(100000, 999999);

        Otp::create([
            'user_id' => $user->id,
            'code' => $otpCode,
            'expires_at' => now()->addMinutes(5),
        ]);

        Mail::to($user->email)->send(new OtpMail($otpCode));

        session(['otp_user_id' => $user->id]);

        return redirect()->route('verify.otp')->with('email', $user->email);
    }

    public function createAccountType()
    {
        return view('auth.choose-account-type');
    }

    public function updateAccountType(Request $request)
    {
        $request->validate([
            'account_type' => 'required|in:student,parent,tutor,admin',
        ]);

        $user = Auth::user();
        $user->update(['account_type' => $request->account_type]);

        return redirect()->route('account.profile')->with('status', 'Account type selected successfully.');
    }

    public function createProfile()
    {
        $forms = Form::all();
        return view('auth.create-profile', compact('forms'));
    }

    public function storeProfile(Request $request)
    {
        $user = Auth::user();

        $rules = [
            'name' => ['required', 'string', 'max:255'],
            'ic_number' => ['required', 'string', 'max:30'],
            'gender' => ['required', 'in:male,female'],
            'avatar_url' => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif,svg', 'max:2048'],
            'phone_prefix' => ['required', 'string', 'max:10'], 
            'phone' => ['required', 'string', 'max:20'],
        ];

        if ($user->account_type === 'student') {
            $rules['level'] = ['required', 'string', 'max:50'];
        }

        if ($user->account_type === 'parent') {
            $rules['postal_code'] = ['required', 'string', 'max:10'];
        }

        $validated = $request->validate($rules);

        $fullPhone = trim($validated['phone_prefix']) . trim($validated['phone']);
        $updateData = [
            'name' => $validated['name'],
            'ic_number' => $validated['ic_number'],
            'gender' => $validated['gender'],
            'phone' => $fullPhone,
            'level' => $validated['level'] ?? null,
            'postal_code' => $validated['postal_code'] ?? null,
        ];

        if ($request->hasFile('avatar_url')) {

            $disk = config('filesystems.default') === 's3' ? 's3' : 'public';

            $folder = 'avatars/' . $user->id;
            $path = $request->file('avatar_url')->store($folder, $disk);
            if ($user->avatar) {
                Storage::disk($disk)->delete($user->avatar);
            }
            $updateData['avatar_url'] = $path;
        }

        $user->update($updateData);

        return redirect()
            ->route('account.profile.success')
            ->with('status', 'Profile created successfully!');
    }

    public function createProfileSuccess()
    {
        return view('auth.profile-success');
    }
}
