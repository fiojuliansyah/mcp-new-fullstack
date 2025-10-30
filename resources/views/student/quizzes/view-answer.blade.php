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
                        <h1 class="text-4xl font-bold tracking-tight text-white">Quiz Answers</h1>
                    </div>
                </div>
                <div class="flex items-center gap-1 mb-3">
                    <span class="text-gray-910 text-[15px] font-medium">Student Log > My Subject > Quiz</span>
                    <span class="text-white text-[15px] font-medium">> View Answers</span>
                </div>
            </div>

            <!-- CONTENT -->
            <div class="space-y-10 divide-y divide-zinc-700">
                <div class="w-full pt-10">
                    <!-- BACK BUTTON -->
                    <div class="flex items-center gap-10 mb-10">
                        <a href="{{ route('student.quizzes.preview', $quiz->id) }}"
                            class="bg-white rounded-full w-8 h-8 flex items-center justify-center cursor-pointer">
                            <img src="/frontend/assets/icons/arrow-left.svg" alt="Icon" class="size-4">
                        </a>
                        <h6 class="text-[20px] text-gray-75 font-semibold">View Answers</h6>
                    </div>

                    <div class="col-span-12 flex items-center gap-3 mb-6">
                        <img src="/frontend/assets/icons/quiz.svg" alt="Icon" class="size-6">
                        <h6 class="text-[20px] text-gray-75 font-semibold">{{ $quiz->schedule->classroom->subject->name }}
                        </h6>
                    </div>

                    <div class="col-span-12 mb-10 lg:mb-20">
                        <div
                            class="inline-flex flex-col lg:flex-row lg:items-center justify-between gap-3 bg-[#181818] px-4 py-2 rounded-[9px] w-full">
                            <span class="text-gray-75 text-[15px] font-bold">
                                {{ $quiz->schedule->topic ?? '-' }}
                            </span>
                            <span class="text-[#4D4B4B] text-[15px]">Total: {{ $quiz->questions->count() }} Questions</span>
                        </div>
                    </div>

                    <!-- QUESTIONS LIST -->
                    <div class="grid grid-cols-12 gap-10">
                        <div class="col-span-12 lg:col-span-8">
                            <div class="flex flex-col gap-10">

                                @foreach ($quiz->questions as $index => $question)
                                    @php
                                        $answer = $attempt->answers->where('question_id', $question->id)->first();
                                        $isCorrect = $answer && $question->isCorrect($answer->answer);
                                    @endphp

                                    <div
                                        class="border border-gray-800 bg-[#101010] rounded-2xl shadow-md hover:shadow-lg transition-all duration-300 p-7">
                                        <!-- QUESTION HEADER -->
                                        <div class="flex flex-col gap-2 mb-6">
                                            <div class="flex justify-between items-center">
                                                <h3 class="text-lg font-semibold text-white flex items-start gap-2">
                                                    <span class="text-gray-400 font-bold">{{ $index + 1 }}.</span>
                                                    {{ $question->question }}
                                                </h3>
                                                <span
                                                    class="text-xs uppercase tracking-wide bg-gray-800 px-3 py-1 rounded-full text-gray-400">
                                                    {{ str_replace('_', ' ', ucfirst($question->type_of_answer)) }}
                                                </span>
                                            </div>

                                            @if ($question->media_url)
                                                @php $ext = pathinfo($question->media_url, PATHINFO_EXTENSION); @endphp
                                                @if (in_array($ext, ['jpg', 'jpeg', 'png', 'gif', 'webp']))
                                                    <img src="{{ Storage::url($question->media_url) }}" alt="Question Media"
                                                        class="rounded-xl max-h-72 w-auto mt-4 border border-gray-700 object-cover mx-auto">
                                                @elseif($ext === 'pdf')
                                                    <iframe src="{{ Storage::url($question->media_url) }}"
                                                        class="w-full h-80 mt-4 border border-gray-700 rounded-xl"></iframe>
                                                @endif
                                            @endif
                                        </div>

                                        <!-- ANSWER OPTIONS -->
                                        <div class="flex flex-col gap-3">
                                            @if ($question->type_of_answer === 'multiple_choice' && is_array($question->answer))
                                                @foreach ($question->answer as $key => $option)
                                                    @php
                                                        $isUserAnswer = $answer && $answer->answer == $key;
                                                        $isOptionCorrect = isset($option['correct']);
                                                        $borderClass = $isOptionCorrect
                                                            ? 'border-green-500'
                                                            : ($isUserAnswer
                                                                ? 'border-red-500'
                                                                : 'border-gray-700');
                                                        $bgClass = $isOptionCorrect
                                                            ? 'bg-green-900/20'
                                                            : ($isUserAnswer
                                                                ? 'bg-red-900/20'
                                                                : 'bg-gray-800/40');
                                                    @endphp
                                                    <div
                                                        class="flex items-center gap-4 p-4 rounded-xl border {{ $borderClass }} {{ $bgClass }} transition-all">
                                                        <div
                                                            class="flex-shrink-0 w-8 h-8 flex items-center justify-center rounded-full border border-gray-500">
                                                            @if ($isOptionCorrect)
                                                                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-green-400" fill="none" viewBox="0 0 24 24"
                                                                    stroke="currentColor" stroke-width="2">
                                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                                                                </svg>
                                                            @elseif($isUserAnswer)
                                                                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-red-400" fill="none" viewBox="0 0 24 24"
                                                                    stroke="currentColor" stroke-width="2">
                                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                                                                </svg>
                                                            @endif

                                                        </div>
                                                        <span
                                                            class="text-white text-base">{{ $option['text'] ?? '—' }}</span>
                                                    </div>
                                                @endforeach
                                            @elseif($question->type_of_answer === 'true_false')
                                                @foreach (['true', 'false'] as $tf)
                                                    @php
                                                        $isUserAnswer = $answer && $answer->answer === $tf;
                                                        $isOptionCorrect = $question->correct_answer === $tf;
                                                        $borderClass = $isOptionCorrect
                                                            ? 'border-green-500'
                                                            : ($isUserAnswer
                                                                ? 'border-red-500'
                                                                : 'border-gray-700');
                                                        $bgClass = $isOptionCorrect
                                                            ? 'bg-green-900/20'
                                                            : ($isUserAnswer
                                                                ? 'bg-red-900/20'
                                                                : 'bg-gray-800/40');
                                                    @endphp
                                                    <div
                                                        class="flex items-center gap-4 p-4 rounded-xl border {{ $borderClass }} {{ $bgClass }} transition-all">
                                                        <div
                                                            class="flex-shrink-0 w-8 h-8 flex items-center justify-center rounded-full border border-gray-500">
                                                             @if ($isOptionCorrect)
                                                                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-green-400" fill="none" viewBox="0 0 24 24"
                                                                    stroke="currentColor" stroke-width="2">
                                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                                                                </svg>
                                                            @elseif($isUserAnswer)
                                                                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-red-400" fill="none" viewBox="0 0 24 24"
                                                                    stroke="currentColor" stroke-width="2">
                                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                                                                </svg>
                                                            @endif
                                                        </div>
                                                        <span
                                                            class="text-white text-base capitalize">{{ $tf }}</span>
                                                    </div>
                                                @endforeach
                                            @endif
                                        </div>

                                        <!-- SCORE / STATUS -->
                                        <div class="mt-5 flex items-center justify-between">
                                            @if ($answer)
                                                @if ($answer->score !== null)
                                                    <span class="text-green-400 font-semibold flex items-center gap-1">
                                                        <img src="/frontend/assets/icons/star.svg" class="w-4 h-4"
                                                            alt="Score">
                                                        Score: {{ $answer->score }}
                                                    </span>
                                                @else
                                                    <span class="text-yellow-400 flex items-center gap-1">
                                                        <img src="/frontend/assets/icons/clock.svg" class="w-4 h-4"
                                                            alt="Waiting">
                                                        Waiting for tutor feedback...
                                                    </span>
                                                @endif
                                            @else
                                                <span class="text-red-400 flex items-center gap-1">
                                                    <img src="/frontend/assets/icons/warning.svg" class="w-4 h-4"
                                                        alt="Not answered">
                                                    Not answered
                                                </span>
                                            @endif
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>


                    <!-- BACK BUTTON -->
                    <div class="mt-10">
                        <a href="{{ route('student.quizzes.preview', $quiz->id) }}"
                            class="inline-block bg-white text-black hover:bg-gray-200 rounded-full text-sm px-5 py-3 font-semibold">
                            Back to Preview
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
