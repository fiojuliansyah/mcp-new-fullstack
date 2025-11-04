@extends('layouts.main')

@section('content')
<div class="space-y-5 py-5 px-10">
    <!-- PAGE TITLE -->
    <div class="flex flex-col md:flex-row md:items-center justify-between">
        <h1 class="text-gray-100 font-[600] text-lg">Support Tickets</h1>
        <form method="GET" class="mt-3 md:mt-0">
            <input type="text" name="search" value="{{ $search }}" placeholder="Search ticket..."
                class="bg-[#1A1A1A] border border-[#2A2A2A] text-gray-200 rounded-full px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-600">
        </form>
    </div>

    <!-- TABLE -->
    <div class="overflow-x-auto mt-6 rounded-[20px] border border-[#2A2A2A] bg-[#1A1A1A]">
        <table class="min-w-full text-sm text-left text-gray-300">
            <thead class="bg-[#111111] text-gray-400 uppercase text-xs">
                <tr>
                    <th class="px-6 py-3">Ticket #</th>
                    <th class="px-6 py-3">User</th>
                    <th class="px-6 py-3">Category</th>
                    <th class="px-6 py-3">Priority</th>
                    <th class="px-6 py-3">Assigned To</th>
                    <th class="px-6 py-3">Status</th>
                    <th class="px-6 py-3 text-right">Action</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($supports as $support)
                    <tr class="border-b border-[#2A2A2A] hover:bg-[#222] transition">
                        <td class="px-6 py-4">{{ $support->ticket_number }}</td>
                        <td class="px-6 py-4">{{ $support->user->name ?? '-' }}</td>
                        <td class="px-6 py-4">{{ ucfirst($support->category) ?? '-' }}</td>
                        <td class="px-6 py-4">
                            <span class="px-3 py-1 rounded-full text-xs font-semibold
                                @if($support->priority === 'high') bg-red-600 text-white
                                @elseif($support->priority === 'medium') bg-yellow-600 text-white
                                @else bg-gray-600 text-white @endif">
                                {{ ucfirst($support->priority ?? '-') }}
                            </span>
                        </td>
                        <td class="px-6 py-4">{{ $support->assignedTo->name ?? '—' }}</td>
                        <td class="px-6 py-4">
                            <span class="px-3 py-1 rounded-full text-xs font-semibold
                                @if($support->status === 'open') bg-green-600 text-white
                                @elseif($support->status === 'closed') bg-gray-600 text-white
                                @elseif($support->status === 'pending') bg-yellow-600 text-white
                                @else bg-gray-500 text-white @endif">
                                {{ ucfirst($support->status ?? '-') }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-right">
                            <a href="{{ route('admin.supports.show', $support->id) }}"
                               class="text-blue-400 hover:underline">View</a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="text-center py-6 text-gray-500">No tickets found.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- PAGINATION -->
    <div class="mt-5">
        {{ $supports->links() }}
    </div>
</div>
@endsection
