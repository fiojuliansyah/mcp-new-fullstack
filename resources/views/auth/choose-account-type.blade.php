@extends('layouts.auth')

@section('content')
<header class="relative w-full md:max-h-[900px] flex items-center font-inter overflow-hidden px-4 py-48 md:py-56">
    <img src="/frontend/assets/images/header-bg.svg" alt="" class="w-full absolute top-0 right-0 -z-10" />
    <div class="w-full max-w-screen-lg mx-auto flex justify-center items-center space-y-3">
        <div class="w-full max-w-3xl mx-auto bg-white text-black text-center shadow-lg rounded-2xl md:rounded-3xl px-5 py-8 md:p-10 lg:p-20">
            <h1 class="text-xl md:text-3xl">Choose account type</h1>
            <p class="text-sm md:text-base text-zinc-500">We will personalise your experience based on this account type</p>
            <form method="POST" action="{{ route('account.choose.update') }}" class="w-full grid grid-cols-1 sm:grid-cols-2 gap-3 md:gap-5 pt-10">
                @csrf
                <button type="submit" name="account_type" value="parent" class="w-full flex items-center gap-3 bg-black rounded-md p-5 py-6 hover:bg-zinc-800">
                    <svg class="w-14 md:w-20" viewBox="0 0 32 33" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M20.2622 15.8823L27.5433 12.6658V28.9472H20.2622V15.8823Z" stroke="#F2F2F2" stroke-opacity="0.75" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                        <path d="M11.1606 11.8616L15.7114 18.2947L20.2622 15.8823V28.9444H11.1606V11.8616Z" stroke="#F2F2F2" stroke-opacity="0.75" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                        <path d="M3.87939 15.0781L11.1606 11.8616V28.947H3.87939V15.0781Z" stroke="#F2F2F2" stroke-opacity="0.75" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                        <path d="M29.348 4.05713L17.8075 11.4344L12.5015 5.23006L3.00781 10.1079" stroke="#F2F2F2" stroke-opacity="0.75" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                        <path d="M23.6104 3.08154L29.3478 4.03778L28.3917 9.77523" stroke="#F2F2F2" stroke-opacity="0.75" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>                         
                    <div class="flex flex-col items-start text-start">
                        <h1 class="text-white">Parent</h1>
                        <p class="text-sm text-zinc-500">Manage your child’s account and oversee their progress.</p>
                    </div>
                </button>

                <button type="submit" name="account_type" value="tutor" class="w-full flex items-center gap-3 bg-black rounded-md p-5 py-6 hover:bg-zinc-800">
                    <svg class="w-14 md:w-20" viewBox="0 0 32 33" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M20.2622 15.8823L27.5433 12.6658V28.9472H20.2622V15.8823Z" stroke="#F2F2F2" stroke-opacity="0.75" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                        <path d="M11.1606 11.8616L15.7114 18.2947L20.2622 15.8823V28.9444H11.1606V11.8616Z" stroke="#F2F2F2" stroke-opacity="0.75" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                        <path d="M3.87939 15.0781L11.1606 11.8616V28.947H3.87939V15.0781Z" stroke="#F2F2F2" stroke-opacity="0.75" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                        <path d="M29.348 4.05713L17.8075 11.4344L12.5015 5.23006L3.00781 10.1079" stroke="#F2F2F2" stroke-opacity="0.75" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                        <path d="M23.6104 3.08154L29.3478 4.03778L28.3917 9.77523" stroke="#F2F2F2" stroke-opacity="0.75" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>                         
                    <div class="flex flex-col items-start text-start">
                        <h1 class="text-white">Tutor</h1>
                        <p class="text-sm text-zinc-500">Manage assigned classes and support student progress.</p>
                    </div>
                </button>

                <button type="submit" name="account_type" value="student" class="w-full flex items-center gap-3 bg-black rounded-md p-5 py-6 hover:bg-zinc-800">
                    <svg class="w-14 md:w-20" viewBox="0 0 32 33" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M20.2622 15.8823L27.5433 12.6658V28.9472H20.2622V15.8823Z" stroke="#F2F2F2" stroke-opacity="0.75" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                        <path d="M11.1606 11.8616L15.7114 18.2947L20.2622 15.8823V28.9444H11.1606V11.8616Z" stroke="#F2F2F2" stroke-opacity="0.75" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                        <path d="M3.87939 15.0781L11.1606 11.8616V28.947H3.87939V15.0781Z" stroke="#F2F2F2" stroke-opacity="0.75" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                        <path d="M29.348 4.05713L17.8075 11.4344L12.5015 5.23006L3.00781 10.1079" stroke="#F2F2F2" stroke-opacity="0.75" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                        <path d="M23.6104 3.08154L29.3478 4.03778L28.3917 9.77523" stroke="#F2F2F2" stroke-opacity="0.75" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>                         
                    <div class="flex flex-col items-start text-start">
                        <h1 class="text-white">Student</h1>
                        <p class="text-sm text-zinc-500">Join live class and access your learning materials.</p>
                    </div>
                </button>

                <button type="submit" name="account_type" value="admin" class="w-full flex items-center gap-3 bg-black rounded-md p-5 py-6 hover:bg-zinc-800">
                    <svg class="w-14 md:w-20" viewBox="0 0 32 33" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M20.2622 15.8823L27.5433 12.6658V28.9472H20.2622V15.8823Z" stroke="#F2F2F2" stroke-opacity="0.75" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                        <path d="M11.1606 11.8616L15.7114 18.2947L20.2622 15.8823V28.9444H11.1606V11.8616Z" stroke="#F2F2F2" stroke-opacity="0.75" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                        <path d="M3.87939 15.0781L11.1606 11.8616V28.947H3.87939V15.0781Z" stroke="#F2F2F2" stroke-opacity="0.75" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                        <path d="M29.348 4.05713L17.8075 11.4344L12.5015 5.23006L3.00781 10.1079" stroke="#F2F2F2" stroke-opacity="0.75" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                        <path d="M23.6104 3.08154L29.3478 4.03778L28.3917 9.77523" stroke="#F2F2F2" stroke-opacity="0.75" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>                         
                    <div class="flex flex-col items-start text-start">
                        <h1 class="text-white">Admin</h1>
                        <p class="text-sm text-zinc-500">Oversee the entire system and manage all users.</p>
                    </div>
                </button>
            </form>
        </div>
    </div>
</header>
@endsection
