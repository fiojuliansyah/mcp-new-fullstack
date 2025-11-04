@extends('layouts.auth')

@section('content')
    <header class="relative w-full md:max-h-[900px] flex items-center font-inter overflow-hidden px-4 py-48 md:py-56">
        <img src="/frontend/assets/images/header-bg.svg" alt="" class="w-full absolute top-0 right-0 -z-10" />
        @if (Auth::user()->account_type === 'parent')
            <div class="w-full max-w-screen-lg mx-auto flex justify-center items-center space-y-3">
                <div
                    class="w-full max-w-lg mx-auto bg-white text-black text-center shadow-lg rounded-2xl md:rounded-3xl px-5 py-8 md:p-10 lg:p-20">
                    <div class="flex justify-center mb-4 mt-4">
                        <img src="/frontend/assets/images/parent-account-success.svg" alt="" class="h-20" />
                    </div>
                    <h1 class="text-xl md:text-3xl">Welcome, parent!</h1>
                    <p class="text-sm md:text-base py-2">Join Malaysia's biggest online tuition biggest online tuition</p>
                    <div class="w-full space-y-5 md:space-y-8 text-left py-5">
                        <div class="w-full flex items-center gap-5">
                            <div class="w-12 flex justify-center items-center">
                                <svg class="w-10" viewBox="0 0 32 33" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M20.2622 15.8823L27.5433 12.6658V28.9472H20.2622V15.8823Z" stroke="#262626"
                                        stroke-opacity="0.75" stroke-width="1.5" stroke-linecap="round"
                                        stroke-linejoin="round" />
                                    <path d="M11.1606 11.8616L15.7114 18.2947L20.2622 15.8823V28.9444H11.1606V11.8616Z"
                                        stroke="#262626" stroke-opacity="0.75" stroke-width="1.5" stroke-linecap="round"
                                        stroke-linejoin="round" />
                                    <path d="M3.87939 15.0781L11.1606 11.8616V28.947H3.87939V15.0781Z" stroke="#262626"
                                        stroke-opacity="0.75" stroke-width="1.5" stroke-linecap="round"
                                        stroke-linejoin="round" />
                                    <path d="M29.348 4.05713L17.8075 11.4344L12.5015 5.23006L3.00781 10.1079"
                                        stroke="#262626" stroke-opacity="0.75" stroke-width="1.5" stroke-linecap="round"
                                        stroke-linejoin="round" />
                                    <path d="M23.6104 3.08154L29.3478 4.03778L28.3917 9.77523" stroke="#262626"
                                        stroke-opacity="0.75" stroke-width="1.5" stroke-linecap="round"
                                        stroke-linejoin="round" />
                                </svg>
                            </div>
                            <p class="text-sm text-zinc-500">Keep track of your children’s progress and performance.</p>
                        </div>
                        <div class="w-full flex items-center gap-5">
                            <div class="w-12 flex justify-center items-center">
                                <svg class="w-10" viewBox="0 0 32 32" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M12.666 21.75L15.5751 24L20.666 18" stroke="#262626" stroke-width="1.5"
                                        stroke-linecap="round" stroke-linejoin="round" />
                                    <path
                                        d="M5.0784 5.38818C4.55177 5.38818 4.04673 5.6118 3.67435 6.00981C3.30197 6.40784 3.09277 6.94767 3.09277 7.51056V27.6732C3.09277 28.236 3.30197 28.776 3.67435 29.1739C4.04673 29.5719 4.55177 29.7956 5.0784 29.7956H26.9203C27.4469 29.7956 27.9521 29.5719 28.3244 29.1739C28.6967 28.776 28.9059 28.236 28.9059 27.6732V7.51056C28.9059 6.94767 28.6967 6.40784 28.3244 6.00981C27.9521 5.6118 27.4469 5.38818 26.9203 5.38818H23.838"
                                        stroke="#262626" stroke-width="1.5" stroke-linecap="round"
                                        stroke-linejoin="round" />
                                    <path d="M3.09277 12.8167H28.9059" stroke="#262626" stroke-width="1.5"
                                        stroke-linecap="round" stroke-linejoin="round" />
                                    <path d="M8.57129 2.20459V8.57173" stroke="#262626" stroke-width="1.5"
                                        stroke-linecap="round" stroke-linejoin="round" />
                                    <path d="M23.4277 2.20459V8.57173" stroke="#262626" stroke-width="1.5"
                                        stroke-linecap="round" stroke-linejoin="round" />
                                    <path d="M8.57129 5.38818H19.1832" stroke="#262626" stroke-width="1.5"
                                        stroke-linecap="round" stroke-linejoin="round" />
                                </svg>
                            </div>
                            <p class="text-sm text-zinc-500">View live class schedules and their attendances.</p>
                        </div>
                        <div class="w-full flex items-center gap-5">
                            <div class="w-12 flex justify-center items-center">
                                <svg class="w-10" viewBox="0 0 30 30" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path
                                        d="M19.7751 10.7554C22.1674 10.7554 24.4617 11.7057 26.1533 13.3973C27.8448 15.0889 28.7952 17.3832 28.7952 19.7755C28.8009 21.5511 28.2761 23.2878 27.2883 24.7631L28.7952 28.7956L23.7227 27.883C22.5028 28.4779 21.1644 28.7899 19.8072 28.7956C18.45 28.8013 17.109 28.5006 15.8841 27.9161C14.6592 27.3316 13.5821 26.478 12.7329 25.4193C11.8836 24.3607 11.2843 23.124 10.9795 21.8013C10.6747 20.4788 10.6722 19.1045 10.9723 17.7809C11.2724 16.4573 11.8674 15.2184 12.7128 14.1567C13.5582 13.095 14.6324 12.2377 15.8552 11.6488C17.078 11.0599 18.4179 10.7545 19.7751 10.7554Z"
                                        stroke="#262626" stroke-width="1.5" stroke-linecap="round"
                                        stroke-linejoin="round" />
                                    <path
                                        d="M21.0696 5.47068C19.6404 3.65195 17.6796 2.32414 15.4602 1.67213C13.2408 1.02013 10.8734 1.07637 8.68753 1.83302C6.50165 2.58968 4.60611 4.00908 3.26479 5.89363C1.92348 7.77815 1.20317 10.0341 1.20415 12.3472C1.19757 14.546 1.84794 16.6966 3.07184 18.5233L1.20415 23.4896L5.70359 22.6831"
                                        stroke="#262626" stroke-width="1.5" stroke-linecap="round"
                                        stroke-linejoin="round" />
                                </svg>
                            </div>
                            <p class="text-sm text-zinc-500">Communicate directly with teachers wxhen necessary</p>
                        </div>
                    </div>
                    <div class="w-full space-y-3 py-3 pt-5">
                        <a href="{{ route('parent.dashboard') }}"
                            class="w-full flex justify-center items-center text-sm md:text-base transition-all duration-300 bg-zinc-200 hover:bg-zinc-300 rounded-lg p-3">Back
                            To Home</a>
                    </div>
                </div>
            </div>
        @elseif(Auth::user()->account_type === 'student')
            <div
                class="w-full max-w-lg mx-auto bg-white text-black text-center shadow-lg rounded-2xl md:rounded-3xl px-5 py-8 md:p-10 lg:p-20">
                <div class="flex justify-center mb-4 mt-4">
                    <img src="{{ Auth::user()->avatar_url }}" alt="" class="h-20" />
                </div>
                <h1 class="text-xl md:text-3xl">Welcome, <br> {{ Auth::user()->name }}</h1>
                <p class="text-sm md:text-base py-2">
                    {{ Auth::user()->form->name }} (2025)
                </p>
                <div class="w-full space-y-3 py-3 pt-5">
                    <a href="{{ route('student.dashboard') }}"
                        class="w-full flex justify-center items-center text-sm md:text-base transition-all duration-300 bg-zinc-200 hover:bg-zinc-300 rounded-lg p-3">Continue</a>
                </div>
            </div>
        @elseif(Auth::user()->account_type === 'tutor')
            <div
                class="w-full max-w-lg mx-auto bg-white text-black text-center shadow-lg rounded-2xl md:rounded-3xl px-5 py-8 md:p-10 lg:p-20">
                <div class="flex justify-center mb-4 mt-4">
                    <img src="/frontend/assets/images/success-reset-password.svg" alt="" class="h-20" />
                </div>
                <h1 class="text-xl md:text-3xl">Your Account has been created successfully</h1>
                <p class="text-sm md:text-base py-2">Only one click to explore online education.</p>
                <div class="w-full space-y-3 py-3 pt-5">
                    <a href="{{ route('tutor.dashboard') }}"
                        class="w-full flex justify-center items-center text-sm md:text-base transition-all duration-300 bg-zinc-200 hover:bg-zinc-300 rounded-lg p-3">Login
                        Now</a>
                </div>
            </div>
        @else
            <div
                class="w-full max-w-lg mx-auto bg-white text-black text-center shadow-lg rounded-2xl md:rounded-3xl px-5 py-8 md:p-10 lg:p-20">
                <div class="flex justify-center mb-4 mt-4">
                    <img src="/frontend/assets/images/success-reset-password.svg" alt="" class="h-20" />
                </div>
                <h1 class="text-xl md:text-3xl">Your Account has been created successfully</h1>
                <p class="text-sm md:text-base py-2">Only one click to explore online education.</p>
                <div class="w-full space-y-3 py-3 pt-5">
                    <a href="{{ route('admin.dashboard') }}"
                        class="w-full flex justify-center items-center text-sm md:text-base transition-all duration-300 bg-zinc-200 hover:bg-zinc-300 rounded-lg p-3">Login
                        Now</a>
                </div>
            </div>
        @endif
    </header>
@endsection
