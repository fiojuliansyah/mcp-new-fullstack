@extends('layouts.app')

@section('content')
<section class="w-full bg-primary text-white px-4 py-10">
    <div class="w-full max-w-screen-xl mx-auto pb-10">
        <!-- HEADER -->
        <div class="flex flex-col lg:flex-row justify-between gap-5 lg:items-end border-b border-white/10">
            <!-- LEFT SECTION -->
            <div class="flex items-center gap-3">
                <img src="/frontend/assets/images/student-profile-vector.svg" alt="Tutor Avatar" class="w-28" />
                <div>
                    <span class="text-gray-250">Tutor Dashboard</span>
                    <h1 class="text-4xl font-bold tracking-tight text-white">Welcome Back!</h1>
                </div>
            </div>
            <!-- RIGHT SECTION - BREADCRUMB -->
            <div class="flex items-center gap-1 mb-3">
                <span class="text-gray-910 text-[15px] font-medium">Home > Students Performance</span>
                <span class="text-white text-[15px font-medium">> View Student Subscription</span>
            </div>
        </div>

        <!-- CONTENT -->
        <div class="space-y-10">
            <!-- ALL CLASSES -->
            <div class="pt-10 grid grid-cols-3">
                <!-- BACK -->
                <div class="col-span-3 flex items-center gap-10 mb-10">
                    <a href="{{ route('tutor.class.index', $classroom->subject->slug) }}"
                        class="bg-white rounded-full w-8 h-8 flex items-center justify-center cursor-pointer">
                        <img src="/frontend/assets/icons/arrow-left.svg" alt="Icon" class="size-4">
                    </a>
                    <h6 class="text-[20px] text-gray-75 font-semibold">View Class</h6>
                </div>

                <!-- ALL CLASSES CONTENT -->
                <div class="col-span-3 lg:col-span-2">
                    <div
                        class="border border-gray-700 rounded-[21px] p-5 flex flex-col lg:flex-row items-center gap-5 shadow-1">
                        <div class="bg-gray-50 rounded-full w-[109px] h-[109px] flex items-center justify-center">
                            <img src="/frontend/assets/icons/presentation.svg" alt="Icon" class="w-10 text-black">
                        </div>
                        <div class="flex flex-col justify-between h-full">
                            <div class="flex flex-col mb-5">
                                <span class="text-gray-275">Class Name</span>
                                <span class="text-white">{{ $classroom->name }}</span>
                            </div>
                            <div class="flex items-center py-2 px-6 bg-[#181818] rounded-[13px]">
                                <span class="font-bebas">{{ $classroom->subject->name }}</span>
                                <div class="text-[#4C4C4C] px-8">
                                    {{ $form }} | Total Students: #
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- CLASS LIST -->
            <div class="w-full pt-10 grid grid-cols-3 gap-8">
                <div class="col-span-3">
                    <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-5">
                        <div class="flex flex-col">
                            <h6 class="text-[15px] text-gray-75 font-semibold">Topic Lists</h6>
                            <span class="text-gray-910">9 Topic</span>
                        </div>
                    </div>
                </div>

                <div class="col-span-3">
                    <!-- START : TABLE -->
                    <div class="overflow-x-auto border border-[#424242] rounded-[12px] mb-10">
                        <table
                            class="min-w-full border border-[#424242] rounded-[12px] overflow-hidden text-sm text-left">
                            <thead class="bg-gray-800 text-gray-200">
                                <tr>
                                    <th class="p-4 border border-[#424242]">No</th>
                                    <th class="p-4 border border-[#424242]">Topic</th>
                                    <th class="p-4 border border-[#424242]">Replay Video Upload Date</th>
                                    <th class="p-4 border border-[#424242]">Notes Upload Date</th>
                                    <th class="p-4 border border-[#424242]">Quizzes Upload Date</th>
                                    <th class="p-4 border border-[#424242]">Live Schedule URL</th>
                                    <th class="p-4 border border-[#424242]">Status</th>
                                    <th class="p-4 border border-[#424242]">Schedule Date | Time</th>
                                </tr>
                            </thead>
                            <tbody class="bg-gray-900 divide-y divide-gray-800 text-gray-50">
                                @forelse($schedules as $key => $schedule)
                                    <tr class="odd:bg-[#141414] even:bg-[#171717]">
                                        <td class="p-4 border border-gray-700">{{ $key + $schedules->firstItem() }}</td>
                                        <td class="p-4 border border-gray-700">{{ $schedule->topic }}</td>

                                        <td class="p-4 border border-gray-700">
                                            <div class="flex items-center justify-between gap-3">
                                                <span>
                                                    {{ optional($schedule->latestReplay)->created_at?->format('d M Y H:i') ?? '-' }}
                                                </span>
                                                @if($schedule->latestReplay)
                                                    <a href="#" target="_blank"
                                                        class="bg-white rounded-full w-8 h-8 flex items-center justify-center cursor-pointer">
                                                        <img src="/frontend/assets/icons/pencil.svg" alt="Edit Replay" class="size-4">
                                                    </a>
                                                @endif
                                            </div>
                                        </td>

                                        <td class="p-4 border border-gray-700">
                                            <div class="flex items-center justify-between gap-3">
                                                <span>
                                                    {{ optional($schedule->latestMaterial)->created_at?->format('d M Y H:i') ?? '-' }}
                                                </span>
                                                @if($schedule->latestMaterial)
                                                    <a href="#" target="_blank"
                                                        class="bg-white rounded-full w-8 h-8 flex items-center justify-center cursor-pointer">
                                                        <img src="/frontend/assets/icons/pencil.svg" alt="Edit Notes" class="size-4">
                                                    </a>
                                                @endif
                                            </div>
                                        </td>

                                        <td class="p-4 border border-gray-700">
                                            <div class="flex items-center justify-between gap-3">
                                                <span>
                                                    {{ optional($schedule->latestQuiz)->publish_date?->format('d M Y H:i') ?? '-' }}
                                                </span>
                                                @if($schedule->latestQuiz)
                                                    <a href="{{ route('tutor.quiz.question.preview', $schedule->latestQuiz->id) }}" target="_blank"
                                                        class="bg-white rounded-full w-8 h-8 flex items-center justify-center cursor-pointer">
                                                        <img src="/frontend/assets/icons/pencil.svg" alt="Edit Quiz" class="size-4">
                                                    </a>
                                                @endif
                                            </div>
                                        </td>

                                        <td class="p-4 border border-gray-700">
                                            <a href="{{ $schedule->zoom_join_url }}" target="_blank" class="text-blue-400 break-words">
                                                {{ $schedule->zoom_join_url }}
                                            </a>
                                        </td>

                                        <td class="p-4 border border-gray-700">{{ $schedule->status }}</td>

                                        <td class="p-4 border border-gray-700">
                                            <div class="flex items-center justify-between gap-3">
                                                <span>{{ $schedule->time->format('D d M Y | H:i') }}</span>
                                                <a href="#"
                                                    class="bg-white rounded-full w-8 h-8 flex items-center justify-center cursor-pointer">
                                                    <img src="/frontend/assets/icons/pencil.svg" alt="Edit Schedule" class="size-4">
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7" class="text-center p-4">No schedules available</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
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
    </div>
    </section>
@endsection