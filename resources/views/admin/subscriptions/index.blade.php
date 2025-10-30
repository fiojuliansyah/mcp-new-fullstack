@extends('layouts.main')

@section('content')
    <div class="space-y-5 py-5 px-10">
        <div class="space-y-7 flex flex-col">
            <h1 class="text-gray-100 font-[600]">Subscriptions Management</h1>
            <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-3">
                <div>
                    <h6 class="font-[600]">Subscriptions List</h6>
                </div>
                <a href="{{ route('admin.subscriptions.create') }}"
                    class="bg-gray-50 hover:bg-gray-200 rounded-full text-sm px-5 py-3 cursor-pointer">
                    <span class="text-black text-[16px] font-semibold">Add New Subscription</span>
                </a>
            </div>
        </div>

        <div class="space-y-10 divide-y divide-gray-200">
            <div class="space-y-5">
                <div class="overflow-x-auto border border-[#424242] rounded-[12px] mb-10">
                    <table class="min-w-full border border-[#424242] rounded-[12px] overflow-hidden text-sm text-left">
                        <thead class="bg-gray-800 text-gray-200">
                            <tr>
                                <th class="p-4 border border-[#424242]">No</th>
                                <th class="p-4 border border-[#424242]">Invoice No.</th>
                                <th class="p-4 border border-[#424242]">User</th>
                                <th class="p-4 border border-[#424242]">Plan</th>
                                <th class="p-4 border border-[#424242]">Total</th>
                                <th class="p-4 border border-[#424242]">Status</th>
                                <th class="p-4 border border-[#424242]">Pmt. Status</th>
                                <th class="p-4 border border-[#424242]">Start Date</th>
                                <th class="p-4 border border-[#424242]">Action</th>
                            </tr>
                        </thead>
                        <tbody class="bg-gray-900 divide-y divide-gray-800 text-gray-50">
                            @foreach ($subscriptions as $index => $subscription)
                                <tr class="odd:bg-[#141414] even:bg-[#171717]">
                                    <td class="p-4 border border-gray-700">{{ $subscriptions->firstItem() + $index }}</td>
                                    <td class="p-4 border border-gray-700">{{ $subscription->invoice_number ?? '-' }}</td>
                                    <td class="p-4 border border-gray-700">
                                        {{ $subscription->user->name ?? 'N/A' }}
                                        <br>
                                        <small><b>Parent :</b> {{ $subscription->user->parent->name ?? 'N/A' }}</small>
                                    </td>
                                    <td class="p-4 border border-gray-700">{{ $subscription->plan->name ?? 'N/A' }}</td>
                                    <td class="p-4 border border-gray-700">
                                        RM{{ number_format($subscription->total, 2) ?? '-' }}</td>
                                    <td class="p-4 border border-gray-700">
                                        @if ($subscription->status === 'active')
                                            <span class="text-green-100">Active</span>
                                        @elseif ($subscription->status === 'expired')
                                            <span class="text-yellow-400">Expired</span>
                                        @else
                                            <span class="text-red-100">{{ ucfirst($subscription->status) }}</span>
                                        @endif
                                    </td>
                                    <td class="p-4 border border-gray-700">
                                        @if ($subscription->payment_status === 'paid')
                                            <span class="text-green-100">Paid</span>
                                        @else
                                            <span class="text-red-100">{{ ucfirst($subscription->payment_status) }}</span>
                                        @endif
                                    </td>
                                    <td class="p-4 border border-gray-700">
                                        {{ $subscription->start_date->format('d M Y') ?? '-' }}
                                    </td>
                                    <td class="p-4 border border-gray-700">
                                        <div class="flex items-center justify-center gap-3">
                                            <a href="{{ route('admin.subscriptions.edit', $subscription->id) }}"
                                                class="flex flex-col items-center">
                                                <div
                                                    class="block bg-gray-50 hover:bg-gray-200 cursor-pointer w-[35px] h-[35px] rounded-full p-2 flex items-center justify-center">
                                                    <img src="/admin/assets/icons/pencil.svg" alt="Icon"
                                                        class="size-5 text-black" />
                                                </div>
                                                <span class="text-[#5F5F5F] text-[10px]">Edit</span>
                                            </a>
                                            <button type="button"
                                                onclick="openDeleteModal('{{ $subscription->id }}', '{{ $subscription->invoice_number ?? 'Subscription' }}')"
                                                class="flex flex-col items-center">
                                                <div
                                                    class="block bg-gray-50 hover:bg-gray-200 cursor-pointer w-[35px] h-[35px] rounded-full p-2 flex items-center justify-center">
                                                    <img src="/admin/assets/icons/trash.svg" alt="Delete"
                                                        class="size-4 text-white" />
                                                </div>
                                                <span class="text-[#5F5F5F] text-[10px]">Delete</span>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="flex items-center justify-center lg:justify-end gap-5">
                    <span class="text-gray-200">Page</span>

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

                    <div class="bg-gray-1000 border border-gray-950 text-gray-75 rounded-[14px] px-8 py-3">
                        {{ $subscriptions->currentPage() }}
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
        </div>


    </div>

    <div id="deleteModal" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-50">
        <div class="bg-gray-900 rounded-xl p-6 w-11/12 max-w-sm border border-gray-700 shadow-xl">
            <h3 class="text-xl font-semibold text-gray-100 mb-4">Confirm Deletion</h3>
            <p class="text-gray-300 mb-6">Are you sure you want to delete the subscription <strong
                    id="modalSubscriptionName"></strong>?
                This action cannot be undone.</p>

            <div class="flex justify-end gap-3">
                <button type="button" onclick="closeDeleteModal()"
                    class="px-4 py-2 text-sm font-medium text-gray-700 bg-gray-300 rounded-full hover:bg-gray-400">
                    Cancel
                </button>
                <form id="deleteForm" action="" method="POST">
                    @csrf
                    @method('DELETE')
                    <button type="submit"
                        class="px-4 py-2 text-sm font-medium text-white bg-red-600 rounded-full hover:bg-red-700">
                        Delete Permanently
                    </button>
                </form>
            </div>
        </div>


    </div>
@endsection

@push('scripts')
    <script>
        function openDeleteModal(subscriptionId, subscriptionName) {
            const form = document.getElementById('deleteForm');
            const deleteRoute = '{{ route('admin.subscriptions.destroy', ':id') }}'.replace(':id', subscriptionId);
            form.action = deleteRoute;
            document.getElementById('modalSubscriptionName').innerText = subscriptionName;
            document.getElementById('deleteModal').classList.remove('hidden');
            document.getElementById('deleteModal').classList.add('flex');
        }

        function closeDeleteModal() {
            document.getElementById('deleteModal').classList.add('hidden');
            document.getElementById('deleteModal').classList.remove('flex');
        }
    </script>
@endpush
