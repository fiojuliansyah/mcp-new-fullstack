@extends('layouts.main')

@section('content')
    <div class="space-y-5 py-5 px-10">
        <!-- PAGE TITLE -->
        <div>
            <h1 class="text-gray-100 font-[600]">Dashboard Overview</h1>
        </div>

        <!-- PAGE CONTENT -->
        <div class="space-y-10">
            <!-- Dashboard Overview Cards -->
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <!-- Total User -->
                <div class="lg:col-span-2">
                    <div class="rounded-[21px] border border-white h-full">
                        <div
                            class="rounded-t-[21px] bg-[#2C2C2C] p-5 flex flex-col lg:flex-row items-center justify-between">
                            <h6>Total User</h6>
                            <button type="button"
                                class="bg-gray-50 hover:bg-gray-200 rounded-full text-sm px-5 py-3 cursor-pointer w-full lg:w-[150px]">
                                <span class="text-black text-[16px] font-semibold">View More</span>
                            </button>
                        </div>
                        <div class="p-4">
                            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 py-2 px-2 sm:px-5">
                                <div class="p-4 flex flex-col lg:flex-row items-center gap-3">
                                    <img src="/admin/assets/images/illustrations/student-illustration.svg"
                                        class="object-contain" alt="Illustration" />
                                    <div>
                                        <p class="text-gray-200 text-sm">Students</p>
                                        <p class="text-[#EAEAEA] text-lg">{{ $studentCount }}</p>
                                    </div>
                                </div>
                                <div class="p-4 flex flex-col lg:flex-row 	items-center gap-3">
                                    <img src="/admin/assets/images/illustrations/parent-illustration.svg"
                                        class="object-contain" alt="Illustration" />
                                    <div>
                                        <p class="text-gray-200 text-sm">Parents</p>
                                        <p class="text-[#EAEAEA] text-lg">{{ $parentCount }}</p>
                                    </div>
                                </div>
                                <div class="p-4 flex flex-col lg:flex-row items-center gap-3">
                                    <img src="/admin/assets/images/illustrations/tutor-illustration.svg"
                                        class="object-contain" alt="Illustration" />
                                    <div>
                                        <p class="text-gray-200 text-sm">Tutors</p>
                                        <p class="text-[#EAEAEA] text-lg">{{ $tutorCount }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Active Subscription -->
                <div class="rounded-[21px] border border-white p-5 flex flex-col items-center justify-center">
                    <p class="text-gray-100">Active Subscription</p>
                    <p class="text-[42px] font-medium my-3">{{ $subsCount }}</p>
                    <a href="{{ route('admin.subscriptions.index') }}"
                        class="bg-gray-50 hover:bg-gray-200 rounded-full text-sm px-5 py-3 cursor-pointer w-full lg:w-[150px]">
                        <span class="text-black text-[16px] font-semibold">View More</span>
                    </a>
                </div>
            </div>

            <!-- Divider -->
            <hr class="border-[#3A3A3A]">

            <!-- Upcoming Classes Today -->
            <div class="space-y-5">
                <!-- TABLE HEADER -->
                <div class="flex flex-col lg:flex-row items-start lg:items-center justify-between gap-3">
                    <div>
                        <h6 class="text-gray-100 font-[600]">Upcoming Classes Today</h6>
                        <p class="text-sm text-gray-200">Date: {{ now()->format('d M Y') }}</p>
                    </div>
                    <a href="{{ route('admin.classrooms.index') }}"
                        class="bg-gray-50 hover:bg-gray-200 rounded-full text-sm px-5 py-3 cursor-pointer w-full lg:w-[150px]">
                        <span class="text-black text-[16px] font-semibold">View More</span>
                    </a>
                </div>
                <!-- START : TABLE -->
                <div class="overflow-x-auto border border-[#424242] rounded-[12px] mb-10">
                    <table class="min-w-full border border-[#424242] rounded-[12px] overflow-hidden text-sm text-left">
                        <thead class="bg-gray-800 text-gray-200">
                            <tr>
                                <th class="p-4 border border-[#424242]">No</th>
                                <th class="p-4 border border-[#424242]">Group Class</th>
                                <th class="p-4 border border-[#424242]">Form</th>
                                <th class="p-4 border border-[#424242]">Topic</th>
                                <th class="p-4 border border-[#424242]">Tutor Name</th>
                                <th class="p-4 border border-[#424242]">Subject</th>
                                <th class="p-4 border border-[#424242]">Time</th>
                                <th class="p-4 border border-[#424242]">Students</th>
                                <th class="p-4 border border-[#424242]">Live URL</th>
                            </tr>
                        </thead>
                        <tbody class="bg-gray-900 divide-y divide-gray-800 text-gray-50">
                            @foreach ($schedules as $schedule)
                                <tr class="odd:bg-[#141414] even:bg-[#171717]">
                                    <td class="p-4 border border-gray-700">1</td>
                                    <td class="p-4 border border-gray-700">{{ $schedule->classroom->name }}</td>
                                    <td class="p-4 border border-gray-700">{{ $schedule->form->name }}</td>
                                    <td class="p-4 border border-gray-700">{{ $schedule->topic }}</td>
                                    <td class="p-4 border border-gray-700">{{ $schedule->classroom->user->name }}</td>
                                    <td class="p-4 border border-gray-700">{{ $schedule->classroom->subject->name }}</td>
                                    <td class="p-4 border border-gray-700">{{ $schedule->time->format('d M Y H:i') }}</td>
                                    <td class="p-4 border border-gray-700">40</td>
                                    <td class="p-4 border border-gray-700 text-green-100 max-w-[200px] truncate">
                                        {{ $schedule->zoom_start_url }}
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <!-- END : TABLE -->
            </div>

            <!-- Divider -->
            <hr class="border-[#3A3A3A]">

            <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">
                <div class="lg:col-span-4">
                    <h3 class="text-base font-semibold mb-4">Study Materials & Replays Pending Upload</h3>

                    <div class="h-[400px] overflow-y-auto pr-3">
                        @forelse ($pendingUploads as $uploadTask)
                            @php
                                $schedule = $uploadTask['schedule'];
                                $type = $uploadTask['missing_type'];
                                $isReplay = $type === 'Replay';

                                $timePassed = $schedule->time->diffForHumans(null, true);

                                $routePrefix = 'admin.' . strtolower($type) . 's.create';

                            @endphp

                            <div class="bg-[#2B1116] border border-red-600/30 rounded-xl p-5 flex flex-col gap-5 mb-4">
                                <div class="space-y-5">
                                    <div class="flex items-center gap-2">
                                        <img src="/admin/assets/icons/{{ $isReplay ? 'video-upload-white' : 'books' }}.svg"
                                            alt="{{ $type }} Icon" class="size-10">
                                        <div class="flex flex-col">
                                            <div class="flex items-center gap-2">
                                                <span class="text-sm text-[#FF2147]">{{ $type }} Upload
                                                    Pending</span>
                                                <span
                                                    class="inline-flex h-4 w-4 items-center justify-center rounded-full bg-red-600 text-white text-xs font-bold">!</span>
                                            </div>
                                            <span class="text-sm text-gray-100">{{ $timePassed }} ago</span>
                                        </div>
                                    </div>
                                    <div class="text-sm mt-3">
                                        <p class="text-gray-100">{{ $schedule->classroom->name ?? 'N/A' }},
                                            {{ $schedule->form->name ?? 'N/A' }}</p>
                                        <p class="text-gray-100">{{ $schedule->classroom->subject->name ?? 'Subject N/A' }}
                                        </p>
                                    </div>
                                </div>
                                <a href="{{ route($routePrefix, ['schedule_id' => $schedule->id]) }}"
                                    class="bg-gray-50 hover:bg-gray-200 rounded-full text-sm px-5 py-3 cursor-pointer text-center w-full">
                                    <span class="text-black text-[16px] font-semibold">Upload {{ $type }}</span>
                                </a>
                            </div>

                        @empty

                            <div class="p-5 text-gray-400 bg-gray-900 border border-gray-700 rounded-xl text-center">
                                All materials and replays have been uploaded. Great!
                            </div>
                        @endforelse

                    </div>

                </div>

                <div class="lg:col-span-8">
                    <h3 class="text-base font-semibold mb-4">Students Performance Overview</h3>
                    <div class="bg-white/5 border border-white/10 rounded-2xl p-5">
                        <!-- Top: 3 columns -->
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-8 items-center">
                            <!-- Donut -->
                            <div class="flex items-center justify-center">
                                @php
                                    $quizPercent = $performance['avgQuizScore'];
                                    $dashOffset = 282.6 - ($quizPercent / 100) * 282.6;
                                @endphp
                                <div class="relative">
                                    <svg class="w-24 h-24 -rotate-90" viewBox="0 0 100 100">
                                        <circle cx="50" cy="50" r="45" stroke="rgba(255,255,255,0.15)"
                                            stroke-width="10" fill="none" />
                                        <circle cx="50" cy="50" r="45" stroke="#8B5CF6" stroke-width="10"
                                            stroke-linecap="round" stroke-dasharray="282.6"
                                            stroke-dashoffset="{{ $dashOffset }}" fill="none" />
                                    </svg>
                                    <div class="absolute inset-0 flex items-center justify-center">
                                        <p class="text-xl font-bold">{{ $quizPercent }}%</p>
                                    </div>
                                </div>
                            </div>

                            <!-- Big number -->
                            <div class="text-center">
                                <p class="text-5xl font-semibold leading-none">{{ $performance['subjectsUnder60'] }}</p>
                                <p class="text-sm text-white/60 mt-2">Subjects under 60%</p>
                            </div>

                            <!-- Subjects bars -->
                            <div class="space-y-4 w-full">
                                @foreach ($performance['subjectsPerformance']->take(3) as $subject)
                                    <div>
                                        <div class="flex items-center justify-between text-sm mb-1">
                                            <p class="text-white/80">{{ $subject->subject->name ?? 'Unknown' }}</p>
                                            <p class="font-semibold">{{ round($subject->avg_score, 1) }}%</p>
                                        </div>
                                        <div class="h-2 w-full bg-white/10 rounded-full overflow-hidden">
                                            <div class="h-full rounded-full bg-gradient-to-r from-rose-400 to-amber-200"
                                                style="width: {{ $subject->avg_score }}%"></div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>

                        <!-- Legend -->
                        <div class="mt-6 grid grid-cols-1 sm:grid-cols-3 gap-4 text-sm text-white/80">
                            <div class="flex items-center lg:justify-center gap-2">
                                <img src="/admin/assets/icons/quiz-1.svg" alt="Icon">
                                <span>Avg Quiz Score</span>
                            </div>
                            <div class="flex items-center lg:justify-center gap-2">
                                <img src="/admin/assets/icons/book-1.svg" alt="Icon">
                                <span>Subjects under 60%</span>
                            </div>
                            <div class="flex items-center lg:justify-center gap-2">
                                <img src="/admin/assets/icons/graduation-1.svg" alt="Icon">
                                <span>{{ $performance['lowAttendanceStudents'] }} students low attendance</span>
                            </div>
                        </div>

                        <div class="mt-6">
                            <a href="{{ route('admin.performances.index') }}"
                                class="flex justify-center items-center bg-gray-50 hover:bg-gray-200 rounded-full text-sm px-5 py-3 cursor-pointer w-full">
                                <span class="text-black text-[16px] font-semibold">View All</span>
                            </a>

                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
@endsection
