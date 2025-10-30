@extends('layouts.main')

@section('content')
<div class="space-y-5 py-5 px-10">
    <!-- PAGE TITLE -->
    <div class="space-y-7 flex flex-col">
        <h1 class="text-gray-100 font-[600]">User Management</h1>
        <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-3">
            <div class="flex items-center gap-3">
                <a href="{{ route('admin.users.student') }}" class="bg-white rounded-full w-8 h-8 flex items-center justify-center cursor-pointer">
                    <img src="/admin/assets/icons/arrow-left.svg" alt="Icon" class="size-4">
                </a>
                <h6 class="text-gray-100 font-[600]">Edit Student</h6>
            </div>
        </div>
    </div>

    <div class="space-y-10">
        <form action="{{ route('admin.users.student.update', $user->id) }}" method="POST" class="space-y-10">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-12 gap-10 pt-4">
                <div class="col-span-12 lg:col-span-12">
                    <label class="block mb-2 text-[15px] font-medium text-gray-200">Student Name</label>
                    <input type="text" name="name" placeholder="Enter student name"
                        value="{{ old('name', $user->name) }}"
                        class="appearance-none bg-gray-1000 border border-gray-950 text-gray-75 rounded-[14px] w-full px-4 py-3"
                        required />
                </div>

                <div class="col-span-12 lg:col-span-6">
                    <label class="block mb-2 text-[15px] font-medium text-gray-200">Email</label>
                    <input type="email" name="email" placeholder="Enter email"
                        value="{{ old('email', $user->email) }}"
                        class="appearance-none bg-gray-1000 border border-gray-950 text-gray-75 rounded-[14px] w-full px-4 py-3"
                        required />
                </div>

                <div class="col-span-12 lg:col-span-6">
                    <label class="block mb-2 text-[15px] font-medium text-gray-200">Password <span class="text-gray-400 text-sm">(Leave blank to keep current)</span></label>
                    <input type="password" name="password" placeholder="Enter new password (optional)"
                        class="appearance-none bg-gray-1000 border border-gray-950 text-gray-75 rounded-[14px] w-full px-4 py-3" />
                </div>

                <div class="col-span-12 lg:col-span-6">
                    <label class="block mb-2 text-[15px] font-medium text-gray-200">Gender</label>
                    <div class="relative">
                        <select name="gender"
                            class="appearance-none pr-10 bg-gray-1000 border border-gray-950 text-gray-75 rounded-[14px] w-full px-4 py-3">
                            <option value="">Select Gender</option>
                            <option value="male" {{ old('gender', $user->gender) == 'male' ? 'selected' : '' }}>Male</option>
                            <option value="female" {{ old('gender', $user->gender) == 'female' ? 'selected' : '' }}>Female</option>
                        </select>
                        <div class="pointer-events-none absolute right-5 top-1/2 transform -translate-y-1/2 text-white">
                            <img src="/frontend/assets/icons/angle-down.svg" alt="Icon">
                        </div>
                    </div>
                </div>

                <div class="col-span-12 lg:col-span-6">
                    <label class="block mb-2 text-[15px] font-medium text-gray-200">Level</label>
                    <div class="relative">
                        <select name="level"
                            class="appearance-none pr-10 bg-gray-1000 border border-gray-950 text-gray-75 rounded-[14px] w-full px-4 py-3">
                            <option value="">Select Level</option>
                            @foreach ($forms as $form)
                                <option value="{{ $form->id }}" {{ old('level', $user->form_id) == $form->id ? 'selected' : '' }}>
                                    {{ $form->name }}
                                </option>
                            @endforeach
                        </select>
                        <div class="pointer-events-none absolute right-5 top-1/2 transform -translate-y-1/2 text-white">
                            <img src="/frontend/assets/icons/angle-down.svg" alt="Icon">
                        </div>
                    </div>
                </div>

                <div class="col-span-12 lg:col-span-12">
                    <label class="block mb-2 text-[15px] font-medium text-gray-200">Assign Parent</label>
                    <div class="relative">
                        <select name="parent_id"
                            class="appearance-none pr-10 bg-gray-1000 border border-gray-950 text-gray-75 rounded-[14px] w-full px-4 py-3">
                            <option value="">Select Parent</option>
                            @foreach ($parents as $parent)
                                <option value="{{ $parent->id }}" {{ old('parent_id', $user->parent_id) == $parent->id ? 'selected' : '' }}>
                                    {{ $parent->name }}
                                </option>
                            @endforeach
                        </select>
                        <div class="pointer-events-none absolute right-5 top-1/2 transform -translate-y-1/2 text-white">
                            <img src="/frontend/assets/icons/angle-down.svg" alt="Icon">
                        </div>
                    </div>
                </div>

                <div class="col-span-12">
                    <button type="submit"
                        class="bg-gray-50 hover:bg-gray-200 rounded-full text-sm px-8 py-3 w-full">
                        <span class="text-black text-[16px] font-semibold">Update Student</span>
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection
