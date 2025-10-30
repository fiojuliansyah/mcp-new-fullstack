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
                <span class="text-gray-910 text-[15px] font-medium">Home</span>
                <span class="text-white text-[15px] font-medium">&gt; Upload Study Notes And Reference Materials</span>
            </div>
        </div>

        <div class="space-y-10 divide-y divide-zinc-700">
            <div class="w-full pt-10">
                <div class="grid grid-cols-12">
                    <div class="col-span-12">
                        <div class="flex items-center gap-10 mb-10">
                            <a href="{{ route('tutor.dashboard.subject', $selectedSubject->slug) }}"
                                class="bg-white rounded-full w-8 h-8 flex items-center justify-center cursor-pointer">
                                <img src="/frontend/assets/icons/arrow-left.svg" alt="Icon" class="size-4">
                            </a>
                            <h6 class="text-[20px] text-gray-75 font-semibold">Upload Study Notes And Reference Materials</h6>
                        </div>

                        <div class="grid grid-cols-12 gap-5">
                            <div class="col-span-12 lg:col-span-2">
                                <div class="flex flex-col gap-3 items-center">
                                    <div class="flex items-center justify-center">
                                        <img src="{{ $user->avatar_url ?? '/frontend/assets/images/default-avatar.png' }}" alt="Image"
                                            class="w-[154px] h-[186px] rounded-[13px] object-cover" />
                                    </div>
                                    <span class="font-bebas">{{ $selectedSubject->name ?? 'Subject' }}</span>
                                </div>
                            </div>

                            <div class="col-span-12 lg:col-span-10">
                                <form action="{{ route('tutor.material.store') }}" method="POST" enctype="multipart/form-data">
                                    @csrf
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

                                        {{-- Material URLs --}}
                                        <div class="col-span-12">
                                            <label class="block mb-2 text-[15px] font-medium text-gray-200">Upload Material (PDF)</label>
                                            <div id="dropzone-area"
                                                class="dropzone border-2 border-dashed border-gray-600 bg-gray-1000 rounded-[14px] p-6 text-center text-gray-400">
                                            </div>
                                            <div id="uploaded-files"></div>
                                        </div>

                                        {{-- Submit Button --}}
                                        <div class="col-span-12">
                                            <button type="submit"
                                                class="bg-gray-50 hover:bg-gray-200 rounded-full text-sm px-8 py-3 w-full">
                                                <span class="text-black text-[16px] font-semibold">Upload Material</span>
                                            </button>
                                        </div>
                                    </div>
                                </form>
                            </div> <!-- /col -->
                        </div> <!-- /grid -->
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection

@push('styles')
<style>
    #dropzone-area {
        background-color: #0F1012;
        border: 1px solid #1F1F1F;
        color: #B3B3B3;
        border-radius: 14px;
        padding: 1rem;
        transition: all 0.2s ease;
    }

    #dropzone-area:hover,
    #dropzone-area.dz-started,
    #dropzone-area.dz-drag-hover {
        background-color: #0F1014;
        border-color: #3B3B3B;
    }

    #dropzone-area p {
        color: #B3B3B3;
        font-size: 15px;
    }

    #dropzone-area .dz-message {
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        min-height: 120px;
        gap: 8px;
    }

    .dz-remove {
        color: #FF6666 !important;
        font-size: 13px;
    }
</style>

@endpush

@push('scripts')
<script src="https://unpkg.com/dropzone@5/dist/min/dropzone.min.js"></script>
<link rel="stylesheet" href="https://unpkg.com/dropzone@5/dist/min/dropzone.min.css" />


<script>
Dropzone.autoDiscover = false;

const pdfDropzone = new Dropzone("#dropzone-area", {
    url: "{{ route('tutor.material.upload-chunk') }}",
    paramName: "file",
    maxFilesize: 20,
    acceptedFiles: ".pdf",
    addRemoveLinks: true,
    headers: {
        'X-CSRF-TOKEN': '{{ csrf_token() }}'
    },
    success: function(file, response) {
        if (response.path) {
            console.log('Uploaded:', response.path);

            // Tambahkan input hidden baru
            const hiddenInput = document.createElement('input');
            hiddenInput.type = 'hidden';
            hiddenInput.name = 'uploaded_files[]';
            hiddenInput.value = response.path;
            document.getElementById('uploaded-files').appendChild(hiddenInput);
        } else {
            console.error('No path returned from server', response);
        }
    },
    error: function(file, response) {
        console.error('Upload error:', response);
    }
});
</script>


<script>
document.addEventListener('DOMContentLoaded', function () {
    const formSelect = document.getElementById('formSelect');
    const classroomSelect = document.getElementById('classroomSelect');
    const topicSelect = document.getElementById('topicSelect');
    const addVideoUrlBtn = document.getElementById('add-video-url');
    const videoUrlWrapper = document.getElementById('video-url-wrapper');

    const baseClassroomUrl = "{{ url('tutor/material/get-classrooms') }}";
    const baseTopicUrl = "{{ url('tutor/material/get-topics') }}";

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
