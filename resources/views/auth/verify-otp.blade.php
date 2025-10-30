@extends('layouts.auth')

@section('content')

    <header class="relative w-full md:max-h-[900px] flex items-center font-inter overflow-hidden px-4 py-48 md:py-56"> <img
            src="/frontend/assets/images/header-bg.svg" alt="" class="w-full absolute top-0 right-0 -z-10" />
        <div class="w-full max-w-screen-lg mx-auto flex justify-center items-center space-y-3">
            <form method="POST" action="{{ route('verify.otp.submit') }}"
                class="w-full max-w-lg mx-auto bg-white text-black text-center shadow-lg rounded-2xl md:rounded-3xl px-5 py-8 md:p-10 lg:p-20">
                @csrf <h1 class="text-xl md:text-3xl">Verify your email address</h1>
                <p class="font-bold pt-3"> We’ve sent a verification code to <span
                        class="text-blue-600">{{ session('email') ?? old('email') }}</span>. Please enter this code to
                    continue. </p>
                <p class="text-zinc-500 mt-2"> Haven't received the email? Check your spam folder or click below to resend
                    the verification code. </p>
                <input type="hidden" name="email" value="{{ session('email') ?? old('email') }}">
                <input type="hidden" id="otp_full" name="otp">

                <div class="w-full space-y-3 py-3 pt-5">
                    <div class="w-full grid grid-cols-6 gap-3">
                        @for ($i = 1; $i <= 6; $i++)
                            <input type="text" maxlength="1" id="otp_{{ $i }}"
                                class="otp-input w-full text-center text-black border border-gray-300 focus:border-black rounded-md p-3 text-lg font-semibold"
                                required />
                        @endfor
                    </div>

                    <div class="w-full flex justify-center items-center py-2 text-gray-600 font-medium">
                        <span id="timer-min">05</span>:<span id="timer-sec">00</span>
                    </div>

                    <button type="submit"
                        class="w-full flex justify-center items-center text-sm md:text-base transition-all duration-300 bg-zinc-200 hover:bg-zinc-300 rounded-lg p-3">
                        Verify
                    </button>

                    <form method="POST" action="{{ route('verify.otp.resend') }}" class="mt-3">
                        @csrf
                        <input type="hidden" name="email" value="{{ session('email') ?? old('email') }}">
                        <p class="text-sm md:text-base">
                            Didn’t receive code?
                            <button type="submit" class="underline text-blue-600 hover:text-blue-800">
                                Resend code
                            </button>
                        </p>
                    </form>

                    @if ($errors->any())
                        <div class="text-red-500 text-sm mt-3">
                            @foreach ($errors->all() as $error)
                                <p>{{ $error }}</p>
                            @endforeach
                        </div>
                    @endif
                </div>
            </form>
        </div>
    </header>
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const inputs = document.querySelectorAll('.otp-input');
            const otpFull = document.getElementById('otp_full');
            inputs.forEach((input, index) => {
                input.addEventListener('input', (e) => {
                    if (e.target.value.length === 1 && index < inputs.length - 1) {
                        inputs[index + 1].focus();
                    }
                    updateOtpValue();
                });
                input.addEventListener('keydown', (e) => {
                    if (e.key === 'Backspace' && !input.value && index > 0) {
                        inputs[index - 1].focus();
                    }
                });
            });

            function updateOtpValue() {
                otpFull.value = Array.from(inputs).map(i => i.value).join('');
            }
            let timerMinutes = 5;
            let timerSeconds = 0;
            const minEl = document.getElementById('timer-min');
            const secEl = document.getElementById('timer-sec');
            const interval = setInterval(() => {
                if (timerMinutes === 0 && timerSeconds === 0) {
                    clearInterval(interval);
                    minEl.textContent = '00';
                    secEl.textContent = '00';
                    return;
                }
                if (timerSeconds === 0) {
                    timerMinutes--;
                    timerSeconds = 59;
                } else {
                    timerSeconds--;
                }
                minEl.textContent = String(timerMinutes).padStart(2, '0');
                secEl.textContent = String(timerSeconds).padStart(2, '0');
            }, 1000);
        });
    </script>

@endsection
