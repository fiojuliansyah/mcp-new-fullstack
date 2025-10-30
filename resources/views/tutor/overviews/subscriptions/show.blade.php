@extends('layouts.app')

@section('content')
<section class="w-full bg-primary text-white px-4 py-10">
        <div class="w-full max-w-screen-xl mx-auto pb-10">
            <!-- HEADER -->
            <div class="flex flex-col lg:flex-row justify-between gap-5 lg:items-end border-b border-white/10">
                <!-- LEFT SECTION -->
                <div class="flex items-center gap-3">
                    <img src="/frontend/assets/images/student-profile-vector.svg" alt="Tutor Avatar" class="w-28" />
                    <div>
                        <span class="text-gray-250">Tutor Dashboard</span>
                        <h1 class="text-4xl font-bold tracking-tight text-white">Welcome Back!</h1>
                    </div>
                </div>
                <!-- RIGHT SECTION - BREADCRUMB -->
                <div class="flex items-center gap-1 mb-3">
                    <span class="text-gray-910 text-[15px] font-medium">Home > Students Performance</span>
                    <span class="text-white text-[15px font-medium">> View Student Subscription</span>
                </div>
            </div>

            <!-- CONTENT -->
            <div class="space-y-10">
                <!-- ALL CLASSES -->
                <div class="pt-10 grid grid-cols-3">
                    <!-- BACK -->
                    <div class="col-span-3 flex items-center gap-10 mb-10">
                        <a href="{{ route('tutor.overview.subscription.index', $latestSubscription->schedule->classroom->subject->slug) }}"
                            class="bg-white rounded-full w-8 h-8 flex items-center justify-center cursor-pointer">
                            <img src="/frontend/assets/icons/arrow-left.svg" alt="Icon" class="size-4">
                        </a>
                        <h6 class="text-[20px] text-gray-75 font-semibold">View Student Subscription</h6>
                    </div>

                    <!-- ALL CLASSES CONTENT -->
                    <div class="col-span-3 lg:col-span-2">
                        <div
                            class="border border-gray-700 rounded-[21px] p-5 flex flex-col lg:flex-row items-center gap-5 shadow-1">
                            <div class="bg-gray-50 rounded-full w-[109px] h-[109px] flex items-center justify-center">
                                <img src="/frontend/assets/icons/student-black.svg" alt="Icon" class="w-10 text-black">
                            </div>
                            <div class="flex flex-col justify-between h-full">
                                <div class="flex flex-col mb-5">
                                    <span class="text-gray-275">Student Name</span>
                                    <span class="text-white">{{ $user->name }}</span>
                                </div>
                                <div class="flex items-center py-2 px-6 bg-[#181818] rounded-[13px]">
                                    <span class="font-bebas">{{ $latestSubscription->schedule->classroom->subject->name }}</span>
                                    <div class="text-[#4C4C4C] px-8">
                                        {{ $latestSubscription->schedule->form->name }} | Class Group: {{ $latestSubscription->schedule->classroom->name }}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- CLASS LIST -->
                <div class="w-full pt-10 grid grid-cols-3 gap-8">
                    <div class="col-span-3 lg:col-span-2">
                        <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-5">
                            <div class="flex flex-col">
                                <h6 class="text-[15px] text-gray-75 font-semibold">Subscription History</h6>
                                <span class="text-gray-910">{{ $subscriptions->count() }} Subscription</span>
                            </div>
                        </div>
                    </div>

                    <div class="col-span-3 lg:col-span-2">
                        <!-- START : TABLE -->
                        <div class="overflow-x-auto border border-[#424242] rounded-[12px] mb-10">
                            <table
                                class="min-w-full border border-[#424242] rounded-[12px] overflow-hidden text-sm text-left">
                                <thead class="bg-gray-800 text-gray-200">
                                    <tr>
                                        <th class="p-4 border border-[#424242]">No</th>
                                        <th class="p-4 border border-[#424242]">Plan</th>
                                        <th class="p-4 border border-[#424242]">Start Date</th>
                                        <th class="p-4 border border-[#424242]">End Date</th>
                                        <th class="p-4 border border-[#424242]">Payment Method</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-gray-900 divide-y divide-gray-800 text-gray-50">
                                    @forelse ($subscriptions as $index => $sub)
                                        <tr class="odd:bg-[#141414] even:bg-[#171717]">
                                            <td class="p-4 border border-gray-700">{{ $index + 1 }}</td>
                                            <td class="p-4 border border-gray-700">{{ $sub->plan->name ?? '-' }}</td>
                                            <td class="p-4 border border-gray-700">
                                                {{ \Carbon\Carbon::parse($sub->start_date)->format('d M Y') }}
                                            </td>
                                            <td class="p-4 border border-gray-700">
                                                {{ \Carbon\Carbon::parse($sub->end_date)->format('d M Y') }}
                                            </td>
                                            <td class="p-4 border border-gray-700 text-uppercase">{{ strtoupper($sub->payment_method) }}</td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="5" class="text-center p-4 text-gray-400">
                                                No subscriptions found for this student.
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection