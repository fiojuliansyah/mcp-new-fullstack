@extends('layouts.app')

@section('content')
<section class="w-full bg-primary text-white px-4 py-10">
        <div class="w-full max-w-screen-xl mx-auto pb-10">
            <div class="flex flex-col lg:flex-row justify-between gap-5 lg:items-end border-b border-white/10">
                <div class="flex items-center gap-3">
                    <img src="/frontend/assets/images/student-profile-vector.svg" alt="Tutor Avatar" class="w-28" />
                    <div>
                        <span class="text-gray-250">Tutor Dashboard</span>
                        <h1 class="text-4xl font-bold tracking-tight text-white">Welcome Back!</h1>
                    </div>
                </div>
                <div class="flex items-center gap-1 mb-3">
                    <span class="text-gray-910 text-[15px] font-medium">Home > Create Quizzes</span>
                    <span class="text-white text-[15px font-medium">> Create Questions</span>
                </div>
            </div>

            <div class="space-y-10">
                <div class="pt-10">
                    <div class="flex items-center gap-10 mb-10">
                        <a href="#"
                            class="bg-white rounded-full w-8 h-8 flex items-center justify-center cursor-pointer">
                            <img src="/frontend/assets/icons/arrow-left.svg" alt="Icon" class="size-4">
                        </a>
                        <h6 class="text-[20px] text-gray-75 font-semibold">Create Question</h6>
                    </div>

                    <div class="grid grid-cols-12 gap-10">
                        <div class="col-span-12 lg:col-span-2">
                            <div class="flex flex-col gap-3 items-center">
                                <div class="flex items-center justify-center">
                                    <img src="{{ $user->avatar_url ?? '/frontend/assets/images/default-avatar.png' }}" alt="Image" class="w-[154px] h-[186px] rounded-[13px] object-cover" />
                                </div>
                                <span class="font-bebas">{{ $quiz->classroom->subject->name ?? 'Subject' }}</span>
                            </div>
                        </div>
                        <div class="col-span-12 lg:col-span-10">
                            <div class="bg-gray-990 rounded-[21px] p-8">
                                <div class="flex items-start gap-5">
                                    <img src="/frontend/assets/icons/quiz.svg" alt="Icon" class="w-8">
                                    <div class="flex flex-col">
                                        <h6 class="text-gray-75 font-bold mb-1">{{ $quiz->schedule->topic }}</h6>
                                        <p class="text-gray-200 mb-8">{{ $quiz->form->name }} | {{ $quiz->classroom->name }}</p>
                                    </div>
                                </div>
                                <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-5 border-t border-[#2C2C2C] pt-5 lg:pr-5">
                                    <div class="flex gap-5">
                                        <img src="/frontend/assets/icons/calendar.svg" alt="Icon" class="w-4">
                                        <div class="flex flex-col">
                                            <span class="text-gray-200">From:</span>
                                            <span class="text-white">{{ \Carbon\Carbon::parse($quiz->start_date)->format('d M Y') }} - {{ \Carbon\Carbon::parse($quiz->end_date)->format('d M Y') }}</span>
                                        </div>
                                    </div>
                                    <div class="flex gap-5">
                                        <img src="/frontend/assets/icons/undo.svg" alt="Icon" class="w-4">
                                        <div class="flex flex-col">
                                            <span class="text-gray-200">Attempts Time:</span>
                                            <span class="text-white">{{ $quiz->attempts_time }}</span>
                                        </div>
                                    </div>
                                    <div class="flex gap-5">
                                        <img src="/frontend/assets/icons/clock.svg" alt="Icon" class="w-4">
                                        <div class="flex flex-col">
                                            <span class="text-gray-200">Estimated Time:</span>
                                            <span class="text-white">{{ $quiz->estimated_time }} mins</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="pt-10">
                    <div class="grid grid-cols-12 gap-10">
                        <div class="col-span-12 lg:col-span-2">
                            <div>
                                <div class="flex items-center justify-center gap-2 h-full">
                                    <h1 class="text-center text-[200px] text-gray-75 font-bold" id="currentQuestionIndex">1</h1>
                                    <div class="text-[41px] leading-none text-gray-75 mt-[7rem]">/ <span id="totalQuestions">{{ $quiz->questions->count() }}</span></div>
                                </div>
                            </div>
                        </div>
                        <div class="col-span-12 lg:col-span-10">
                            <div class="grid grid-cols-12 gap-10">
                                <div class="col-span-12">
                                    <div class="flex flex-col justify-between border border-white gap-10 rounded-[21px] p-12">
                                       <div class="quiz-wrapper">
                                            @foreach ($quiz->questions as $index => $question)
                                                <div class="question-item @if ($index !== 0) hidden @endif"
                                                    data-index="{{ $index }}">
                                                    <div class="flex gap-2 mb-5">
                                                        <span class="text-white">{{ $index + 1 }}.</span>
                                                        <p class="text-white">{{ $question->question }}</p>
                                                    </div>

                                                    @if ($question->media_url)
                                                        @php
                                                            $extension = pathinfo($question->media_url, PATHINFO_EXTENSION);
                                                        @endphp

                                                        @if (strtolower($extension) === 'pdf')
                                                            <a href="{{ Storage::url($question->media_url) }}" target="_blank" class="text-blue-500 underline mb-5 block">
                                                                Download PDF
                                                            </a>
                                                        @else
                                                            <img src="{{ Storage::url($question->media_url) }}" alt="Question Media" class="w-60 mb-5">
                                                        @endif
                                                    @endif

                                                    <div class="flex flex-col gap-3">
                                                        {{-- Multiple Choice --}}
                                                        @if ($question->type_of_answer === 'multiple_choice' && is_array($question->answer))
                                                            @foreach ($question->answer as $key => $option)
                                                                @php
                                                                    // cek correct
                                                                    $isCorrect =
                                                                        isset($option['correct']) &&
                                                                        $option['correct'] == 1;
                                                                @endphp
                                                                <label
                                                                    class="flex items-center gap-3 bg-gray-925 text-white rounded-[15px] py-3 px-6 cursor-pointer @if ($isCorrect) border-2 border-green-500 @endif">
                                                                    <input type="radio" name="question_{{ $question->id }}"
                                                                        class="hidden peer"
                                                                        @if ($isCorrect) checked @endif />
                                                                    <span
                                                                        class="w-6 h-6 rounded-full border-2 border-gray-300 flex items-center justify-center peer-checked:border-white peer-checked:bg-white">
                                                                        <span
                                                                            class="w-3 h-3 rounded-full bg-black peer-checked:bg-black"></span>
                                                                    </span>
                                                                    <span class="text-base">{{ $option['text'] }}</span>
                                                                </label>
                                                            @endforeach

                                                            {{-- True/False --}}
                                                        @elseif($question->type_of_answer === 'true_false')
                                                            @foreach (['True', 'False'] as $option)
                                                                <label
                                                                    class="flex items-center gap-3 bg-gray-925 text-white rounded-[15px] py-3 px-6 cursor-pointer @if (strtolower($option) === strtolower($question->correct_answer)) border-2 border-green-500 @endif">
                                                                    <input type="radio" name="question_{{ $question->id }}"
                                                                        class="hidden peer"
                                                                        @if (strtolower($option) === strtolower($question->correct_answer)) checked @endif />
                                                                    <span
                                                                        class="w-6 h-6 rounded-full border-2 border-gray-300 flex items-center justify-center peer-checked:border-white peer-checked:bg-white">
                                                                        <span
                                                                            class="w-3 h-3 rounded-full bg-black peer-checked:bg-black"></span>
                                                                    </span>
                                                                    <span class="text-base">{{ $option }}</span>
                                                                </label>
                                                            @endforeach

                                                            {{-- Short Answer / Essay --}}
                                                        @elseif(in_array($question->type_of_answer, ['short_answer', 'essay']))
                                                            <input type="text" class="w-full rounded-[15px] p-3 text-black"
                                                                placeholder="Answer here..."
                                                                value="{{ $question->correct_answer ?? '' }}" />
                                                        @endif
                                                    </div>
                                                </div>
                                            @endforeach
                                            <div class="flex items-center justify-center gap-5 mt-5">
                                                <button id="prevBtn" class="bg-white rounded-full w-8 h-8 flex items-center justify-center cursor-pointer">
                                                    <img src="/frontend/assets/icons/angle-left.svg" alt="Prev" class="size-3">
                                                </button>
                                                <button id="nextBtn" class="bg-white rounded-full w-8 h-8 flex items-center justify-center cursor-pointer">
                                                    <img src="/frontend/assets/icons/angle-right.svg" alt="Next" class="size-3">
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <form action="{{ route('tutor.quiz.update', $quiz->id) }}" method="POST" class="w-full grid grid-cols-12 gap-5 mt-10">
                                @csrf
                                @method('PUT')
                                
                                <div class="col-span-12 lg:col-span-12">
                                    <label class="block mb-2 text-[15px] font-medium text-gray-200">Publish On</label>
                                    <input type="date" name="publish_date"
                                        class="appearance-none bg-gray-1000 border border-gray-950 text-gray-75 placeholder:text-gray-500 rounded-[14px] w-full px-4 py-3"
                                        value="{{ $quiz->publish_date }}" placeholder="dd/mm/yy" required />
                                </div>

                                <input type="hidden" name="status" id="statusInput" value="{{ $quiz->status }}">

                                <div class="col-span-6">
                                    <button type="submit" onclick="document.getElementById('statusInput').value='published';"
                                        class="bg-gray-50 hover:bg-gray-200 rounded-full text-black text-[16px] font-semibold w-full py-3 px-6">
                                        Publish
                                    </button>
                                </div>
                                <div class="col-span-6">
                                    <button type="submit" onclick="document.getElementById('statusInput').value='draft';"
                                        class="bg-gray-50 hover:bg-gray-200 rounded-full text-black text-[16px] font-semibold w-full py-3 px-6">
                                        Save Draft
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

@push('scripts')
    <script>
        let currentIndex = 0;
        const questions = document.querySelectorAll('.question-item');
        const prevBtn = document.getElementById('prevBtn');
        const nextBtn = document.getElementById('nextBtn');
        const currentQuestionIndex = document.getElementById('currentQuestionIndex');
        const totalQuestions = document.getElementById('totalQuestions');

        function showQuestion(index) {
            questions.forEach((q, i) => q.classList.toggle('hidden', i !== index));
            currentQuestionIndex.textContent = index + 1;
        }

        prevBtn.addEventListener('click', () => {
            if(currentIndex > 0) currentIndex--;
            showQuestion(currentIndex);
        });

        nextBtn.addEventListener('click', () => {
            if(currentIndex < questions.length - 1) currentIndex++;
            showQuestion(currentIndex);
        });
    </script>
@endpush