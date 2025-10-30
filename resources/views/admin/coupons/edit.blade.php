@extends('layouts.main')

@section('content')
    <div class="space-y-5 py-5 px-10">
        <div class="space-y-7 flex flex-col">
            <h1 class="text-gray-100 font-[600]">Coupon Management</h1>
            <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-3">
                <div class="flex items-center gap-3">
                    <a href="{{ route('admin.coupons.index') }}"
                        class="bg-white rounded-full w-8 h-8 flex items-center justify-center cursor-pointer">
                        <img src="/admin/assets/icons/arrow-left.svg" alt="Icon" class="size-4">
                    </a>
                    <h6 class="text-gray-100 font-[600]">Edit Coupon: {{ $coupon->code }}</h6>
                </div>
            </div>
        </div>

        <div class="space-y-10">
            <form action="{{ route('admin.coupons.update', $coupon->id) }}" method="POST" class="space-y-10">
                @csrf
                @method('PUT')

                <div class="grid grid-cols-12 gap-10 pt-4">

                    <div class="col-span-12 lg:col-span-6">
                        <label class="block mb-2 text-[15px] font-medium text-gray-200">Coupon Code *</label>
                        <input type="text" name="code" placeholder="e.g., BIGSALE20"
                            class="appearance-none bg-gray-1000 border border-gray-950 text-gray-75 rounded-[14px] w-full px-4 py-3 @error('code') border-red-500 @enderror"
                            value="{{ old('code', $coupon->code) }}" required />
                        @error('code')
                            <p class="mt-2 text-sm text-red-500">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="col-span-12 lg:col-span-6">
                        <label class="block mb-2 text-[15px] font-medium text-gray-200">Discount Value *</label>
                        <input type="number" name="value" placeholder="e.g., 10 (for 10% or 10 RM)"
                            class="appearance-none bg-gray-1000 border border-gray-950 text-gray-75 rounded-[14px] w-full px-4 py-3 @error('value') border-red-500 @enderror"
                            value="{{ old('value', $coupon->value) }}" required />
                        @error('value')
                            <p class="mt-2 text-sm text-red-500">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="col-span-12 lg:col-span-6">
                        <label class="block mb-2 text-[15px] font-medium text-gray-200">Discount Type *</label>
                        <div class="relative">
                            <select name="type"
                                class="appearance-none pr-10 bg-gray-1000 border border-gray-950 text-gray-75 rounded-[14px] w-full px-4 py-3 @error('type') border-red-500 @enderror"
                                required>
                                <option value="">Select Type</option>
                                <option value="percentage" @if (old('type', $coupon->type) == 'percentage') selected @endif>Percentage (%)
                                </option>
                                <option value="fixed" @if (old('type', $coupon->type) == 'fixed') selected @endif>Fixed Amount (RM)
                                </option>
                            </select>
                            <div class="pointer-events-none absolute right-5 top-1/2 transform -translate-y-1/2 text-white">
                                <img src="/frontend/assets/icons/angle-down.svg" alt="Icon">
                            </div>
                        </div>
                        @error('type')
                            <p class="mt-2 text-sm text-red-500">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="col-span-12 lg:col-span-6">
                        <label class="block mb-2 text-[15px] font-medium text-gray-200">Expired At (Optional)</label>
                        <input type="date" name="expired_at"
                            class="appearance-none bg-gray-1000 border border-gray-950 text-gray-75 rounded-[14px] w-full px-4 py-3 @error('expired_at') border-red-500 @enderror"
                            value="{{ old('expired_at', $coupon->expired_at ? \Carbon\Carbon::parse($coupon->expired_at)->format('Y-m-d') : null) }}" />
                        @error('expired_at')
                            <p class="mt-2 text-sm text-red-500">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="col-span-12 lg:col-span-6">
                        <label class="block mb-2 text-[15px] font-medium text-gray-200">Total Usage Limit (Optional)</label>
                        <input type="number" name="limit" placeholder="Max times coupon can be used overall (e.g., 100)"
                            class="appearance-none bg-gray-1000 border border-gray-950 text-gray-75 rounded-[14px] w-full px-4 py-3 @error('limit') border-red-500 @enderror"
                            value="{{ old('limit', $coupon->limit) }}" />
                        @error('limit')
                            <p class="mt-2 text-sm text-red-500">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="col-span-12 lg:col-span-6">
                        <label class="block mb-2 text-[15px] font-medium text-gray-200">Max Uses Per User</label>
                        <input type="number" name="max_uses_per_user"
                            placeholder="Max times a single user can use it (Default: 1)"
                            class="appearance-none bg-gray-1000 border border-gray-950 text-gray-75 rounded-[14px] w-full px-4 py-3 @error('max_uses_per_user') border-red-500 @enderror"
                            value="{{ old('max_uses_per_user', $coupon->max_uses_per_user ?? 1) }}" required />
                        @error('max_uses_per_user')
                            <p class="mt-2 text-sm text-red-500">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="col-span-12 lg:col-span-6">
                        <label class="block mb-2 text-[15px] font-medium text-gray-200">Min Purchase Amount
                            (Optional)</label>
                        <input type="number" name="min_purchase_amount" placeholder="Minimum order total to use coupon"
                            class="appearance-none bg-gray-1000 border border-gray-950 text-gray-75 rounded-[14px] w-full px-4 py-3 @error('min_purchase_amount') border-red-500 @enderror"
                            value="{{ old('min_purchase_amount', $coupon->min_purchase_amount) }}" />
                        @error('min_purchase_amount')
                            <p class="mt-2 text-sm text-red-500">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="col-span-12 lg:col-span-6 flex items-center pt-8">
                        <label class="relative inline-flex items-center cursor-pointer">
                            <input type="checkbox" name="is_active" value="1" class="sr-only peer"
                                {{ old('is_active', $coupon->is_active) ? 'checked' : '' }}>
                            <div
                                class="w-11 h-6 bg-gray-700 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-blue-800 rounded-full peer peer-checked:after:translate-x-full rtl:peer-checked:after:-translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:start-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-blue-600">
                            </div>
                            <span class="ms-3 text-[15px] font-medium text-gray-200">Is Active</span>
                        </label>
                    </div>

                    <div class="col-span-12">
                        <label class="block mb-2 text-[15px] font-medium text-gray-200">Coupon Name (Optional)</label>
                        <input type="text" name="name" placeholder="Name for internal tracking"
                            class="appearance-none bg-gray-1000 border border-gray-950 text-gray-75 rounded-[14px] w-full px-4 py-3 @error('name') border-red-500 @enderror"
                            value="{{ old('name', $coupon->name) }}" />
                        @error('name')
                            <p class="mt-2 text-sm text-red-500">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="col-span-12">
                        <label class="block mb-2 text-[15px] font-medium text-gray-200">Description (Optional)</label>
                        <textarea name="description" placeholder="Internal description about this coupon" rows="3"
                            class="appearance-none bg-gray-1000 border border-gray-950 text-gray-75 rounded-[14px] w-full px-4 py-3 @error('description') border-red-500 @enderror">{{ old('description', $coupon->description) }}</textarea>
                        @error('description')
                            <p class="mt-2 text-sm text-red-500">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="col-span-12">
                        <button type="submit" class="bg-gray-50 hover:bg-gray-200 rounded-full text-sm px-8 py-3 w-full">
                            <span class="text-black text-[16px] font-semibold">Update Coupon</span>
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection
