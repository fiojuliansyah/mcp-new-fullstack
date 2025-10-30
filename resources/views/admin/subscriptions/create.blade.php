@extends('layouts.main')

@section('content')
    <div class="space-y-5 py-5 px-10">
        <div class="space-y-7 flex flex-col">
            <h1 class="text-gray-100 font-[600]">Subscriptions Management</h1>
            <div class="flex items-center gap-3">
                <a href="{{ route('admin.subscriptions.index') }}"
                    class="bg-white rounded-full w-8 h-8 flex items-center justify-center cursor-pointer">
                    <img src="/admin/assets/icons/arrow-left.svg" alt="Icon" class="size-4">
                </a>
                <h6 class="text-gray-100 font-[600]">Add New Subscription</h6>
            </div>
        </div>

        <div class="space-y-10">
            <form action="{{ route('admin.subscriptions.store') }}" method="POST" id="subscriptionForm" class="space-y-10">
                @csrf

                <div class="grid grid-cols-12 gap-10 pt-4">

                    {{-- USER --}}
                    <div class="col-span-12 lg:col-span-6">
                        <label class="block mb-2 text-[15px] font-medium text-gray-200">User</label>
                        <select name="user_id" id="user_id"
                            class="select2 appearance-none pr-10 bg-gray-1000 border border-gray-950 text-gray-75 rounded-[14px] w-full px-4 py-3"
                            required>
                            <option value="">Select User</option>
                            @foreach ($users as $user)
                                <option value="{{ $user->id }}">{{ $user->name }} ({{ $user->form->name }})</option>
                            @endforeach
                        </select>
                    </div>

                    {{-- PLAN --}}
                    <div class="col-span-12 lg:col-span-6">
                        <label class="block mb-2 text-[15px] font-medium text-gray-200">Plan</label>
                        <select name="plan_id" id="plan_id"
                            class="appearance-none pr-10 bg-gray-1000 border border-gray-950 text-gray-75 rounded-[14px] w-full px-4 py-3"
                            required>
                            <option value="">Select Plan</option>
                            @foreach ($plans as $plan)
                                <option value="{{ $plan->id }}" data-price="{{ $plan->price }}">
                                    {{ $plan->name }} - RM{{ number_format($plan->price, 2) }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    {{-- PRICE FIELDS --}}
                    <div class="col-span-12 lg:col-span-6">
                        <label class="block mb-2 text-[15px] font-medium text-gray-200">Base Price (RM)</label>
                        <input type="number" step="0.01" id="price" name="price" readonly
                            class="bg-gray-1000 border border-gray-950 text-gray-75 rounded-[14px] w-full px-4 py-3" />
                    </div>

                    <div class="col-span-12 lg:col-span-6">
                        <label class="block mb-2 text-[15px] font-medium text-gray-200">Total Price (RM)</label>
                        <input type="number" step="0.01" id="total" name="total" readonly
                            class="bg-gray-1000 border border-gray-950 text-gray-75 rounded-[14px] w-full px-4 py-3" />
                    </div>

                    {{-- PAYMENT --}}
                    <div class="col-span-12 lg:col-span-6">
                        <label class="block mb-2 text-[15px] font-medium text-gray-200">Payment Method</label>
                        <select name="payment_method"
                            class="appearance-none pr-10 bg-gray-1000 border border-gray-950 text-gray-75 rounded-[14px] w-full px-4 py-3"
                            required>
                            <option value="">Select Method</option>
                            <option value="fpx">FPX</option>
                            <option value="bank_transfer">Bank Transfer</option>
                        </select>
                    </div>

                    <div class="col-span-12 lg:col-span-6">
                        <label class="block mb-2 text-[15px] font-medium text-gray-200">Payment Status</label>
                        <select name="payment_status"
                            class="appearance-none pr-10 bg-gray-1000 border border-gray-950 text-gray-75 rounded-[14px] w-full px-4 py-3"
                            required>
                            <option value="">Select Payment Status</option>
                            <option value="paid">Paid</option>
                            <option value="unpaid">Unpaid</option>
                            <option value="failed">Failed</option>
                            <option value="refunded">Refunded</option>
                        </select>
                    </div>

                    {{-- DATE + STATUS --}}
                    <div class="col-span-12 lg:col-span-3">
                        <label class="block mb-2 text-[15px] font-medium text-gray-200">Start Date</label>
                        <input type="date" name="start_date"
                            class="bg-gray-1000 border border-gray-950 text-gray-75 rounded-[14px] w-full px-4 py-3"
                            required>
                    </div>

                    <div class="col-span-12 lg:col-span-3">
                        <label class="block mb-2 text-[15px] font-medium text-gray-200">End Date</label>
                        <input type="date" name="end_date"
                            class="bg-gray-1000 border border-gray-950 text-gray-75 rounded-[14px] w-full px-4 py-3">
                    </div>

                    <div class="col-span-12 lg:col-span-6">
                        <label class="block mb-2 text-[15px] font-medium text-gray-200">Subscription Status</label>
                        <select name="status"
                            class="appearance-none pr-10 bg-gray-1000 border border-gray-950 text-gray-75 rounded-[14px] w-full px-4 py-3"
                            required>
                            <option value="">Select Status</option>
                            <option value="active">Active</option>
                            <option value="pending">Pending</option>
                            <option value="cancelled">Cancelled</option>
                            <option value="expired">Expired</option>
                            <option value="on_hold">On Hold</option>
                        </select>
                    </div>

                    {{-- CLASSROOM LIST (CHECKBOX) --}}
                    <div class="col-span-12">
                        <label class="block mb-2 text-[15px] font-medium text-gray-200">Subjects</label>
                        <div class="grid grid-cols-2 lg:grid-cols-4 gap-3">
                            @foreach ($classrooms as $classroom)
                                <label
                                    class="flex items-center gap-3 bg-gray-1000 border border-gray-950 text-gray-75 rounded-[14px] px-4 py-3 cursor-pointer">
                                    <input type="checkbox" name="classroom_id[]" value="{{ $classroom->id }}"
                                        class="classroom-checkbox accent-gray-300 size-4">
                                    <span>{{ $classroom->subject->name }}</span>
                                </label>
                            @endforeach
                        </div>
                    </div>

                    <div class="col-span-12">
                        <button type="submit" class="bg-gray-50 hover:bg-gray-200 rounded-full text-sm px-8 py-3 w-full">
                            <span class="text-black text-[16px] font-semibold">Add Subscription</span>
                        </button>
                    </div>

                </div>
            </form>
        </div>
    </div>
@endsection

@push('styles')
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<style>
    .select2-container--default .select2-search--dropdown {
    background-color: #0a0a0a;
    padding: 6px 8px;
    border-bottom: 1px solid #1a1a1a;
    }

    .select2-container--default .select2-search--dropdown .select2-search__field {
        background-color: #1a1a1a;
        color: #d1d5db;
        border: 1px solid #333; 
        border-radius: 10px;
        padding: 6px 10px;
        outline: none;
    }

    .select2-container--default .select2-search--dropdown .select2-search__field::placeholder {
        color: #6b7280;
    }

    .select2-container--default .select2-search--dropdown .select2-search__field:focus {
        border-color: #4b5563; 
        background-color: #111;
    }
    .select2-container .select2-selection--single {
        background-color: #0f1012;
        border: 1px solid #1a1a1a;
        border-radius: 14px;
        height: 46px;   
        display: flex;
        align-items: center;
        color: #d1d5db;
    }
    .select2-container .select2-selection__rendered {
        color: #fff;
        line-height: 46px;
        padding-left: 16px;
    }
    .select2-container .select2-selection__arrow b {
        border-color: #0f1012 transparent transparent transparent;
    }
    .select2-dark-dropdown {
        background-color: #202223 !important;
        border: 1px solid #1a1a1a !important;
        color: #fff !important;
        border-radius: 12px;
        padding: 4px 0;
    }
    .select2-results__option {
        padding: 8px 12px;
        cursor: pointer;
    }
    .select2-results__option--highlighted {
        background-color: #1f2937 !important;
        color: #fff !important;
    }
</style>
@endpush


@push('scripts')
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script>
        $(document).ready(function() {
            $('.select2').select2({
                minimumResultsForSearch: 0,
                width: '100%',
                dropdownCssClass: 'select2-dark-dropdown'
            });
            $('#plan_id').on('change', function() {
                const planId = $(this).val();
                if (planId) {
                    $.ajax({
                        url: "/admin/plans/" + planId + "/price",
                        type: 'GET',
                        success: function(response) {
                            const price = parseFloat(response.price) || 0;
                            $('#price').val(price.toFixed(2));
                            updateTotal();
                        },
                        error: function() {
                            $('#price').val('');
                            $('#total').val('');
                        }
                    });
                } else {
                    $('#price').val('');
                    $('#total').val('');
                }
            });
            $('.classroom-checkbox').on('change', function() {
                updateTotal();
            });

            function updateTotal() {
                const price = parseFloat($('#price').val()) || 0;
                const checkedCount = $('.classroom-checkbox:checked').length;
                const total = price * checkedCount;
                $('#total').val(total.toFixed(2));
            }
        });
    </script>
@endpush
