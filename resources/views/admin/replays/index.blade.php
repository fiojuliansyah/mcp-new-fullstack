@extends('layouts.main')

@section('content')
<div class="space-y-5 py-5 px-10">
    <div class="space-y-7 flex flex-col">
        <h1 class="text-gray-100 font-[600]">Replay Management</h1>
        <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-3">
            <div>
                <h6 class="font-[600]">Schedule List</h6>
            </div>
        </div>
    </div>

    <div class="space-y-10 divide-y divide-gray-200">
        <div class="space-y-5">
            <!-- Search -->
            <form method="GET" action="{{ route('admin.replays.index') }}" class="flex items-center w-full lg:w-[350px] bg-white border border-gray-280 rounded-full px-2 py-2">
                <div class="flex items-center justify-center w-8 h-8 rounded-full bg-black text-white mr-3">
                    <img src="/admin/assets/icons/search.svg" alt="Icon">
                </div>
                <input type="text" name="search" value="{{ request('search') }}" class="flex-1 bg-transparent text-gray-700 placeholder:text-[#A6A1A1] focus:outline-none" placeholder="Search Schedule" />
                @if(request('search'))
                    <a href="{{ route('admin.replays.index') }}" class="text-gray-500 text-sm pr-3">✕</a>
                @endif
            </form>

            <!-- Table -->
            <div class="overflow-x-auto border border-[#424242] rounded-[12px] mb-10">
                @php
                    $grouped = $schedules->groupBy(fn($item) => $item->classroom->user->id . '-' . $item->classroom->subject->id);
                    $rowNumber = $schedules->firstItem();
                @endphp

                <table class="min-w-full border border-[#424242] text-sm text-left">
                    <thead class="bg-gray-800 text-gray-200">
                        <tr>
                            <th class="p-4 border border-[#424242]">No</th>
                            <th class="p-4 border border-[#424242]">Tutor Name</th>
                            <th class="p-4 border border-[#424242]">Subject</th>
                            <th class="p-4 border border-[#424242]">Classroom</th>
                            <th class="p-4 border border-[#424242]">Total Replay Videos</th>
                            <th class="p-4 border border-[#424242]">Action</th>
                        </tr>
                    </thead>

                    <tbody class="bg-gray-900 divide-y divide-gray-800 text-gray-50">
                        @foreach ($grouped as $key => $group)
                            @foreach ($group as $index => $schedule)
                                <tr class="odd:bg-[#141414] even:bg-[#171717]">
                                    @if ($index === 0)
                                        <td class="p-4 border border-gray-700 align-top" rowspan="{{ $group->count() }}">
                                            {{ $rowNumber++ }}
                                        </td>
                                        <td class="p-4 border border-gray-700 align-top" rowspan="{{ $group->count() }}">
                                            {{ $schedule->classroom->user->name ?? '-' }}
                                        </td>
                                        <td class="p-4 border border-gray-700 align-top" rowspan="{{ $group->count() }}">
                                            {{ $schedule->classroom->subject->name ?? '-' }}
                                        </td>
                                    @endif

                                    {{-- Classroom --}}
                                    <td class="p-4 border border-gray-700">
                                        {{ $schedule->classroom->name ?? '-' }}
                                        <br>
                                        <small class="text-gray-300">{{ $schedule->form->name ?? '-' }}</small>
                                    </td>

                                    {{-- Total replay videos --}}
                                    <td class="p-4 border border-gray-700">
                                        {{ $schedule->replays->flatMap->replayVideos->count() ?? '-' }}
                                    </td>

                                    {{-- Action --}}
                                    <td class="p-4 border border-gray-700">
                                        <a href="{{ route('admin.replays.show', $schedule->id) }}" class="flex flex-col items-center">
                                            <div class="block bg-gray-50 hover:bg-gray-200 cursor-pointer w-[35px] h-[35px] rounded-full p-2 flex items-center justify-center">
                                                <img src="/admin/assets/icons/eye.svg" alt="Icon" class="size-5" />
                                            </div>
                                            <span class="text-[#5F5F5F] text-[10px]">View</span>
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        @endforeach
                    </tbody>
                </table>

            </div>

            <!-- Pagination -->
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
