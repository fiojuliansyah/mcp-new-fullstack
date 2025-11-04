@extends('layouts.main')

@section('content')
<div class="space-y-5 py-5 px-10">
    <div class="space-y-7 flex flex-col">
        <h1 class="text-gray-100 font-[600]">Class Attendance</h1>

        <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-3">
            <div>
                <h6 class="font-[600]">Class Attendance Overview</h6>
                <p class="text-gray-910">{{ $schedules->total() }} Classes</p>
            </div>

            <form method="GET" class="flex items-center w-full lg:w-[350px] bg-white border border-gray-280 rounded-full px-2 py-2">
                <div class="flex items-center justify-center w-8 h-8 rounded-full bg-black text-white mr-3">
                    <img src="/admin/assets/icons/search.svg" alt="Icon">
                </div>
                <input type="text" name="search" value="{{ $search }}"
                    class="flex-1 bg-transparent text-gray-700 placeholder:text-[#A6A1A1] focus:outline-none"
                    placeholder="Search subject or tutor..." />
            </form>
        </div>
    </div>

    <div class="space-y-10 divide-y divide-gray-200">
        <div class="space-y-5">
            <div class="overflow-x-auto border border-[#424242] rounded-[12px] mb-10">
                <table class="min-w-full border border-[#424242] rounded-[12px] overflow-hidden text-sm text-left">
                    <thead class="bg-gray-800 text-gray-200">
                        <tr>
                            <th class="p-4 border border-[#424242]">No</th>
                            <th class="p-4 border border-[#424242]">Tutor Name</th>
                            <th class="p-4 border border-[#424242]">Subject Enrolled</th>
                            <th class="p-4 border border-[#424242]">Class</th>
                            <th class="p-4 border border-[#424242]">Class Date</th>
                            <th class="p-4 border border-[#424242]">Class Time</th>
                            <th class="p-4 border border-[#424242]">Total Student</th>
                            <th class="p-4 border border-[#424242]">Attendance</th>
                            <th class="p-4 border border-[#424242]">Class Status</th>
                        </tr>
                    </thead>

                    <tbody class="bg-gray-900 divide-y divide-gray-800 text-gray-50">
                        @forelse ($schedules as $index => $schedule)
                            @php
                                $totalStudents = $schedule->attendances->count();
                                $present = $schedule->attendances->where('status', 'present')->count();
                                $absent = $schedule->attendances->where('status', 'absent')->count();
                            @endphp

                            <tr class="odd:bg-[#141414] even:bg-[#171717] hover:bg-[#202020] transition">
                                <td class="p-4 border border-gray-700">{{ $schedules->firstItem() + $index }}</td>
                                <td class="p-4 border border-gray-700">{{ $schedule->classroom->user->name ?? '-' }}</td>
                                <td class="p-4 border border-gray-700">{{ $schedule->classroom->subject->name ?? '-' }}</td>
                                <td class="p-4 border border-gray-700">{{ $schedule->classroom->name ?? '-' }}</td>
                                <td class="p-4 border border-gray-700">{{ \Carbon\Carbon::parse($schedule->time)->format('d M Y') }}</td>
                                <td class="p-4 border border-gray-700">{{ \Carbon\Carbon::parse($schedule->time)->format('H:i') }}</td>
                                <td class="p-4 border border-gray-700 text-center">{{ $totalStudents }}</td>
                                <td class="p-4 border border-gray-700 text-sm">
                                    <span class="text-green-400 font-semibold">Present: {{ $present }}</span><br>
                                    <span class="text-red-400 font-semibold">Absent: {{ $absent }}</span>
                                </td>
                                <td class="p-4 border border-gray-700">
                                    <span class="capitalize">{{ $schedule->status ?? 'pending' }}</span>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="9" class="text-center py-6 text-gray-400">No attendance data found.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- PAGINATION -->
            <div class="flex items-center justify-center lg:justify-end gap-5">
                <span class="text-gray-200">Page</span>

                @if($schedules->onFirstPage())
                    <button class="bg-white rounded-full w-10 h-10 flex items-center justify-center cursor-not-allowed opacity-50">
                        <img src="/frontend/assets/icons/angle-left.svg" alt="Prev" class="size-3">
                    </button>
                @else
                    <a href="{{ $schedules->previousPageUrl() }}" class="bg-white rounded-full w-10 h-10 flex items-center justify-center">
                        <img src="/frontend/assets/icons/angle-left.svg" alt="Prev" class="size-3">
                    </a>
                @endif

                <div class="bg-gray-1000 border border-gray-950 text-gray-75 rounded-[14px] px-8 py-3">
                    {{ $schedules->currentPage() }}
                </div>

                @if($schedules->hasMorePages())
                    <a href="{{ $schedules->nextPageUrl() }}" class="bg-white rounded-full w-10 h-10 flex items-center justify-center">
                        <img src="/frontend/assets/icons/angle-right.svg" alt="Next" class="size-3">
                    </a>
                @else
                    <button class="bg-white rounded-full w-10 h-10 flex items-center justify-center cursor-not-allowed opacity-50">
                        <img src="/frontend/assets/icons/angle-right.svg" alt="Next" class="size-3">
                    </button>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
