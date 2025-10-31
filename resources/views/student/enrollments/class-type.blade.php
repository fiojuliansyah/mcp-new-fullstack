@extends('layouts.app')

@section('content')
<section class="w-full bg-primary text-white px-4 py-10">
    <div class="w-full max-w-screen-xl mx-auto">
        <!-- HEADER -->
        <div class="flex flex-col lg:flex-row justify-between gap-5 lg:items-end border-b border-white/10">
            <div class="flex items-center gap-3">
                <img src="/frontend/assets/images/student-profile-vector.svg" alt="Tutor Avatar" class="w-28" />
                <div>
                    <span class="text-gray-250">Student</span>
                    <h1 class="text-4xl font-bold tracking-tight text-white">Subject Enrollment</h1>
                </div>
            </div>
            <div class="flex items-center gap-1 mb-3">
                <span class="text-gray-910 text-[15px] font-medium">My Profile </span>
                <span class="text-white text-[15px font-medium">> Subject Enrolment</span>
            </div>
        </div>

        <div class="space-y-10 divide-y divide-zinc-700">
            <div class="pt-10">
                <div class="mb-6">
                    <div class="flex items-center gap-3 mb-2">
                        <img src="/frontend/assets/icons/books.svg" alt="Icon" class="size-6">
                        <h6 class="text-[20px] text-gray-75 font-semibold">Subject Enrolment</h6>
                    </div>
                    <span class="text-gray-250 text-[15px]">Choose your subscription type</span>
                </div>
                <div class="grid grid-cols-12 gap-5">
                    <div class="col-span-12 lg:col-span-4">
                        <div class="bg-gray-990 rounded-[21px] p-8">
                            <a href="{{ route('student.enrollment.checkout') }}"
                                class="bg-gray-700 rounded-[21px] p-5 flex items-center gap-5 shadow-1">
                                <div
                                    class="bg-gray-50 rounded-full w-[92px] h-[92px] flex items-center justify-center">
                                    <img src="/frontend/assets/icons/presentation.svg" alt="Icon"
                                        class="size-10 text-black">
                                </div>
                                <div class="flex flex-col">
                                    <span class="text-white">Normal Classes</span>
                                    <span class="text-gray-275">Weekly Live Class</span>
                                </div>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection