@extends('layouts.main')

@section('content')
 <div class="space-y-5 py-5 px-10">
    <div class="space-y-7 flex flex-col">
        <h1 class="text-gray-100 font-[600]">User Management</h1>
        <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-3">
            <div>
                <h6 class="font-[600]">Students Lists</h6>
                <p class="text-gray-910">{{ $count }}  Students</p>
            </div>
            <a href="{{ route('admin.users.student.create') }}"
                class="bg-gray-50 hover:bg-gray-200 rounded-full text-sm px-5 py-3 cursor-pointer">
                <span class="text-black text-[16px] font-semibold">Add New Student</span>
            </a>
        </div>
    </div>

    <!-- PAGE CONTENT -->
    <div class="space-y-10 divide-y divide-gray-200">
        <!-- TABLE SECTION -->
        <div class="space-y-5">
            <!-- TABLE SEARCH -->
            <div class="flex items-center w-full lg:w-[350px] bg-white border border-gray-280 rounded-full px-2 py-2">
                <div class="flex items-center justify-center w-8 h-8 rounded-full bg-black text-white mr-3">
                    <img src="/admin/assets/icons/search.svg" alt="Icon">
                </div>
                <input type="text" class="flex-1 bg-transparent text-gray-700 placeholder:text-[#A6A1A1] focus:outline-none" placeholder="Search" />
            </div>

            <!-- START : TABLE -->
            <div class="overflow-x-auto border border-[#424242] rounded-[12px] mb-10">
                <table
                    class="min-w-full border border-[#424242] rounded-[12px] overflow-hidden text-sm text-left">
                    <thead class="bg-gray-800 text-gray-200">
                        <tr>
                            <th class="p-4 border border-[#424242]">No</th>
                            <th class="p-4 border border-[#424242]">Name</th>
                            <th class="p-4 border border-[#424242]">Subjects Enrolled</th>
                            <th class="p-4 border border-[#424242]">Email</th>
                            <th class="p-4 border border-[#424242]">Password</th>
                            <th class="p-4 border border-[#424242]">Parents</th>
                            <th class="p-4 border border-[#424242]">Status Account</th>
                            <th class="p-4 border border-[#424242]">Action</th>
                        </tr>
                    </thead>
                    <tbody class="bg-gray-900 divide-y divide-gray-800 text-gray-50">
                        @foreach ($users as $index => $user) 
                            <tr class="odd:bg-[#141414] even:bg-[#171717]">
                                <td class="p-4 border border-gray-700">{{ $index+1 }}</td>
                                <td class="p-4 border border-gray-700">
                                   {{ $user->name ?? '-' }}
                                </td>
                                <td class="p-4 border border-gray-700">{{ $user->subscriptions->count() }}</td>
                                <td class="p-4 border border-gray-700">
                                    {{ $user->email ?? '-' }}
                                </td>
                                <td class="p-4 border border-gray-700">********</td>
                                <td class="p-4 border border-gray-700">{{ $user->parent->name ?? '-' }}</td>
                                <td class="p-4 border border-gray-700">
                                    @if ($user->status === 'active')
                                        <span class="text-green-100">Active</span>   
                                        @else
                                        <span class="text-red-100">Suspend</span>   
                                    @endif
                                </td>
                                <td class="p-4 border border-gray-700">
                                    <div class="flex items-center justify-center gap-3">
                                        <a href="{{ route('admin.users.student.show', $user->id) }}" target="_blank" class="flex flex-col items-center">
                                            <div class="block bg-gray-50 hover:bg-gray-200 cursor-pointer w-[35px] h-[35px] rounded-full p-2 flex items-center justify-center">
                                                <img src="/admin/assets/icons/eye.svg" alt="Icon" class="size-5 text-black" />
                                            </div>
                                            <span class="text-[#5F5F5F] text-[10px]">View</span>
                                        </a>
                                        <a href="{{ route('admin.users.student.edit', $user->id) }}" class="flex flex-col items-center">
                                            <div class="block bg-gray-50 hover:bg-gray-200 cursor-pointer w-[35px] h-[35px] rounded-full p-2 flex items-center justify-center">
                                                <img src="/admin/assets/icons/pencil.svg" alt="Icon" class="size-5 text-black" />
                                            </div>
                                            <span class="text-[#5F5F5F] text-[10px]">Edit</span>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="flex items-center justify-center lg:justify-end gap-5">
                <span class="text-gray-200">Page</span>

                @if($users->onFirstPage())
                    <button class="bg-white rounded-full w-10 h-10 flex items-center justify-center cursor-not-allowed opacity-50">
                        <img src="/frontend/assets/icons/angle-left.svg" alt="Prev" class="size-3">
                    </button>
                @else
                    <a href="{{ $users->previousPageUrl() }}" class="bg-white rounded-full w-10 h-10 flex items-center justify-center">
                        <img src="/frontend/assets/icons/angle-left.svg" alt="Prev" class="size-3">
                    </a>
                @endif

                <div class="bg-gray-1000 border border-gray-950 text-gray-75 rounded-[14px] px-8 py-3">
                    {{ $users->currentPage() }}
                </div>

                @if($users->hasMorePages())
                    <a href="{{ $users->nextPageUrl() }}" class="bg-white rounded-full w-10 h-10 flex items-center justify-center">
                        <img src="/frontend/assets/icons/angle-right.svg" alt="Next" class="size-3">
                    </a>
                @else
                    <button class="bg-white rounded-full w-10 h-10 flex items-center justify-center cursor-not-allowed opacity-50">
                        <img src="/frontend/assets/icons/angle-right.svg" alt="Next" class="size-3">
                    </button>
                @endif
            </div>
            <!-- END : TABLE -->
        </div>
    </div>
</div>
@endsection