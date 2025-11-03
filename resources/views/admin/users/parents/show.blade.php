@extends('layouts.main')

@section('content')
    <div class="space-y-5 py-5 px-10">
        <div class="space-y-7 flex flex-col">
            <h1 class="text-gray-100 font-[600]">User Management</h1>
            <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-3">
                <div class="flex items-center gap-3">
                    <a href="{{ route('admin.users.parents.index') }}"
                        class="bg-white rounded-full w-8 h-8 flex items-center justify-center cursor-pointer">
                        <img src="/admin/assets/icons/arrow-left.svg" alt="Icon" class="size-4">
                    </a>
                    <h6 class="text-gray-100 font-[600]">View Parent Details</h6>
                </div>
            </div>
        </div>

        <div class="space-y-10">
            <div class="rounded-[21px] border border-[#2A2A2A] h-full">
                <div class="rounded-t-[21px] bg-[#2C2C2C] py-3 px-8">
                    <span>Parent Details</span>
                </div>
                <div class="grid grid-cols-7 gap-5 p-5">
                    <div class="col-span-7 lg:col-span-1 flex items-center justify-center">
                        @if ($parent->avatar_url)
                            <img src="{{ $parent->avatar_url }}" alt="Avatar"
                                class="rounded-full w-[109px] h-[109px] flex items-center justify-center">
                        @else
                            <div class="bg-gray-50 rounded-full w-[109px] h-[109px] flex items-center justify-center">
                                <img src="/admin/assets/icons/student-black.svg" alt="Icon" class="w-10 text-black">
                            </div>
                        @endif
                    </div>
                    <div class="col-span-7 lg:col-span-6">
                        <div class="grid grid-cols-2 gap-5">
                            <div class="col-span-2 flex flex-col">
                                <span class="text-gray-200">Parent Name</span>
                                <span class="text-[#EAEAEA]">{{ $parent->name ?? '-' }}</span>
                            </div>
                            <div class="col-span-2 lg:col-span-1 flex flex-col">
                                <span class="text-gray-200">Email</span>
                                <span class="text-[#EAEAEA]">{{ $parent->email ?? '-' }}</span>
                            </div>
                            <div class="col-span-2 lg:col-span-1 flex flex-col">
                                <span class="text-gray-200">Status Account</span>
                                {{-- Ganti dengan status akun actual dari Parent --}}
                                <span class="{{ $parent->status === 'active' ? 'text-green-100' : 'text-red-100' }} capitalize">{{ $parent->status ?? 'pending' }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="space-y-5">
                <div class="flex items-center justify-between gap-3">
                    <div>
                        <h6 class="font-[600]">Children List</h6>
                        <p class="text-gray-910">{{ $parent->children->count() }} Children</p>
                    </div>
                </div>

                <div class="overflow-x-auto border border-[#424242] rounded-[12px] mb-10">
                    <table class="min-w-full border border-[#424242] rounded-[12px] overflow-hidden text-sm text-left">
                        <thead class="bg-gray-800 text-gray-200">
                            <tr>
                                <th class="p-4 border border-[#424242]">No</th>
                                <th class="p-4 border border-[#424242]">Children Name</th>
                                <th class="p-4 border border-[#424242]">Enrolled Subjects</th>
                                <th class="p-4 border border-[#424242]">Subscription Status</th>
                                <th class="p-4 border border-[#424242]">Subscription Plan</th>
                                <th class="p-4 border border-[#424242]">Action</th>
                            </tr>
                        </thead>
                        <tbody class="bg-gray-900 divide-y divide-gray-800 text-gray-50">
                            
                            @php $no = 1; $hasChildren = $parent->children->isNotEmpty(); @endphp

                            @forelse ($parent->children as $child)
                                @php
                                    $enrolledSubjects = $child->subscriptions->flatMap->classrooms->map(fn($c) => $c->subject->name ?? 'N/A')->unique()->implode(', ');
                                    
                                    $latestSubscription = $child->subscriptions->sortByDesc('created_at')->first();
                                @endphp
                                
                                <tr class="odd:bg-[#141414] even:bg-[#171717]">
                                    <td class="p-4 border border-gray-700">{{ $no++ }}</td>
                                    <td class="p-4 border border-gray-700">
                                        <span class="font-semibold">{{ $child->name ?? '-' }}</span>
                                        <br>
                                        <span class="text-gray-400 text-xs">{{ $child->email ?? '-' }}</span>
                                    </td>
                                    <td class="p-4 border border-gray-700">
                                        {{ $enrolledSubjects }}
                                    </td>
                                    <td class="p-4 border border-gray-700">
                                        @if ($latestSubscription)
                                            <span class="text-xs font-medium uppercase {{ $latestSubscription->status === 'active' ? 'text-green-400' : 'text-red-400' }}">
                                                {{ $latestSubscription->status ?? 'N/A' }}
                                            </span>
                                        @else
                                            <span class="text-gray-400 text-xs">No Subscription</span>
                                        @endif
                                    </td>
                                    <td class="p-4 border border-gray-700">
                                        {{ $latestSubscription->plan->name ?? '-' }}
                                    </td>
                                    <td class="p-4 border border-gray-700">
                                        <div class="flex items-center justify-center gap-3">
                                            <a href="{{ route('admin.users.student.show', $child->id) }}" class="flex flex-col items-center">
                                                <div
                                                    class="block bg-gray-50 hover:bg-gray-200 cursor-pointer w-[35px] h-[35px] rounded-full p-2 flex items-center justify-center">
                                                    <img src="/admin/assets/icons/eye.svg" alt="Icon"
                                                        class="size-5 text-black" />
                                                </div>
                                                <span class="text-[#5F5F5F] text-[10px]">View</span>
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="text-center py-6 text-gray-500">This parent has no children linked.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection