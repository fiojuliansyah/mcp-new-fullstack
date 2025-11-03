@extends('layouts.main')

@section('content')
    <div class="space-y-5 py-5 px-10">
        <div class="space-y-7 flex flex-col">
            <h1 class="text-gray-100 font-[600]">User Management</h1>
            <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-3">
                <div class="flex items-center gap-3">
                    <a href="{{ route('admin.users.student') }}"
                        class="bg-white rounded-full w-8 h-8 flex items-center justify-center cursor-pointer">
                        <img src="/admin/assets/icons/arrow-left.svg" alt="Icon" class="size-4">
                    </a>
                    <h6 class="text-gray-100 font-[600]">View Student Details</h6>
                </div>
            </div>
        </div>

        <div class="space-y-10">
            <div class="rounded-[21px] border border-[#2A2A2A] h-full">
                <div class="rounded-t-[21px] bg-[#2C2C2C] py-3 px-8">
                    <span>Student Details</span>
                </div>
                <div class="grid grid-cols-7 gap-5 p-5">
                    <div class="col-span-7 lg:col-span-1 flex items-center justify-center">
                        @if ($student->avatar_url)
                            <img src="{{ $student->avatar_url }}" alt="Icon"
                                class="rounded-full w-[109px] h-[109px] flex items-center justify-center">
                        @else
                            <div class="bg-gray-50 rounded-full w-[109px] h-[109px] flex items-center justify-center">
                                <img src="/admin/assets/icons/student-black.svg" alt="Icon" class="w-10 text-black">
                            </div>
                        @endif
                    </div>
                    <div class="col-span-7 lg:col-span-6">
                        <div class="grid grid-cols-2 gap-5">
                            <div class="col-span-2 flex flex-col">
                                <span class="text-gray-200">Student Name</span>
                                <span class="text-[#EAEAEA]">{{ $student->name ?? '-' }}</span>
                            </div>
                            <div class="col-span-2 lg:col-span-1 flex flex-col">
                                <span class="text-gray-200">Email</span>
                                <span class="text-[#EAEAEA]">{{ $student->email ?? '-' }}</span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="grid grid-cols-12 gap-5 p-5">
                    <div class="col-span-3 flex flex-col">
                        <span class="text-gray-200">Status</span>
                        <span class="text-green-100">Active</span>
                    </div>
                    <div class="col-span-3 flex flex-col">
                        <span class="text-gray-200">Subscription Plan</span>        
                        @if ($latestSubscription)
                            <span class="text-[#EAEAEA]">{{ $latestSubscription->plan?->name ?? 'N/A' }}</span>
                        @else
                            <span class="text-[#EAEAEA]">- No Plan -</span>
                        @endif
                    </div>
                    <div class="col-span-3 flex flex-col">
                        <span class="text-gray-200">Date</span>        
                        @if ($latestSubscription)
                            <span class="text-[#EAEAEA]">{{ $latestSubscription->start_date->format('d M Y') ?? '-' }} - {{ $latestSubscription->end_date->format('d M Y') ?? '-' }}</span>
                        @else
                            <span class="text-[#EAEAEA]">-</span>
                        @endif
                    </div>
                    <div class="col-span-3 flex flex-col">
                        <span class="text-gray-200">Payment Status</span>        
                        @if ($latestSubscription)
                            <span class="text-[#EAEAEA]">{{ $latestSubscription->payment_status ?? '-' }}</span>
                        @else
                            <span class="text-[#EAEAEA]">-</span>
                        @endif
                    </div>
                </div>
            </div>

            <div class="space-y-5">
                <div class="flex items-center justify-between gap-3">
                    <div>
                        <h6 class="font-[600]">Subject Enrolled</h6>
                        <p class="text-gray-910">1 Schedule</p>
                    </div>
                </div>

                <div class="overflow-x-auto border border-[#424242] rounded-[12px] mb-10">
                    <table class="min-w-full border border-[#424242] rounded-[12px] overflow-hidden text-sm text-left">
                        <thead class="bg-gray-800 text-gray-200">
                            <tr>
                                <th class="p-4 border border-[#424242]">No</th>
                                <th class="p-4 border border-[#424242]">Class Group</th>
                                <th class="p-4 border border-[#424242]">Class Date</th>
                                <th class="p-4 border border-[#424242]">Topic</th>
                                <th class="p-4 border border-[#424242]">Attendance</th>
                                <th class="p-4 border border-[#424242]">Quiz Scores</th>
                                <th class="p-4 border border-[#424242]">Replay Views</th>
                            </tr>
                        </thead>
                        <tbody class="bg-gray-900 divide-y divide-gray-800 text-gray-50">

                            @php $scheduleNo = 1; $hasSchedule = false; @endphp

                            @foreach ($student->subscriptions as $subscription)
                                @foreach ($subscription->classrooms as $classroom)
                                    @foreach ($classroom->schedules as $schedule)
                                        @php
                                            $hasSchedule = true;

                                            $attendance = $schedule->attendances->first();
                                            $attendanceStatus = $attendance?->status ?? 'pending';
                                            $statusColor = match ($attendanceStatus) {
                                                'present' => 'text-green-500',
                                                'late' => 'text-yellow-500',
                                                'absent' => 'text-red-500',
                                                default => 'text-gray-400',
                                            };

                                            $quizAttempts = $schedule->quizzes->flatMap(fn($q) => $q->attempts);
                                            $avgQuizScore = $quizAttempts->avg('score');
                                            $quizScoreDisplay = $avgQuizScore !== null ? round($avgQuizScore) . '%' : '-';
                                            $totalAttempts = $quizAttempts->count();

                                            $videoViews = $schedule->replays->flatMap(fn($r) => $r->replayVideos)->flatMap(fn($v) => $v->views);
                                            $maxWatchedDuration = $videoViews->max('duration_watched') ?? 0;
                                            $videoDuration = $schedule->replays->flatMap(fn($r) => $r->replayVideos)->avg('duration') ?? 1;

                                            $replayRate = 0;
                                            if ($videoDuration > 0 && $videoViews->isNotEmpty()) {
                                                $replayRate = min(100, round(($maxWatchedDuration / $videoDuration) * 100));
                                            }
                                        @endphp

                                        <tr class="odd:bg-[#141414] even:bg-[#171717]">
                                            <td class="p-4 border border-gray-700">{{ $scheduleNo++ }}</td>
                                            <td class="p-4 border border-gray-700">
                                                <span class="font-semibold">{{ $classroom->subject->name ?? '-' }}</span>
                                                <br>
                                                <span class="text-gray-400 text-xs">{{ $classroom->name ?? '-' }}</span>
                                            </td>
                                            <td class="p-4 border border-gray-700">
                                                {{ \Carbon\Carbon::parse($schedule->date)->format('d M Y') ?? '-' }}
                                                <br>
                                                <span class="text-gray-400 text-xs">{{ \Carbon\Carbon::parse($schedule->start_time)->format('H:i') }} - {{ \Carbon\Carbon::parse($schedule->end_time)->format('H:i') }}</span>
                                            </td>
                                            <td class="p-4 border border-gray-700">{{ $schedule->topic ?? '-' }}</td>
                                            <td class="p-4 border border-gray-700">
                                                <span class="{{ $statusColor }} capitalize">{{ $attendanceStatus }}</span>
                                                @if ($attendanceStatus === 'late')
                                                     <br><span class="text-gray-400 text-xs">(Joined: {{ \Carbon\Carbon::parse($attendance->join_time)->format('H:i') }})</span>
                                                @endif
                                            </td>
                                            <td class="p-4 border border-gray-700">
                                                <span class="font-semibold">{{ $quizScoreDisplay }}</span>
                                                <br>
                                                <span class="text-gray-400 text-xs">({{ $totalAttempts }} attempt{{ $totalAttempts !== 1 ? 's' : '' }})</span>
                                            </td>
                                            <td class="p-4 border border-gray-700">
                                                <span class="font-semibold">{{ $replayRate }}%</span>
                                            </td>
                                        </tr>
                                    @endforeach
                                @endforeach
                            @endforeach

                            @if (!$hasSchedule)
                                <tr>
                                    <td colspan="7" class="text-center py-6 text-gray-500">No schedules found for enrolled subjects.</td>
                                </tr>
                            @endif
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection
