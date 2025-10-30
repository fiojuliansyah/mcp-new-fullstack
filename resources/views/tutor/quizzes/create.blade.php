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
                    <span class="text-gray-910 text-[15px] font-medium">Home</span>
                    <span class="text-white text-[15px font-medium">> Create Quizzes</span>
                </div>
            </div>

            <!-- CONTENT -->
            <div class="space-y-10 divide-y divide-zinc-700">
                <div class="w-full pt-10">
                    <div class="grid grid-cols-12">
                        <div class="col-span-12 md:col-span-12 lg:col-span-12">
                            <!-- BACK -->
                            <div class="flex items-center gap-10 mb-10">
                                <a href="{{ route('tutor.dashboard.subject', $selectedSubject->slug) }}"
                                    class="bg-white rounded-full w-8 h-8 flex items-center justify-center cursor-pointer">
                                    <img src="/frontend/assets/icons/arrow-left.svg" alt="Icon" class="size-4">
                                </a>
                                <h6 class="text-[20px] text-gray-75 font-semibold">Create Quizzes</h6>
                            </div>

                            <!-- FORM -->
                            <form action="{{ route('tutor.quiz.store') }}" method="POST" enctype="multipart/form-data"
                                class="grid grid-cols-12 gap-5">
                                @csrf
                                <div class="col-span-12 lg:col-span-2">
                                    <div class="flex flex-col gap-3 items-center">
                                        <div class="flex items-center justify-center">
                                            <img src="{{ $user->avatar_url ?? '/frontend/assets/images/default-avatar.png' }}"
                                                alt="Image" class="w-[154px] h-[186px] rounded-[13px] object-cover" />
                                        </div>
                                        <span class="font-bebas text-lg">{{ $selectedSubject->name ?? 'Subject' }}</span>
                                    </div>
                                </div>
                                <div class="col-span-12 lg:col-span-10">
                                    <div class="grid grid-cols-12 gap-10">
                                        {{-- Select Form --}}
                                        <div class="col-span-12 lg:col-span-6">
                                            <label class="block mb-2 text-[15px] font-medium text-gray-200">Form</label>
                                            <div class="relative">
                                                <select id="formSelect" name="form_id"
                                                    class="appearance-none pr-10 bg-gray-1000 border border-gray-950 text-gray-75 rounded-[14px] w-full px-4 py-3">
                                                    <option value="">Select Form</option>
                                                    @foreach ($forms as $form)
                                                        <option value="{{ $form->id }}">{{ $form->name }}</option>
                                                    @endforeach
                                                </select>
                                                <div class="pointer-events-none absolute right-5 top-1/2 transform -translate-y-1/2 text-white">
                                                    <img src="/frontend/assets/icons/angle-down.svg" alt="Icon">
                                                </div>
                                            </div>
                                        </div>

                                        {{-- Select Classroom --}}
                                        <div class="col-span-12 lg:col-span-6">
                                            <label class="block mb-2 text-[15px] font-medium text-gray-200">Class Group</label>
                                            <div class="relative">
                                                <select id="classroomSelect" name="classroom_id"
                                                    class="appearance-none pr-10 bg-gray-1000 border border-gray-950 text-gray-75 rounded-[14px] w-full px-4 py-3">
                                                    <option value="">Select class group</option>
                                                </select>
                                                <div class="pointer-events-none absolute right-5 top-1/2 transform -translate-y-1/2 text-white">
                                                    <img src="/frontend/assets/icons/angle-down.svg" alt="Icon">
                                                </div>
                                            </div>
                                        </div>

                                        {{-- Select Topic --}}
                                        <div class="col-span-12">
                                            <label class="block mb-2 text-[15px] font-medium text-gray-200">Topic Name</label>
                                            <div class="relative">
                                                <select id="topicSelect" name="schedule_id"
                                                    class="appearance-none pr-10 bg-gray-1000 border border-gray-950 text-gray-75 rounded-[14px] w-full px-4 py-3">
                                                    <option value="">Select topic name</option>
                                                </select>
                                                <div class="pointer-events-none absolute right-5 top-1/2 transform -translate-y-1/2 text-white">
                                                    <img src="/frontend/assets/icons/angle-down.svg" alt="Icon">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-span-12 lg:col-span-6">
                                            <div class="w-full">
                                                <label for=""
                                                    class="block mb-2 text-[15px] font-medium text-gray-200 mb-1">Start
                                                    Date</label>
                                                <input type="date" name="start_date"
                                                    class="appearance-none bg-gray-1000 border border-gray-950 text-gray-75 placeholder:text-gray-500 rounded-[14px] w-full px-4 py-3"
                                                    placeholder="dd/mm/yy" required />
                                            </div>
                                        </div>
                                        <div class="col-span-12 lg:col-span-6">
                                            <div class="w-full">
                                                <label for=""
                                                    class="block mb-2 text-[15px] font-medium text-gray-200 mb-1">End
                                                    Date</label>
                                                <input type="date" name="end_date"
                                                    class="appearance-none bg-gray-1000 border border-gray-950 text-gray-75 placeholder:text-gray-500 rounded-[14px] w-full px-4 py-3"
                                                    placeholder="Select time" required />
                                            </div>
                                        </div>
                                        <div class="col-span-12 lg:col-span-6">
                                            <div class="w-full">
                                                <label for=""
                                                    class="block mb-2 text-[15px] font-medium text-gray-200 mb-1">Estimated
                                                    Time</label>
                                                <div class="relative">
                                                    <input type="number" name="estimated_time"
                                                    class="appearance-none bg-gray-1000 border border-gray-950 text-gray-75 placeholder:text-gray-500 rounded-[14px] w-full px-4 py-3"
                                                    placeholder="Estimated time in minute" required />
                                                    <div
                                                        class="pointer-events-none absolute right-5 top-1/2 transform -translate-y-1/2 text-white">
                                                        <img src="/frontend/assets/icons/angle-down.svg" alt="Icon">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-span-12 lg:col-span-6">
                                            <div class="w-full">
                                                <label for=""
                                                    class="block mb-2 text-[15px] font-medium text-gray-200 mb-1">Attempts
                                                    Time</label>
                                                <div class="relative">
                                                    <input type="number" name="attempts_time"
                                                    class="appearance-none bg-gray-1000 border border-gray-950 text-gray-75 placeholder:text-gray-500 rounded-[14px] w-full px-4 py-3"
                                                    placeholder="Attempts Time" required />
                                                    <div
                                                        class="pointer-events-none absolute right-5 top-1/2 transform -translate-y-1/2 text-white">
                                                        <img src="/frontend/assets/icons/angle-down.svg" alt="Icon">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-span-12 lg:col-span-6">
                                            <div class="w-full">
                                                <label for=""
                                                    class="block mb-2 text-[15px] font-medium text-gray-200 mb-1">Max
                                                    Score</label>
                                                <input type="number" name="max_score"
                                                    class="bg-gray-1000 border border-gray-950 text-gray-75 placeholder:text-gray-500 rounded-[14px] w-full px-4 py-3"
                                                    placeholder="Enter max score" required />
                                            </div>
                                        </div>
                                        <div class="col-span-12 lg:col-span-6">
                                            <div class="w-full">
                                                <label for=""
                                                    class="block mb-2 text-[15px] font-medium text-gray-200 mb-1">Total
                                                    Questions</label>
                                                <input type="number"  name="total_question"
                                                    class="bg-gray-1000 border border-gray-950 text-gray-75 placeholder:text-gray-500 rounded-[14px] w-full px-4 py-3"
                                                    placeholder="Enter total question" required />
                                            </div>
                                        </div>
                                        <div class="col-span-12 lg:col-span-6">
                                            <div class="w-full">
                                                <label for=""
                                                    class="block mb-2 text-[15px] font-medium text-gray-200 mb-1">Auto
                                                    Mark</label>
                                                <div class="relative">
                                                    <select name="auto_mark"
                                                        class="appearance-none pr-10 bg-gray-1000 border border-gray-950 text-gray-75 placeholder:text-gray-500 rounded-[14px] w-full px-4 py-3">
                                                        <option value="yes" selected>Yes</option>
                                                        <option value="no">No</option>
                                                    </select>
                                                    <div
                                                        class="pointer-events-none absolute right-5 top-1/2 transform -translate-y-1/2 text-white">
                                                        <img src="/frontend/assets/icons/angle-down.svg" alt="Icon">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-span-12 lg:col-span-12">
                                            <button type="submit"
                                                class="bg-gray-50 hover:bg-gray-200 rounded-full text-sm px-8 py-3 w-full">
                                                <span class="text-black text-[16px] font-semibold">Continue</span>
                                            </button>
                                        </div>
                                    </div>
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
document.addEventListener('DOMContentLoaded', function () {
    const formSelect = document.getElementById('formSelect');
    const classroomSelect = document.getElementById('classroomSelect');
    const topicSelect = document.getElementById('topicSelect');
    const addVideoUrlBtn = document.getElementById('add-video-url');
    const videoUrlWrapper = document.getElementById('video-url-wrapper');

    const baseClassroomUrl = "{{ url('tutor/quiz/get-classrooms') }}";
    const baseTopicUrl = "{{ url('tutor/quiz/get-topics') }}";

    function resetDropdown(select, placeholder) {
        select.innerHTML = `<option value="">${placeholder}</option>`;
    }

    formSelect.addEventListener('change', function () {
        const formId = this.value;
        resetDropdown(classroomSelect, 'Select class group');
        resetDropdown(topicSelect, 'Select topic name');

        if (formId) {
            fetch(`${baseClassroomUrl}/${formId}`)
                .then(res => res.json())
                .then(data => {
                    data.forEach(classroom => {
                        classroomSelect.insertAdjacentHTML('beforeend',
                            `<option value="${classroom.id}">${classroom.name}</option>`);
                    });
                })
                .catch(err => console.error(err));
        }
    });

    classroomSelect.addEventListener('change', function () {
        const classroomId = this.value;
        resetDropdown(topicSelect, 'Select topic name');

        if (classroomId) {
            fetch(`${baseTopicUrl}/${classroomId}`)
                .then(res => res.json())
                .then(data => {
                    data.forEach(topic => {
                        topicSelect.insertAdjacentHTML('beforeend',
                            `<option value="${topic.id}">${topic.topic_name}</option>`);
                    });
                })
                .catch(err => console.error(err));
        }
    });
});
</script>
@endpush