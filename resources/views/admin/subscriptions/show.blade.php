@extends('layouts.main')

@section('content')
<div class="space-y-5 py-5 px-10">
    <!-- HEADER -->
    <div class="space-y-7 flex flex-col">
        <h1 class="text-gray-100 font-[600]">Subscription</h1>
        <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-3">
            <div class="flex items-center gap-3">
                <a href="{{ route('admin.subscriptions.index') }}"
                    class="bg-white rounded-full w-8 h-8 flex items-center justify-center cursor-pointer">
                    <img src="/frontend/assets/icons/arrow-left.svg" alt="Icon" class="size-4">
                </a>
                <h6 class="text-gray-100 font-[600]">View Subscription Detail</h6>
            </div>
        </div>
    </div>

    <!-- PAGE CONTENT -->
    <div class="space-y-10">
        <!-- USER DETAIL -->
        <div>
            <div class="rounded-[20px] border border-[#2A2A2A] p-5 lg:p-7 space-y-5">
                <!-- Student Row -->
                <div class="flex items-start gap-4">
                    <div class="w-10 h-10 rounded-full bg-white flex items-center justify-center shrink-0">
                        <img src="/frontend/assets/icons/student-black.svg" alt="Icon" class="w-5 h-5">
                    </div>
                    <div class="flex-1">
                        <p class="text-gray-200">Student Name</p>
                        <p class="text-gray-75">{{ $subscription->user->name ?? '-' }}</p>
                    </div>
                </div>
                <!-- Parent Row -->
                <div class="flex items-start gap-4">
                    <div class="w-10 h-10 rounded-full bg-white flex items-center justify-center shrink-0">
                        <img src="/frontend/assets/icons/user-black.svg" alt="Icon" class="w-5 h-5">
                    </div>
                    <div class="flex-1">
                        <p class="text-gray-200">Parent Name</p>
                        <p class="text-gray-75">{{ $subscription->user->parent->name ?? '-' }}</p>
                    </div>
                </div>
                <!-- Divider -->
                <hr class="border-[#3A3A3A]">
                <!-- Status Row -->
                <div class="flex items-center justify-between">
                    <p class="text-gray-200">
                        Status Account:
                        <span class="text-green-100 capitalize">
                            {{ $subscription->user->status ?? '-' }}
                        </span>
                    </p>
                </div>
                <!-- Divider -->
                <hr class="border-[#3A3A3A]">
                <!-- Actions -->
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-5">
                    <a href="{{ route('admin.subscriptions.renewal', $subscription->id) }}"
                        class="bg-white hover:bg-gray-200 text-black font-semibold rounded-full py-3 text-center w-full">
                        Renew Subscription
                    </a>
                    <a href="{{ route('admin.subscriptions.refund', $subscription->id) }}"
                        class="bg-white hover:bg-gray-200 text-black font-semibold rounded-full py-3 text-center w-full">
                        Apply Refund
                    </a>
                    <a href="{{ route('admin.subscriptions.cancel', $subscription->id) }}"
                        class="rounded-full py-3 border border-white text-white hover:bg-white/10 text-center w-full">
                        Cancel Subscription
                    </a>
                </div>
            </div>
        </div>

        <!-- SUBSCRIPTION INFO -->
        <div class="grid grid-cols-12 gap-5">
            <!-- LEFT: INFO TABLE -->
            <div class="col-span-12 lg:col-span-8">
                <div class="space-y-5 h-full">
                    <div>
                        <h6 class="text-gray-100 font-[600]">Subscription Info</h6>
                    </div>
                    <div class="rounded-[16px] border border-[#424242] overflow-hidden">
                        <div class="grid grid-cols-12 text-sm">
                            <div class="col-span-5 bg-[#222222]/80 text-gray-300 px-5 py-4 border-b border-[#3a3a3a]">
                                Invoice Number
                            </div>
                            <div class="col-span-7 px-5 py-4 border-b border-[#3a3a3a]">
                                {{ $subscription->invoice_number }}
                            </div>

                            <div class="col-span-5 bg-[#222222]/80 text-gray-300 px-5 py-4 border-b border-[#3a3a3a]">
                                Plan
                            </div>
                            <div class="col-span-7 px-5 py-4 border-b border-[#3a3a3a]">
                                {{ $subscription->plan->name ?? '-' }}
                            </div>

                            <div class="col-span-5 bg-[#222222]/80 text-gray-300 px-5 py-4 border-b border-[#3a3a3a]">
                                Start Date
                            </div>
                            <div class="col-span-7 px-5 py-4 border-b border-[#3a3a3a]">
                                {{ $subscription->start_date ? $subscription->start_date->format('d M Y') : '-' }}
                            </div>

                            <div class="col-span-5 bg-[#222222]/80 text-gray-300 px-5 py-4 border-b border-[#3a3a3a]">
                                End Date
                            </div>
                            <div class="col-span-7 px-5 py-4 border-b border-[#3a3a3a]">
                                {{ $subscription->end_date ? $subscription->end_date->format('d M Y') : '-' }}
                            </div>

                            <div class="col-span-5 bg-[#222222]/80 text-gray-300 px-5 py-4 border-b border-[#3a3a3a]">
                                Payment Method
                            </div>
                            <div class="col-span-7 px-5 py-4 border-b border-[#3a3a3a]">
                                {{ ucfirst($subscription->payment_method) ?? '-' }}
                            </div>

                            <div class="col-span-5 bg-[#222222]/80 text-gray-300 px-5 py-4 border-b border-[#3a3a3a]">
                                Payment Status
                            </div>
                            <div class="col-span-7 px-5 py-4 border-b border-[#3a3a3a] capitalize">
                                {{ $subscription->payment_status }}
                            </div>

                            <div class="col-span-5 bg-[#222222]/80 text-gray-300 px-5 py-4">
                                Total Amount
                            </div>
                            <div class="col-span-7 px-5 py-4">
                                RM{{ number_format($subscription->total, 2) }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- RIGHT: SUBJECTS -->
            <div class="col-span-12 lg:col-span-4">
                <div class="space-y-5">
                    <div class="flex items-center justify-between mb-3">
                        <h6 class="text-gray-100 font-[600]">Subjects Covered</h6>
                        <span class="text-gray-910 text-sm">
                            {{ $subscription->classrooms->count() }} Subjects
                        </span>
                    </div>
                    <div
                        class="rounded-[16px] bg-[#1A1A1A] border border-[#2A2A2A] p-5 shadow-[0_20px_40px_-20px_rgba(0,0,0,0.5)]">
                        <ul class="space-y-4 text-gray-100 text-sm">
                            @forelse ($subscription->classrooms as $classroom)
                                <li>{{ $classroom->subject->name ?? 'N/A' }}</li>
                            @empty
                                <li>No subjects assigned</li>
                            @endforelse
                        </ul>
                    </div>
                </div>
            </div>
        </div>

        <!-- PAYMENT HISTORY -->
        <div class="space-y-5">
            <h6 class="text-gray-100 font-[600]">Payment History</h6>
            <div class="overflow-x-auto border border-[#424242] rounded-[12px] mb-10">
                <table class="min-w-full border border-[#424242] rounded-[12px] overflow-hidden text-sm text-left">
                    <thead class="bg-gray-800 text-gray-200">
                        <tr>
                            <th class="p-4 border border-[#424242]">No</th>
                            <th class="p-4 border border-[#424242]">Invoice</th>
                            <th class="p-4 border border-[#424242]">Date</th>
                            <th class="p-4 border border-[#424242]">Amount</th>
                            <th class="p-4 border border-[#424242]">Payment Method</th>
                            <th class="p-4 border border-[#424242]">Status</th>
                        </tr>
                    </thead>
                    <tbody class="bg-gray-900 divide-y divide-gray-800 text-gray-50">
                        <tr class="odd:bg-[#141414] even:bg-[#171717]">
                            <td class="p-4 border border-gray-700">1</td>
                            <td class="p-4 border border-gray-700">{{ $subscription->invoice_number }}</td>
                            <td class="p-4 border border-gray-700">{{ $subscription->created_at->format('d M Y') }}</td>
                            <td class="p-4 border border-gray-700">RM{{ number_format($subscription->total, 2) }}</td>
                            <td class="p-4 border border-gray-700">{{ ucfirst($subscription->payment_method) }}</td>
                            <td class="p-4 border border-gray-700 text-green-100 capitalize">
                                {{ $subscription->payment_status }}
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
