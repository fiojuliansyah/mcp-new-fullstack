@extends('layouts.main')

@section('content')
<div class="space-y-5 py-5 px-10">

    {{-- Header --}}
    <div class="space-y-7 flex flex-col">
        <h1 class="text-gray-100 font-[600]">Cancel Subscription</h1>
        <div class="flex items-center gap-3">
            <a href="{{ route('admin.subscriptions.index') }}" 
               class="bg-white rounded-full w-8 h-8 flex items-center justify-center cursor-pointer">
                <img src="/admin/assets/icons/arrow-left.svg" alt="Icon" class="size-4">
            </a>
            <h6 class="text-gray-100 font-[600]">Cancel for Invoice #{{ $subscription->invoice_number }}</h6>
        </div>
    </div>

    {{-- Form --}}
    <div class="space-y-10">
        <form action="{{ route('admin.subscriptions.update', $subscription->id) }}" method="POST" class="space-y-10">
            @csrf
            @method('PUT')

            <input type="hidden" name="payment_status" value="refunded">

            <div class="grid grid-cols-12 gap-10 pt-4">
                {{-- User Info --}}
                <div class="col-span-12 lg:col-span-12">
                    <label class="block mb-2 text-[15px] font-medium text-gray-200">User</label>
                    <input type="text" value="{{ $subscription->user->name }}" readonly 
                           class="bg-gray-1000 border border-gray-950 text-gray-75 rounded-[14px] w-full px-4 py-3">
                </div>

                <div class="col-span-12 lg:col-span-6">
                    <label class="block mb-2 text-[15px] font-medium text-gray-200">User</label>
                    <input type="text" value="{{ $subscription->plan->name }}" readonly 
                           class="bg-gray-1000 border border-gray-950 text-gray-75 rounded-[14px] w-full px-4 py-3">
                </div>

                <div class="col-span-12 lg:col-span-6">
                    <label class="block mb-2 text-[15px] font-medium text-gray-200">User</label>
                    <input type="text" value="{{ $subscription->status }}" readonly 
                           class="bg-gray-1000 border border-gray-950 text-gray-75 rounded-[14px] w-full px-4 py-3">
                </div>

                <div class="col-span-12">
                    <label class="block mb-2 text-[15px] font-medium text-gray-200">Reason for Cancel</label>
                    <textarea name="cancel_reason" rows="4" placeholder="Enter refund reason..." 
                              class="bg-gray-1000 border border-gray-950 text-gray-75 rounded-[14px] w-full px-4 py-3" required></textarea>
                </div>
                {{-- Cancel Button --}}
                <div class="col-span-12">
                    <button type="submit" 
                            class="bg-gray-50 hover:bg-gray-200 rounded-full text-sm px-8 py-3 w-full">
                        <span class="text-black text-[16px] font-semibold">Process Cancel</span>
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection
