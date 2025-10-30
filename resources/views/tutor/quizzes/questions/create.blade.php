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
                <span class="text-white text-[15px] font-medium">> Create Questions</span>
            </div>
        </div>

        <form id="question-form" action="{{ route('tutor.quiz.question.store', $quiz->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="pt-10 space-y-20">
                <div class="pt-10">
                    <div class="flex items-center gap-10 mb-10">
                        <a href="#" class="bg-white rounded-full w-8 h-8 flex items-center justify-center cursor-pointer">
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
                @for ($i = 1; $i <= $quiz->total_question; $i++)
                <div class="grid grid-cols-12 gap-10 mb-20 question-block">
                    <div class="col-span-12 lg:col-span-2">
                        <h1 class="text-center text-[200px] font-bold">{{ $i }}</h1>
                    </div>

                    <div class="col-span-12 lg:col-span-10 bg-gray-990 p-6 rounded-[21px]">
                        <div class="grid grid-cols-12 gap-10">

                            <!-- Question Text -->
                            <div class="col-span-12">
                                <label class="block mb-2 text-[15px] font-medium text-gray-200">Question</label>
                                <input type="text" name="questions[{{ $i }}][text]"
                                    class="bg-gray-1000 border border-gray-950 text-gray-75 rounded-[14px] w-full px-4 py-3"
                                    placeholder="Enter question {{ $i }}" required />
                            </div>

                            <!-- Upload Media -->
                            <div class="col-span-12">
                                <label class="block mb-2 text-[15px] font-medium text-gray-200">Upload Media</label>
                                <input type="file" name="questions[{{ $i }}][media]" class="block w-full text-sm text-gray-300 file-input" />
                                <!-- Container preview -->
                                <div class="file-preview mt-2"></div>
                            </div>

                            <!-- Type of Answer -->
                            <div class="col-span-12 lg:col-span-6">
                                <label class="block mb-2 text-[15px] font-medium text-gray-200">Type of Answer</label>
                                <select name="questions[{{ $i }}][type]" class="type-select bg-gray-1000 border border-gray-950 text-gray-75 rounded-[14px] w-full px-4 py-3" data-question="{{ $i }}">
                                    <option selected>Select Option</option>
                                    <option value="multiple_choice">Multiple Choice</option>
                                    <option value="true_false">True / False</option>
                                    <option value="short_answer">Short Answer</option>
                                    <option value="essay">Essay</option>
                                </select>
                            </div>

                            <!-- Answer Point -->
                            <div class="col-span-12 lg:col-span-6">
                                <label class="block mb-2 text-[15px] font-medium text-gray-200">Answer Point</label>
                                <input type="number" name="questions[{{ $i }}][point]"
                                    class="bg-gray-1000 border border-gray-950 text-gray-75 rounded-[14px] w-full px-4 py-3"
                                    placeholder="Enter point" required />
                            </div>

                            <!-- Answers -->
                            <div class="col-span-12 answer-section" id="answers-{{ $i }}"></div>
                        </div>
                    </div>
                </div>
                @endfor

                <div class="grid grid-cols-12 gap-10 mt-10">
                    <div class="col-span-12 lg:col-span-2"></div>
                    <div class="col-span-12 lg:col-span-10">
                        <button type="submit" class="bg-gray-50 hover:bg-gray-200 rounded-full text-sm px-8 py-3 w-full">
                            <span class="text-black text-[16px] font-semibold">Save Questions</span>
                        </button>
                    </div>
                </div>
            </div>
        </form>
    </div>
</section>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', () => {

    // ---------- File Preview ----------
    document.querySelectorAll('.file-input').forEach(input => {
        input.addEventListener('change', e => {
            const file = e.target.files[0];
            const parent = e.target.parentElement;
            const previewContainer = parent.querySelector('.file-preview');

            previewContainer.innerHTML = '';

            if (!file) return;

            if (file.type.startsWith('image/')) {
                const img = document.createElement('img');
                img.src = URL.createObjectURL(file);
                img.classList.add('w-32','h-32','object-cover','rounded-md');
                previewContainer.appendChild(img);
            } else if (file.type === 'application/pdf') {
                const span = document.createElement('span');
                span.textContent = file.name;
                span.classList.add('text-gray-200');
                previewContainer.appendChild(span);
            } else {
                const span = document.createElement('span');
                span.textContent = 'File type not supported for preview';
                previewContainer.appendChild(span);
            }
        });
    });

    // ---------- Multiple Choice Answers ----------
    function renderMultipleChoice(questionId, count = 2) {
        let html = '';
        for (let a = 1; a <= count; a++) {
            html += `<div class="grid grid-cols-12 gap-5 mb-3 answer-row">
                <div class="col-span-8 lg:col-span-9">
                    <label class="block mb-2 text-[15px] font-medium text-gray-200">Answer ${a}</label>
                    <input type="text" name="questions[${questionId}][answer][${a}][text]"
                        class="bg-gray-1000 border border-gray-950 text-gray-75 rounded-[14px] w-full px-4 py-3"
                        placeholder="Enter answer ${a}" />
                </div>
                <div class="col-span-4 lg:col-span-3 flex items-end justify-center">
                    <label class="relative flex items-center justify-center cursor-pointer">
                        <input type="checkbox" name="questions[${questionId}][answer][${a}][correct]" value="1" class="peer hidden" />
                        <span class="w-10 h-10 flex items-center justify-center rounded-[11px] border border-[#C8C8C8] bg-black peer-checked:bg-white transition">
                            <svg class="hidden w-4 h-4 text-black peer-checked:block" xmlns="http://www.w3.org/2000/svg" fill="none"
                                viewBox="0 0 24 24" stroke="currentColor" stroke-width="3">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                            </svg>
                        </span>
                    </label>
                </div>
            </div>`;
        }
        html += `<button type="button" class="add-answer-btn bg-gray-800 hover:bg-gray-700 text-white text-sm rounded-full px-4 py-2 mt-3"
                    data-question="${questionId}" data-next="${count + 1}">+ Add Answer</button>`;
        return html;
    }

    // ---------- Handle Type Select ----------
    document.querySelectorAll('.type-select').forEach(select => {
        select.addEventListener('change', e => {
            const questionId = e.target.dataset.question;
            const section = document.getElementById(`answers-${questionId}`);
            const type = e.target.value;
            let html = '';

            if (type === 'multiple_choice') html = renderMultipleChoice(questionId);
            else if (type === 'true_false') html = `<div class="flex gap-10">
                <label class="flex items-center gap-3"><input type="radio" name="questions[${questionId}][correct]" value="true" /> True</label>
                <label class="flex items-center gap-3"><input type="radio" name="questions[${questionId}][correct]" value="false" /> False</label>
            </div>`;
            else if (type === 'short_answer' || type === 'essay') html = `<label class="block mb-2 text-[15px] font-medium text-gray-200">Correct Answer</label>
                <input type="text" name="questions[${questionId}][correct]" class="bg-gray-1000 border border-gray-950 text-gray-75 rounded-[14px] w-full px-4 py-3" placeholder="Enter correct answer">`;

            section.innerHTML = html;
        });
    });

    // ---------- Add Answer Button ----------
    document.addEventListener('click', e => {
        if (e.target.classList.contains('add-answer-btn')) {
            const btn = e.target;
            const questionId = btn.dataset.question;
            let next = parseInt(btn.dataset.next);
            const container = btn.closest('.answer-section');

            const newAnswer = document.createElement('div');
            newAnswer.classList.add('grid','grid-cols-12','gap-5','mb-3','answer-row');
            newAnswer.innerHTML = `<div class="col-span-8 lg:col-span-9">
                <label class="block mb-2 text-[15px] font-medium text-gray-200">Answer ${next}</label>
                <input type="text" name="questions[${questionId}][answer][${next}][text]" class="bg-gray-1000 border border-gray-950 text-gray-75 rounded-[14px] w-full px-4 py-3" placeholder="Enter answer ${next}" />
            </div>
            <div class="col-span-4 lg:col-span-3 flex items-end justify-center">
                <label class="relative flex items-center justify-center cursor-pointer">
                    <input type="checkbox" name="questions[${questionId}][answer][${next}][correct]" value="1" class="peer hidden" />
                    <span class="w-10 h-10 flex items-center justify-center rounded-[11px] border border-[#C8C8C8] bg-black peer-checked:bg-white transition">
                        <svg class="hidden w-4 h-4 text-black peer-checked:block" xmlns="http://www.w3.org/2000/svg" fill="none"
                            viewBox="0 0 24 24" stroke="currentColor" stroke-width="3">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                        </svg>
                    </span>
                </label>
            </div>`;
            container.insertBefore(newAnswer, btn);
            btn.dataset.next = next + 1;
        }
    });

});

</script>
@endpush
