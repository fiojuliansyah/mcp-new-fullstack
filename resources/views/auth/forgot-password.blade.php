@extends('layouts.auth')

@section('content')
<header class="relative w-full md:max-h-[900px] flex items-center font-inter overflow-hidden px-4 py-48 md:py-56">
    <img src="/frontend/assets/images/header-bg.svg" alt="" class="w-full absolute top-0 right-0 -z-10" />

    <div class="w-full max-w-screen-lg mx-auto flex justify-center items-center space-y-3">
        <form method="POST" action="{{ route('password.email') }}" class="w-full max-w-lg mx-auto bg-white text-black text-center shadow-lg rounded-2xl md:rounded-3xl px-5 py-8 md:p-10 lg:p-20 relative">
             @csrf

            <a href="{{ route('login') }}" class="absolute top-4 left-4 z-10 p-2 bg-white rounded-full hover:bg-zinc-100 transition duration-200">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-black" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
            </a>

            <h1 class="text-xl md:text-3xl pt-6">Forgot Password?</h1>
            <div class="flex justify-center my-5">
                <img src="/frontend/assets/images/reset-password-vector.svg" alt="" class="h-14" >
            </div>
            <p class="text-sm md:text-base text-gray-600 mt-2">Enter your registered email. We will email you a link to reset your password.</p>

            <div class="w-full space-y-3 py-3 pt-5">
                <div class="w-full text-left">
                    <label for="email" class="text-sm text-zinc-500 pb-2">Email</label>
                    <input type="text" id="email" name="email" class="w-full text-white bg-black rounded-md p-3 mt-1 @error('email') border-2 border-red-500 @enderror" value="{{ old('email') }}" />
                    @error('email')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
                <div class="pt-5">
                    <button type="submit" class="w-full flex justify-center items-center text-sm md:text-base transition-all duration-300 bg-zinc-200 hover:bg-zinc-300 rounded-lg hover:cursor-pointer p-3">Send Reset Link</button>
                </div>
            </div>
        </form>
    </div>
</header>
@endsection
