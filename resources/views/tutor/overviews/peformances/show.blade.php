@extends('layouts.app')

@section('content')
<section class="w-full bg-primary text-white px-4 py-10">
    <div class="w-full max-w-screen-xl mx-auto pb-10">
        <!-- HEADER -->
        <div class="flex flex-col lg:flex-row justify-between gap-5 lg:items-end border-b border-white/10">
            <div class="flex items-center gap-3">
                <img src="/frontend/assets/images/student-profile-vector.svg" alt="Tutor Avatar" class="w-28" />
                <div>
                    <span class="text-gray-250">Tutor Dashboard</span>
                    <h1 class="text-4xl font-bold tracking-tight text-white">Welcome Back!</h1>
                </div>
            </div>
            <div class="flex items-center gap-1 mb-3">
                <span class="text-gray-910 text-[15px] font-medium">Home > Students Performance</span>
                <span class="text-white text-[15px font-medium">> View</span>
            </div>
        </div>

        <!-- CONTENT -->
        <div class="space-y-10">
            <div class="pt-10 grid grid-cols-3 gap-10">
                <!-- LEFT SIDE -->
                <div class="col-span-3 lg:col-span-2 lg:border-r border-white/10 lg:pr-10">
                    <!-- BACK -->
                    <div class="flex items-center gap-10 mb-10">
                        <a href="{{ route('tutor.overview.peformance.index', $selectedSubject->slug) }}"
                            class="bg-white rounded-full w-8 h-8 flex items-center justify-center cursor-pointer">
                            <img src="/frontend/assets/icons/arrow-left.svg" alt="Icon" class="size-4">
                        </a>
                        <h6 class="text-[20px] text-gray-75 font-semibold">View Student</h6>
                    </div>

                    <!-- STUDENT CARD -->
                    <div class="border border-gray-700 rounded-[21px] p-5 flex flex-col lg:flex-row items-center gap-5 shadow-1">
                        <div class="bg-gray-50 rounded-full w-[109px] h-[109px] flex items-center justify-center">
                            <img src="/frontend/assets/icons/student-black.svg" alt="Icon" class="w-10 text-black">
                        </div>
                        <div class="flex flex-col justify-between h-full">
                            <div class="flex flex-col mb-5">
                                <span class="text-gray-275">Student Name</span>
                                <span class="text-white">{{ $user->name }}</span>
                            </div>
                            <div class="flex items-center py-2 px-6 bg-[#181818] rounded-[13px]">
                                <span class="font-bebas">{{ $selectedSubject->name }}</span>
                                <div class="text-[#4C4C4C] px-8">
                                    Form: {{ $formName ?? '-' }} | Class Group: {{ $groupName ?? '-' }}
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- SCORE LIST -->
                    <div class="w-full pt-10 grid grid-cols-3 gap-8">
                        <div class="col-span-3">
                            <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-5">
                                <div class="flex flex-col">
                                    <h6 class="text-[15px] text-gray-75 font-semibold">Score Lists</h6>
                                    <span class="text-gray-910">{{ count($records) }} topics</span>
                                </div>

                                <form method="GET" action="{{ request()->url() }}" class="flex items-center w-full lg:w-[350px] bg-white border border-gray-280 rounded-full px-2 py-2">
                                    <div class="flex items-center justify-center w-8 h-8 rounded-full bg-black text-white mr-3">
                                        <img src="/frontend/assets/icons/search.svg" alt="Icon">
                                    </div>
                                    <input 
                                        type="text" 
                                        name="search"
                                        value="{{ request('search') }}"
                                        class="flex-1 bg-transparent text-gray-700 placeholder:text-[#A6A1A1] focus:outline-none" 
                                        placeholder="Search topic..." 
                                    />
                                </form>

                            </div>
                        </div>

                        <!-- TABLE -->
                        <div class="col-span-3">
                            <div class="overflow-x-auto border border-[#424242] rounded-[12px] mb-10">
                                <table class="min-w-full border border-[#424242] rounded-[12px] overflow-hidden text-sm text-left">
                                    <thead class="bg-gray-800 text-gray-200">
                                        <tr>
                                            <th class="p-4 border border-[#424242]">No</th>
                                            <th class="p-4 border border-[#424242]">Topic</th>
                                            <th class="p-4 border border-[#424242]">Quiz Scores</th>
                                            <th class="p-4 border border-[#424242]">Attendance</th>
                                            <th class="p-4 border border-[#424242]">Replay Views</th>
                                            <th class="p-4 border border-[#424242]">Notes Remarks</th>
                                        </tr>
                                    </thead>
                                    <tbody class="bg-gray-900 divide-y divide-gray-800 text-gray-50">
                                        @forelse ($records as $index => $record)
                                            <tr class="odd:bg-[#141414] even:bg-[#171717]">
                                                <td class="p-4 border border-gray-700">{{ $index + 1 }}</td>
                                                <td class="p-4 border border-gray-700">{{ $record['topic'] }}</td>
                                                <td class="p-4 border border-gray-700">
                                                    {{ $record['quiz_score'] ? $record['quiz_score'] . '%' : '-' }}
                                                </td>
                                                <td class="p-4 border border-gray-700">
                                                    @if($record['attendance'])
                                                        <span class="text-green-100">Attend</span>
                                                    @else
                                                        <span class="text-red-100">Absent</span>
                                                    @endif
                                                </td>
                                                <td class="p-4 border border-gray-700">
                                                    {{ $record['replay_rate'] ? $record['replay_rate'] . '%' : '0%' }}
                                                </td>
                                                <td class="p-4 border border-gray-700">
                                                    <div class="flex items-center justify-between gap-3">
                                                        <span>{{ $record['note'] ?? '-' }}</span>
                                                        <a href="#"
                                                            class="bg-white rounded-full w-8 h-8 flex items-center justify-center cursor-pointer">
                                                            <img src="/frontend/assets/icons/pencil.svg" alt="Icon" class="size-4">
                                                        </a>
                                                    </div>
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="6" class="p-4 text-center text-gray-400">No data available</td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- OVERVIEW -->
                <div class="col-span-3 lg:col-span-1">
                    <h6 class="text-[20px] text-gray-75 font-semibold mb-8">Overview</h6>
                    <div class="flex flex-col gap-8">
                        <div class="flex flex-col gap-5">
                            <span class="text-gray-200">Quiz Scores</span>
                            <div class="flex items-center gap-8">
                                <img src="/frontend/assets/icons/quiz.svg" alt="Icon" class="w-15">
                                <span class="text-gray-50 text-[30px]">{{ round($overview['avg_quiz'], 1) }}%</span>
                            </div>
                        </div>
                        <div class="flex flex-col gap-5">
                            <span class="text-gray-200">Attendance</span>
                            <div class="flex items-center gap-8">
                                <img src="/frontend/assets/icons/student.svg" alt="Icon" class="w-10">
                                <span class="text-gray-50 text-[30px]">{{ round($overview['attendance_rate'], 1) }}%</span>
                            </div>
                        </div>
                        <div class="flex flex-col gap-5">
                            <span class="text-gray-200">Replay Views</span>
                            <div class="flex items-center gap-8">
                                <img src="/frontend/assets/icons/playback.svg" alt="Icon" class="w-10">
                                <span class="text-gray-50 text-[30px]">{{ round($overview['replay_rate'], 1) }}%</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
