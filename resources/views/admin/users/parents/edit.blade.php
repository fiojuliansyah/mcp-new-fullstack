@extends('layouts.main')

@section('content')
<div class="space-y-5 py-5 px-10">
    <div class="space-y-7 flex flex-col">
        <h1 class="text-gray-100 font-[600]">User Management</h1>
        <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-3">
            <div class="flex items-center gap-3">
                <a href="{{ route('admin.users.parents.index') }}" class="bg-white rounded-full w-8 h-8 flex items-center justify-center cursor-pointer">
                    <img src="/admin/assets/icons/arrow-left.svg" alt="Icon" class="size-4">
                </a>
                <h6 class="text-gray-100 font-[600]">Edit Parent: {{ $parent->name }}</h6>
            </div>
        </div>
    </div>
    
    <div class="space-y-10">
        {{-- Ganti rute ke update dan tambahkan method PUT --}}
        <form action="{{ route('admin.users.parents.update', $parent->id) }}" method="POST" class="space-y-10">
            @csrf
            @method('PUT')
            
            <div class="grid grid-cols-12 gap-10 pt-4">
                {{-- 1. Parent Name --}}
                <div class="col-span-12 lg:col-span-12">
                    <label class="block mb-2 text-[15px] font-medium text-gray-200">Parent Name</label>
                    <input type="text" name="name" placeholder="Enter parent name"
                        class="appearance-none bg-gray-1000 border border-gray-950 text-gray-75 rounded-[14px] w-full px-4 py-3"
                        value="{{ old('name', $parent->name) }}"
                        required />
                </div>

                {{-- 2. Email --}}
                <div class="col-span-12 lg:col-span-6">
                    <label class="block mb-2 text-[15px] font-medium text-gray-200">Email</label>
                    <input type="email" name="email" placeholder="Enter email"
                        class="appearance-none bg-gray-1000 border border-gray-950 text-gray-75 rounded-[14px] w-full px-4 py-3"
                        value="{{ old('email', $parent->email) }}"
                        required />
                </div>

                {{-- 3. Password (Optional) --}}
                <div class="col-span-12 lg:col-span-6">
                    <label class="block mb-2 text-[15px] font-medium text-gray-200">Password (Kosongkan jika tidak diubah)</label>
                    <input type="password" name="password" placeholder="Enter new password"
                        class="appearance-none bg-gray-1000 border border-gray-950 text-gray-75 rounded-[14px] w-full px-4 py-3"
                        />
                </div>

                {{-- 4. Gender --}}
                <div class="col-span-12 lg:col-span-6">
                    <label class="block mb-2 text-[15px] font-medium text-gray-200">Gender</label>
                    <div class="relative">
                        <select name="gender"
                            class="appearance-none pr-10 bg-gray-1000 border border-gray-950 text-gray-75 rounded-[14px] w-full px-4 py-3">
                            <option value="">Select Gender</option>
                            <option value="male" {{ old('gender', $parent->gender) == 'male' ? 'selected' : '' }}>Male</option>
                            <option value="female" {{ old('gender', $parent->gender) == 'female' ? 'selected' : '' }}>Female</option>
                        </select>
                        <div class="pointer-events-none absolute right-5 top-1/2 transform -translate-y-1/2 text-white">
                            <img src="/frontend/assets/icons/angle-down.svg" alt="Icon">
                        </div>
                    </div>
                </div>

                {{-- 5. Link Children (Optional) --}}
                <div class="col-span-12 lg:col-span-6">
                    <label class="block mb-2 text-[15px] font-medium text-gray-200">Link Children (Optional)</label>
                    <div class="relative">
                        <select name="children_ids[]" multiple
                            class="appearance-none pr-10 bg-gray-1000 border border-gray-950 text-gray-75 rounded-[14px] w-full px-4 py-3 h-32">
                            
                            @php
                                $linkedChildrenIds = $parent->children->pluck('id')->toArray();
                            @endphp
                            
                            @foreach ($availableChildren as $child)
                                <option value="{{ $child->id }}"
                                    {{ in_array($child->id, old('children_ids', $linkedChildrenIds)) ? 'selected' : '' }}>
                                    {{ $child->name }} ({{ $child->email }})
                                </option>
                            @endforeach
                        </select>
                        <p class="text-xs text-gray-500 mt-2">Gunakan Ctrl/Cmd + klik untuk memilih lebih dari satu anak.</p>
                    </div>
                </div>

                <div class="col-span-12">
                    <button type="submit"
                        class="bg-gray-50 hover:bg-gray-200 rounded-full text-sm px-8 py-3 w-full">
                        <span class="text-black text-[16px] font-semibold">Update Parent</span>
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection