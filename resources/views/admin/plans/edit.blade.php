@extends('layouts.main')

@section('content')
<div class="space-y-5 py-5 px-10">
    <!-- PAGE TITLE -->
    <div class="space-y-7 flex flex-col">
        <h1 class="text-gray-100 font-[600]">Plans Management</h1>
        <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-3">
            <div class="flex items-center gap-3">
                <a href="{{ route('admin.plans.index') }}"
                    class="bg-white rounded-full w-8 h-8 flex items-center justify-center cursor-pointer">
                    <img src="/admin/assets/icons/arrow-left.svg" alt="Icon" class="size-4">
                </a>
                <h6 class="text-gray-100 font-[600]">Edit Plan</h6>
            </div>
        </div>
    </div>

    <div class="space-y-10">
        <form action="{{ route('admin.plans.update', $plan->id) }}" method="POST" class="space-y-10">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-12 gap-10 pt-4">
                <!-- PLAN NAME -->
                <div class="col-span-12">
                    <label class="block mb-2 text-[15px] font-medium text-gray-200">Plan Name</label>
                    <input type="text" name="name" value="{{ $plan->name }}" placeholder="Enter plan name"
                        class="appearance-none bg-gray-1000 border border-gray-950 text-gray-75 rounded-[14px] w-full px-4 py-3"
                        required />
                </div>

                <!-- PRICE -->
                <div class="col-span-12 lg:col-span-6">
                    <label class="block mb-2 text-[15px] font-medium text-gray-200">Price</label>
                    <input type="text" name="price" value="{{ $plan->price }}" placeholder="Enter price"
                        class="appearance-none bg-gray-1000 border border-gray-950 text-gray-75 rounded-[14px] w-full px-4 py-3" />
                </div>

                <!-- DURATION -->
                <div class="col-span-12 lg:col-span-6">
                    <label class="block mb-2 text-[15px] font-medium text-gray-200">Duration</label>
                    <div class="relative">
                        <select name="duration"
                            class="appearance-none pr-10 bg-gray-1000 border border-gray-950 text-gray-75 rounded-[14px] w-full px-4 py-3">
                            <option value="">Select Duration</option>
                            <option value="week" {{ $plan->duration == 'week' ? 'selected' : '' }}>Week</option>
                            <option value="month" {{ $plan->duration == 'month' ? 'selected' : '' }}>Month</option>
                            <option value="year" {{ $plan->duration == 'year' ? 'selected' : '' }}>Year</option>
                        </select>
                        <div
                            class="pointer-events-none absolute right-5 top-1/2 transform -translate-y-1/2 text-white">
                            <img src="/frontend/assets/icons/angle-down.svg" alt="Icon">
                        </div>
                    </div>
                </div>

                <!-- DURATION VALUE -->
                <div class="col-span-12 lg:col-span-6">
                    <label class="block mb-2 text-[15px] font-medium text-gray-200">Duration Value</label>
                    <input type="number" name="duration_value" value="{{ $plan->duration_value }}"
                        placeholder="Enter duration value"
                        class="appearance-none bg-gray-1000 border border-gray-950 text-gray-75 rounded-[14px] w-full px-4 py-3" />
                </div>

                <!-- DEVICE LIMIT -->
                <div class="col-span-12 lg:col-span-6">
                    <label class="block mb-2 text-[15px] font-medium text-gray-200">Device Limit</label>
                    <input type="number" name="device_limit" value="{{ $plan->device_limit }}"
                        placeholder="Enter device limit"
                        class="appearance-none bg-gray-1000 border border-gray-950 text-gray-75 rounded-[14px] w-full px-4 py-3" />
                </div>
                <!-- REPLAY DAY -->
                <div class="col-span-12 lg:col-span-6">
                    <label class="block mb-2 text-[15px] font-medium text-gray-200">Replay Day</label>
                    <input type="number" name="replay_day" value="{{ $plan->replay_day }}"
                        placeholder="Enter replay day"
                        class="appearance-none bg-gray-1000 border border-gray-950 text-gray-75 rounded-[14px] w-full px-4 py-3" />
                </div>

                <!-- STATUS -->
                <div class="col-span-12 lg:col-span-6">
                    <label class="block mb-2 text-[15px] font-medium text-gray-200">Status</label>
                    <div class="relative">
                        <select name="status"
                            class="appearance-none pr-10 bg-gray-1000 border border-gray-950 text-gray-75 rounded-[14px] w-full px-4 py-3">
                            <option value="">Select Status</option>
                            <option value="active" {{ $plan->status == 'active' ? 'selected' : '' }}>Active</option>
                            <option value="inactive" {{ $plan->status == 'inactive' ? 'selected' : '' }}>Inactive</option>
                        </select>
                        <div
                            class="pointer-events-none absolute right-5 top-1/2 transform -translate-y-1/2 text-white">
                            <img src="/frontend/assets/icons/angle-down.svg" alt="Icon">
                        </div>
                    </div>
                </div>

                <!-- SUBMIT -->
                <div class="col-span-12">
                    <button type="submit"
                        class="bg-gray-50 hover:bg-gray-200 rounded-full text-sm px-8 py-3 w-full">
                        <span class="text-black text-[16px] font-semibold">Update Plan</span>
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection
