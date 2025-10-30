@extends('layouts.app')

@section('content')
<section class="w-full bg-primary text-white px-4 py-10">
    <div class="w-full max-w-screen-xl mx-auto pb-10">
        <!-- HEADER -->
        <div class="flex flex-col lg:flex-row justify-between gap-5 lg:items-end border-b border-white/10">
            <!-- LEFT SECTION -->
            <div class="flex items-center gap-3">
                <img src="/frontend/assets/images/student-profile-vector.svg" alt="Tutor Avatar" class="w-28" />
                <div>
                    <span class="text-gray-250">Tutor Dashboard</span>
                    <h1 class="text-4xl font-bold tracking-tight text-white">Welcome Back!</h1>
                </div>
            </div>
            <!-- RIGHT SECTION - BREADCRUMB -->
            <div class="flex items-center gap-1 mb-3">
                <span class="text-gray-910 text-[15px] font-medium">Home</span>
                <span class="text-white text-[15px font-medium">> All Classes</span>
            </div>
        </div>

        <!-- CONTENT -->
        <div class="space-y-10 divide-y divide-zinc-700">
            <!-- ALL CLASSES -->
            <div class="pt-10 grid grid-cols-3">
                <!-- BACK -->
                <div class="col-span-3 flex items-center gap-10 mb-10">
                    <a href="{{ route('tutor.dashboard.subject', $selectedSubject->slug) }}"
                        class="bg-white rounded-full w-8 h-8 flex items-center justify-center cursor-pointer">
                        <img src="/frontend/assets/icons/arrow-left.svg" alt="Icon" class="size-4">
                    </a>
                    <h6 class="text-[20px] text-gray-75 font-semibold">All Classes</h6>
                </div>

                <!-- ALL CLASSES CONTENT -->
                <div class="col-span-3 lg:col-span-1">
                    <div class="flex flex-col lg:flex-row items-center gap-8">
                        <div class="w-full lg:w-[150px]">
                            <img src="{{ $user->avatar_url ?? '/frontend/assets/images/default-avatar.png' }}" alt="Image"
                                class="w-full h-auto lg:h-[130px] rounded-[13px] object-cover" />
                        </div>
                        <div class="flex flex-col justify-between w-full h0full">
                            <span class="font-bebas pb-3">{{ $selectedSubject->name ?? 'Subject' }}</span>
                            <div class="w-full">
                                <label for="form"
                                    class="block mb-2 text-[15px] font-medium text-gray-200 mb-1">Form</label>
                                <div class="relative">
                                    <form id="formFilter" method="GET" class="flex flex-col lg:flex-row items-center gap-3 w-full lg:w-auto">
                                        <select name="form_id" 
                                            class="appearance-none pr-10 bg-gray-1000 border border-gray-950 text-gray-75 placeholder:text-gray-500 rounded-[14px] w-full px-4 py-3"
                                            onchange="document.getElementById('formFilter').submit()">
                                            <option value="">All Forms</option>
                                            @foreach($forms as $form)
                                                <option value="{{ $form->id }}" @selected(request('form_id') == $form->id)>{{ $form->name }}</option>
                                            @endforeach
                                        </select>
                                    </form>
                                    <div
                                        class="pointer-events-none absolute right-5 top-1/2 transform -translate-y-1/2 text-white">
                                        <img src="/frontend/assets/icons/angle-down.svg" alt="Icon">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- CLASS LIST -->
            <div class="w-full pt-10 grid grid-cols-3 gap-8">
                <div class="col-span-3">
                    <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-5">
                        <div class="flex flex-col">
                            <h6 class="text-[15px] text-gray-75 font-semibold">Classes Lists</h6>
                            <span class="text-gray-910">
                                {{ $classes->sum(fn($class) => $class->schedules->count()) }} Schedules
                            </span>
                        </div>

                        <!-- SEARCH -->
                        <form method="GET">
                            <div class="flex items-center w-full lg:w-[350px] bg-white border border-gray-280 rounded-full px-2 py-2">
                                <button type="submit"
                                    class="flex items-center justify-center w-8 h-8 rounded-full bg-black text-white mr-3">
                                    <img src="/frontend/assets/icons/search.svg" alt="Icon">
                                </button>
                                <input type="text" name="search" placeholder="Search classes or topics"
                                class="lex-1 bg-transparent text-gray-700 placeholder:text-[#A6A1A1] focus:outline-none"
                                value="{{ request('search') }}">
                            </div>
                        </form>
                    </div>
                </div>

                <div class="col-span-3">
                    <!-- START : TABLE -->
                    <div class="overflow-x-auto border border-[#424242] rounded-[12px] mb-10">
                        <table
                            class="min-w-full border border-[#424242] rounded-[12px] overflow-hidden text-sm text-left">
                            <thead class="bg-gray-800 text-gray-200">
                                <tr>
                                    <th class="p-4 border border-[#424242]">No</th>
                                    <th class="p-4 border border-[#424242]">Group Name</th>
                                    <th class="p-4 border border-[#424242]">Form</th>
                                    <th class="p-4 border border-[#424242]">Total Students</th>
                                    <th class="p-4 border border-[#424242]">Latest Topic</th>
                                    <th class="p-4 border border-[#424242]">Replay Video Upload Date</th>
                                    <th class="p-4 border border-[#424242]">Notes Upload Date</th>
                                    <th class="p-4 border border-[#424242]">Quizzes Upload Date</th>
                                    <th class="p-4 border border-[#424242]">Latest Live URL</th>
                                    <th class="p-4 border border-[#424242]">Action</th>
                                </tr>
                            </thead>
                            <tbody class="bg-gray-900 divide-y divide-gray-800 text-gray-50">
                                @forelse($classes as $class)
                                    <tr class="odd:bg-[#141414] even:bg-[#171717]">
                                        <td class="p-4 border border-gray-700">{{ $loop->iteration }}</td>
                                        <td class="p-4 border border-gray-700">{{ $class->name }}</td>
                                        <td class="p-4 border border-gray-700">{{ $class->latestSchedule?->form->name ?? '-' }}</td>
                                        <td class="p-4 border border-gray-700">#</td>
                                        <td class="p-4 border border-gray-700">{{ $class->latestSchedule?->topic ?? '-' }}</td>
                                        <td class="p-4 border border-gray-700">{{ $class->latestSchedule?->latestReplay?->created_at?->format('d M Y') ?? '-' }}</td>
                                        <td class="p-4 border border-gray-700">{{ $class->latestSchedule?->latestMaterial?->created_at?->format('d M Y') ?? '-' }}</td>
                                        <td class="p-4 border border-gray-700">{{ $class->latestSchedule?->latestQuiz?->publish_date?->format('d M Y') ?? '-' }}</td>
                                        <td class="p-4 border border-gray-700">
                                            <a href="{{ $class->latestSchedule?->zoom_join_url }}" target="_blank" class="text-blue-400 underline">
                                                Join Meeting
                                            </a>
                                        </td>
                                        <td class="p-4 border border-gray-700">
                                            <a href="{{ route('tutor.class.show', ['classroom' => $class->id, 'form_id' => request('form_id')]) }}"
                                            class="block bg-gray-50 hover:bg-gray-200 rounded-full text-center text-sm px-4 py-2 cursor-pointer w-full text-black text-[16px] font-semibold">
                                                View
                                            </a>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="10" class="p-4 text-center text-gray-400">No schedules found.</td>
                                    </tr>
                                @endforelse

                            </tbody>
                        </table>
                    </div>

                   <div class="flex items-center justify-center lg:justify-end gap-3 mt-5">
                    @if ($classes->onFirstPage())
                        <button
                            class="bg-white rounded-full w-10 h-10 flex items-center justify-center cursor-not-allowed opacity-50">
                            <img src="/frontend/assets/icons/angle-left.svg" alt="Prev" class="size-3">
                        </button>
                    @else
                        <a href="{{ $classes->previousPageUrl() }}"
                            class="bg-white rounded-full w-10 h-10 flex items-center justify-center">
                            <img src="/frontend/assets/icons/angle-left.svg" alt="Prev" class="size-3">
                        </a>
                    @endif

                    <div class="bg-gray-1000 border border-gray-950 text-gray-75 rounded-[14px] px-4 py-2 text-sm">
                        Page {{ $classes->currentPage() }} of {{ $classes->lastPage() }}
                    </div>

                    @if ($classes->hasMorePages())
                        <a href="{{ $classes->nextPageUrl() }}"
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
    </div>
</section>
@endsection