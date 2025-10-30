@extends('layouts.main')

@section('content')
<div class="space-y-5 py-5 px-10">
    <div class="space-y-7 flex flex-col">
        <h1 class="text-gray-100 font-[600]">User Management</h1>
        <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-3">
            <div class="flex items-center gap-3">
                <a href="{{ route('admin.users.student') }}" class="bg-white rounded-full w-8 h-8 flex items-center justify-center cursor-pointer">
                    <img src="/admin/assets/icons/arrow-left.svg" alt="Icon" class="size-4">
                </a>
                <h6 class="text-gray-100 font-[600]">View Student Details</h6>
            </div>
        </div>
    </div>

    <div class="space-y-10">
        <div class="rounded-[21px] border border-[#2A2A2A] h-full">
            <div class="rounded-t-[21px] bg-[#2C2C2C] py-3 px-8">
                <span>Student Details</span>
            </div>
            <div class="grid grid-cols-7 gap-5 p-5">
                <div class="col-span-7 lg:col-span-1 flex items-center justify-center">
                    @if ($user->avatar_url)
                        <img src="{{ $user->avatar_url }}" alt="Icon" class="rounded-full w-[109px] h-[109px] flex items-center justify-center">
                    @else
                        <div class="bg-gray-50 rounded-full w-[109px] h-[109px] flex items-center justify-center">
                            <img src="/admin/assets/icons/user-black.svg" alt="Icon" class="w-10 text-black">
                        </div>
                    @endif
                </div>
                <div class="col-span-7 lg:col-span-6">
                    <div class="grid grid-cols-2 gap-5">
                        <div class="col-span-2 flex flex-col">
                            <span class="text-gray-200">Student Name</span>
                            <span class="text-[#EAEAEA]">{{ $user->name ?? '-' }}</span>
                        </div>
                        <div class="col-span-2 lg:col-span-1 flex flex-col">
                            <span class="text-gray-200">Email</span>
                            <span class="text-[#EAEAEA]">{{ $user->email ?? '-' }}</span>
                        </div>
                        <div class="col-span-2 lg:col-span-1 flex flex-col">
                            <span class="text-gray-200">Status Account</span>
                            <span class="text-green-100">Active</span>
                        </div>
                    </div>
                </div>
            </div>

            @if ($user->parent)
            <div class="rounded-xl bg-[#1C1C1C] py-3 px-8 max-w-8xl mx-auto">
                <span>Parent Details</span>
            </div>
            <div class="grid grid-cols-7 gap-5 p-5">
                <div class="col-span-7 lg:col-span-1 flex items-center justify-center">
                    @if ($user->parent->avatar_url)
                        <img src="{{ $user->parent->avatar_url }}" alt="Icon" class="rounded-full w-[109px] h-[109px] flex items-center justify-center">
                    @else
                        <div class="bg-gray-50 rounded-full w-[109px] h-[109px] flex items-center justify-center">
                            <img src="/admin/assets/icons/user-black.svg" alt="Icon" class="w-10 text-black">
                        </div>
                    @endif
                </div>
                <div class="col-span-7 lg:col-span-6">
                    <div class="grid grid-cols-2 gap-5">
                        <div class="col-span-2 flex flex-col">
                            <span class="text-gray-200">Parent Name</span>
                            <span class="text-[#EAEAEA]">{{ $user->parent->name ?? '-' }}</span>
                        </div>
                        <div class="col-span-2 lg:col-span-1 flex flex-col">
                            <span class="text-gray-200">Email</span>
                            <span class="text-[#EAEAEA]">{{ $user->parent->email ?? '-' }}</span>
                        </div>
                        <div class="col-span-2 lg:col-span-1 flex flex-col">
                            <span class="text-gray-200">Status Account</span>
                            <span class="text-green-100">Active</span>
                        </div>
                    </div>
                </div>
            </div>
            @endif
        </div>

        <div class="space-y-5">
            <div class="flex items-center justify-between gap-3">
                <div>
                    <h6 class="font-[600]">Subject Enrolled</h6>
                    <p class="text-gray-910">{{ $user->subscriptions->flatMap->classrooms->count() }} Subjects</p>
                </div>
            </div>

            <div class="overflow-x-auto border border-[#424242] rounded-[12px] mb-10">
                <table class="min-w-full border border-[#424242] rounded-[12px] overflow-hidden text-sm text-left">
                    <thead class="bg-gray-800 text-gray-200">
                        <tr>
                            <th class="p-4 border border-[#424242]">No</th>
                            <th class="p-4 border border-[#424242]">Subject</th>
                            <th class="p-4 border border-[#424242]">Class Group</th>
                            <th class="p-4 border border-[#424242]">Tutor Name</th>
                            <th class="p-4 border border-[#424242]">Current Plan</th>
                            <th class="p-4 border border-[#424242]">Start Date & End Date</th>
                            <th class="p-4 border border-[#424242]">Payment</th>
                            <th class="p-4 border border-[#424242]">Participan Logs</th>
                            <th class="p-4 border border-[#424242]">Performance</th>
                            <th class="p-4 border border-[#424242]">Status</th>
                            <th class="p-4 border border-[#424242]">Action</th>
                        </tr>
                    </thead>
                    <tbody class="bg-gray-900 divide-y divide-gray-800 text-gray-50">
                        @php $no = 1; @endphp
                        @foreach ($user->subscriptions as $subscription)
                            @foreach ($subscription->classrooms as $classroom)
                                <tr class="odd:bg-[#141414] even:bg-[#171717]">
                                    <td class="p-4 border border-gray-700">{{ $no++ }}</td>
                                    <td class="p-4 border border-gray-700">{{ $classroom->subject->name ?? '-' }}</td>
                                    <td class="p-4 border border-gray-700">{{ $classroom->name ?? '-' }}</td>
                                    <td class="p-4 border border-gray-700">{{ $classroom->user->name ?? '-' }}</td>
                                    <td class="p-4 border border-gray-700">{{ $subscription->plan->name ?? '-' }}</td>
                                    <td class="p-4 border border-gray-700">{{ $subscription->start_date->format('d M Y') ?? '-' }} - {{ $subscription->end_date->format('d M Y') ?? '-' }}</td>
                                    <td class="p-4 border border-gray-700">
                                        <span class="text-green-100">
                                            {{ strtoupper($subscription->payment_status ?? '-') }}
                                        </span>
                                        <br>
                                        {{ strtoupper($subscription->payment_method ?? '-') }}
                                    </td>
                                    <td class="p-4 border border-gray-700"></td>
                                    <td class="p-4 border border-gray-700"></td>
                                    <td class="p-4 border border-gray-700">
                                        <span class="capitalize">{{ $subscription->status }}</span>
                                    </td>
                                    <td>
                                        <div class="flex items-center justify-center gap-3">
                                            <a href="#" target="_blank" class="flex flex-col items-center">
                                                <div class="block bg-gray-50 hover:bg-gray-200 cursor-pointer w-[35px] h-[35px] rounded-full p-2 flex items-center justify-center">
                                                    <img src="/admin/assets/icons/eye.svg" alt="Icon" class="size-5 text-black" />
                                                </div>
                                                <span class="text-[#5F5F5F] text-[10px]">View</span>
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        @endforeach

                        @if ($no === 1)
                            <tr>
                                <td colspan="9" class="text-center py-6 text-gray-500">No subjects enrolled</td>
                            </tr>
                        @endif
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
