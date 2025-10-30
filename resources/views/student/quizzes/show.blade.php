@extends('layouts.app')

@section('content')
<section class="w-full bg-primary text-white px-4 py-10">
        <div class="w-full max-w-screen-xl mx-auto">
            <!-- HEADER -->
            <div class="flex flex-col lg:flex-row justify-between gap-5 lg:items-end border-b border-white/10">
                <div class="flex items-center gap-3">
                    <img src="/frontend/assets/images/student-profile-vector.svg" alt="Tutor Avatar" class="w-28" />
                    <div>
                        <span class="text-gray-250">Student</span>
                        <h1 class="text-4xl font-bold tracking-tight text-white">Quiz</h1>
                    </div>
                </div>
                <div class="flex items-center gap-1 mb-3">
                    <span class="text-gray-910 text-[15px] font-medium">Student Log > My Subject > Quiz</span>
                    <span class="text-white text-[15px font-medium">> Quiz</span>
                </div>
            </div>

            <!-- CONTENT -->
            <div class="space-y-10 divide-y divide-zinc-700">
                <div class="pt-10">
                    <!-- HEADING -->
                    <div class="flex items-center gap-3 mb-6">
                        <img src="/frontend/assets/icons/quiz.svg" alt="Icon" class="size-6">
                        <h6 class="text-[20px] text-gray-75 font-semibold">{{ $quiz->schedule->classroom->subject->name }}</h6>
                    </div>
                    <!-- CONTENT -->
                    <div class="bg-gray-990 rounded-[21px] p-8">
                        <h6 class="text-white text-[15px] font-bold mb-1">{{ $quiz->schedule->topic }}</h6>
                        <p class="text-gray-200 text-[12px] mb-8">{{ $quiz->schedule->form->name }}</p>
                        <div
                            class="flex flex-col lg:flex-row lg:items-center justify-between gap-5 border-t border-b border-[#2C2C2C] py-5 lg:pr-5 mb-5">
                            <div class="flex gap-2">
                                <img src="/frontend/assets/icons/calendar.svg" alt="Icon" class="size-6">
                                <span class="text-white">Due Date: {{ \Carbon\Carbon::parse($quiz->end_date)->format('d M') }}</span>
                            </div>
                            <div class="flex gap-2">
                                <img src="/frontend/assets/icons/undo.svg" alt="Icon" class="size-6">
                                <span class="text-white">Attempts Left: {{ $quiz->attempts_time ?? '' }}</span>
                            </div>
                            <div class="flex gap-2">
                                <img src="/frontend/assets/icons/clock.svg" alt="Icon" class="size-6">
                                <span class="text-white">Estimated Time: {{ $quiz->estimated_time ?? '' }} Minutes</span>
                            </div>
                            <div class="flex gap-2">
                                <img src="/frontend/assets/icons/quiz.svg" alt="Icon" class="size-6">
                                <span class="text-white">Total Questions: {{ $quiz->total_question ?? '' }}</span>
                            </div>
                        </div>
                        <div class="flex flex-col lg:flex-row items-center justify-between gap-5">
                            <div
                                class="inline-flex items-center bg-black px-4 py-2 font-medium text-[#4C4C4C] text-[15px] rounded-[9px]">
                                Feedback will be shown after submission
                            </div>
                            <a href="{{ route('student.quizzes.start', $quiz->id) }}"
                                class="bg-gray-50 hover:bg-gray-200 rounded-full text-center text-sm px-5 py-3 w-full lg:w-[185px] cursor-pointer">
                                <span class="text-black text-[16px] font-semibold">Start Quiz</span>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection