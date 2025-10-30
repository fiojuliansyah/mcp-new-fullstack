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
                        <h1 class="text-4xl font-bold tracking-tight text-white">Subject Enrollment</h1>
                    </div>
                </div>
                <div class="flex items-center gap-1 mb-3">
                    <span class="text-gray-910 text-[15px] font-medium">My Profile </span>
                    <span class="text-white text-[15px font-medium">> Subject Enrolment</span>
                </div>
            </div>

            <!-- CONTENT -->
            <div class="space-y-10 divide-y divide-zinc-700">
                <div class="pt-10">
                    <div class="grid grid-cols-12 gap-5 lg:pb-5">
                        <div class="col-span-12 md:col-span-12 lg:col-span-8 lg:pr-5 lg:border-r border-gray-510">
                            <!-- Add Math -->
                            <section class="mb-10">
                                <!-- Title -->
                                <div class="flex items-center gap-3 mb-6">
                                    <img src="/frontend/assets/icons//toga.svg" alt="Icon" class="size-6">
                                    <h6 class="text-[20px] text-gray-75 font-semibold">{{ $classroom->subject->name }}</h6>
                                </div>
                                <!-- Filter -->
                                <form method="GET" action="{{ route('student.classrooms.show', $classroom->id) }}">
                                    <div class="flex flex-col lg:flex-row lg:items-center lg:justify-end gap-3 w-full mb-8">
                                        <span class="text-gray-200 min-w-[150px]">Filter By Topic:</span>
                                        <div class="w-full">
                                            <select name="topic"
                                                class="bg-gray-1000 border border-gray-950 text-white placeholder:text-gray-500 rounded-[14px] block w-full px-4 py-3"
                                                onchange="this.form.submit()">
                                                @foreach ($classroom->schedules->pluck('topic')->unique() as $topic)
                                                    <option value="{{ $topic }}"
                                                        {{ $topicFilter == $topic ? 'selected' : '' }}>{{ $topic }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                </form>
                                <!-- Content -->
                                @forelse ($schedulesToday as $schedule)
                                    <div class="flex gap-8 border border-white rounded-[21px] p-8">
                                        <div class="relative h-[170px] w-[200px] lg:w-[150px] rounded-[13px]">
                                            <img src="{{ $classroom->user->avatar_url ?? '/frontend/assets/images/sample/image-1.png' }}"
                                                alt="Image" class="h-full w-full rounded-[13px] object-cover">
                                            <div
                                                class="absolute w-full flex items-center gap-3 rounded-b-[13px] bg-gray-800 bottom-0 py-2 px-2">
                                                <img src="/frontend/assets/icons/clock.svg" alt="Icon" class="size-4">
                                                <span
                                                    class="text-white text-[12px]">{{ \Carbon\Carbon::parse($schedule->time)->format('d M · h:i A') }}</span>
                                            </div>
                                        </div>
                                        <div class="flex flex-col justify-between w-full">
                                            <div class="flex flex-col">
                                                @php
                                                    $statusColors = [
                                                        'live' =>
                                                            'inline-flex items-center justify-center bg-[#115206] px-2 py-2 font-medium text-[#29B700] rounded-full',
                                                        'completed' =>
                                                            'inline-flex items-center justify-center bg-[#424242] px-2 py-2 font-medium text-[#FFFFFF] rounded-full',
                                                        'scheduled' =>
                                                            'inline-flex items-center justify-center bg-[#065052] px-2 py-2 font-medium text-[#17EFD9] rounded-full',
                                                        'replacement' =>
                                                            'inline-flex items-center justify-center bg-[#523E06] px-2 py-2 font-medium text-yellow-200 rounded-full',
                                                        'cancelled' =>
                                                            'inline-flex items-center justify-center bg-[#520606] px-2 py-2 font-medium text-[#ba0000] rounded-full',
                                                    ];

                                                    $status = strtolower($schedule->status);
                                                    $bgClass = $statusColors[$status] ?? 'bg-gray-800 text-white';

                                                    switch ($status) {
                                                        case 'live':
                                                            $statusText = 'This class is live now. Join immediately!';
                                                            break;
                                                        case 'completed':
                                                            $statusText = 'This class has ended. Watch the replay';
                                                            break;
                                                        case 'scheduled':
                                                            $statusText = 'This class is scheduled. Get ready!';
                                                            break;
                                                        case 'replacement':
                                                            $statusText = 'This class is a replacement session';
                                                            break;
                                                        case 'cancelled':
                                                            $statusText = 'This class has been cancelled';
                                                            break;
                                                        default:
                                                            $statusText = 'Status unknown';
                                                    }
                                                @endphp

                                                <div
                                                    class="inline-flex items-center px-4 py-2 font-medium text-[15px] rounded-[9px] mb-4 {{ $bgClass }}">
                                                    {{ $statusText }}
                                                </div>

                                                <span class="text-[#444444] text-[22px] uppercase font-bebas">
                                                    {{ $status === 'completed' ? 'Replay Class' : $schedule->topic ?? 'No Topic' }}
                                                </span>
                                            </div>

                                            <div class="flex flex-row gap-5">
                                                @php
                                                    $firstMaterial = $schedule->materials->first();
                                                    $firstFile = $firstMaterial ? $firstMaterial->materialFiles->first() : null;
                                                @endphp

                                                @if($firstFile)
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
                                    </div>
                                @empty
                                @endforelse
                            </section>

                            <section>
                                <div class="flex items-center justify-between gap-3 lg:pr-3 mb-6">
                                    <div class="flex items-center gap-3">
                                        <img src="/frontend/assets/icons//toga.svg" alt="Icon" class="size-6">
                                        <h6 class="text-[20px] text-gray-75 font-semibold">Study Notes and Reference
                                            Materials</h6>
                                    </div>
                                    @foreach ($subscriptions as $subscription)
                                        @foreach ($subscription->classrooms as $classroom)
                                            @foreach ($classroom->schedules as $schedule)
                                                @if ($schedule->materials->count())
                                                    <span class="text-[15px] text-gray-400">Total:
                                                        {{ $schedule->materials->count() }}</span>
                                                @endif
                                            @endforeach
                                        @endforeach
                                    @endforeach
                                </div>

                                <div class="flex rounded-t-[21px]">
                                    <button type="button"
                                        class="tab-btn active w-full rounded-tl-[21px] px-2 py-4 text-center cursor-pointer"
                                        data-tab="#tab1">
                                        <span class="text-[15px]">Study Notes And Reference Materials</span>
                                    </button>
                                    <button type="button"
                                        class="tab-btn w-full rounded-tr-[21px] px-2 py-4 text-center cursor-pointer"
                                        data-tab="#tab2">
                                        <span class="text-[15px]">Quizzes</span>
                                    </button>
                                </div>

                                <div id="tab1" class="tab-content bg-gray-975 rounded-b-[21px] pt-10">
                                    <div
                                        class="flex flex-col lg:flex-row justify-center gap-5 lg:gap-10 bg-[#434343] py-5 px-10">
                                        <div class="flex flex-col lg:flex-row lg:items-center lg:justify-end gap-3">
                                            <span class="text-gray-200">Filter By:</span>
                                            <div class="w-full lg:w-[300px]">
                                                <select id="filterSelect"
                                                    class="bg-gray-1000 border border-gray-950 text-white placeholder:text-gray-500 rounded-[14px] block w-full px-4 py-3">
                                                    <option value="subject">Subject</option>
                                                    <option value="day">Day</option>
                                                </select>
                                            </div>
                                        </div>
                                        <button type="button"
                                            class="bg-gray-50 hover:bg-gray-200 rounded-full text-sm px-5 py-3 w-full lg:w-[230px] cursor-pointer">
                                            <span class="text-black text-[16px] font-semibold">Download All Notes</span>
                                        </button>
                                    </div>

                                    <div class="h-[90vh] overflow-y-auto mb-5 lg:mb-0">
                                        <div class="flex flex-col py-10 px-5">
                                            @php $hasMaterial = false; @endphp
                                            @foreach ($subscriptions as $subscription)
                                                @foreach ($subscription->classrooms as $classroom)
                                                    @foreach ($classroom->schedules as $schedule)
                                                        @if ($schedule->materials->count())
                                                            @foreach ($schedule->materials as $material)
                                                                @foreach ($material->materialFiles as $file)
                                                                    @php
                                                                        $fileName = basename($file->file_url);
                                                                        $fileExt = strtoupper(
                                                                            pathinfo(
                                                                                $file->file_url,
                                                                                PATHINFO_EXTENSION,
                                                                            ),
                                                                        );
                                                                    @endphp
                                                                    <div
                                                                        class="flex items-center justify-between gap-10 border border-gray-510 rounded-[21px] py-8 px-12 mb-4">
                                                                        <div class="flex flex-col gap-2">
                                                                            <span
                                                                                class="text-white">{{ $fileName }}</span>
                                                                            <div class="w-full flex items-center gap-2">
                                                                                <img src="/frontend/assets/icons/clock.svg"
                                                                                    alt="Icon" class="size-4">
                                                                                <span class="text-gray-200 text-[12px]">
                                                                                    Class On:
                                                                                    {{ \Carbon\Carbon::parse($schedule->time)->format('d M · h:i A') }}
                                                                                    |
                                                                                    File: {{ $fileExt }}
                                                                                </span>
                                                                            </div>
                                                                        </div>
                                                                        <a href="{{ Storage::url($file->file_url) }}"
                                                                            download
                                                                            class="bg-gray-50 hover:bg-gray-200 rounded-full text-sm px-5 py-3 w-[195px] cursor-pointer text-center">
                                                                            <span
                                                                                class="text-black text-[16px] font-semibold">
                                                                                Download {{ $file->title ?? 'File' }}
                                                                            </span>
                                                                        </a>
                                                                    </div>
                                                                @endforeach
                                                            @endforeach
                                                        @endif
                                                    @endforeach
                                                @endforeach
                                            @endforeach

                                        </div>
                                    </div>
                                </div>

                                <!-- Tab Item Quizzes -->
                                <div id="tab2" class="tab-content hidden bg-gray-975 rounded-b-[21px] pt-10">

                                    <div class="h-[90vh] overflow-y-auto mb-5 lg:mb-0">
                                        <div class="flex flex-col py-10 px-5">
                                            @foreach ($subscriptions as $subscription)
                                                @foreach ($subscription->classrooms as $classroom)
                                                    @foreach ($classroom->schedules as $schedule)
                                                        @foreach ($schedule->quizzes as $quiz)
                                                            <div
                                                                class="flex items-center justify-between gap-5 py-6 px-10 border border-gray-510 rounded-[21px] mb-4">
                                                                <div>
                                                                    <div class="flex items-center gap-3 mb-3">
                                                                        <span
                                                                            class="text-[#9D9D9D] font-bold">{{ $quiz->schedule->classroom->name }}</span>
                                                                        <span class="text-green-100">Status:
                                                                            {{ ucfirst($quiz->status) }}</span>
                                                                    </div>
                                                                    <div class="flex items-center gap-5">
                                                                        <img src="/frontend/assets/icons/quiz.svg"
                                                                            alt="Icon" class="size-14">
                                                                        <div class="flex flex-col gap-2">
                                                                            <span
                                                                                class="text-white">{{ $quiz->schedule->topic ?? 'No Topic' }}</span>
                                                                            <div class="w-full flex items-center gap-2">
                                                                                <img src="/frontend/assets/icons/clock.svg"
                                                                                    alt="Icon" class="size-4">
                                                                                <span class="text-gray-200 text-[12px]">
                                                                                    Submitted:
                                                                                    {{ $quiz->submitted_at ? \Carbon\Carbon::parse($quiz->submitted_at)->format('d M') : '-' }}
                                                                                </span>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="flex items-center gap-10">
                                                                    <span class="text-white">Score:
                                                                        {{ $quiz->score ?? '-' }}%</span>
                                                                    @if ($quiz->feedback_url)
                                                                        <a href="{{ Storage::url($quiz->feedback_url) }}"
                                                                            target="_blank"
                                                                            class="bg-gray-50 hover:bg-gray-200 rounded-full text-sm px-5 py-3 w-[195px] text-center">
                                                                            <span
                                                                                class="text-black text-[16px] font-semibold">View
                                                                                Feedback</span>
                                                                        </a>
                                                                    @else
                                                                        <a href="{{ route('student.quizzes.show', $quiz->id) }}"
                                                                            class="bg-gray-50 hover:bg-gray-200 rounded-full text-sm px-5 py-3 w-[195px] text-center">
                                                                            <span
                                                                                class="text-black text-[16px] font-semibold">Start
                                                                                Quiz</span>
                                                                        </a>
                                                                    @endif
                                                                </div>
                                                            </div>
                                                        @endforeach
                                                    @endforeach
                                                @endforeach
                                            @endforeach
                                        </div>
                                    </div>

                                </div>
                            </section>
                        </div>

                        <!-- Replay Videos -->
                        <div class="col-span-12 md:col-span-12 lg:col-span-4">
                            <section>
                                @foreach ($subscriptions as $subscription)
                                    @foreach ($subscription->classrooms as $classroom)
                                        @foreach ($classroom->schedules as $schedule)
                                            @foreach ($schedule->replays as $replay)
                                                <div class="flex items-center justify-between gap-3 lg:pr-3 mb-6">
                                                    <div class="flex items-center gap-3">
                                                        <img src="/frontend/assets/icons//replay.svg" alt="Icon"
                                                            class="size-6">
                                                        <h6 class="text-[20px] text-gray-75 font-semibold">Replay Videos
                                                        </h6>
                                                    </div>
                                                    <span class="text-[15px] text-gray-400">available until:
                                                        {{ \Carbon\Carbon::parse($replay->end_date)->format('d M') }}</span>
                                                </div>
                                                <!-- Content -->
                                                <div class="h-[135vh] overflow-y-auto">
                                                    <div class="space-y-6 lg:pr-4">
                                                        @php $hasReplay = false; @endphp
                                                        @foreach ($replay->replayVideos as $video)
                                                            @php $hasReplay = true; @endphp
                                                            <div class="flex flex-col gap-5">
                                                                <div data-modal-target="modal-1"
                                                                    class="flex flex-col items-center justify-center gap-3 bg-black rounded-[21px] border border-gray-850 h-[242px] cursor-pointer">
                                                                    <img src="/frontend/assets/icons/replay.svg"
                                                                        alt="Icon" class="w-16">
                                                                    <span class="text-[12px] text-[#F8C026]">Replay
                                                                        Available Soon</span>
                                                                </div>
                                                                <div class="flex flex-col gap-3 px-5">
                                                                    <span class="text-white text-[15px]">Topic
                                                                        {{ $schedule->topic }}</span>
                                                                    <div class="flex items-center justify-between gap-3">
                                                                        <div class="flex items-center gap-2">
                                                                            <span class="text-gray-275">Watch :</span>
                                                                            <span
                                                                                class="w-[150px] inline-flex items-center justify-center bg-[#523E06] px-2 py-2 font-medium text-yellow-200 rounded-full">Waiting</span>
                                                                        </div>
                                                                        <span
                                                                            class="text-[15px] text-gray-400">{{ \Carbon\Carbon::parse($replay->start_date)->format('d M') }}</span>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        @endforeach
                                                    </div>
                                                </div>
                                            @endforeach
                                        @endforeach
                                    @endforeach
                                @endforeach
                            </section>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div id="calendarModal" class="fixed inset-0 hidden z-50 flex items-center justify-center bg-black bg-opacity-50">
            <div class="bg-gray-900 rounded-xl p-5 w-full max-w-5xl h-[450px] flex flex-col">
                <!-- Header -->
                <div class="flex justify-between items-center mb-4">
                    <h3 class="text-white text-lg font-semibold">Select Date</h3>
                    <button onclick="closeCalendar()" class="text-white font-bold text-2xl leading-none">✕</button>
                </div>

                <!-- Body -->
                <div class="flex flex-col lg:flex-row gap-6 flex-1">
                    <!-- Legend -->
                    <div class="lg:w-1/3">
                        <div class="flex items-center gap-3 mb-4">
                            <img src="/frontend/assets/icons/calendar.svg" alt="Icon" class="size-6">
                            <h6 class="text-[20px] text-gray-100 font-semibold">Class Status</h6>
                        </div>
                        <div class="rounded-[21px] border border-black p-4">
                            <div class="grid grid-cols-2 gap-3">
                                <div class="flex items-center gap-2"><span
                                        class="w-5 h-5 bg-green-500 rounded-full"></span><span
                                        class="text-white">Live</span></div>
                                <div class="flex items-center gap-2"><span
                                        class="w-5 h-5 bg-blue-500 rounded-full"></span><span
                                        class="text-white">Upcoming</span></div>
                                <div class="flex items-center gap-2"><span
                                        class="w-5 h-5 bg-gray-200 rounded-full"></span><span
                                        class="text-white">Completed</span></div>
                                <div class="flex items-center gap-2"><span
                                        class="w-5 h-5 bg-red-500 rounded-full"></span><span
                                        class="text-white">Cancelled</span></div>
                                <div class="flex items-center gap-2"><span
                                        class="w-5 h-5 bg-gray-700 rounded-full"></span><span class="text-white">No
                                        Class</span></div>
                            </div>
                        </div>
                    </div>

                    <!-- Calendar -->
                    <div class="lg:w-2/3">
                        <div id="calendar" class="h-full w-full rounded-[21px] bg-gray-800 p-3"></div>
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
            const filterSelect = document.getElementById('filterSelect');

            window.openCalendar = () => document.getElementById('calendarModal').classList.remove('hidden');
            window.closeCalendar = () => document.getElementById('calendarModal').classList.add('hidden');

            filterSelect.addEventListener('change', function() {
                if (this.value === 'day') {
                    openCalendar();
                    this.value = 'none';
                }
            });

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
                'completed': '#ffffff',
                'no_class': '#424242'
            };

            const calendar = new FullCalendar.Calendar(calendarEl, {
                initialView: 'dayGridMonth',
                themeSystem: 'standard',
                timeZone: 'Asia/Jakarta',
                locale: 'id',
                height: '20rem',
                headerToolbar: {
                    left: 'prev,next today',
                    center: 'title',
                    right: ''
                },
                dayCellDidMount: function(info) {
                    const dateStr = info.date.toISOString().split('T')[0];
                    const status = dateStatusMap[dateStr] || 'no_class';
                    const color = statusColors[status] || '#ccc';
                    const dayNumberEl = info.el.querySelector('.fc-daygrid-day-number');
                    if (dayNumberEl) dayNumberEl.style.color = color;

                    info.el.setAttribute('title', status.toUpperCase());
                },
                dateClick: function(info) {
                    const selectedDate = info.dateStr;
                    const url = new URL(window.location.href);
                    url.searchParams.set('date', selectedDate);
                    window.location.href = url.toString();
                }
            });

            calendar.render();
        });
    </script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const tabButtons = document.querySelectorAll('.tab-btn');
            const tabContents = document.querySelectorAll('.tab-content');

            tabButtons.forEach(btn => {
                btn.addEventListener('click', () => {
                    const target = document.querySelector(btn.dataset.tab);

                    tabButtons.forEach(b => b.classList.remove('active'));
                    tabContents.forEach(c => c.classList.add('hidden'));

                    btn.classList.add('active');
                    target.classList.remove('hidden');
                });
            });
        });
    </script>
@endpush
