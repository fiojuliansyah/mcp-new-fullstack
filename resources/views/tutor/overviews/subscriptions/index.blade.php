@extends('layouts.app')

@section('content')
<section class="w-full bg-primary text-white px-4 py-10">
    <div class="w-full max-w-screen-xl mx-auto pb-10">
        <!-- HEADER -->
        <div class="flex flex-col lg:flex-row justify-between gap-5 lg:items-end border-b border-white/10">
            <div class="flex items-center gap-3">
                <img src="/frontend/assets/images/student-profile-vector.svg" alt="Tutor Avatar" class="w-28" />
                <div>
                    <span class="text-gray-250">Tutor Dashboard</span>
                    <h1 class="text-4xl font-bold tracking-tight text-white">Students Subscription</h1>
                </div>
            </div>
            <div class="flex items-center gap-1 mb-3">
                <span class="text-gray-910 text-[15px] font-medium">Home</span>
                <span class="text-white text-[15px] font-medium">> Students Subscription</span>
            </div>
        </div>
        <div class="col-span-3 flex items-center gap-10 mb-10 pt-10">
            <a href="{{ route('tutor.dashboard.subject', $selectedSubject->slug) }}"
                class="bg-white rounded-full w-8 h-8 flex items-center justify-center cursor-pointer">
                <img src="/frontend/assets/icons/arrow-left.svg" alt="Icon" class="size-4">
            </a>
            <h6 class="text-[20px] text-gray-75 font-semibold">Dashboard</h6>
        </div>
        <div class="col-span-3 lg:col-span-2 pt-10">
            <div class="flex flex-col lg:flex-row items-center gap-8">
                <div class="w-full lg:w-[150px]">
                    <img src="{{ $tutor->avatar_url ?? '/frontend/assets/images/default-avatar.png' }}" alt="Image"
                        class="w-full h-auto lg:h-[130px] rounded-[13px] object-cover" />
                </div>
                <div class="flex flex-col justify-between w-full h0full">
                    <span class="font-bebas pb-3">{{ $selectedSubject->name }}</span>
                </div>
            </div>
        </div>

        <!-- FILTER FORM -->
        <form method="GET" class="pt-10 mb-10 grid grid-cols-1 md:grid-cols-3 lg:grid-cols-4 gap-5">
            <!-- FORM -->
            <div>
                <label class="block mb-2 text-[15px] font-medium text-gray-200">Form</label>
                <div class="relative">
                    <select name="form"
                        class="appearance-none pr-10 bg-gray-1000 border border-gray-950 text-gray-75 rounded-[14px] w-full px-4 py-3">
                        <option value="">All Forms</option>
                        @foreach ($forms as $form)
                            <option value="{{ $form->id }}" {{ request('form') == $form->id ? 'selected' : '' }}>
                                {{ $form->name }}
                            </option>
                        @endforeach
                    </select>
                    <div
                        class="pointer-events-none absolute right-5 top-1/2 transform -translate-y-1/2 text-white">
                        <svg xmlns="http://www.w3.org/2000/svg" class="size-4" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M19 9l-7 7-7-7" />
                        </svg>
                    </div>
                </div>
            </div>

            <!-- CLASSROOM -->
            <div>
                <label class="block mb-2 text-[15px] font-medium text-gray-200">Classroom</label>
                <div class="relative">
                    <select name="classroom_id"
                        class="appearance-none pr-10 bg-gray-1000 border border-gray-950 text-gray-75 rounded-[14px] w-full px-4 py-3">
                        <option value="">All Classrooms</option>
                        @foreach ($classroomFilter as $classroom)
                            <option value="{{ $classroom->id }}" {{ request('classroom_id') == $classroom->id ? 'selected' : '' }}>
                                {{ $classroom->name }}
                            </option>
                        @endforeach
                    </select>
                    <div
                        class="pointer-events-none absolute right-5 top-1/2 transform -translate-y-1/2 text-white">
                        <svg xmlns="http://www.w3.org/2000/svg" class="size-4" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M19 9l-7 7-7-7" />
                        </svg>
                    </div>
                </div>
            </div>

            <!-- SEARCH -->
            <div class="md:col-span-2 lg:col-span-1">
                <label class="block mb-2 text-[15px] font-medium text-gray-200">Search Student</label>
                <div
                    class="flex items-center w-full bg-gray-1000 border border-gray-950 rounded-full px-4 py-3">
                    <svg xmlns="http://www.w3.org/2000/svg" class="size-5 text-gray-400 mr-2" fill="none"
                        viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M21 21l-4.35-4.35m0 0A7.5 7.5 0 1010.5 18.5a7.5 7.5 0 006.15-3.85z" />
                    </svg>
                    <input type="text" name="search" value="{{ request('search') }}"
                        class="flex-1 bg-transparent text-gray-200 placeholder:text-gray-500 focus:outline-none"
                        placeholder="Type student name..." />
                </div>
            </div>

            <!-- FILTER BUTTON -->
            <div class="flex items-end">
                <button type="submit"
                    class="bg-white text-black hover:bg-gray-200 rounded-full px-6 py-3 font-semibold w-full">
                    Apply Filter
                </button>
            </div>
        </form>

        <!-- STUDENT LIST -->
        <div class="overflow-x-auto border border-[#424242] rounded-[12px] mb-10">
            <table class="min-w-full border border-[#424242] text-sm text-left">
                <thead class="bg-gray-800 text-gray-200">
                    <tr>
                        <th class="p-4 border border-[#424242]">No</th>
                        <th class="p-4 border border-[#424242]">Student Name</th>
                        <th class="p-4 border border-[#424242]">Classroom</th>
                        <th class="p-4 border border-[#424242]">Subscription Plan</th>
                        <th class="p-4 border border-[#424242]">Start Date</th>
                        <th class="p-4 border border-[#424242]">End Date</th>
                        <th class="p-4 border border-[#424242]">Status</th>
                        <th class="p-4 border border-[#424242]">Action</th>
                    </tr>
                </thead>
                <tbody class="bg-gray-900 divide-y divide-gray-800 text-gray-50">
                    @forelse ($subscriptions as $index => $sub)
                        @php
                            $today = now();
                            $end = \Carbon\Carbon::parse($sub->end_date);
                            $statusClass = $end->isPast()
                                ? 'text-red-400'
                                : ($end->diffInDays($today) <= 7
                                    ? 'text-yellow-400'
                                    : 'text-green-400');
                            $statusText = $end->isPast()
                                ? 'Expired'
                                : ($end->diffInDays($today) <= 7
                                    ? 'Expiring Soon'
                                    : 'Active');
                        @endphp
                        <tr class="odd:bg-[#141414] even:bg-[#171717]">
                            <td class="p-4 border border-gray-700">{{ $subscriptions->firstItem() + $index }}</td>
                            <td class="p-4 border border-gray-700">{{ $sub->user->name ?? '-' }}</td>
                            <td class="p-4 border border-gray-700">
                                {{ $sub->classrooms->pluck('name')->join(', ') ?: '-' }}
                            </td>
                            <td class="p-4 border border-gray-700">{{ $sub->plan->name ?? '-' }}</td>
                            <td class="p-4 border border-gray-700">{{ \Carbon\Carbon::parse($sub->start_date)->format('d M Y') }}</td>
                            <td class="p-4 border border-gray-700">{{ \Carbon\Carbon::parse($sub->end_date)->format('d M Y') }}</td>
                            <td class="p-4 border border-gray-700"><span class="{{ $statusClass }}">{{ $statusText }}</span></td>
                            <td class="p-4 border border-gray-700">
                                <a href="{{ route('tutor.overview.subscription.show', $sub->user->id) }}"
                                    class="block bg-gray-50 hover:bg-gray-200 rounded-full text-center text-sm px-8 py-3 cursor-pointer text-black font-semibold">
                                    View
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center p-6 text-gray-400">No subscriptions found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="flex items-center justify-center lg:justify-end gap-3 mt-5">
            @if ($subscriptions->onFirstPage())
                <button
                    class="bg-white rounded-full w-10 h-10 flex items-center justify-center cursor-not-allowed opacity-50">
                    <img src="/frontend/assets/icons/angle-left.svg" alt="Prev" class="size-3">
                </button>
            @else
                <a href="{{ $subscriptions->previousPageUrl() }}"
                    class="bg-white rounded-full w-10 h-10 flex items-center justify-center">
                    <img src="/frontend/assets/icons/angle-left.svg" alt="Prev" class="size-3">
                </a>
            @endif

            <div class="bg-gray-1000 border border-gray-950 text-gray-75 rounded-[14px] px-4 py-2 text-sm">
                Page {{ $subscriptions->currentPage() }} of {{ $subscriptions->lastPage() }}
            </div>

            @if ($subscriptions->hasMorePages())
                <a href="{{ $subscriptions->nextPageUrl() }}"
                    class="bg-white rounded-full w-10 h-10 flex items-center justify-center">
                    <img src="/frontend/assets/icons/angle-right.svg" alt="Next" class="size-3">
                </a>
            @else
                <button
                    class="bg-white rounded-full w-10 h-10 flex items-center justify-center cursor-not-allowed opacity-50">
                    <img src="/frontend/assets/icons/angle-right.svg" alt="Next" class="size-3">
                </button>
            @endif
        </div>
    </div>
</section>
@endsection
