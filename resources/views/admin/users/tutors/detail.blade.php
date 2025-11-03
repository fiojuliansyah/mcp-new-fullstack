@extends('layouts.main')

@section('content')
    <div class="space-y-5 py-5 px-10">
        <div class="space-y-7 flex flex-col">
            <h1 class="text-gray-100 font-[600]">User Management</h1>

            <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-3">
                <div class="flex items-center gap-3">
                    <a href="{{ url()->previous() }}"
                        class="bg-white rounded-full w-8 h-8 flex items-center justify-center cursor-pointer">
                        <img src="/admin/assets/icons/arrow-left.svg" alt="Icon" class="size-4">
                    </a>
                    <h6 class="text-gray-100 font-[600]">Classroom Detail - {{ $classroom->subject->name ?? '-' }}</h6>
                </div>
            </div>
        </div>

        <div class="space-y-10">
            <div class="rounded-[21px] border border-[#2A2A2A] h-full">
                <div class="rounded-t-[21px] bg-[#2C2C2C] py-3 px-8 flex justify-between items-center">
                    <span>Classroom Information</span>
                    <span class="text-gray-400 text-sm">Total Student: {{ $classroom->total_students ?? 0 }}</span>
                </div>

                <div class="grid grid-cols-2 md:grid-cols-3 gap-5 p-5 text-sm">
                    <div class="flex flex-col">
                        <span class="text-gray-200">Subject</span>
                        <span class="text-[#EAEAEA]">{{ $classroom->subject->name ?? '-' }}</span>
                    </div>
                    <div class="flex flex-col">
                        <span class="text-gray-200">Total Schedule</span>
                        <span class="text-[#EAEAEA]">{{ $classroom->schedules->count() }}</span>
                    </div>
                    <div class="flex flex-col">
                        <span class="text-gray-200">Total Student</span>
                        <span class="text-[#EAEAEA]">{{ $classroom->total_students ?? 0 }}</span>
                    </div>
                </div>
            </div>

            <div class="space-y-5">
                <div class="flex items-center justify-between gap-3">
                    <div>
                        <h6 class="font-[600]">Schedule List</h6>
                        <p class="text-gray-910">{{ $classroom->schedules->count() }} Schedules</p>
                    </div>
                </div>

                <div class="overflow-x-auto border border-[#424242] rounded-[12px] mb-10">
                    <table class="min-w-full border border-[#424242] rounded-[12px] overflow-hidden text-sm text-left">
                        <thead class="bg-gray-800 text-gray-200">
                            <tr>
                                <th class="p-4 border border-[#424242]">No</th>
                                <th class="p-4 border border-[#424242]">Topic</th>
                                <th class="p-4 border border-[#424242]">Date</th>
                                <th class="p-4 border border-[#424242]">Replay Status</th>
                                <th class="p-4 border border-[#424242]">Pending Days</th>
                                <th class="p-4 border border-[#424242]">Attendance Rate</th>
                                <th class="p-4 border border-[#424242]">Action</th>
                            </tr>
                        </thead>
                        <tbody class="bg-gray-900 divide-y divide-gray-800 text-gray-50">
                            @forelse ($classroom->schedules as $i => $schedule)
                                <tr class="odd:bg-[#141414] even:bg-[#171717]">
                                    <td class="p-4 border border-gray-700">{{ $i + 1 }}</td>
                                    <td class="p-4 border border-gray-700">{{ $schedule->topic ?? '-' }}</td>
                                    <td class="p-4 border border-gray-700">
                                        {{ \Carbon\Carbon::parse($schedule->date)->format('d M Y') }}
                                    </td>
                                    <td class="p-4 border border-gray-700">
                                        <span class="{{ $schedule->replay_status === 'pending' ? 'text-yellow-400' : 'text-green-400' }}">
                                            {{ ucfirst($schedule->replay_status) }}
                                        </span>
                                    </td>
                                    <td class="p-4 border border-gray-700">
                                        @if ($schedule->pending_days > 0)
                                            {{ $schedule->pending_days }} Days
                                        @else
                                            -
                                        @endif
                                    </td>
                                    <td class="p-4 border border-gray-700">
                                        {{ $schedule->attendance_rate }}%
                                    </td>
                                    <td class="p-4 border border-gray-700">
                                        <div class="flex items-center justify-center gap-3">
                                            <a href="#"
                                                class="flex flex-col items-center">
                                                <div
                                                    class="block bg-gray-50 hover:bg-gray-200 cursor-pointer w-[35px] h-[35px] rounded-full p-2 flex items-center justify-center">
                                                    <img src="/admin/assets/icons/eye.svg" alt="Icon"
                                                        class="size-5 text-black" />
                                                </div>
                                                <span class="text-[#5F5F5F] text-[10px]">View</span>
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="text-center py-6 text-gray-500">
                                        No schedules found for this classroom.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection
