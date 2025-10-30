<x-guest-layout>
    <div class="mb-4 text-sm text-gray-600">
        {{ __('This is a secure area of the application. Please confirm your password before continuing.') }}
    </div>

    <form method="POST" action="{{ route('password.confirm') }}">
        @csrf

        <!-- Password -->
        <div>
            <x-input-label for="password" :value="__('Password')" />

            <x-text-input id="password" class="block mt-1 w-full"
                            type="password"
                            name="password"
                            required autocomplete="current-password" />

            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <div class="flex justify-end mt-4">
            <x-primary-button>
                {{ __('Confirm') }}
            </x-primary-button>
        </div>
    </form>
</x-guest-layout>

@extends('layouts.auth')

@section('content')
<header class="relative w-full md:max-h-[900px] flex items-center font-inter overflow-hidden px-4 py-48 md:py-56">
    <img src="/frontend/assets/images/header-bg.svg" alt="" class="w-full absolute top-0 right-0 -z-10" />

    <div class="w-full max-w-screen-lg mx-auto flex justify-center items-center space-y-3">
        <form method="POST" action="{{ route('password.confirm') }}" class="w-full max-w-lg mx-auto bg-white text-black text-center shadow-lg rounded-2xl md:rounded-3xl px-5 py-8 md:p-10 lg:p-20 relative">
             @csrf

            <h1 class="text-xl md:text-3xl pt-6">Reset Password</h1>

            <div class="w-full space-y-3 py-3 pt-5">
                <div class="w-full text-left">
                    <label for="password" class="text-sm text-zinc-500 pb-2">New Password</label>
                    <input type="text" id="password" name="password" class="w-full text-white bg-black rounded-md p-3 mt-1 @error('password') border-2 border-red-500 @enderror" value="{{ old('password') }}" />
                    @error('password')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
                <div class="w-full text-left">
                    <label for="password" class="text-sm text-zinc-500 pb-2">Confirm Password</label>
                    <input type="text" id="password_confirmation" name="password_confirmation" class="w-full text-white bg-black rounded-md p-3 mt-1 @error('password_confirmation') border-2 border-red-500 @enderror" value="{{ old('password_confirmation') }}" />
                    @error('password_confirmation')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
                <div class="pt-5">
                    <button type="submit" class="w-full flex justify-center items-center text-sm md:text-base transition-all duration-300 bg-zinc-200 hover:bg-zinc-300 rounded-lg hover:cursor-pointer p-3">Reset Password</button>
                </div>
            </div>
        </form>
    </div>
</header>
@endsection

