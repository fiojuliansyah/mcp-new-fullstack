@extends('layouts.main')

@section('content')
<div class="space-y-5 py-5 px-10">
    <div class="space-y-10">
        <form action="{{ route('admin.users.tutors.update', $tutor->id) }}" method="POST" class="space-y-10">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-12 gap-10 pt-4">
                <div class="col-span-12">
                    <label class="block mb-2 text-[15px] font-medium text-gray-200">Tutor Name</label>
                    <input type="text" name="name" placeholder="Enter tutor name"
                        class="appearance-none bg-gray-1000 border border-gray-950 text-gray-75 rounded-[14px] w-full px-4 py-3"
                        value="{{ old('name', $tutor->name) }}" required />
                </div>

                <div class="col-span-12 lg:col-span-6">
                    <label class="block mb-2 text-[15px] font-medium text-gray-200">Email</label>
                    <input type="email" name="email" placeholder="Enter email"
                        class="appearance-none bg-gray-1000 border border-gray-950 text-gray-75 rounded-[14px] w-full px-4 py-3"
                        value="{{ old('email', $tutor->email) }}" required />
                </div>

                <div class="col-span-12 lg:col-span-6">
                    <label class="block mb-2 text-[15px] font-medium text-gray-200">Password (Leave blank if unchanged)</label>
                    <input type="password" name="password" placeholder="Enter new password (optional)"
                        class="appearance-none bg-gray-1000 border border-gray-950 text-gray-75 rounded-[14px] w-full px-4 py-3" />
                </div>

                <div class="col-span-12 lg:col-span-6">
                    <label class="block mb-2 text-[15px] font-medium text-gray-200">Gender</label>
                    <div class="relative">
                        <select name="gender"
                            class="appearance-none pr-10 bg-gray-1000 border border-gray-950 text-gray-75 rounded-[14px] w-full px-4 py-3">
                            <option value="">Select Gender</option>
                            <option value="male" {{ old('gender', $tutor->gender) === 'male' ? 'selected' : '' }}>Male</option>
                            <option value="female" {{ old('gender', $tutor->gender) === 'female' ? 'selected' : '' }}>Female</option>
                        </select>
                        <div class="pointer-events-none absolute right-5 top-1/2 transform -translate-y-1/2 text-white">
                            <img src="/frontend/assets/icons/angle-down.svg" alt="Icon">
                        </div>
                    </div>
                </div>

                <div class="col-span-12">
                    <button type="submit"
                        class="bg-gray-50 hover:bg-gray-200 rounded-full text-sm px-8 py-3 w-full">
                        <span class="text-black text-[16px] font-semibold">Update Tutor</span>
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection
