@extends('layouts.main')

@section('content')
    <div class="space-y-5 py-5 px-10">
        <div class="space-y-7 flex flex-col">
            <h1 class="text-gray-100 font-[600]">Plans Management</h1>
            <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-3">
                <div>
                    <h6 class="font-[600]">Plans List</h6>
                </div>
                <a href="{{ route('admin.plans.create') }}"
                    class="bg-gray-50 hover:bg-gray-200 rounded-full text-sm px-5 py-3 cursor-pointer">
                    <span class="text-black text-[16px] font-semibold">Add New Plan</span>
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
                                <th class="p-4 border border-[#424242]">Name</th>
                                <th class="p-4 border border-[#424242]">Price</th>
                                <th class="p-4 border border-[#424242]">Duration</th>
                                <th class="p-4 border border-[#424242]">Replay Day</th>
                                <th class="p-4 border border-[#424242]">Status</th>
                                <th class="p-4 border border-[#424242]">Action</th>
                            </tr>
                        </thead>
                        <tbody class="bg-gray-900 divide-y divide-gray-800 text-gray-50">
                            @foreach ($plans as $index => $plan)
                                <tr class="odd:bg-[#141414] even:bg-[#171717]">
                                    <td class="p-4 border border-gray-700">{{ $plans->firstItem() + $index }}</td>
                                    <td class="p-4 border border-gray-700">{{ $plan->name ?? '-' }}</td>
                                    <td class="p-4 border border-gray-700">RM{{ $plan->price ?? '-' }}</td>
                                    <td class="p-4 border border-gray-700">
                                        {{ ucfirst($plan->duration) }} ({{ $plan->duration_value }})
                                    </td>
                                    <td class="p-4 border border-gray-700">{{ $plan->replay_day ?? '-' }}</td>
                                    <td class="p-4 border border-gray-700">
                                        @if ($plan->status === 'active')
                                            <span class="text-green-100">Active</span>
                                        @else
                                            <span class="text-red-100">Inactive</span>
                                        @endif
                                    </td>
                                    <td class="p-4 border border-gray-700">
                                        <div class="flex items-center justify-center gap-3">
                                            <a href="{{ route('admin.plans.edit', $plan->id) }}"
                                                class="flex flex-col items-center">
                                                <div
                                                    class="block bg-gray-50 hover:bg-gray-200 cursor-pointer w-[35px] h-[35px] rounded-full p-2 flex items-center justify-center">
                                                    <img src="/admin/assets/icons/pencil.svg" alt="Icon"
                                                        class="size-5 text-black" />
                                                </div>
                                                <span class="text-[#5F5F5F] text-[10px]">Edit</span>
                                            </a>
                                            <button type="button"
                                                onclick="openDeleteModal('{{ $plan->id }}', '{{ $plan->name ?? 'Plan' }}')"
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

                    @if ($plans->onFirstPage())
                        <button
                            class="bg-white rounded-full w-10 h-10 flex items-center justify-center cursor-not-allowed opacity-50">
                            <img src="/frontend/assets/icons/angle-left.svg" alt="Prev" class="size-3">
                        </button>
                    @else
                        <a href="{{ $plans->previousPageUrl() }}"
                            class="bg-white rounded-full w-10 h-10 flex items-center justify-center">
                            <img src="/frontend/assets/icons/angle-left.svg" alt="Prev" class="size-3">
                        </a>
                    @endif

                    <div class="bg-gray-1000 border border-gray-950 text-gray-75 rounded-[14px] px-8 py-3">
                        {{ $plans->currentPage() }}
                    </div>

                    @if ($plans->hasMorePages())
                        <a href="{{ $plans->nextPageUrl() }}"
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
            <p class="text-gray-300 mb-6">Are you sure you want to delete the plan <strong id="modalPlanName"></strong>?
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
        function openDeleteModal(planId, planName) {
            const form = document.getElementById('deleteForm');
            const deleteRoute = '{{ route('admin.plans.destroy', ':id') }}'.replace(':id', planId);
            form.action = deleteRoute;
            document.getElementById('modalPlanName').innerText = planName;
            document.getElementById('deleteModal').classList.remove('hidden');
            document.getElementById('deleteModal').classList.add('flex');
        }

        function closeDeleteModal() {
            document.getElementById('deleteModal').classList.add('hidden');
            document.getElementById('deleteModal').classList.remove('flex');
        }
    </script>
@endpush
