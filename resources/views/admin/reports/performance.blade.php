@extends('layouts.main')

@section('content')
    <div class="space-y-5 py-5 px-10">
        <div class="space-y-7 flex flex-col">
            <h1 class="text-gray-100 font-[600]">Performance Overview</h1>
            <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-3">
                <div>
                    <h6 class="font-[600]">Students Performance</h6>
                    <p class="text-gray-910">{{ $count }} Students</p>
                </div>

                <div class="flex items-center w-full lg:w-[350px] bg-white border border-gray-280 rounded-full px-2 py-2">
                    <div class="flex items-center justify-center w-8 h-8 rounded-full bg-black text-white mr-3">
                        <img src="/admin/assets/icons/search.svg" alt="Icon">
                    </div>
                    <input type="text" id="searchInput"
                        class="flex-1 bg-transparent text-gray-700 placeholder:text-[#A6A1A1] focus:outline-none"
                        placeholder="Search student name..." />
                </div>
            </div>
        </div>

        <!-- PAGE CONTENT -->
        <div class="space-y-10 divide-y divide-gray-200">
            <!-- TABLE SECTION -->
            <div class="space-y-5">
                <div class="overflow-x-auto border border-[#424242] rounded-[12px] mb-10">
                    <table class="min-w-full border border-[#424242] rounded-[12px] overflow-hidden text-sm text-left">
                        <thead class="bg-gray-800 text-gray-200">
                            <tr>
                                <th class="p-4 border border-[#424242]">No</th>
                                <th class="p-4 border border-[#424242]">Student Name</th>
                                <th class="p-4 border border-[#424242]">Subjects</th>
                                <th class="p-4 border border-[#424242]">Group Class</th>
                                <th class="p-4 border border-[#424242]">Avg Quiz Score</th>
                                <th class="p-4 border border-[#424242]">Attendance Rate</th>
                                <th class="p-4 border border-[#424242]">Replay Rate</th>
                                <th class="p-4 border border-[#424242]">Engagment Alert</th>
                                <th class="p-4 border border-[#424242]">Last Active</th>
                                <th class="p-4 border border-[#424242] text-center">Action</th>
                            </tr>
                        </thead>

                        <tbody class="bg-gray-900 divide-y divide-gray-800 text-gray-50">
                            @php $no = 1; @endphp

                            @foreach ($students as $student)
                                @foreach ($student->subscriptions as $subscription)
                                    @foreach ($subscription->classrooms as $classroom)
                                        @php
                                            $schedules = $classroom->schedules;
                                            $totalSchedules = $schedules->count();
                                            $attendedCount = $schedules->sum(function ($schedule) use ($student) {
                                                return $schedule->attendances->where('user_id', $student->id)
                                                    ->whereIn('status', ['present', 'late'])
                                                    ->isNotEmpty() ? 1 : 0;
                                            });
                                            $attendanceRate = $totalSchedules > 0 ? round(($attendedCount / $totalSchedules) * 100) : 0;

                                            // Quiz
                                            $totalQuizScore = 0;
                                            $totalQuizzesWithAttempts = 0;
                                            $totalQuizAttempts = 0;

                                            foreach ($schedules as $schedule) {
                                                foreach ($schedule->quizzes as $quiz) {
                                                    $attempts = $quiz->attempts->where('user_id', $student->id);
                                                    if ($attempts->isNotEmpty()) {
                                                        $totalQuizzesWithAttempts++;
                                                        $totalQuizScore += $attempts->avg('score');
                                                    }
                                                    $totalQuizAttempts += $attempts->count();
                                                }
                                            }

                                            $avgQuizScore = $totalQuizzesWithAttempts > 0 ? round($totalQuizScore / $totalQuizzesWithAttempts) : 0;

                                            // Replay watch rate
                                            $totalReplayPercentage = 0;
                                            $totalReplayCount = 0;

                                            foreach ($schedules as $schedule) {
                                                foreach ($schedule->replays as $replay) {
                                                    foreach ($replay->replayVideos as $video) {
                                                        $views = $video->views->where('user_id', $student->id);
                                                        if ($views->isNotEmpty()) {
                                                            $maxWatched = $views->max('duration_watched') ?? 0;
                                                            $duration = $video->duration ?? 1;
                                                            $percentage = min(100, round(($maxWatched / $duration) * 100));
                                                            $totalReplayPercentage += $percentage;
                                                            $totalReplayCount++;
                                                        }
                                                    }
                                                }
                                            }

                                            $avgReplayWatchRate = $totalReplayCount > 0 ? round($totalReplayPercentage / $totalReplayCount) : 0;
                                        @endphp

                                        <tr class="odd:bg-[#141414] even:bg-[#171717]">
                                            <td class="p-4 border border-gray-700">{{ $no++ }}</td>
                                            <td class="p-4 border border-gray-700">{{ $student->name }}</td>
                                            <td class="p-4 border border-gray-700">{{ $classroom->subject->name ?? '-' }}</td>
                                            <td class="p-4 border border-gray-700">{{ $classroom->name ?? '-' }}</td>
                                            <td class="p-4 border border-gray-700 text-center text-yellow-400">{{ $avgQuizScore }}%</td>
                                            <td class="p-4 border border-gray-700 text-center text-green-400">{{ $attendanceRate }}%</td>
                                            <td class="p-4 border border-gray-700 text-center text-blue-400">{{ $avgReplayWatchRate }}%</td>
                                            <td class="p-4 border border-gray-700 text-center text-blue-400"></td>
                                            <td class="p-4 border border-gray-700 text-gray-400">
                                                {{ $student->last_login ? \Carbon\Carbon::parse($student->last_login)->diffForHumans() : '-' }}
                                            </td>
                                            <td class="p-4 border border-gray-700">
                                                <div class="flex items-center justify-center gap-3">
                                                    <a href="#"
                                                        class="flex flex-col items-center">
                                                        <div
                                                            class="block bg-gray-50 hover:bg-gray-200 cursor-pointer w-[35px] h-[35px] rounded-full p-2 flex items-center justify-center">
                                                            <img src="/admin/assets/icons/file.svg" alt="Icon"
                                                                class="size-5 text-black invert" />
                                                        </div>
                                                        <span class="text-white text-[10px]">Export</span>
                                                    </a>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                @endforeach
                            @endforeach

                            @if ($no === 1)
                                <tr>
                                    <td colspan="9" class="text-center py-6 text-gray-500">No student data found.</td>
                                </tr>
                            @endif
                        </tbody>

                    </table>
                </div>

                <!-- PAGINATION -->
                <div class="flex items-center justify-center lg:justify-end gap-5">
                    <span class="text-gray-200">Page</span>

                    @if ($students->onFirstPage())
                        <button
                            class="bg-white rounded-full w-10 h-10 flex items-center justify-center cursor-not-allowed opacity-50">
                            <img src="/frontend/assets/icons/angle-left.svg" alt="Prev" class="size-3">
                        </button>
                    @else
                        <a href="{{ $students->previousPageUrl() }}"
                            class="bg-white rounded-full w-10 h-10 flex items-center justify-center">
                            <img src="/frontend/assets/icons/angle-left.svg" alt="Prev" class="size-3">
                        </a>
                    @endif

                    <div class="bg-gray-1000 border border-gray-950 text-gray-75 rounded-[14px] px-8 py-3">
                        {{ $students->currentPage() }}
                    </div>

                    @if ($students->hasMorePages())
                        <a href="{{ $students->nextPageUrl() }}"
                            class="bg-white rounded-full w-10 h-10 flex items-center justify-center">
                            <img src="/frontend/assets/icons/angle-right.svg" alt="Next" class="size-3">
                        </a>
                    @else
                        <button
                            class="bg-white rounded-full w-10 h-10 flex items-center justify-center cursor-not-allowed opacity-50">
                            <img src="/frontend/assets/icons/angle-right.svg" alt="Next" class="size-3">
                        </button>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection
