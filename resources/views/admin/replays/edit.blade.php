@extends('layouts.main')

@section('content')
<div class="space-y-5 py-5 px-10">

    {{-- PAGE HEADER --}}
    <div class="space-y-7 flex flex-col">
        <h1 class="text-gray-100 font-[600]">Replays Management</h1>
        <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-3">
            <div class="flex items-center gap-3">
                <a href="{{ route('admin.replays.index') }}"
                    class="bg-white rounded-full w-8 h-8 flex items-center justify-center cursor-pointer">
                    <img src="/admin/assets/icons/arrow-left.svg" alt="Icon" class="size-4">
                </a>
                <h6 class="text-gray-100 font-[600]">Edit Replay</h6>
            </div>
        </div>
    </div>

    {{-- FORM --}}
    <form method="POST" action="{{ route('admin.replays.update', $replay->id) }}" enctype="multipart/form-data" class="space-y-10 pt-5">
        @csrf
        @method('PUT')

        <div class="grid grid-cols-12 gap-10">
            
            {{-- Select Form --}}
            <div class="col-span-12 lg:col-span-6">
                <label class="block mb-2 text-[15px] font-medium text-gray-200">Form</label>
                <div class="relative">
                    <select id="formSelect" name="form_id"
                        class="appearance-none pr-10 bg-gray-1000 border border-gray-950 text-gray-75 rounded-[14px] w-full px-4 py-3">
                        <option value="">Select Form</option>
                        @foreach ($forms as $form)
                            <option value="{{ $form->id }}" {{ $form->id == $replay->form_id ? 'selected' : '' }}>
                                {{ $form->name }}
                            </option>
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
                        {{-- Opsi akan diisi oleh JavaScript --}}
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
                        {{-- Opsi akan diisi oleh JavaScript --}}
                        <option value="">Select topic name</option>
                    </select>
                    <div class="pointer-events-none absolute right-5 top-1/2 transform -translate-y-1/2 text-white">
                        <img src="/frontend/assets/icons/angle-down.svg" alt="Icon">
                    </div>
                </div>
            </div>

            @if($replay->replayVideos->count())
            <div class="col-span-6 space-y-4">
                <label class="block mb-2 text-[15px] font-medium text-gray-200">Existing Videos</label>
                <div id="existing-videos" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                    @foreach($replay->replayVideos as $video)
                        <div class="border border-gray-700 rounded-lg p-3 flex flex-col bg-gray-900" data-video-id="{{ $video->id }}">
                            <video controls class="w-full rounded mb-2">
                                <source src="{{ Storage::disk(config('filesystems.default'))->url($video->video_url) }}" type="video/mp4">
                                Your browser does not support the video tag.
                            </video>
                            <button type="button"
                                class="delete-video bg-red-600 text-white text-sm px-3 py-1 rounded hover:bg-red-700"
                                data-id="{{ $video->id }}">
                                Delete
                            </button>
                        </div>
                    @endforeach
                </div>
            </div>
            @endif

            {{-- Video Upload --}}
            <div class="col-span-6">
                <label class="block mb-2 text-[15px] font-medium text-gray-200">Upload Video Replay</label>
                <div id="dropzone-area"
                    class="dropzone border-2 border-dashed border-gray-600 bg-gray-1000 rounded-[14px] p-6 text-center text-gray-400">
                </div>
                <input type="hidden" name="uploaded_video" id="uploaded_video" value="{{ old('uploaded_video', '[]') }}">
            </div>

            {{-- Start Date --}}
            <div class="col-span-12 lg:col-span-6">
                <label class="block mb-2 text-[15px] font-medium text-gray-200">Start Date</label>
                <input type="date" name="start_date"
                    value="{{ old('start_date', $replay->start_date ? \Carbon\Carbon::parse($replay->start_date)->format('Y-m-d') : '') }}"
                    class="appearance-none bg-gray-1000 border border-gray-950 text-gray-75 rounded-[14px] w-full px-4 py-3"
                    required />
            </div>

            {{-- End Date --}}
            <div class="col-span-12 lg:col-span-6">
                <label class="block mb-2 text-[15px] font-medium text-gray-200">End Date</label>
                <input type="date" name="end_date"
                    value="{{ old('end_date', $replay->end_date ? \Carbon\Carbon::parse($replay->end_date)->format('Y-m-d') : '') }}"
                    class="appearance-none bg-gray-1000 border border-gray-950 text-gray-75 rounded-[14px] w-full px-4 py-3" />
            </div>

            {{-- Submit --}}
            <div class="col-span-12">
                <button type="submit" class="bg-gray-50 hover:bg-gray-200 rounded-full text-sm px-8 py-3 w-full">
                    <span class="text-black text-[16px] font-semibold">Update Replay</span>
                </button>
            </div>
        </div>
    </form>
</div>
@endsection


@push('styles')
<style>
    /* Styling Dropzone (sudah Anda berikan) */
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

const dropzone = new Dropzone("#dropzone-area", {
    url: "{{ route('admin.replays.upload-chunk') }}",
    chunking: true,
    forceChunking: true,
    chunkSize: 2000000,
    retryChunks: true,
    retryChunksLimit: 3,
    parallelUploads: 1,
    maxFilesize: 2048, 
    acceptedFiles: "video/*",
    addRemoveLinks: true,
    dictRemoveFile: "Remove",
    headers: { "X-CSRF-TOKEN": "{{ csrf_token() }}" },
    init: function () {
        this.on("success", function (file, response) {
            console.log("Upload success:", response);
            let input = document.getElementById("uploaded_video");
            let current = input.value ? JSON.parse(input.value) : [];
            current.push(response.path); 
            input.value = JSON.stringify(current);
        });
        this.on("error", function (file, errorMessage) {
            console.error("Upload error:", errorMessage);
        });
    }
});

document.addEventListener('DOMContentLoaded', async function () {
    const formSelect = document.getElementById('formSelect');
    const classroomSelect = document.getElementById('classroomSelect');
    const topicSelect = document.getElementById('topicSelect');

    const baseClassroomUrl = "{{ url('tutor/replay/get-classrooms') }}";
    const baseTopicUrl = "{{ url('tutor/replay/get-topics') }}";

    const selectedForm = "{{ $replay->form_id }}";
    const selectedClassroom = "{{ $replay->classroom_id }}";
    const selectedTopic = "{{ $replay->schedule_id }}";

    function resetDropdown(select, placeholder, value) {
        select.innerHTML = `<option value="">${placeholder}</option>`;
        if (value) {
            select.insertAdjacentHTML('beforeend', 
                `<option value="${value.id}" selected>${value.name || value.topic_name}</option>`);
        }
    }

    async function loadClassrooms(formId, selectedId = null) {
        if (!selectedId || formSelect.value != selectedForm) {
            resetDropdown(classroomSelect, 'Select class group');
        }
        
        if (!formId) return;
        
        try {
            const res = await fetch(`${baseClassroomUrl}/${formId}`);
            const data = await res.json();
            
            if (data.length > 0) {
                classroomSelect.innerHTML = `<option value="">Select class group</option>`;
            }

            data.forEach(c => {
                classroomSelect.insertAdjacentHTML('beforeend',
                    `<option value="${c.id}" ${c.id == selectedId ? 'selected' : ''}>${c.name}</option>`);
            });
        } catch (error) {
            console.error("Error loading classrooms:", error);
        }
    }

    // Load topics
    async function loadTopics(classroomId, selectedId = null) {
        // Jangan reset jika selectedId ada, kecuali jika classroomId berubah
        if (!selectedId || classroomSelect.value != selectedClassroom) {
            resetDropdown(topicSelect, 'Select topic name');
        }

        if (!classroomId) return;
        
        try {
            const res = await fetch(`${baseTopicUrl}/${classroomId}`);
            const data = await res.json();

            if (data.length > 0) {
                topicSelect.innerHTML = `<option value="">Select topic name</option>`;
            }

            data.forEach(t => {
                topicSelect.insertAdjacentHTML('beforeend',
                    `<option value="${t.id}" ${t.id == selectedId ? 'selected' : ''}>${t.topic_name}</option>`);
            });
        } catch (error) {
            console.error("Error loading topics:", error);
        }
    }

    formSelect.addEventListener('change', e => {
        loadClassrooms(e.target.value);
        resetDropdown(topicSelect, 'Select topic name');
    });
    
    classroomSelect.addEventListener('change', e => loadTopics(e.target.value));

    if (selectedForm) {
        await loadClassrooms(selectedForm, selectedClassroom);
        if (selectedClassroom) {
            await loadTopics(selectedClassroom, selectedTopic);
        }
    }
});

document.querySelectorAll('.delete-video').forEach(btn => {
    btn.addEventListener('click', async function() {
        const id = this.dataset.id;
        if (!confirm('Are you sure you want to delete this video?')) return;

        try {
            const res = await fetch(`{{ url('admin/replays/video') }}/${id}`, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Content-Type': 'application/json'
                }
            });
            
            const data = await res.json();
            
            if (data.success) {
                document.querySelector(`[data-video-id="${id}"]`).remove();
            } else {
                alert('Failed to delete video: ' + (data.error || 'Unknown error.'));
            }
        } catch (error) {
            alert('An error occurred during deletion.');
            console.error('Fetch error:', error);
        }
    });
});
</script>
@endpush