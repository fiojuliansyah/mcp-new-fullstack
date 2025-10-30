@extends('layouts.auth')

@section('content')
<header class="relative w-full md:max-h-[900px] flex items-center font-inter overflow-hidden px-4 py-48 md:py-56">
    <img src="/frontend/assets/images/header-bg.svg" alt="" class="w-full absolute top-0 right-0 -z-10" />
    <div class="w-full max-w-screen-lg mx-auto flex justify-center items-center space-y-3">
        <!-- FORM -->
        <div class="w-full max-w-lg mx-auto bg-white text-black text-center shadow-lg rounded-2xl md:rounded-3xl px-5 py-8 md:p-10 lg:p-20">
            <h1 class="text-xl md:text-3xl">Log into account</h1>
            <p class="text-sm md:text-base">Welcome back!</p>
            <p class="text-sm md:text-base">Let's continue learning</p>
            <div class="w-full space-y-3 py-3 pt-5">
                <a href="{{ route('login') }}" class="w-full flex justify-center items-center text-sm md:text-base transition-all duration-300 bg-zinc-200 hover:bg-zinc-300 rounded-lg p-3">Continue With Email</a>
                <p>or</p>
                <a href="#" class="w-full flex justify-center items-center space-x-2 text-sm md:text-base bg-white border border-zinc-300 rounded-lg p-3">
                    <iconify-icon icon="duo-icons:apple" width="22" height="22"></iconify-icon>
                    <span>Continue With Apple</span>
                </a>
                <a href="#" class="w-full flex justify-center items-center space-x-2 text-sm md:text-base bg-white border border-zinc-300 rounded-lg p-3">
                    <iconify-icon icon="devicon:google" class="w-5"></iconify-icon>
                    <span>Continue With Google</span>
                </a>
                <p class="text-sm md:text-base">By continuing, you agree to our <a href="" class="underline hover:text-zinc-500">Privacy Policy</a> and <a href="" class="underline hover:text-zinc-500">Terms and Conditions</a></p>
                <p class="text-sm md:text-base">Don't have an account? <a href="{{ route('register') }}" class="underline hover:text-zinc-500">Sign Up</a></p>
            </div>
        </div>
    </div>
</header>
@endsection