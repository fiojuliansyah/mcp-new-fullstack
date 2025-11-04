@extends('layouts.main')

@section('content')
    <div class="space-y-5 py-5 px-10">
        <!-- PAGE TITLE -->
        <div class="space-y-7 flex flex-col">
            <h1 class="text-gray-100 font-[600]">Report</h1>
        </div>

        <!-- PAGE CONTENT -->
        <div class="space-y-10 divide-y divide-gray-200">
            <div class="grid grid-cols-1 md:grid-cols-1 lg:grid-cols-4 gap-8">
                <!-- Card: Revenue -->
                <div class="lg:col-span-1 rounded-[20px] border border-[#2A2A2A] bg-[#1A1A1A] p-5 lg:p-7 flex flex-col">
                    <p class="text-gray-100">Revenue</p>
                    <p class="text-gray-200 mt-3">{{ now()->format('d M Y') }}</p>
                    <p class="text-green-100 text-xl font-semibold mt-1">RM{{ number_format($todayRevenue, 2) }}</p>
                    <p class="text-gray-400 text-sm mt-1">Total: RM{{ number_format($totalRevenue, 2) }}</p>
                    <div class="mt-6">
                        <a href="{{ route('admin.reports.subscription') }}"
                            class="block w-full bg-white hover:bg-gray-200 text-black font-semibold rounded-full py-3 text-center">
                            View Subscription
                        </a>
                    </div>
                </div>

                <!-- Card: Student Performance -->
                <div class="lg:col-span-2 rounded-[20px] border border-[#2A2A2A] bg-[#1A1A1A] p-5 lg:p-7 flex flex-col">
                    <p class="text-gray-100">Student Performance</p>
                    <div class="grid grid-cols-2 gap-5 mt-4">
                        <div>
                            <p class="text-gray-200">Quiz Score</p>
                            <div class="mt-1 flex items-center gap-2">
                                <img src="/admin/assets/icons/quiz.svg" alt="Icon" class="size-6">
                                <span class="text-white font-semibold">{{ $avgQuizScore }}%</span>
                            </div>
                        </div>
                        <div>
                            <p class="text-gray-200">Subject Progress</p>
                            <div class="mt-1 flex items-center gap-2">
                                <img src="/admin/assets/icons/books.svg" alt="Icon" class="size-6">
                                <span class="text-white font-semibold">{{ $subjectProgress }}%</span>
                            </div>
                        </div>
                    </div>
                    <div class="mt-6">
                        <a href="{{ route('admin.reports.performance') }}"
                            class="block w-full bg-white hover:bg-gray-200 text-black font-semibold rounded-full py-3 text-center">
                            View Performance
                        </a>
                    </div>
                </div>

                <!-- Card: Replay Usage -->
                <div class="lg:col-span-1 rounded-[20px] border border-[#2A2A2A] bg-[#1A1A1A] p-5 lg:p-7 flex flex-col">
                    <p class="text-gray-100">Replay Usage</p>
                    <p class="text-gray-200 mt-4">Replays Watched</p>
                    <div class="mt-1 flex items-center gap-2">
                        <img src="/admin/assets/icons/replay.svg" alt="Icon" class="size-6">
                        <span class="text-white font-semibold">{{ $replayUsage }}%</span>
                    </div>
                    <div class="mt-6">
                        <a href="{{ route('admin.reports.replay') }}"
                            class="block w-full bg-white hover:bg-gray-200 text-black font-semibold rounded-full py-3 text-center">
                            View Report
                        </a>
                    </div>
                </div>

                <!-- Card: Class Attendance -->
                <div class="lg:col-span-2 rounded-[20px] border border-[#2A2A2A] bg-[#1A1A1A] p-5 lg:p-7">
                    <p class="text-gray-100">Class Attendance</p>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-6 mt-4">
                        <div>
                            <p class="text-gray-300 text-sm">Top Class Attendance</p>
                            <p class="text-white font-semibold mt-1">
                                {{ $topClass->classroom->name ?? '-' }} - {{ $topClass->form->name ?? '-' }}
                            </p>
                        </div>
                        <div>
                            <p class="text-gray-300 text-sm">Low Class Attendance</p>
                            <p class="text-white font-semibold mt-1">
                                {{ $lowClass->classroom->name ?? '-' }} - {{ $topClass->form->name ?? '-' }}
                            </p>
                        </div>
                    </div>
                    <div class="mt-6">
                        <a href="{{ route('admin.attendances.index') }}"
                            class="block w-full bg-white hover:bg-gray-200 text-black font-semibold rounded-full py-3 text-center">
                            View Attendance
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
