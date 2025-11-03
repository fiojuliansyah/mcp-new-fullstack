@extends('layouts.app')

@section('content')
    <section class="w-full bg-primary text-white px-4 py-10">
        <div class="w-full max-w-screen-xl mx-auto">
            <!-- HEADER -->
            <div class="flex flex-col lg:flex-row justify-between gap-5 lg:items-end border-b border-white/10">
                <div class="flex items-center gap-3">
                    <img src="/frontend/assets/images/student-profile-vector.svg" alt="Tutor Avatar" class="w-28" />
                    <div>
                        <span class="text-gray-250">Student</span>
                        <h1 class="text-4xl font-bold tracking-tight text-white">Quiz</h1>
                    </div>
                </div>
                <div class="flex items-center gap-1 mb-3">
                    <span class="text-gray-910 text-[15px] font-medium">Student Log</span>
                    <span class="text-white text-[15px font-medium">> My Subject</span>
                </div>
            </div>

            <!-- CONTENT -->
            <div class="space-y-10 divide-y divide-zinc-700">
                <div class="pt-10">
                    <!-- HEADING -->
                    <div class="flex items-center gap-3 mb-6">
                        <img src="/frontend/assets/icons/toga.svg" alt="Icon" class="size-6">
                        <h6 class="text-[20px] text-gray-75 font-semibold">My Subjects</h6>
                    </div>
                    <!-- CONTENT -->
                    <div class="grid grid-cols-12 gap-10">
                        <!-- LEFT SECTION -->
                        <div class="col-span-12 lg:col-span-4">
                            <div class="flex items-center justify-between px-3 mb-4">
                                <span class="text-[15px] text-gray-250">Live Classes</span>
                                <div class="text-[15px] text-gray-250">
                                    {{ $today->format('d M Y') }}
                                </div>
                            </div>
                            @if ($schedulesToday->count())
                                @foreach ($schedulesToday as $schedule)
                                    <div class="grid grid-cols-12 gap-5 border border-white rounded-[21px] p-5 mb-5">
                                        <div class="col-span-12 lg:col-span-5">
                                            <div
                                                class="relative h-full lg:h-[170px] w-full lg:w-[140px] rounded-[13px] overflow-hidden">
                                                <img src="{{ $classroom->user->avatar_url ?? '/frontend/assets/images/sample/image-1.png' }}"
                                                    alt="Image" class="h-full w-full object-cover rounded-[13px]">
                                                <div
                                                    class="absolute w-full flex items-center gap-1 rounded-b-[13px] bg-green-100 bottom-0 py-2 px-3">
                                                    <img src="/frontend/assets/icons/clock.svg" alt="Icon"
                                                        class="size-4">
                                                    <span class="text-white text-[11px]">
                                                        {{ \Carbon\Carbon::parse($schedule->time)->format('d M · h:i A') }}
                                                    </span>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-span-12 lg:col-span-7 flex flex-col justify-between">
                                            <div class="flex flex-col mb-3">
                                                <span
                                                    class="text-white uppercase text-[13px] font-bebas">{{ $classroom->name }}</span>
                                                <span
                                                    class="text-white uppercase text-[22px] font-bebas">{{ $schedule->topic }}</span>
                                                <span
                                                    class="text-white text-[15px]">{{ $classroom->user->name ?? 'Unknown' }}</span>
                                            </div>
                                         @php
                                                $firstMaterial = $schedule->materials->first() ?? null;
                                                
                                                $firstFile = null;
                                                if ($firstMaterial) {
                                                    $firstFile = $firstMaterial->materialFiles->first() ?? null;
                                                }
                                            @endphp

                                            @if ($firstFile)
                                                <a href="{{ Storage::url($firstFile->file_url) }}" download
                                                    class="hover:bg-white text-white hover:text-black border border-white rounded-full text-sm px-5 py-3 text-center">
                                                    <span class="text-[16px] font-semibold">
                                                        Download {{ $firstFile->title ?? 'Note' }}
                                                    </span>
                                                </a>
                                            @else
                                                <span class="text-gray-400">No files available</span>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="flex flex-col flex-1">
                                        <a href="{{ route('student.schedule.class', $schedule->id) }}"
                                            class="bg-gray-50 hover:bg-gray-200 rounded-full text-sm px-5 py-3 w-full mt-auto cursor-pointer text-center">
                                            <span class="text-black text-[16px] font-semibold">Join Zoom</span>
                                        </a>
                                    </div>
                                @endforeach
                            @else
                                <div class="text-gray-400 text-center py-10">
                                    No live classes scheduled for today.
                                </div>
                            @endif

                        </div>
                        <!-- RIGHT SECTION -->
                        <div class="col-span-12 lg:col-span-8">
                            <div class="grid grid-cols-12 gap-5 bg-gray-800 rounded-[21px] p-6">
                                <div class="col-span-12 lg:col-span-5">
                                    <div class="flex items-center gap-3 mb-6">
                                        <img src="/frontend/assets/icons/calendar.svg" alt="Icon" class="size-6">
                                        <h6 class="text-[20px] text-gray-100 font-semibold">Calendar</h6>
                                    </div>

                                    <div class="rounded-[21px] border border-black w-auto mb-5">
                                        <div class="px-5 py-3 rounded-t-[21px] bg-black">
                                            <span class="text-white text-[15px]">Class Status</span>
                                        </div>
                                        <div class="grid grid-cols-12 p-5 gap-5">
                                            <div class="col-span-6 flex items-center gap-3">
                                                <div class="w-5 h-5 bg-green-100 rounded-full"></div>
                                                <span class="text-green-100 text-[15px]">Live</span>
                                            </div>
                                            <div class="col-span-6 flex items-center gap-3">
                                                <div class="w-5 h-5 bg-blue-200 rounded-full"></div>
                                                <span class="text-blue-200 text-[15px]">Upcoming</span>
                                            </div>
                                            <div class="col-span-6 flex items-center gap-3">
                                                <div class="w-5 h-5 bg-white rounded-full"></div>
                                                <span class="text-white text-[15px]">Completed</span>
                                            </div>
                                            <div class="col-span-6 flex items-center gap-3">
                                                <div class="w-5 h-5 bg-red-100 rounded-full"></div>
                                                <span class="text-red-100 text-[15px]">Cancelled</span>
                                            </div>
                                            <div class="col-span-6 flex items-center gap-3">
                                                <div class="w-5 h-5 bg-[#424242] rounded-full"></div>
                                                <span class="text-[#424242] text-[15px]">No Class</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-span-12 lg:col-span-7">
                                    <div id="calendar" class="h-[13rem] rounded-[21px] bg-gray-900 p-3"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="pt-10">
                    <div class="grid grid-cols-12">
                        <div class="col-span-12">
                            <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-5 mb-6">
                                <div class="flex items-center gap-3">
                                    <img src="/frontend/assets/icons/upcoming-classes.svg" alt="Icon" class="size-6">
                                    <h6 class="text-[20px] text-gray-75 font-semibold">Upcoming Class</h6>
                                </div>
                            </div>

                            <div class="grid grid-cols-12 gap-5">
                                @php
                                    $statusColors = [
                                        'live' => '#22c55e',
                                        'scheduled' => '#17EFD9',
                                        'replacement' => '#facc15',
                                        'cancelled' => '#f87171',
                                        'completed' => '#ffffff',
                                    ];
                                @endphp

                                @foreach ($schedules as $schedule)
                                        <a href="{{ route('student.classrooms.show', ['classroom' => $schedule->classroom->id]) }}?topic={{ urlencode($schedule->topic) }}"
                                            class="col-span-12 lg:col-span-2 bg-gray-900 rounded-[21px] text-white">
                                            <div class="relative flex justify-center p-2">
                                                <img src="{{ $schedule->classroom->user->avatar_url ?? '/frontend/assets/images/sample/image-1.png' }}" 
                                                    alt="Image"
                                                    class="w-full object-cover lg:w-[225px] h-full lg:h-[192px] rounded-[13px]">

                                                <div class="absolute inset-0 rounded-[13px] bg-gradient-to-t from-black via-transparent to-transparent"></div>

                                                <div class="absolute bottom-2 left-2 text-white p-2">
                                                    <div class="text-sm font-semibold shadow-lg">
                                                        {{ $schedule->classroom->subject->name ?? 'No Subject' }}
                                                    </div>
                                                    <div class="text-xl font-bold shadow-lg">
                                                        {{ $schedule->topic ?? 'No Topic' }}
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="px-4 py-3 flex flex-col">
                                                <span class="text-gray-200 text-[12px]">{{ $schedule->form->name }}</span>
                                                <span class="text-white text-[15px]">{{ $schedule->classroom->user->name }}</span>
                                            </div>
                                            <div class="w-full flex items-center gap-3 rounded-b-[21px] bg-gray-800 bottom-0 p-4" style="background-color: {{ $statusColors[$schedule->status] ?? '#ffffff' }}">
                                                <img src="/frontend/assets/icons/clock.svg" alt="Icon" class="size-4">
                                                <span class="text-black text-[12px]">{{ \Carbon\Carbon::parse($schedule->time)->format('d M · h:i A') }}</span>
                                            </div>
                                        </a>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>

                <div class="pt-10">
                    <div class="grid grid-cols-12">
                        <div class="col-span-12">
                            <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-5 mb-6">
                                <div class="flex items-center gap-3">
                                    <img src="/frontend/assets/icons/replay.svg" alt="Icon" class="size-6">
                                    <h6 class="text-[20px] text-gray-75 font-semibold">Replay Class</h6>
                                </div>
                            </div>

                            <form method="GET" class="flex flex-col lg:flex-row lg:items-center justify-between gap-5 mb-6">
                                <div class="flex flex-col lg:flex-row lg:items-center gap-3">
                                    <span class="text-gray-200">Filter By Topic:</span>
                                    <select name="topic"
                                            class="bg-gray-1000 border border-gray-950 text-white rounded-[14px] px-4 py-3"
                                            onchange="this.form.submit()">
                                        <option value="">All</option>
                                        @foreach($schedules->pluck('topic')->unique() as $topic)
                                            <option value="{{ $topic }}" {{ ($topicFilter ?? '') == $topic ? 'selected' : '' }}>{{ $topic }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="flex items-center gap-3">
                                    <input type="text" name="search" placeholder="Search topic..."
                                        class="bg-gray-1000 border border-gray-950 text-white rounded-[14px] px-4 py-3"
                                        value="{{ $search ?? '' }}">
                                    <button type="submit" class="bg-gray-50 hover:bg-gray-200 text-black rounded-full px-5 py-3 font-semibold">
                                        Search
                                    </button>
                                </div>

                                <div class="flex flex-col lg:flex-row lg:items-center gap-3">
                                    <span class="text-gray-200">Sort By:</span>
                                    <select name="sort" onchange="this.form.submit()"
                                            class="bg-gray-1000 border border-gray-950 text-white rounded-[14px] px-4 py-3">
                                        <option value="latest" {{ ($sort ?? '') == 'latest' ? 'selected' : '' }}>Latest</option>
                                        <option value="oldest" {{ ($sort ?? '') == 'oldest' ? 'selected' : '' }}>Oldest</option>
                                    </select>
                                </div>
                            </form>


                            <div class="grid grid-cols-12 gap-5">
                                @foreach ($replaySchedules as $schedule)
                                    @foreach ($schedule->replays as $replay)
                                        <a href="{{ route('student.classrooms.show', ['classroom' => $schedule->classroom->id]) }}?topic={{ urlencode($schedule->topic) }}"
                                        class="col-span-12 lg:col-span-2 bg-gray-900 rounded-[21px] text-white">
                                            <div class="relative flex justify-center p-2">
                                                <img src="{{ $schedule->classroom->user->avatar_url ?? '/frontend/assets/images/sample/image-1.png' }}" 
                                                    alt="Image"
                                                    class="w-full object-cover lg:w-[225px] h-full lg:h-[192px] rounded-[13px]">

                                                <div class="absolute inset-0 rounded-[13px] bg-gradient-to-t from-black via-transparent to-transparent"></div>

                                                <div class="absolute bottom-2 left-2 text-white p-2">
                                                    <div class="text-sm font-semibold shadow-lg">
                                                        {{ $schedule->classroom->subject->name ?? 'No Subject' }}
                                                    </div>
                                                    <div class="text-xl font-bold shadow-lg">
                                                        {{ $schedule->topic ?? 'No Topic' }}
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="px-4 py-3 flex flex-col">
                                                <span class="text-gray-200 text-[12px]">{{ $schedule->form->name }}</span>
                                                <span class="text-white text-[15px]">{{ $schedule->classroom->user->name }}</span>
                                            </div>
                                            <div class="w-full flex items-center gap-3 rounded-b-[21px] bg-gray-800 bottom-0 p-4">
                                                <img src="/frontend/assets/icons/clock.svg" alt="Icon" class="size-4">
                                                <span class="text-white text-[12px]">{{ \Carbon\Carbon::parse($schedule->time)->format('d M · h:i A') }}</span>
                                            </div>
                                        </a>
                                    @endforeach
                                @endforeach
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

@push('scripts')
    <link href="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.9/index.global.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.9/index.global.min.js"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const calendarEl = document.getElementById('calendar');

            const dateStatusMap = {
                @foreach ($subscriptions as $subscription)
                    @foreach ($subscription->classrooms as $classroom)
                        @foreach ($classroom->schedules as $schedule)
                            "{{ \Carbon\Carbon::parse($schedule->time)->toDateString() }}": "{{ strtolower($schedule->status ?? 'scheduled') }}",
                        @endforeach
                    @endforeach
                @endforeach
            };

            const statusColors = {
                'live': '#22c55e',
                'scheduled': '#17EFD9',
                'replacement': '#facc15',
                'cancelled': '#f87171',
                'completed': '#ffffff'
            };

            const calendar = new FullCalendar.Calendar(calendarEl, {
                initialView: 'dayGridMonth',
                themeSystem: 'standard',
                timeZone: 'Asia/Jakarta',
                locale: 'id',
                height: '20rem',
                headerToolbar: {
                    left: 'prev,next',
                    center: 'title',
                    right: ''
                },
                dayCellDidMount: function(info) {
                    const dateStr = info.date.toISOString().split('T')[0];
                    const status = dateStatusMap[dateStr];
                    if (status && statusColors[status]) {
                        const dayNumberEl = info.el.querySelector('.fc-daygrid-day-number');
                        if (dayNumberEl) {
                            dayNumberEl.style.color = statusColors[status];
                        }
                    }
                }
            });

            calendar.render();
        });
    </script>
@endpush
