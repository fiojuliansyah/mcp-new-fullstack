@extends('layouts.main')

@section('content')
<div class="space-y-5 py-5 px-10">
    <div class="space-y-7 flex flex-col">
        <h1 class="text-gray-100 font-[600]">Classes Management</h1>
        <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-3">
            <div class="flex items-center gap-3">
                <a href="{{ route('admin.classrooms.index') }}" class="bg-white rounded-full w-8 h-8 flex items-center justify-center cursor-pointer">
                    <img src="/admin/assets/icons/arrow-left.svg" alt="Icon" class="size-4">
                </a>
                <h6 class="text-gray-100 font-[600]">View Schedule Class</h6>
            </div>
        </div>
    </div>
    <div class="space-y-10 divide-y divide-gray-200">
        <!-- TABLE SECTION -->
        <div class="space-y-5">
            <div>
                <div class="rounded-[20px] border border-[#2A2A2A] p-5 lg:p-7 space-y-5">
                    <!-- Student Row -->
                    <div class="flex items-start gap-4">
                        <div class="w-10 h-10 rounded-full bg-white flex items-center justify-center shrink-0">
                            <img src="../assets/icons/user-black.svg" alt="Icon" class="w-5 h-5">
                        </div>
                        <div class="flex-1">
                            <p class="text-gray-200">Tutor Name</p>
                            <p class="text-gray-75">{{ $classroom->user->name ?? '-' }}</p>
                        </div>
                    </div>
                    <!-- Parent Row -->
                    <div class="flex items-start gap-4">
                        <div class="flex-1">
                            <p class="text-gray-200">Subject</p>
                            <p class="text-gray-75">{{ $classroom->subject->name ?? '-' }}</p>
                        </div>
                        <div class="flex-1">
                            <p class="text-gray-200">Class Group</p>
                            <p class="text-gray-75">{{ $classroom->name ?? '-' }}</p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="overflow-x-auto border border-[#424242] rounded-[12px] mb-10">
                <table class="min-w-full border border-[#424242] rounded-[12px] overflow-hidden text-sm text-left">
                    <thead class="bg-gray-800 text-gray-200">
                        <tr>
                            <th class="p-4 border border-[#424242]">No</th>
                            <th class="p-4 border border-[#424242]">Group Class</th>
                            <th class="p-4 border border-[#424242]">Form</th>
                            <th class="p-4 border border-[#424242]">Topic</th>
                            <th class="p-4 border border-[#424242]">Date</th>
                            <th class="p-4 border border-[#424242]">Time</th>
                            <th class="p-4 border border-[#424242]">Status</th>
                            <th class="p-4 border border-[#424242]">Action</th>
                        </tr>
                    </thead>
                    <tbody class="bg-gray-900 divide-y divide-gray-800 text-gray-50">
                        @forelse ($schedules as $index => $schedule)
                            <tr class="odd:bg-[#141414] even:bg-[#171717]">
                                <td class="p-4 border border-gray-700">{{ $schedules->firstItem() + $index }}</td>
                                <td class="p-4 border border-gray-700">{{ $schedule->classroom->name ?? '-' }}</td>
                                <td class="p-4 border border-gray-700">{{ $schedule->form->name ?? '-' }}</td>
                                <td class="p-4 border border-gray-700">{{ $schedule->topic ?? '-' }}</td>
                                <td class="p-4 border border-gray-700">{{ $schedule->time->format('d M Y') ?? '-' }}</td>
                                <td class="p-4 border border-gray-700">{{ $schedule->time->format('H:i') ?? '-' }}</td>
                                <td class="p-4 border border-gray-700">{{ $schedule->status ?? '-' }}</td>
                                <td class="p-4 border border-gray-700">
                                    <div class="flex items-center justify-center gap-3">
                                        <a href="{{ route('admin.schedules.edit', $schedule->id) }}" class="flex flex-col items-center">
                                            <div class="block bg-gray-50 hover:bg-gray-200 cursor-pointer w-[35px] h-[35px] rounded-full p-2 flex items-center justify-center">
                                                <img src="/admin/assets/icons/pencil.svg" alt="Icon" class="size-5" />
                                            </div>
                                            <span class="text-[#5F5F5F] text-[10px]">Edit</span>
                                        </a>

                                        <button type="button" 
                                                data-id="{{ $schedule->id }}" 
                                                data-topic="{{ $schedule->topic ?? 'Material' }}"
                                                class="delete-button flex flex-col items-center">
                                            <div class="block bg-gray-50 hover:bg-gray-200 cursor-pointer w-[35px] h-[35px] rounded-full p-2 flex items-center justify-center">
                                                <img src="/admin/assets/icons/trash.svg" alt="Icon" class="size-5" />
                                            </div>
                                            <span class="text-[#5F5F5F] text-[10px]">Delete</span>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="p-4 text-center text-gray-400 italic">No schedule found for this class.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- PAGINATION -->
            <div class="flex items-center justify-center lg:justify-end gap-5">
                <span class="text-gray-200">Page</span>

                @if($schedules->onFirstPage())
                    <button class="bg-white rounded-full w-10 h-10 flex items-center justify-center cursor-not-allowed opacity-50">
                        <img src="/frontend/assets/icons/angle-left.svg" alt="Prev" class="size-3">
                    </button>
                @else
                    <a href="{{ $schedules->previousPageUrl() }}" class="bg-white rounded-full w-10 h-10 flex items-center justify-center">
                        <img src="/frontend/assets/icons/angle-left.svg" alt="Prev" class="size-3">
                    </a>
                @endif

                <div class="bg-gray-1000 border border-gray-950 text-gray-75 rounded-[14px] px-8 py-3">
                    {{ $schedules->currentPage() }}
                </div>

                @if($schedules->hasMorePages())
                    <a href="{{ $schedules->nextPageUrl() }}" class="bg-white rounded-full w-10 h-10 flex items-center justify-center">
                        <img src="/frontend/assets/icons/angle-right.svg" alt="Next" class="size-3">
                    </a>
                @else
                    <button class="bg-white rounded-full w-10 h-10 flex items-center justify-center cursor-not-allowed opacity-50">
                        <img src="/frontend/assets/icons/angle-right.svg" alt="Next" class="size-3">
                    </button>
                @endif
            </div>
        </div>
    </div>
</div>
<div id="deleteModal" class="fixed inset-0 bg-black bg-opacity-75 hidden items-center justify-center z-50 transition-opacity duration-300">
    <div class="bg-[#141414] rounded-xl p-6 w-full max-w-sm mx-4 space-y-4 border border-gray-700">
        <h3 class="text-lg font-semibold text-gray-100">Confirm Deletion</h3>
        <p class="text-gray-300">Are you sure you want to delete the material for topic: <span id="modalTopicName" class="font-bold text-white"></span>? This action cannot be undone, and associated videos will be permanently removed.</p>
        
        <form id="deleteForm" method="POST" action="">
            @csrf
            @method('DELETE')
            <div class="flex justify-end gap-3 mt-5">
                <button type="button" onclick="closeModal()" class="px-4 py-2 text-sm font-medium text-gray-300 rounded-full border border-gray-600 hover:bg-gray-800 transition duration-150">
                    Cancel
                </button>
                <button type="submit" class="px-4 py-2 text-sm font-medium text-white bg-red-600 rounded-full hover:bg-red-700 transition duration-150">
                    Yes, Delete
                </button>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
    const deleteModal = document.getElementById('deleteModal');
    const deleteForm = document.getElementById('deleteForm');
    const modalTopicName = document.getElementById('modalTopicName');

    const baseUrl = '{{ route('admin.schedules.destroy', 0) }}';
    
    function openModal(id, topic) {
        const finalUrl = baseUrl.replace('/0', '/' + id);
        
        deleteForm.action = finalUrl;
        modalTopicName.textContent = topic;
        
        deleteModal.classList.remove('hidden');
        deleteModal.classList.add('flex');
    }

    function closeModal() {
        deleteModal.classList.add('hidden');
        deleteModal.classList.remove('flex');
    }

    document.querySelectorAll('.delete-button').forEach(button => {
        button.addEventListener('click', function() {
            const materialId = this.getAttribute('data-id');
            const topicName = this.getAttribute('data-topic');
            openModal(materialId, topicName);
        });
    });

    deleteModal.addEventListener('click', function(e) {
        if (e.target === deleteModal) {
            closeModal();
        }
    });
</script>
@endpush
