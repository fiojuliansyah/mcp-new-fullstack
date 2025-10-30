@extends('layouts.main')

@section('content')
<div class="space-y-5 py-5 px-10">
    <!-- PAGE TITLE -->
    <div class="space-y-7 flex flex-col">
        <h1 class="text-gray-100 font-[600]">Classrooms Management</h1>
        <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-3">
            <div class="flex items-center gap-3">
                <a href="{{ route('admin.classrooms.index') }}"
                    class="bg-white rounded-full w-8 h-8 flex items-center justify-center cursor-pointer">
                    <img src="/admin/assets/icons/arrow-left.svg" alt="Icon" class="size-4">
                </a>
                <h6 class="text-gray-100 font-[600]">Add New Classroom</h6>
            </div>
        </div>
    </div>

    <div class="space-y-10">
        <form action="{{ route('admin.classrooms.store') }}" method="POST" class="space-y-10">
            @csrf

            <div class="grid grid-cols-12 gap-10 pt-4">
                <!-- CLASS NAME -->
                <div class="col-span-12">
                    <label class="block mb-2 text-[15px] font-medium text-gray-200">Classroom Name</label>
                    <input type="text" name="name" placeholder="Enter classroom name"
                        class="appearance-none bg-gray-1000 border border-gray-950 text-gray-75 rounded-[14px] w-full px-4 py-3"
                        required />
                </div>

                <!-- SUBJECT -->
                <div class="col-span-12 lg:col-span-6">
                    <label class="block mb-2 text-[15px] font-medium text-gray-200">Subject</label>
                    <div class="relative">
                        <select name="subject_id"
                            class="appearance-none pr-10 bg-gray-1000 border border-gray-950 text-gray-75 rounded-[14px] w-full px-4 py-3" required>
                            <option value="">Select Subject</option>
                            @foreach($subjects as $subject)
                                <option value="{{ $subject->id }}">{{ $subject->name }}</option>
                            @endforeach
                        </select>
                        <div class="pointer-events-none absolute right-5 top-1/2 transform -translate-y-1/2 text-white">
                            <img src="/frontend/assets/icons/angle-down.svg" alt="Icon">
                        </div>
                    </div>
                </div>

                <!-- TEACHER -->
                <div class="col-span-12 lg:col-span-6">
                    <label class="block mb-2 text-[15px] font-medium text-gray-200">Teacher</label>
                    <div class="relative">
                        <select name="user_id"
                            class="appearance-none pr-10 bg-gray-1000 border border-gray-950 text-gray-75 rounded-[14px] w-full px-4 py-3" required>
                            <option value="">Select Teacher</option>
                            @foreach($tutors as $tutor)
                                <option value="{{ $tutor->id }}">{{ $tutor->name }}</option>
                            @endforeach
                        </select>
                        <div class="pointer-events-none absolute right-5 top-1/2 transform -translate-y-1/2 text-white">
                            <img src="/frontend/assets/icons/angle-down.svg" alt="Icon">
                        </div>
                    </div>
                </div>

                <!-- DATE -->
                <div class="col-span-12 lg:col-span-6">
                    <label class="block mb-2 text-[15px] font-medium text-gray-200">Date</label>
                    <input type="date" name="date"
                        class="appearance-none bg-gray-1000 border border-gray-950 text-gray-75 rounded-[14px] w-full px-4 py-3" />
                </div>

                <!-- TIME -->
                <div class="col-span-12 lg:col-span-6">
                    <label class="block mb-2 text-[15px] font-medium text-gray-200">Time</label>
                    <input type="time" name="time"
                        class="appearance-none bg-gray-1000 border border-gray-950 text-gray-75 rounded-[14px] w-full px-4 py-3" />
                </div>

                <!-- STATUS -->
                <div class="col-span-12 lg:col-span-6">
                    <label class="block mb-2 text-[15px] font-medium text-gray-200">Status</label>
                    <div class="relative">
                        <select name="status"
                            class="appearance-none pr-10 bg-gray-1000 border border-gray-950 text-gray-75 rounded-[14px] w-full px-4 py-3">
                            <option value="active" selected>Active</option>
                            <option value="deactive">Deactive</option>
                        </select>
                        <div class="pointer-events-none absolute right-5 top-1/2 transform -translate-y-1/2 text-white">
                            <img src="/frontend/assets/icons/angle-down.svg" alt="Icon">
                        </div>
                    </div>
                </div>

                <!-- SUBMIT -->
                <div class="col-span-12">
                    <button type="submit"
                        class="bg-gray-50 hover:bg-gray-200 rounded-full text-sm px-8 py-3 w-full">
                        <span class="text-black text-[16px] font-semibold">Create Classroom</span>
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection
