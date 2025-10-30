@extends('layouts.app')

@section('content')
    <section class="w-full bg-primary text-white px-4 py-10">
        <div class="w-full max-w-screen-xl mx-auto">

            <div class="flex flex-col lg:flex-row justify-between gap-5 lg:items-end border-b border-white/10">
                <div class="flex items-center gap-3">
                    <img src="/frontend/assets/images/student-profile-vector.svg" alt="Tutor Avatar" class="w-28" />
                    <div>
                        <span class="text-gray-250">Student</span>
                        <h1 class="text-4xl font-bold tracking-tight text-white">Quiz</h1>
                    </div>
                </div>
                <div class="flex items-center gap-3 text-white text-lg">
                    <span>Time Left:</span>
                    <span id="timer">--:--</span>
                </div>
            </div>

            <div class="grid grid-cols-12 gap-10 pt-10">
                <div class="col-span-12 lg:col-span-8">
                    <form id="quizForm" method="POST" action="{{ route('student.quizzes.submit', $quiz->id) }}">
                        @csrf
                        <div id="quiz-container" class="bg-[#181818] p-10 rounded-[21px] flex flex-col gap-10">

                            @foreach ($quiz->questions as $index => $question)
                                <div class="quiz-question {{ $index !== 0 ? 'hidden' : '' }}"
                                    data-index="{{ $index }}" id="question-{{ $question->id }}">

                                    <p class="text-white font-semibold mb-5">{{ $index + 1 }}. {{ $question->question }}
                                    </p>

                                    @if ($question->media_url)
                                        @php
                                            $ext = pathinfo($question->media_url, PATHINFO_EXTENSION);
                                            $mediaUrl = \Illuminate\Support\Facades\Storage::url($question->media_url);
                                        @endphp

                                        @if (in_array($ext, ['jpg', 'jpeg', 'png', 'gif', 'webp']))
                                            <img src="{{ $mediaUrl }}" alt="Question Media"
                                                class="mb-5 max-h-80 w-auto">
                                        @elseif($ext === 'pdf')
                                            <iframe src="{{ $mediaUrl }}"
                                                class="w-full h-80 mb-5 border border-gray-700"></iframe>
                                        @endif
                                    @endif


                                    @if ($question->type_of_answer === 'multiple_choice')
                                        @php $options = $question->answer ?? []; @endphp
                                        @foreach ($options as $key => $option)
                                            @php $optionText = is_array($option) && isset($option['text']) ? $option['text'] : $option; @endphp
                                            <label
                                                class="flex items-center gap-3 bg-gray-500 text-white rounded-[15px] py-3 px-6 cursor-pointer mb-3 border border-gray-700">
                                                <input type="radio" name="answer[{{ $question->id }}]"
                                                    value="{{ $key }}" class="hidden peer">
                                                <span
                                                    class="w-6 h-6 rounded-full border-2 border-gray-300 flex items-center justify-center peer-checked:border-white peer-checked:bg-white">
                                                    <span
                                                        class="w-3 h-3 rounded-full bg-black peer-checked:bg-black"></span>
                                                </span>
                                                <span class="text-base">{{ $optionText }}</span>
                                            </label>
                                        @endforeach
                                    @elseif ($question->type_of_answer === 'true_false')
                                        @php $options = ['true'=>'True','false'=>'False']; @endphp
                                        @foreach ($options as $key => $optionText)
                                            <label
                                                class="flex items-center gap-3 bg-gray-500 text-white rounded-[15px] py-3 px-6 cursor-pointer mb-3 border border-gray-700">
                                                <input type="radio" name="answer[{{ $question->id }}]"
                                                    value="{{ $key }}" class="hidden peer">
                                                <span
                                                    class="w-6 h-6 rounded-full border-2 border-gray-300 flex items-center justify-center peer-checked:border-white peer-checked:bg-white">
                                                    <span
                                                        class="w-3 h-3 rounded-full bg-black peer-checked:bg-black"></span>
                                                </span>
                                                <span class="text-base">{{ $optionText }}</span>
                                            </label>
                                        @endforeach
                                    @elseif($question->type_of_answer === 'short_answer')
                                        <input type="text" name="answer[{{ $question->id }}]"
                                            class="w-full bg-gray-500 text-white rounded-[15px] py-3 px-4 border border-gray-700">
                                    @elseif($question->type_of_answer === 'essay')
                                        <textarea name="answer[{{ $question->id }}]" rows="5"
                                            class="w-full bg-gray-500 text-white rounded-[15px] py-3 px-4 border border-gray-700"></textarea>
                                    @endif
                                </div>
                            @endforeach

                            <div class="flex justify-between mt-5">
                                <button type="button" id="prevBtn"
                                    class="bg-white text-black px-5 py-2 rounded-full">Previous</button>
                                <button type="button" id="nextBtn"
                                    class="bg-gray-50 text-black px-5 py-2 rounded-full">Next</button>
                                <button type="submit" id="submitBtn"
                                    class="bg-green-500 text-white px-5 py-2 rounded-full hidden">Submit</button>
                            </div>
                        </div>
                    </form>
                </div>

                <div class="col-span-12 lg:col-span-4">
                    <div class="bg-[#181818] p-5 rounded-[21px]">
                        <h6 class="text-white font-semibold mb-5">Jump to Question</h6>
                        <div class="grid grid-cols-4 gap-3">
                            @foreach ($quiz->questions as $index => $question)
                                <button type="button" class="jumpBtn w-full py-2 rounded-lg bg-gray-700 text-white"
                                    data-index="{{ $index }}">
                                    {{ $index + 1 }}
                                </button>
                            @endforeach
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
        const questions = document.querySelectorAll('.quiz-question');
        const totalQuestions = questions.length;
        const jumpButtons = document.querySelectorAll('.jumpBtn');

        let savedTime = localStorage.getItem('quiz_timer_{{ $quiz->id }}');
        let totalTime = savedTime ? parseInt(savedTime) : {{ $quiz->estimated_time ?? 10 }} * 60;

        const timerEl = document.getElementById('timer');

        const updateButtons = () => {
            document.getElementById('prevBtn').style.display = currentIndex === 0 ? 'none' : 'inline-block';
            document.getElementById('nextBtn').style.display = currentIndex === totalQuestions - 1 ? 'none' :
                'inline-block';
            document.getElementById('submitBtn').style.display = currentIndex === totalQuestions - 1 ? 'inline-block' :
                'none';
            highlightJumpButtons();
        };

        const showQuestion = (index) => {
            questions.forEach(q => q.classList.add('hidden'));
            questions[index].classList.remove('hidden');
            currentIndex = index;
            updateButtons();
        };

        const highlightJumpButtons = () => {
            jumpButtons.forEach((btn, i) => {
                const q = questions[i];
                const answered = q.querySelector('input:checked, textarea, input[type="text"]')?.value;
                btn.classList.remove('bg-green-500', 'bg-red-500');
                if (answered) btn.classList.add('bg-green-500');
                else if (i < currentIndex) btn.classList.add('bg-red-500');
            });
        };

        document.getElementById('nextBtn').addEventListener('click', () => showQuestion(currentIndex + 1));
        document.getElementById('prevBtn').addEventListener('click', () => showQuestion(currentIndex - 1));

        jumpButtons.forEach(btn => {
            btn.addEventListener('click', e => showQuestion(parseInt(e.target.dataset.index)));
        });

        questions.forEach(q => {
            q.addEventListener('change', () => highlightJumpButtons());
        });

        const timerInterval = setInterval(() => {
            let minutes = Math.floor(totalTime / 60);
            let seconds = totalTime % 60;
            timerEl.textContent = `${minutes.toString().padStart(2,'0')}:${seconds.toString().padStart(2,'0')}`;

            if (totalTime <= 0) {
                clearInterval(timerInterval);
                localStorage.removeItem('quiz_timer_{{ $quiz->id }}');
                alert('Time is up! Quiz will be submitted.');
                document.getElementById('quizForm').submit();
            } else {
                localStorage.setItem('quiz_timer_{{ $quiz->id }}', totalTime);
            }

            totalTime--;
        }, 1000);

        document.getElementById('quizForm').addEventListener('submit', () => {
            localStorage.removeItem('quiz_timer_{{ $quiz->id }}');
        });

        showQuestion(0);
        highlightJumpButtons();
    </script>
@endpush
