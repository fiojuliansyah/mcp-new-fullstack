@extends('layouts.app')

@section('content')
    <section class="w-full bg-primary text-white px-4 py-10">
        <div class="w-full max-w-screen-xl mx-auto pb-10">
            <!-- HEADER -->
            <div class="flex flex-col lg:flex-row justify-between gap-5 lg:items-end border-b border-white/10">
                <div class="flex items-center gap-3">
                    <img src="/frontend/assets/images/student-profile-vector.svg" alt="Tutor Avatar" class="w-28" />
                    <div>
                        <span class="text-gray-250">Tutor Dashboard</span>
                        <h1 class="text-4xl font-bold tracking-tight text-white">Welcome Back!</h1>
                    </div>
                </div>
                <div class="flex items-center gap-1 mb-3">
                    <span class="text-gray-910 text-[15px] font-medium">Home</span>
                    <span class="text-white text-[15px] font-medium">&gt; Students Performance</span>
                </div>
            </div>

            <!-- CONTENT -->
            <div class="space-y-10 divide-y divide-zinc-700">
                <!-- STUDENTS PERFORMANCE -->
                <div class="pt-10 grid grid-cols-3">
                    <div class="col-span-3 flex items-center gap-10 mb-10">
                        <a href="{{ route('tutor.dashboard.subject', $selectedSubject->slug) }}"
                            class="bg-white rounded-full w-8 h-8 flex items-center justify-center cursor-pointer">
                            <img src="/frontend/assets/icons/arrow-left.svg" alt="Icon" class="size-4">
                        </a>
                        <h6 class="text-[20px] text-gray-75 font-semibold">Students Performance</h6>
                    </div>

                    <div class="col-span-3">
                        <div class="flex flex-col lg:flex-row items-center gap-8">
                            <div class="w-full lg:w-[150px]">
                                <img src="{{ $tutor->avatar_url ?? '/frontend/assets/images/default-avatar.png' }}"
                                    alt="Image" class="w-full h-auto lg:h-[130px] rounded-[13px] object-cover" />
                            </div>
                            <div class="flex flex-col justify-between w-full">
                                <span class="font-bebas pb-3">{{ $selectedSubject->name }}</span>

                                <!-- FILTERS -->
                                <form method="GET"
                                    action="{{ route('tutor.overview.performance.index', $selectedSubject->slug) }}"
                                    class="flex flex-col lg:flex-row gap-5">
                                    <div class="w-full flex-1">
                                        <label class="block mb-2 text-[15px] font-medium text-gray-200 mb-1">Form</label>
                                        <div class="relative">
                                            <select name="form_id"
                                                class="appearance-none pr-10 bg-gray-1000 border border-gray-950 text-gray-75 rounded-[14px] w-full px-4 py-3"
                                                onchange="this.form.submit()">
                                                <option value="">All forms</option>
                                                @foreach ($forms as $form)
                                                    <option value="{{ $form->id }}"
                                                        {{ request('form_id') == $form->id ? 'selected' : '' }}>
                                                        {{ $form->name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                            <div
                                                class="pointer-events-none absolute right-5 top-1/2 transform -translate-y-1/2 text-white">
                                                <img src="/frontend/assets/icons/angle-down.svg" alt="Icon">
                                            </div>
                                        </div>
                                    </div>

                                    <div class="w-full flex-1">
                                        <label class="block mb-2 text-[15px] font-medium text-gray-200 mb-1">Class
                                            Group</label>
                                        <div class="relative">
                                            <select name="classroom_id"
                                                class="appearance-none pr-10 bg-gray-1000 border border-gray-950 text-gray-75 rounded-[14px] w-full px-4 py-3"
                                                onchange="this.form.submit()">
                                                <option value="">All groups</option>
                                                @foreach ($schedules->pluck('classroom')->filter()->unique('name') as $classroom)
                                                    <option value="{{ $classroom->id }}"
                                                        {{ request('classroom_id') == $classroom->id ? 'selected' : '' }}>
                                                        {{ $classroom->name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                            <div
                                                class="pointer-events-none absolute right-5 top-1/2 transform -translate-y-1/2 text-white">
                                                <img src="/frontend/assets/icons/angle-down.svg" alt="Icon">
                                            </div>
                                        </div>
                                    </div>

                                    <div class="w-full flex-1">
                                        <label class="block mb-2 text-[15px] font-medium text-gray-200 mb-1">Performance
                                            Type</label>
                                        <div class="relative">
                                            <select name="type"
                                                class="appearance-none pr-10 bg-gray-1000 border border-gray-950 text-gray-75 rounded-[14px] w-full px-4 py-3"
                                                onchange="this.form.submit()">
                                                <option value="">All</option>
                                                <option value="good" {{ request('type') == 'good' ? 'selected' : '' }}>
                                                    Good Progress</option>
                                                <option value="needs" {{ request('type') == 'needs' ? 'selected' : '' }}>
                                                    Needs Practice</option>
                                            </select>
                                            <div
                                                class="pointer-events-none absolute right-5 top-1/2 transform -translate-y-1/2 text-white">
                                                <img src="/frontend/assets/icons/angle-down.svg" alt="Icon">
                                            </div>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- STUDENT LIST -->
                <div class="w-full pt-10 grid grid-cols-3 gap-8">
                    <div class="col-span-3">
                        <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-5">
                            <div class="flex flex-col">
                                <h6 class="text-[15px] text-gray-75 font-semibold">Students List</h6>
                                <span class="text-gray-910">{{ count($paginatedStudents) }} Students</span>
                            </div>

                            <!-- SEARCH -->
                            <form method="GET"
                                action="{{ route('tutor.overview.performance.index', $selectedSubject->slug) }}"
                                class="flex w-full lg:w-[350px]">
                                <div
                                    class="flex items-center w-full bg-white border border-gray-280 rounded-full px-2 py-2">
                                    <div
                                        class="flex items-center justify-center w-8 h-8 rounded-full bg-black text-white mr-3">
                                        <img src="/frontend/assets/icons/search.svg" alt="Icon">
                                    </div>
                                    <input type="text" name="search" value="{{ request('search') }}"
                                        class="flex-1 bg-transparent text-gray-700 placeholder:text-[#A6A1A1] focus:outline-none"
                                        placeholder="Search student..." onchange="this.form.submit()" />
                                </div>

                                <input type="hidden" name="form_id" value="{{ request('form_id') }}">
                                <input type="hidden" name="classroom_id" value="{{ request('classroom_id') }}">
                                <input type="hidden" name="type" value="{{ request('type') }}">
                            </form>
                        </div>
                    </div>

                    <div class="col-span-3">
                        <div class="overflow-x-auto border border-[#424242] rounded-[12px] mb-10">
                            <table id="studentTable"
                                class="min-w-full border border-[#424242] rounded-[12px] overflow-hidden text-sm text-left">
                                <thead class="bg-gray-800 text-gray-200">
                                    <tr>
                                        <th class="p-4 border border-[#424242]">No</th>
                                        <th class="p-4 border border-[#424242]">Student Name</th>
                                        <th class="p-4 border border-[#424242]">Average Quiz Score</th>
                                        <th class="p-4 border border-[#424242]">Attendance Rate</th>
                                        <th class="p-4 border border-[#424242]">Replay View Rate</th>
                                        <th class="p-4 border border-[#424242]">Notes / Remarks</th>
                                        <th class="p-4 border border-[#424242]">Action</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-gray-900 divide-y divide-gray-800 text-gray-50">
                                    @foreach ($paginatedStudents as $index => $student)
                                        <tr class="student-row {{ $loop->odd ? 'bg-[#141414]' : 'bg-[#171717]' }}"
                                            data-name="{{ strtolower($student['user']->name) }}"
                                            data-performance="{{ strtolower(str_contains($student['notes'], 'good') ? 'good' : 'needs') }}"
                                            data-form="form {{ rand(1, 3) }}"
                                            data-group="{{ rand(0, 1) ? 'A' : 'B' }}">
                                            <td class="p-4 border border-gray-700">{{ $index + 1 }}</td>
                                            <td class="p-4 border border-gray-700">{{ $student['user']->name }}</td>
                                            <td class="p-4 border border-gray-700">{{ $student['avgScore'] }}%</td>
                                            <td class="p-4 border border-gray-700 text-green-400">
                                                {{ $student['attendanceRate'] }}%</td>
                                            <td class="p-4 border border-gray-700 text-blue-400">
                                                {{ $student['replayRate'] }}%</td>
                                            <td class="p-4 border border-gray-700">{{ $student['notes'] }}</td>
                                            <td class="p-4 border border-gray-700">
                                                <a href="{{ route('tutor.overview.performance.show', [$selectedSubject->slug, $student['user']->id]) }}"
                                                    class="block bg-gray-50 hover:bg-gray-200 rounded-full text-center text-sm px-8 py-3 cursor-pointer w-full text-black text-[16px] font-semibold">
                                                    View
                                                </a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        <div class="flex items-center justify-center lg:justify-end gap-3 mt-5">
                            @if ($paginatedStudents->onFirstPage())
                                <button
                                    class="bg-white rounded-full w-10 h-10 flex items-center justify-center cursor-not-allowed opacity-50">
                                    <img src="/frontend/assets/icons/angle-left.svg" alt="Prev" class="size-3">
                                </button>
                            @else
                                <a href="{{ $paginatedStudents->previousPageUrl() }}"
                                    class="bg-white rounded-full w-10 h-10 flex items-center justify-center">
                                    <img src="/frontend/assets/icons/angle-left.svg" alt="Prev" class="size-3">
                                </a>
                            @endif

                            <div class="bg-gray-1000 border border-gray-950 text-gray-75 rounded-[14px] px-4 py-2 text-sm">
                                Page {{ $paginatedStudents->currentPage() }} of {{ $paginatedStudents->lastPage() }}
                            </div>

                            @if ($paginatedStudents->hasMorePages())
                                <a href="{{ $paginatedStudents->nextPageUrl() }}"
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
        </div>
    </section>
@endsection
