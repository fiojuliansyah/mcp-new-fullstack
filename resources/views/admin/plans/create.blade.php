@extends('layouts.main')

@section('content')
<div class="space-y-5 py-5 px-10">
    <!-- PAGE TITLE -->
    <div class="space-y-7 flex flex-col">
        <h1 class="text-gray-100 font-[600]">Plans Management</h1>
        <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-3">
            <div class="flex items-center gap-3">
                <a href="{{ route('admin.plans.index') }}" class="bg-white rounded-full w-8 h-8 flex items-center justify-center cursor-pointer">
                    <img src="/admin/assets/icons/arrow-left.svg" alt="Icon" class="size-4">
                </a>
                <h6 class="text-gray-100 font-[600]">Add Plan</h6>
            </div>
        </div>
    </div>

    <div class="space-y-10">
        <form action="{{ route('admin.plans.store') }}" method="POST" class="space-y-10">
            @csrf

            <div class="grid grid-cols-12 gap-10 pt-4">
                <div class="col-span-12 lg:col-span-12">
                    <label class="block mb-2 text-[15px] font-medium text-gray-200">Plan Name</label>
                    <input type="text" name="name" placeholder="Enter plan name"
                        class="appearance-none bg-gray-1000 border border-gray-950 text-gray-75 rounded-[14px] w-full px-4 py-3"
                        required />
                </div>

                <div class="col-span-12 lg:col-span-6">
                    <label class="block mb-2 text-[15px] font-medium text-gray-200">Price</label>
                    <input type="text" name="price" placeholder="Enter price"
                        class="appearance-none bg-gray-1000 border border-gray-950 text-gray-75 rounded-[14px] w-full px-4 py-3" />
                </div>

                <div class="col-span-12 lg:col-span-6">
                    <label class="block mb-2 text-[15px] font-medium text-gray-200">Duration</label>
                    <div class="relative">
                        <select name="duration"
                            class="appearance-none pr-10 bg-gray-1000 border border-gray-950 text-gray-75 rounded-[14px] w-full px-4 py-3">
                            <option value="">Select Duration</option>
                            <option value="week">Week</option>
                            <option value="month">Month</option>
                            <option value="year">Year</option>
                        </select>
                        <div class="pointer-events-none absolute right-5 top-1/2 transform -translate-y-1/2 text-white">
                            <img src="/frontend/assets/icons/angle-down.svg" alt="Icon">
                        </div>
                    </div>
                </div>

                <div class="col-span-12 lg:col-span-6">
                    <label class="block mb-2 text-[15px] font-medium text-gray-200">Duration Value</label>
                    <input type="number" name="duration_value" placeholder="Enter duration value (e.g. 3)"
                        class="appearance-none bg-gray-1000 border border-gray-950 text-gray-75 rounded-[14px] w-full px-4 py-3" />
                </div>

                <div class="col-span-12 lg:col-span-6">
                    <label class="block mb-2 text-[15px] font-medium text-gray-200">Device Limit</label>
                    <input type="number" name="device_limit" placeholder="Enter device limit (optional)"
                        class="appearance-none bg-gray-1000 border border-gray-950 text-gray-75 rounded-[14px] w-full px-4 py-3" />
                </div>

                <div class="col-span-12 lg:col-span-6">
                    <label class="block mb-2 text-[15px] font-medium text-gray-200">Replay Day</label>
                    <input type="number" name="replay_day" placeholder="Enter replay day (optional)"
                        class="appearance-none bg-gray-1000 border border-gray-950 text-gray-75 rounded-[14px] w-full px-4 py-3" />
                </div>

                <div class="col-span-12">
                    <button type="submit"
                        class="bg-gray-50 hover:bg-gray-200 rounded-full text-sm px-8 py-3 w-full">
                        <span class="text-black text-[16px] font-semibold">Add Plan</span>
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection
