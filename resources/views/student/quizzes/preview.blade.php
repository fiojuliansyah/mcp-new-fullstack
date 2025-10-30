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
                    <h1 class="text-4xl font-bold tracking-tight text-white">{{ $quiz->title ?? 'Quiz' }}</h1>
                </div>
            </div>
            <div class="flex items-center gap-1 mb-3">
                <span class="text-gray-910 text-[15px] font-medium">Student Log > My Subject > Quiz</span>
                <span class="text-white text-[15px] font-medium">> Preview</span>
            </div>
        </div>

        <!-- CONTENT -->
        <div class="space-y-10 divide-y divide-zinc-700">
            <div class="pt-10">
                <!-- HEADING -->
                <div class="flex items-center gap-3 mb-6">
                    <img src="/frontend/assets/icons/quiz.svg" alt="Icon" class="size-6">
                    <h6 class="text-[20px] text-gray-75 font-semibold">{{ $quiz->topic ?? 'Topic Name' }}</h6>
                </div>
                <!-- CONTENT -->
                <div class="bg-gray-990 rounded-[21px] p-8">
                    <h6 class="text-white text-[15px] font-bold mb-1">{{ $quiz->title ?? 'Quiz Title' }}</h6>
                    <p class="text-gray-200 text-[12px] mb-8">{{ $quiz->classroom->name ?? '-' }}</p>

                    <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-5 border-t border-b border-[#2C2C2C] py-5 lg:pr-5 mb-5">
                        <div class="flex gap-2">
                            <img src="/frontend/assets/icons/calendar.svg" alt="Icon" class="size-6">
                            <span class="text-white">Due Date: {{ \Carbon\Carbon::parse($quiz->end_date)->format('d M') }}</span>
                        </div>
                        <div class="flex gap-2">
                            <img src="/frontend/assets/icons/undo.svg" alt="Icon" class="size-6">
                            <span class="text-white">Attempts Left: 
                                @php
                                    $userAttempts = $lastAttempt ? $lastAttempt->attempt_number : 0;
                                    $maxAttempts = $quiz->attempts_time ?? 'Unlimited';
                                    $attemptsLeft = is_numeric($maxAttempts) ? max($maxAttempts - $userAttempts, 0) : 'Unlimited';
                                @endphp
                                {{ $attemptsLeft }}
                            </span>
                        </div>
                        <div class="flex gap-2">
                            <img src="/frontend/assets/icons/clock.svg" alt="Icon" class="size-6">
                            <span class="text-white">Estimated Time: {{ $quiz->estimated_time ?? '-' }} mins</span>
                        </div>
                        <div class="flex gap-2">
                            <img src="/frontend/assets/icons/quiz.svg" alt="Icon" class="size-6">
                            <span class="text-white">Total Questions: {{ $quiz->questions->count() }}</span>
                        </div>
                    </div>

                    @if($lastAttempt)
                        @php
                            $hasUnscoredAnswers = $lastAttempt->answers()->whereNull('score')->exists();
                        @endphp

                        <div class="flex flex-col lg:flex-row items-center justify-between gap-5 mb-8">
                            <div class="flex lg:flex-col items-center lg:items-start justify-between gap-4">
                                @if($lastAttempt->feedback)
                                    <div class="inline-flex items-center bg-black px-4 py-2 font-medium text-[#4C4C4C] text-[15px] rounded-[9px]">
                                        Tutor Feedback: "{{ $lastAttempt->feedback }}"
                                    </div>
                                @endif

                                @if($hasUnscoredAnswers)
                                    <span class="text-yellow-400 font-semibold">
                                        Waiting for tutor feedback...
                                    </span>
                                @else
                                    <span class="text-white">
                                        Score: {{ $lastAttempt->score ?? 0 }} / {{ $quiz->questions->sum('answer_point_mark') }}
                                    </span>
                                @endif
                            </div>

                            <div class="flex flex-col gap-4">
                                @if($hasUnscoredAnswers)
                                    <span class="w-full lg:w-auto inline-flex items-center justify-center bg-yellow-900 text-yellow-100
                                        px-2 py-2 font-medium rounded-full">
                                        Waiting
                                    </span>
                                @else
                                    <span class="w-full lg:w-auto inline-flex items-center justify-center 
                                        {{ $lastAttempt->status === 'completed' ? 'bg-green-900 text-green-100' : 'bg-yellow-700 text-white' }} 
                                        px-2 py-2 font-medium rounded-full">
                                        {{ ucfirst($lastAttempt->status) }}
                                    </span>
                                @endif
                                <span class="text-[#4C4C4C]">
                                    Submitted On: {{ $lastAttempt->ended_at ? $lastAttempt->ended_at->format('d M | H:i') : '-' }}
                                </span>
                            </div>
                        </div>
                    @endif


                    <div class="flex items-center justify-between gap-10">
                        @php
                            $attemptNumber = $lastAttempt ? $lastAttempt->attempt_number + 1 : 1;
                            $canRetry = !$quiz->attempts_time || (is_numeric($quiz->attempts_time) && $attemptNumber <= $quiz->attempts_time);
                        @endphp

                        @if($lastAttempt && $lastAttempt->status === 'completed')
                        <a href="{{ route('student.quizzes.viewAnswer', [$quiz->id, $lastAttempt->id]) }}"
                           class="bg-gray-50 hover:bg-gray-200 text-black rounded-full text-center text-sm px-5 py-3 w-full cursor-pointer">
                            <span class="text-[16px] font-semibold">View Answers</span>
                        </a>
                        @endif

                        @if($canRetry)
                        <a href="{{ route('student.quizzes.start', $quiz->id) }}"
                           class="hover:bg-white text-center text-white hover:text-black border border-white rounded-full text-sm px-5 py-3 w-full cursor-pointer">
                            <span class="text-[16px] font-semibold">{{ $lastAttempt ? 'Retry Quiz' : 'Start Quiz' }}</span>
                        </a>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
