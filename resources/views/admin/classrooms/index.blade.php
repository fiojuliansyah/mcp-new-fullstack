@extends('layouts.main')

@section('content')
 <div class="space-y-5 py-5 px-10">
    <!-- PAGE TITLE -->
    <div class="space-y-7 flex flex-col">
        <h1 class="text-gray-100 font-[600]">Classes Management</h1>
        <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-3">
            <div>
                <h6 class="font-[600]">Classes List</h6>
            </div>
            <a href="{{ route('admin.classrooms.create') }}"
                class="bg-gray-50 hover:bg-gray-200 rounded-full text-sm px-5 py-3 cursor-pointer">
                <span class="text-black text-[16px] font-semibold">Add New Class</span>
            </a>
        </div>
    </div>

    <!-- PAGE CONTENT -->
    <div class="space-y-10 divide-y divide-gray-200">
        <!-- TABLE SECTION -->
        <div class="space-y-5">

            <!-- TABLE SEARCH -->
            <form method="GET" action="{{ route('admin.classrooms.index') }}" class="flex items-center w-full lg:w-[350px] bg-white border border-gray-280 rounded-full px-2 py-2">
                <div class="flex items-center justify-center w-8 h-8 rounded-full bg-black text-white mr-3">
                    <img src="/admin/assets/icons/search.svg" alt="Icon">
                </div>
                <input 
                    type="text" 
                    name="search" 
                    value="{{ request('search') }}" 
                    class="flex-1 bg-transparent text-gray-700 placeholder:text-[#A6A1A1] focus:outline-none" 
                    placeholder="Search"
                />
                @if(request('search'))
                    <a href="{{ route('admin.classrooms.index') }}" class="text-gray-500 text-sm pr-3">✕</a>
                @endif
            </form>


            <!-- START : TABLE -->
            <div class="overflow-x-auto border border-[#424242] rounded-[12px] mb-10">
                <table
                    class="min-w-full border border-[#424242] rounded-[12px] overflow-hidden text-sm text-left">
                    <thead class="bg-gray-800 text-gray-200">
                        <tr>
                            <th class="p-4 border border-[#424242]">No</th>
                            <th class="p-4 border border-[#424242]">Subject</th>
                            <th class="p-4 border border-[#424242]">Tutors</th>
                            <th class="p-4 border border-[#424242]">Total Classes</th>
                            <th class="p-4 border border-[#424242]">Status</th>
                            <th class="p-4 border border-[#424242]">Action</th>
                        </tr>
                    </thead>
                    <tbody class="bg-gray-900 divide-y divide-gray-800 text-gray-50">
                        @foreach ($classrooms as $index => $classroom) 
                            <tr class="odd:bg-[#141414] even:bg-[#171717]">
                                <td class="p-4 border border-gray-700">{{ $classrooms->firstItem() + $index }}</td>
                                <td class="p-4 border border-gray-700">{{ $classroom->subject->name ?? '-' }}</td>
                                <td class="p-4 border border-gray-700">{{ $classroom->user->name ?? '-' }}</td>
                                <td class="p-4 border border-gray-700">{{ $classroom->schedules->count() ?? '-' }}</td>
                                <td class="p-4 border border-gray-700">
                                    @if ($classroom->status === 'active')
                                        <span class="text-green-100">Active</span>   
                                    @else
                                        <span class="text-red-100">Deactive</span>   
                                    @endif
                                </td>
                                <td class="p-4 border border-gray-700">
                                    <div class="flex items-center justify-center gap-3">
                                        <a href="{{ route('admin.classrooms.show', $classroom->id) }}" class="flex flex-col items-center">
                                            <div class="block bg-gray-50 hover:bg-gray-200 cursor-pointer w-[35px] h-[35px] rounded-full p-2 flex items-center justify-center">
                                                <img src="/admin/assets/icons/eye.svg" alt="Icon" class="size-5 text-black" />
                                            </div>
                                            <span class="text-[#5F5F5F] text-[10px]">Show</span>
                                        </a>
                                        <a href="{{ route('admin.classrooms.edit', $classroom->id) }}" class="flex flex-col items-center">
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

            <!-- PAGINATION -->
            <div class="flex items-center justify-center lg:justify-end gap-5">
                <span class="text-gray-200">Page</span>

                @if($classrooms->onFirstPage())
                    <button class="bg-white rounded-full w-10 h-10 flex items-center justify-center cursor-not-allowed opacity-50">
                        <img src="/frontend/assets/icons/angle-left.svg" alt="Prev" class="size-3">
                    </button>
                @else
                    <a href="{{ $classrooms->previousPageUrl() }}" class="bg-white rounded-full w-10 h-10 flex items-center justify-center">
                        <img src="/frontend/assets/icons/angle-left.svg" alt="Prev" class="size-3">
                    </a>
                @endif

                <div class="bg-gray-1000 border border-gray-950 text-gray-75 rounded-[14px] px-8 py-3">
                    {{ $classrooms->currentPage() }}
                </div>

                @if($classrooms->hasMorePages())
                    <a href="{{ $classrooms->nextPageUrl() }}" class="bg-white rounded-full w-10 h-10 flex items-center justify-center">
                        <img src="/frontend/assets/icons/angle-right.svg" alt="Next" class="size-3">
                    </a>
                @else
                    <button class="bg-white rounded-full w-10 h-10 flex items-center justify-center cursor-not-allowed opacity-50">
                        <img src="/frontend/assets/icons/angle-right.svg" alt="Next" class="size-3">
                    </button>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
