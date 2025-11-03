@extends('layouts.app')

@section('content')
    <section class="w-full bg-primary text-white px-4 py-10">
        <div class="w-full max-w-screen-xl mx-auto">
            <!-- WELCOME BACK -->
            <div class="flex items-center border-b border-zinc-800 space-x-5">
                <img src="/frontend/assets/images/parent-profile-vector.svg" alt="Tutor Avatar" class="w-28" />
                <div>
                    <p class="text-sm text-gray-400">Parent Dashboard</p>
                    <h1 class="text-xl md:text-4xl font-bold">My Profile</h1>
                </div>
            </div>
            <!-- PROFILE -->
            <div class="mt-10">
                <!-- GRID -->
                <div class="w-full grid grid-cols-1 lg:grid-cols-4 gap-5 lg:divide-x lg:divide-zinc-700">
                    <!-- LEFT -->
                    <div class="w-full">
                        <div>
                            <h2 class="flex items-center space-x-3 text-lg">
                                <svg class="w-4" viewBox="0 0 20 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path
                                        d="M17.7642 5.2339L15.0074 6.25421C14.9126 7.51597 15.1358 8.75453 14.7512 9.98219C13.278 14.6868 6.39287 14.7421 4.83315 10.0599C4.41627 8.8091 4.64772 7.54325 4.55225 6.25421L0.609352 4.75034C0.00359716 4.42637 0.0406841 3.5411 0.687647 3.29352L9.52122 0L10.0789 0.0102304L19.0478 3.374C19.3081 3.55951 19.3871 3.77299 19.4138 4.08331C19.2971 5.97458 19.5629 8.03568 19.4138 9.90716C19.3596 10.5844 18.7854 11.1437 18.1268 10.7195C18.0389 10.6629 17.7635 10.3443 17.7635 10.2625V5.2339H17.7642ZM16.2182 4.00897L9.75817 1.66279L3.34074 4.03421L9.79732 6.44723C11.8323 5.62538 13.9387 4.96791 15.9744 4.14879C16.0437 4.12082 16.2065 4.08604 16.2189 4.00897H16.2182ZM13.3343 6.86736C12.1324 7.24929 10.9814 7.80992 9.76298 8.12434L6.22597 6.86736V8.93527C6.22597 9.21149 6.54671 9.94399 6.69986 10.2018C8.08582 12.5412 11.561 12.5071 12.8975 10.1363C13.0362 9.89011 13.3343 9.19376 13.3343 8.93596V6.86804V6.86736Z"
                                        fill="white" />
                                    <path
                                        d="M17.9891 23.1346C17.8675 23.0207 17.783 22.8324 17.7652 22.6674C17.6835 21.9178 17.8229 21.0325 17.7672 20.2659C17.6368 18.4633 16.1038 17.1279 14.5551 16.4043C14.2584 16.2658 13.3731 15.89 13.0902 15.8594C13.0325 15.8532 12.9899 15.8532 12.9473 15.8989C12.2976 16.4643 11.7028 17.2568 11.0414 17.7902C10.641 18.1128 10.1232 18.2976 9.60123 18.2505C8.38904 18.1414 7.46598 16.5489 6.55117 15.8594C4.49902 16.3634 1.9723 17.9204 1.79785 20.2175C1.74222 20.9534 1.92353 22.102 1.78549 22.7581C1.63096 23.4926 0.464778 23.6058 0.215471 22.8058C0.0266017 22.2015 0.145418 20.0518 0.275909 19.3725C0.747738 16.9254 3.6522 14.8356 5.988 14.3132C7.71804 13.9258 8.43917 15.6425 9.5978 16.5687C9.70494 16.6451 9.85878 16.6444 9.96455 16.5687C10.6143 16.0019 11.2083 15.2148 11.8663 14.676C12.6595 14.0267 13.3566 14.2198 14.2378 14.5233C16.5935 15.3342 19.0413 17.1982 19.3613 19.809C19.4492 20.5272 19.4842 21.7957 19.4066 22.5078C19.3242 23.2689 18.5674 23.6788 17.9891 23.1346Z"
                                        fill="white" />
                                </svg>
                                <span>My Children</span>
                            </h2>
                            <p class="text-sm text-zinc-500 mt-2">View progress for</p>
                        </div>
                        <div class="w-full space-y-5 rounded-2xl pt-5 bg-[#131313] mt-5 px-6 py-10">

                            @forelse ($parent->children as $child)
                                @php
                                    $isSelected = isset($selectedChild) && $selectedChild->slug === $child->slug;

                                    $defaultClasses = 'bg-[#232323] hover:bg-[#333333]';
                                    $selectedClasses = 'bg-black hover:bg-black';

                                    $cardClasses = $isSelected ? $selectedClasses : $defaultClasses;
                                    $textColor = $isSelected ? 'text-white' : 'text-zinc-100';
                                @endphp
                                <a href="{{ route('parent.dashboard.child', $child->slug) }}"
                                    class="w-full flex items-center space-x-3 lg:space-x-5 bg-gray-secondary rounded-md px-4 py-3 {{ $cardClasses }} rounded-xl">
                                    <div
                                        class="w-12 min-w-12 h-12 lg:w-16 lg:min-w-16 lg:min-h-16 flex justify-center items-center bg-white rounded-full">
                                        <img src="{{ $child->avatar_url }}" class="rounded-full">
                                    </div>
                                    <div class="space-y-2">
                                        <p class="text-sm text-zinc-100">{{ $child->name }}</p>
                                        <p class="text-sm text-zinc-100">{{ $child->form->name }}</p>
                                    </div>
                                </a>
                            @empty
                            @endforelse
                        </div>
                    </div>
                    <!-- RIGHT -->
                    @if (isset($selectedChild))
                        @php
                            $metrics = $childMetrics;
                        @endphp
                        <div class="w-full lg:col-span-3 space-y-5">
                            <div class="w-full md:pl-5">
                                <div class="flex items-center space-x-5 text-lg">
                                    <svg class="w-8" viewBox="0 0 20 24" fill="none"
                                        xmlns="http://www.w3.org/2000/svg">
                                        <path
                                            d="M17.7642 5.2339L15.0074 6.25421C14.9126 7.51597 15.1358 8.75453 14.7512 9.98219C13.278 14.6868 6.39287 14.7421 4.83315 10.0599C4.41627 8.8091 4.64772 7.54325 4.55225 6.25421L0.609352 4.75034C0.00359716 4.42637 0.0406841 3.5411 0.687647 3.29352L9.52122 0L10.0789 0.0102304L19.0478 3.374C19.3081 3.55951 19.3871 3.77299 19.4138 4.08331C19.2971 5.97458 19.5629 8.03568 19.4138 9.90716C19.3596 10.5844 18.7854 11.1437 18.1268 10.7195C18.0389 10.6629 17.7635 10.3443 17.7635 10.2625V5.2339H17.7642ZM16.2182 4.00897L9.75817 1.66279L3.34074 4.03421L9.79732 6.44723C11.8323 5.62538 13.9387 4.96791 15.9744 4.14879C16.0437 4.12082 16.2065 4.08604 16.2189 4.00897H16.2182ZM13.3343 6.86736C12.1324 7.24929 10.9814 7.80992 9.76298 8.12434L6.22597 6.86736V8.93527C6.22597 9.21149 6.54671 9.94399 6.69986 10.2018C8.08582 12.5412 11.561 12.5071 12.8975 10.1363C13.0362 9.89011 13.3343 9.19376 13.3343 8.93596V6.86804V6.86736Z"
                                            fill="white" />
                                        <path
                                            d="M17.9891 23.1346C17.8675 23.0207 17.783 22.8324 17.7652 22.6674C17.6835 21.9178 17.8229 21.0325 17.7672 20.2659C17.6368 18.4633 16.1038 17.1279 14.5551 16.4043C14.2584 16.2658 13.3731 15.89 13.0902 15.8594C13.0325 15.8532 12.9899 15.8532 12.9473 15.8989C12.2976 16.4643 11.7028 17.2568 11.0414 17.7902C10.641 18.1128 10.1232 18.2976 9.60123 18.2505C8.38904 18.1414 7.46598 16.5489 6.55117 15.8594C4.49902 16.3634 1.9723 17.9204 1.79785 20.2175C1.74222 20.9534 1.92353 22.102 1.78549 22.7581C1.63096 23.4926 0.464778 23.6058 0.215471 22.8058C0.0266017 22.2015 0.145418 20.0518 0.275909 19.3725C0.747738 16.9254 3.6522 14.8356 5.988 14.3132C7.71804 13.9258 8.43917 15.6425 9.5978 16.5687C9.70494 16.6451 9.85878 16.6444 9.96455 16.5687C10.6143 16.0019 11.2083 15.2148 11.8663 14.676C12.6595 14.0267 13.3566 14.2198 14.2378 14.5233C16.5935 15.3342 19.0413 17.1982 19.3613 19.809C19.4492 20.5272 19.4842 21.7957 19.4066 22.5078C19.3242 23.2689 18.5674 23.6788 17.9891 23.1346Z"
                                            fill="white" />
                                    </svg>
                                    <div>
                                        <p class="text-sm text-white">{{ $selectedChild->name }}</p>
                                        <p class="text-sm text-zinc-500">{{ $selectedChild->form->name }}</p>
                                    </div>
                                </div>
                                <div class="w-full bg-gray-secondary rounded-lg p-5 md:p-10">
                                    <div class="flex items-center justify-between mb-8">
                                        <div class="flex items-center gap-3">
                                            <img src="/frontend/assets/icons/calendar.svg" alt="Icon" class="size-6">
                                            <h6 class="text-[20px] text-gray-100 font-semibold">Child's Attendance Record
                                            </h6>
                                        </div>
                                        <div class="flex space-x-3">
                                            <div class="flex space-x-3">
                                                @php
                                                    $currentMonth = date('m');
                                                    $currentYear = date('Y');
                                                    $months = [
                                                        '01' => 'January',
                                                        '02' => 'February',
                                                        '03' => 'March',
                                                        '04' => 'April',
                                                        '05' => 'May',
                                                        '06' => 'June',
                                                        '07' => 'July',
                                                        '08' => 'August',
                                                        '09' => 'September',
                                                        '10' => 'October',
                                                        '11' => 'November',
                                                        '12' => 'December',
                                                    ];
                                                    $yearRange = range($currentYear, $currentYear - 5);
                                                @endphp

                                                <select id="month-select"
                                                    class="bg-[#131313] border border-zinc-700 text-sm rounded-lg text-white p-2">
                                                    @foreach ($months as $num => $name)
                                                        <option value="{{ $num }}"
                                                            {{ $num == $currentMonth ? 'selected' : '' }}>
                                                            {{ $name }}</option>
                                                    @endforeach
                                                </select>

                                                <select id="year-select"
                                                    class="bg-[#131313] border border-zinc-700 text-sm rounded-lg text-white p-2">
                                                    @foreach ($yearRange as $year)
                                                        <option value="{{ $year }}"
                                                            {{ $year == $currentYear ? 'selected' : '' }}>
                                                            {{ $year }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="grid grid-cols-12 gap-5 bg-gray-800 rounded-[21px] p-6 h-full">
                                        <div class="col-span-12 lg:col-span-5">
                                            <div class="rounded-[21px] border border-black w-auto">
                                                <div class="px-5 py-3 rounded-t-[21px] bg-black">
                                                    <span class="text-white text-[15px]">Class Status</span>
                                                </div>
                                                <div class="grid grid-cols-12 p-5 gap-5">
                                                    <div class="col-span-12 flex items-center gap-3">
                                                        <div class="w-5 h-5 bg-green-100 rounded-full"></div>
                                                        <span class="text-green-100 text-[15px]">Attend</span>
                                                    </div>
                                                    <div class="col-span-6 flex items-center gap-3">
                                                        <div class="w-5 h-5 bg-red-100 rounded-full"></div>
                                                        <span class="text-red-100 text-[15px]">Absent</span>
                                                    </div>
                                                    <div class="col-span-12 flex items-center gap-3">
                                                        <div class="w-5 h-5 bg-white rounded-full"></div>
                                                        <span class="text-white text-[15px]">Live Class</span>
                                                    </div>
                                                    <div class="col-span-6 flex items-center gap-3">
                                                        <div class="w-5 h-5 bg-[#424242] rounded-full"></div>
                                                        <span class="text-[#424242] text-[15px]">No Class</span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-span-12 lg:col-span-7">
                                            <div id="calendar" class="h-[13rem]"></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="w-full md:pl-5">
                                <div class="w-full bg-gray-secondary rounded-lg p-5 md:p-10">
                                    <div class="flex items-center justify-between">
                                        <div class="flex items-center gap-3">
                                            <img src="/frontend/assets/icons/calendar.svg" alt="Icon" class="size-6">
                                            <h6 class="text-[20px] text-gray-100 font-semibold">Learning Overview
                                            </h6>
                                        </div>
                                        <div class="flex space-x-3">
                                            <div class="flex space-x-3">
                                                <form method="GET" action="#"
                                                    class="flex items-center w-full lg:w-[350px] bg-white border border-gray-280 rounded-full px-2 py-2">
                                                    <div
                                                        class="flex items-center justify-center w-8 h-8 rounded-full bg-black text-white mr-3">
                                                        <img src="/admin/assets/icons/search.svg" alt="Icon">
                                                    </div>
                                                    <input type="text" name="search" value="{{ request('search') }}"
                                                        class="flex-1 bg-transparent text-gray-700 placeholder:text-[#A6A1A1] focus:outline-none"
                                                        placeholder="Search" />
                                                    @if (request('search'))
                                                        <a href="#" class="text-gray-500 text-sm pr-3">✕</a>
                                                    @endif
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="w-full bg-gray-secondary rounded-lg p-5 md:p-10">
                                    <div class="h-[60vh] lg:h-[90vh] overflow-y-auto mb-5">
                                        <div id="tab1" data-group="group-tabs-1" class="tab-content flex flex-col">
                                            <div class="border radius-xl rounded-[21px] p-8 mt-10 grid grid-cols-12">
                                                <div class="col-span-12 lg:col-span-7 lg:pr-10">
                                                    <div class="grid grid-cols-12 gap-5">
                                                        <div class="col-span-12 lg:col-span-4">
                                                            <div class="w-full">
                                                                <img src="{{ $selectedChild->avatar_url ?? '/frontend/assets/images/default-avatar.png' }}"
                                                                    alt="Image"
                                                                    class="w-full object-cover lg:w-[120px] lg:h-[170px] rounded-[13px]" />
                                                            </div>
                                                        </div>
                                                        <div class="col-span-12 lg:col-span-8">
                                                            <div class="text-white mb-3">{{ $metrics['subject_name'] ?? 'N/A' }}</div>
                                                            <span class="text-gray-200">{{ $metrics['tutor_name'] ?? 'N/A' }}</span>
                                                            <div class="flex items-center gap-2 mb-5">
                                                                <span class="text-gray-275 text-[15px]">Topics
                                                                    Covers:</span>
                                                                {{-- TOPICS COVERED --}}
                                                                <span class="text-white text-[15px]">{{ $metrics['topics_covered'] ?? 0 }}/{{ $metrics['total_schedules'] ?? 0 }}</span>
                                                            </div>
                                                            <div
                                                                class="w-full lg:border border-[#523E06] rounded-full h-5">
                                                                {{-- OVERALL PROGRESS BAR --}}
                                                                <div class="bg-[#523E06] h-5 rounded-full flex items-center justify-end px-3 transition-all duration-500"
                                                                    style="width: {{ $metrics['overall_progress'] ?? 0 }}%">
                                                                    <span class="text-white text-[12px] font-medium">{{ $metrics['overall_progress'] ?? 0 }}%</span>
                                                                </div>
                                                            </div>
                                                            <div class="flex items-center gap-2 mt-5">
                                                                <span class="text-gray-275 text-[15px]">Class</span>
                                                                {{-- ATTENDANCE COUNT --}}
                                                                <span class="text-white text-[15px]">
                                                                    {{ $metrics['attendance_count'] ?? 0 }} Attend | {{ $metrics['absent_count'] ?? 0 }} Absent
                                                                </span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div
                                                    class="col-span-12 lg:col-span-5 border radius-xl rounded-xl border-gray-300 p-8 mt-6 lg:mt-0">
                                                    {{-- AVG QUIZ SCORE --}}
                                                    <div class="flex items-center justify-between mb-6">
                                                        <div class="flex items-center gap-3">
                                                            <img src="/frontend/assets/icons/quiz.svg" alt="Icon"
                                                                class="size-4">
                                                            <h6 class="text-white">Avg Quiz Score
                                                            </h6>
                                                        </div>
                                                        <span class="text-white text-xl font-bold">{{ $metrics['avg_quiz_score'] ?? 0 }}%</span>
                                                    </div>
                                                    
                                                    {{-- AVG REPLAY WATCHED --}}
                                                    <div class="flex items-center justify-between mb-6">
                                                        <div class="flex items-center gap-3">
                                                            <img src="/frontend/assets/icons/replay.svg" alt="Icon"
                                                                class="size-4">
                                                            <h6 class="text-white">Avg Replay Watched
                                                            </h6>
                                                        </div>
                                                        <span class="text-white text-xl font-bold">{{ $metrics['avg_replay_watch'] ?? 0 }}%</span>
                                                    </div>

                                                    {{-- ATTENDANCE RATE (As a score) --}}
                                                    <div class="flex items-center justify-between mb-6">
                                                        <div class="flex items-center gap-3">
                                                            <img src="/frontend/assets/icons/calendar.svg" alt="Icon"
                                                                class="size-4">
                                                            <h6 class="text-white">Attendance Rate
                                                            </h6>
                                                        </div>
                                                        <span class="text-white text-xl font-bold">{{ $metrics['attendance_rate'] ?? 0 }}%</span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div id="tab2" data-group="group-tabs-1" class="tab-content">

                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @else
                        <div class="w-full lg:col-span-3 space-y-5">
                            <div class="p-5 md:p-10">
                                <div class="w-full bg-gray-secondary rounded-lg p-5 md:p-10 text-center">
                                    <img src="/frontend/assets/icons/no-progress.svg" alt="Tutor Avatar"
                                        class="w-full max-w-xs mx-auto" />
                                    <p class="text-sm text-zinc-500 mt-5">Please Select Children To View The Progress</p>
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
                @if (isset($selectedChild))
                <div class="w-full">
                    <div class="w-full bg-gray-secondary rounded-lg p-5 md:p-10">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center gap-3">
                                <img src="/frontend/assets/icons/calendar.svg" alt="Icon" class="size-6">
                                <h6 class="text-[20px] text-gray-100 font-semibold">Payment History
                                </h6>
                            </div>
                            <div class="flex space-x-3">
                                <div class="flex space-x-3">
                                    <form method="GET" action="#"
                                        class="flex items-center w-full lg:w-[350px] bg-white border border-gray-280 rounded-full px-2 py-2">
                                        <div
                                            class="flex items-center justify-center w-8 h-8 rounded-full bg-black text-white mr-3">
                                            <img src="/admin/assets/icons/search.svg" alt="Icon">
                                        </div>
                                        <input type="text" name="search" value="{{ request('search') }}"
                                            class="flex-1 bg-transparent text-gray-700 placeholder:text-[#A6A1A1] focus:outline-none"
                                            placeholder="Search" />
                                        @if (request('search'))
                                            <a href="#" class="text-gray-500 text-sm pr-3">✕</a>
                                        @endif
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="w-full bg-gray-secondary rounded-lg p-5 md:p-10">
                        <div class="grid grid-cols-12 gap-5">

                            <div class="col-span-12 lg:col-span-8">
                                <div class="bg-[#161616] rounded-2xl p-6">

                                    <h3 class="text-xl font-bold text-white mb-6">Active Subscriptions</h3>

                                    <div class="space-y-4">
                                        @forelse ($selectedChild->subscriptions as $subscription)
                                        @php
                                            $subjectNames = $subscription->classrooms->map(function ($classroom) {
                                                return $classroom->subject->name ?? 'Unknown Subject';
                                            })->implode(', ');
                                        @endphp
                                        <div class="flex items-center justify-between p-4">
                                        
                                            <div class="flex items-center space-x-4">
                                                <div>
                                                    <p class="text-white font-semibold mb-3">
                                                        {{ $subjectNames }}
                                                    </p>
                                                    <p class="text-sm text-zinc-400">
                                                        {{ $subscription->created_at->format('d M Y') }}
                                                    </p>
                                                    <p class="text-sm text-zinc-400">
                                                        Total : <span class="text-[#28B700]">RM{{ $subscription->subtotal }}</span>
                                                    </p>
                                                </div>
                                            </div>

                                            <div class="text-right">
                                                <button type="button"
                                                    class="bg-gray-50 hover:bg-gray-200 rounded-full text-sm px-5 py-3 w-full cursor-pointer">
                                                    <span class="text-black text-[16px] font-semibold">Download Receipt</span>
                                                </button>
                                            </div>
                                        </div>
                                        @empty
                                            <div class="p-8 text-center">
                                                <p class="text-zinc-400">This child currently has no active subscriptions.
                                                </p>
                                                <a href="#"
                                                    class="mt-4 inline-block text-gray-500 font-semibold hover:text-blue-400">
                                                    Browse Plans
                                                </a>
                                            </div>
                                        @endforelse
                                    </div>

                                </div>
                            </div>

                            <div class="col-span-12 lg:col-span-4">
                                <div class="bg-[#161616] rounded-2xl">
                                    <div class="p-5 md:p-10">
                                        <div class="w-full bg-gray-secondary rounded-lg p-5 md:p-10 text-center">

                                            <img src="/frontend/assets/images/receipt.svg" alt="Receipt Icon"
                                                class="w-full max-w-xs mx-auto" />

                                            <button type="button"
                                                class="bg-gray-50 hover:bg-gray-200 rounded-full text-sm px-5 py-3 mt-10 w-full cursor-pointer">
                                                <span class="text-black text-[16px] font-semibold">Download All</span>
                                            </button>

                                            <p class="text-sm text-zinc-500 mt-5">{{ $selectedChild->subscriptions->count() }} Receipts</p>
                                        </div>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
                @else
                @endif
            </div>
        </div>
    </section>
@endsection

@push('scripts')
    <link href="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.9/index.global.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.9/index.global.min.js"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const calendarEl = document.getElementById('calendar');

            @if (isset($selectedChild))
                const monthSelect = document.getElementById('month-select');
                const yearSelect = document.getElementById('year-select');

                const calendar = new FullCalendar.Calendar(calendarEl, {
                    initialView: 'dayGridMonth',
                    timeZone: 'Asia/Jakarta',
                    locale: 'id',
                    height: '20rem',
                    headerToolbar: {
                        left: 'prev,next',
                        center: 'title',
                        right: ''
                    },
                    selectable: true,
                    editable: false,

                    initialDate: `${yearSelect.value}-${monthSelect.value}-01`
                });

                calendar.render();

                const updateCalendarDate = () => {
                    const month = monthSelect.value;
                    const year = yearSelect.value;
                    const newDate = `${year}-${month}-01`;

                    calendar.gotoDate(newDate);
                };

                monthSelect.addEventListener('change', updateCalendarDate);
                yearSelect.addEventListener('change', updateCalendarDate);
            @endif
        });
    </script>
@endpush
