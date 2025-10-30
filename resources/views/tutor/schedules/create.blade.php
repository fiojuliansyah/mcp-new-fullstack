@extends('layouts.app')

@section('content')
    <section class="w-full bg-primary text-white px-4 py-10">
        <div class="w-full max-w-screen-xl mx-auto pb-10"> <!-- Header -->
            <div class="flex flex-col lg:flex-row justify-between gap-5 lg:items-end border-b border-white/10 pb-4">
                <div class="flex items-center gap-3"> <img src="/frontend/assets/images/student-profile-vector.svg"
                        alt="Tutor Avatar" class="w-28" />
                    <div> <span class="text-gray-250">Tutor Dashboard</span>
                        <h1 class="text-4xl font-bold tracking-tight text-white">Welcome Back!</h1>
                    </div>
                </div>
                <div class="flex items-center gap-1 mb-3"> <span class="text-gray-400 text-[15px] font-medium">Home</span>
                    <span class="text-white text-[15px] font-medium">> Schedule New Class</span>
                </div>
            </div>
            <div class="pt-10">
                <div class="grid grid-cols-12">
                    <div class="col-span-12">
                        <!-- Back -->
                        <div class="flex items-center gap-10 mb-10">
                            <a href="{{ route('tutor.dashboard.subject', $selectedSubject->slug) }}"
                                class="bg-white rounded-full w-8 h-8 flex items-center justify-center cursor-pointer">
                                <img src="/frontend/assets/icons/arrow-left.svg" alt="Icon" class="size-4">
                            </a>
                            <h6 class="text-[20px] text-gray-75 font-semibold">Schedule New Class</h6>
                        </div>

                        <!-- FORM -->
                        <form action="{{ route('tutor.schedule.store') }}" method="POST" enctype="multipart/form-data"
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
                                <div class="grid grid-cols-12 gap-8">
                                    <div class="col-span-12">
                                        <label class="block mb-2 text-[15px] font-medium text-gray-200">Topic Name</label>
                                        <input type="text" name="topic"
                                            class="bg-gray-1000 border border-gray-950 text-gray-75 rounded-[14px] w-full px-4 py-3"
                                            placeholder="Enter topic name" required />
                                    </div>

                                    <div class="col-span-12 lg:col-span-6">
                                        <label class="block mb-2 text-[15px] font-medium text-gray-200">Form</label>
                                        <div class="relative">
                                            <select name="form_id"
                                                class="appearance-none pr-10 bg-gray-1000 border border-gray-950 text-gray-75 rounded-[14px] w-full px-4 py-3"
                                                required>
                                                <option value="">Select Level</option>
                                                @foreach ($forms as $form)
                                                    <option value="{{ $form->id }}">
                                                        {{ strtoupper(str_replace('-', ' ', $form->slug)) }}</option>
                                                @endforeach
                                            </select>
                                            <div
                                                class="pointer-events-none absolute right-5 top-1/2 transform -translate-y-1/2 text-white">
                                                <img src="/frontend/assets/icons/angle-down.svg" alt="Icon">
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-span-12 lg:col-span-6">
                                        <label class="block mb-2 text-[15px] font-medium text-gray-200">Class Group</label>
                                        <div class="relative">
                                            <select name="classroom_id"
                                                class="appearance-none pr-10 bg-gray-1000 border border-gray-950 text-gray-75 rounded-[14px] w-full px-4 py-3"
                                                required>
                                                <option value="">Select class group</option>
                                                @foreach ($classes as $class)
                                                    <option value="{{ $class->id }}">{{ $class->name }}</option>
                                                @endforeach
                                            </select>
                                            <div
                                                class="pointer-events-none absolute right-5 top-1/2 transform -translate-y-1/2 text-white">
                                                <img src="/frontend/assets/icons/angle-down.svg" alt="Icon">
                                            </div>
                                        </div>
                                    </div>

                                    <!-- DateTime & Duration -->
                                    <div class="col-span-12 lg:col-span-6">
                                        <label class="block mb-2 text-[15px] font-medium text-gray-200">Date & Time</label>
                                        <input type="datetime-local" name="time"
                                            class="bg-gray-1000 border border-gray-950 text-gray-75 rounded-[14px] w-full px-4 py-3"
                                            required />
                                    </div>
                                    <div class="col-span-12 lg:col-span-6">
                                        <label class="block mb-2 text-[15px] font-medium text-gray-200">Duration
                                            (Minutes)</label>
                                        <input type="number" name="duration"
                                            class="bg-gray-1000 border border-gray-950 text-gray-75 rounded-[14px] w-full px-4 py-3"
                                            placeholder="e.g. 40" required />
                                    </div>

                                    <!-- Agenda -->
                                    <div class="col-span-12">
                                        <label class="block mb-2 text-[15px] font-medium text-gray-200">Agenda</label>
                                        <textarea name="agenda" class="bg-gray-1000 border border-gray-950 text-gray-75 rounded-[14px] w-full px-4 py-3"
                                            placeholder="Enter agenda..." required></textarea>
                                    </div>

                                    <!-- Zoom Settings -->
                                    <div class="col-span-12">
                                        <label class="block text-[15px] font-medium text-gray-200 mb-3">Zoom
                                            Settings</label>
                                        @php $settings = old('settings', []); @endphp
                                        <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                                            @foreach ([
                                            'join_before_host' => 'Join Before Host',
                                            'host_video' => 'Host Video',
                                            'participant_video' => 'Participant Video',
                                            'mute_upon_entry' => 'Mute Upon Entry',
                                            'waiting_room' => 'Waiting Room',
                                        ] as $key => $label)
                                                <label class="flex items-center space-x-2">
                                                    <input type="checkbox" name="settings[{{ $key }}]"
                                                        value="1" {{ isset($settings[$key]) ? 'checked' : '' }}
                                                        class="form-check-input">
                                                    <span>{{ $label }}</span>
                                                </label>
                                            @endforeach
                                        </div>

                                        <div class="grid grid-cols-1 md:grid-cols-3 gap-5 mt-5">
                                            <div>
                                                <label class="block mb-1 text-sm">Audio</label>
                                                <select name="settings[audio]"
                                                    class="appearance-none pr-10 bg-gray-1000 border border-gray-950 text-gray-75 rounded-[14px] w-full px-4 py-3">
                                                    <option value="both">Both</option>
                                                    <option value="telephony">Telephony</option>
                                                    <option value="voip">VoIP</option>
                                                </select>
                                            </div>
                                            <div>
                                                <label class="block mb-1 text-sm">Auto Recording</label>
                                                <select name="settings[auto_recording]"
                                                    class="appearance-none pr-10 bg-gray-1000 border border-gray-950 text-gray-75 rounded-[14px] w-full px-4 py-3">
                                                    <option value="none">None</option>
                                                    <option value="local">Local</option>
                                                    <option value="cloud">Cloud</option>
                                                </select>
                                            </div>
                                            <div>
                                                <label class="block mb-1 text-sm">Approval Type</label>
                                                <select name="settings[approval_type]"
                                                    class="appearance-none pr-10 bg-gray-1000 border border-gray-950 text-gray-75 rounded-[14px] w-full px-4 py-3">
                                                    <option value="0">Auto Approve</option>
                                                    <option value="1">Manual Approve</option>
                                                    <option value="2">No Registration</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- File Upload -->
                                    <div class="col-span-12">
                                        <label class="block text-[15px] font-medium text-gray-200 mb-2">
                                            Study Notes and Reference Materials
                                        </label>
                                        <input type="file" name="materials[]" multiple
                                            class="block w-full text-sm text-gray-400
                                    file:mr-4 file:py-2 file:px-4
                                    file:rounded-full file:border-0
                                    file:text-sm file:font-semibold
                                    file:bg-gray-50 file:text-black hover:file:bg-gray-200" />
                                    </div>

                                    <!-- Submit -->
                                    <div class="col-span-12">
                                        <button type="submit"
                                            class="bg-gray-50 hover:bg-gray-200 rounded-full text-sm px-8 py-3 w-full font-semibold text-black">
                                            Confirm Schedule
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
