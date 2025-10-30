@extends('layouts.main')

@section('content')
<div class="space-y-5 py-5 px-10">
    <!-- PAGE TITLE -->
    <div class="space-y-7 flex flex-col">
        <h1 class="text-gray-100 font-[600]">Schedules Management</h1>
        <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-3">
            <div class="flex items-center gap-3">
                {{-- Mengarahkan kembali ke dashboard subjek --}}
                <a href="{{ route('admin.classrooms.show', $schedule->classroom_id) }}"
                    class="bg-white rounded-full w-8 h-8 flex items-center justify-center cursor-pointer">
                    <img src="/admin/assets/icons/arrow-left.svg" alt="Icon" class="size-4">
                </a>
                <h6 class="text-gray-100 font-[600]">Edit Schedule: {{ $schedule->topic }}</h6>
            </div>
        </div>
    </div>

    {{-- FORM START --}}
    <form method="POST" action="{{ route('admin.schedules.update', $schedule->id) }}" enctype="multipart/form-data">
        @csrf
        @method('PATCH')

        <div class="space-y-10">
            <div class="grid grid-cols-12 gap-10 pt-4">
                <div class="col-span-12">
                    <div class="grid grid-cols-12 gap-8">
                        
                        {{-- Topic Name --}}
                        <div class="col-span-12">
                            <label class="block mb-2 text-[15px] font-medium text-gray-200">Topic Name</label>
                            <input type="text" name="topic"
                                class="bg-gray-1000 border border-gray-950 text-gray-75 rounded-[14px] w-full px-4 py-3"
                                placeholder="Enter topic name" required 
                                value="{{ old('topic', $schedule->topic) }}" />
                            @error('topic')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Form (Level) --}}
                        <div class="col-span-12 lg:col-span-6">
                            <label class="block mb-2 text-[15px] font-medium text-gray-200">Form</label>
                            <div class="relative">
                                <select name="form_id"
                                    class="appearance-none pr-10 bg-gray-1000 border border-gray-950 text-gray-75 rounded-[14px] w-full px-4 py-3"
                                    required>
                                    <option value="">Select Level</option>
                                    @foreach ($forms as $form)
                                        <option value="{{ $form->id }}" 
                                            {{ old('form_id', $schedule->form_id) == $form->id ? 'selected' : '' }}>
                                            {{ strtoupper(str_replace('-', ' ', $form->slug)) }}
                                        </option>
                                    @endforeach
                                </select>
                                <div
                                    class="pointer-events-none absolute right-5 top-1/2 transform -translate-y-1/2 text-white">
                                    <img src="/frontend/assets/icons/angle-down.svg" alt="Icon">
                                </div>
                            </div>
                            @error('form_id')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Class Group --}}
                        <div class="col-span-12 lg:col-span-6">
                            <label class="block mb-2 text-[15px] font-medium text-gray-200">Class Group</label>
                            <div class="relative">
                                <select name="classroom_id"
                                    class="appearance-none pr-10 bg-gray-1000 border border-gray-950 text-gray-75 rounded-[14px] w-full px-4 py-3"
                                    required>
                                    <option value="">Select class group</option>
                                    @foreach ($classes as $class)
                                        <option value="{{ $class->id }}" 
                                            {{ old('classroom_id', $schedule->classroom_id) == $class->id ? 'selected' : '' }}>
                                            {{ $class->name }}
                                        </option>
                                    @endforeach
                                </select>
                                <div
                                    class="pointer-events-none absolute right-5 top-1/2 transform -translate-y-1/2 text-white">
                                    <img src="/frontend/assets/icons/angle-down.svg" alt="Icon">
                                </div>
                            </div>
                            @error('classroom_id')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Date & Time --}}
                        <div class="col-span-12 lg:col-span-6">
                            <label class="block mb-2 text-[15px] font-medium text-gray-200">Date & Time</label>
                            <input type="datetime-local" name="time"
                                class="bg-gray-1000 border border-gray-950 text-gray-75 rounded-[14px] w-full px-4 py-3"
                                required 
                                value="{{ old('time', $schedule->time->format('Y-m-d\TH:i')) }}" />
                            @error('time')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Duration (Minutes) --}}
                        <div class="col-span-12 lg:col-span-6">
                            <label class="block mb-2 text-[15px] font-medium text-gray-200">Duration
                                (Minutes)</label>
                            <input type="number" name="duration"
                                class="bg-gray-1000 border border-gray-950 text-gray-75 rounded-[14px] w-full px-4 py-3"
                                placeholder="e.g. 40" required 
                                value="{{ old('duration', $schedule->duration) }}" />
                            @error('duration')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Agenda --}}
                        <div class="col-span-12">
                            <label class="block mb-2 text-[15px] font-medium text-gray-200">Agenda</label>
                            <textarea name="agenda" class="bg-gray-1000 border border-gray-950 text-gray-75 rounded-[14px] w-full px-4 py-3"
                                placeholder="Enter agenda..." required>{{ old('agenda', $schedule->agenda) }}</textarea>
                            @error('agenda')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Zoom Settings --}}
                        <div class="col-span-12">
                            <label class="block text-[15px] font-medium text-gray-200 mb-3">Zoom
                                Settings</label>
                            
                            {{-- Ambil setting dari jadwal atau old input --}}
                            @php 
                                $settings = old('settings', $schedule->settings ?? []); 
                            @endphp
                            
                            {{-- Checkbox Settings --}}
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                                @foreach ([
                                'join_before_host' => 'Join Before Host',
                                'host_video' => 'Host Video',
                                'participant_video' => 'Participant Video',
                                'mute_upon_entry' => 'Mute Upon Entry',
                                'waiting_room' => 'Waiting Room',
                            ] as $key => $label)
                                    <label class="flex items-center space-x-2 text-gray-200">
                                        <input type="checkbox" name="settings[{{ $key }}]"
                                            value="1" 
                                            {{ ($settings[$key] ?? false) ? 'checked' : '' }}
                                            class="form-check-input bg-gray-1000 border-gray-950 text-gray-75 rounded">
                                        <span>{{ $label }}</span>
                                    </label>
                                @endforeach
                            </div>

                            {{-- Dropdown Settings (Audio, Recording, Approval) --}}
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-5 mt-5">
                                {{-- Audio --}}
                                <div>
                                    <label class="block mb-1 text-sm text-gray-200">Audio</label>
                                    <select name="settings[audio]"
                                        class="appearance-none pr-10 bg-gray-1000 border border-gray-950 text-gray-75 rounded-[14px] w-full px-4 py-3">
                                        @foreach (['both' => 'Both', 'telephony' => 'Telephony', 'voip' => 'VoIP'] as $value => $label)
                                            <option value="{{ $value }}" 
                                                {{ ($settings['audio'] ?? 'both') == $value ? 'selected' : '' }}>
                                                {{ $label }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                {{-- Auto Recording --}}
                                <div>
                                    <label class="block mb-1 text-sm text-gray-200">Auto Recording</label>
                                    <select name="settings[auto_recording]"
                                        class="appearance-none pr-10 bg-gray-1000 border border-gray-950 text-gray-75 rounded-[14px] w-full px-4 py-3">
                                        @foreach (['none' => 'None', 'local' => 'Local', 'cloud' => 'Cloud'] as $value => $label)
                                            <option value="{{ $value }}" 
                                                {{ ($settings['auto_recording'] ?? 'none') == $value ? 'selected' : '' }}>
                                                {{ $label }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                {{-- Approval Type --}}
                                <div>
                                    <label class="block mb-1 text-sm text-gray-200">Approval Type</label>
                                    <select name="settings[approval_type]"
                                        class="appearance-none pr-10 bg-gray-1000 border border-gray-950 text-gray-75 rounded-[14px] w-full px-4 py-3">
                                        @foreach ([0 => 'Auto Approve', 1 => 'Manual Approve', 2 => 'No Registration'] as $value => $label)
                                            <option value="{{ $value }}" 
                                                {{ ($settings['approval_type'] ?? 0) == $value ? 'selected' : '' }}>
                                                {{ $label }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="col-span-12">
                            <button type="submit"
                                class="bg-gray-50 hover:bg-gray-200 rounded-full text-sm px-8 py-3 w-full font-semibold text-black">
                                Update Schedule
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
    {{-- FORM END --}}
</div>
@endsection
