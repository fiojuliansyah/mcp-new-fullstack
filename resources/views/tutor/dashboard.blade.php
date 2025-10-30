@extends('layouts.app')

@section('content')
    <section class="w-full bg-primary text-white px-4 py-10">
        <div class="w-full max-w-screen-xl mx-auto">
            <div class="flex flex-col lg:flex-row justify-between gap-5 lg:items-end border-b border-white/10">
                <div class="flex items-center gap-3">
                    <img src="/frontend/assets/images/tutor-profile-vector.svg" alt="Tutor Avatar" class="w-28" />
                    <div>
                        <span class="text-gray-250">Tutor Dashboard</span>
                        <h1 class="text-4xl font-bold tracking-tight text-white">Welcome Back!</h1>
                    </div>
                </div>
            </div>

            <div class="space-y-10 divide-y divide-zinc-700">
                <div class="pt-10">
                    <div class="flex items-center gap-3 mb-6">
                        <img src="/frontend/assets/icons/books.svg" alt="Icon" class="size-6">
                        <h6 class="text-[20px] text-gray-75 font-semibold">My Subject</h6>
                    </div>

                    <div class="flex flex-col lg:flex-row lg:items-center gap-5 mb-10">
                        <div class="flex flex-col lg:flex-row lg:items-center gap-3">
                            <div class="text-gray-200 w-[120px]">Subject Name:</div>
                            <div class="w-full lg:w-[300px]">
                                <select id="subjectSelect"
                                    class="bg-gray-1000 border border-gray-950 text-white placeholder:text-gray-500 rounded-[14px] block w-full lg:w-[250px] px-4 py-3">
                                    <option value="">Select Subject</option>
                                    @foreach ($subjects as $subject)
                                        <option value="{{ route('tutor.dashboard.subject', $subject->slug) }}"
                                            @isset($selectedSubject)
                                            {{ $selectedSubject->id === $subject->id ? 'selected' : '' }}
                                        @endisset>
                                            {{ $subject->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <a href="{{ isset($selectedSubject) ? route('tutor.schedule.create', $selectedSubject->slug) : '#' }}"
                            class="bg-gray-50 hover:bg-gray-200 rounded-full text-center text-sm px-5 py-3 cursor-pointer w-full lg:w-[300px]">
                            <span class="text-black text-[16px] font-semibold">Schedule New Class</span>
                        </a>
                    </div>

                    @if (isset($selectedSubject))
                        <div class="mb-6">
                            <div class="flex items-center gap-3 mb-2">
                                <img src="/frontend/assets/icons/slideshow.svg" alt="Icon" class="size-6">
                                <h6 class="text-[20px] text-gray-75 font-semibold">
                                    My Class — {{ $selectedSubject->name }}
                                </h6>
                            </div>
                        </div>

                        <div class="grid grid-cols-12 gap-10">
                            <div class="col-span-12 lg:col-span-7">
                                <div class="grid grid-cols-12 gap-5 bg-gray-800 rounded-[21px] p-6 h-full">
                                    <div class="col-span-12 lg:col-span-5">
                                        <div class="flex items-center gap-3 mb-6">
                                            <img src="/frontend/assets/icons/calendar.svg" alt="Icon" class="size-6">
                                            <h6 class="text-[20px] text-gray-100 font-semibold">Class Calendar</h6>
                                        </div>

                                        <div class="rounded-[21px] border border-black w-auto">
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
                                        <div id="calendar" class="h-[13rem]"></div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-span-12 lg:col-span-5">
                                <div>
                                    <div class="flex items-center justify-between mb-5">
                                        <span class="text-[#4B4B4B]">Class Details</span>
                                        <span class="text-[#4B4B4B]">{{ now()->format('d M Y') }}</span>
                                    </div>

                                    <div id="schedule-container"
                                        class="max-h-[450px] overflow-y-auto pr-2 scrollbar-thin scrollbar-thumb-gray-600 scrollbar-track-gray-900">
                                        @forelse ($classrooms as $classroom)
                                            @foreach ($classroom->schedules->take(2) as $schedule)
                                                <div class="flex flex-col border-b border-white/10 pb-5 mb-5">
                                                    <div class="flex items-center justify-between mb-4">
                                                        <div class="flex items-center gap-3">
                                                            <span>{{ $classroom->name ?? 'Unnamed Class' }}</span>

                                                            @php
                                                                $status = strtolower($schedule->status);
                                                                $statusColors = [
                                                                    'live' => 'bg-green-100 text-black',
                                                                    'scheduled' => 'bg-blue-200 text-black',
                                                                    'completed' => 'bg-white text-black',
                                                                    'canceled' => 'bg-red-100 text-black',
                                                                ];

                                                                $statusLabels = [
                                                                    'live' => 'Live',
                                                                    'scheduled' => 'Upcoming',
                                                                    'completed' => 'Completed',
                                                                    'canceled' => 'Canceled',
                                                                ];

                                                                $colorClass =
                                                                    $statusColors[$status] ?? 'bg-[#424242] text-white';
                                                                $label = $statusLabels[$status] ?? ucfirst($status);
                                                            @endphp

                                                            <span
                                                                class="w-[150px] inline-flex items-center justify-center {{ $colorClass }} px-2 py-2 font-medium rounded-full">
                                                                {{ $label }}
                                                            </span>
                                                        </div>

                                                        <span>{{ Str::upper($classroom->subject->name) }}</span>
                                                    </div>

                                                    <div class="flex items-center gap-3 mb-3">
                                                        <img src="/frontend/assets/icons/clock.svg" alt="Icon"
                                                            class="size-4">
                                                        <span class="text-gray-200 text-[13px]">
                                                            {{ \Carbon\Carbon::parse($schedule->time)->format('d M · g:i A') }}
                                                        </span>
                                                    </div>

                                                    <div class="flex items-center justify-between">
                                                        <span>Topic: {{ $schedule->topic ?? 'TBD' }}</span>
                                                        {{-- <a href="#"
                                                            class="bg-gray-50 hover:bg-gray-200 rounded-full text-sm px-5 py-3 cursor-pointer w-full lg:w-[150px] text-center">
                                                            <span class="text-black text-[16px] font-semibold">View</span>
                                                        </a> --}}
                                                    </div>
                                                </div>
                                            @endforeach
                                        @empty
                                            <div class="text-gray-400 text-center py-10">
                                                No schedules available for {{ $selectedSubject->name }}
                                            </div>
                                        @endforelse
                                    </div>
                                </div>
                            </div>

                        </div>

                        <div class="w-full pt-10 space-y-10">
                            <section>
                                <div class="mb-6">
                                    <div class="flex items-center gap-3 mb-2">
                                        <img src="/frontend/assets/icons/calendar.svg" alt="Icon" class="size-6">
                                        <h6 class="text-[20px] text-gray-75 font-semibold">Manage Class</h6>
                                    </div>
                                    <span class="text-gray-910 text-[15px]">Choose an action to begin</span>
                                </div>
                                <div class="grid grid-cols-12 gap-10 bg-gray-990 rounded-[21px] p-8">
                                    <div class="col-span-12 lg:col-span-4">
                                        <a href="{{ isset($selectedSubject) ? route('tutor.replay.create', $selectedSubject->slug) : '#' }}"
                                            class="bg-gray-700 rounded-[21px] p-5 flex flex-col items-center gap-5 shadow-1">
                                            <div
                                                class="bg-gray-50 rounded-full w-[92px] h-[92px] flex items-center justify-center">
                                                <img src="/frontend/assets/icons/video-upload.svg" alt="Icon"
                                                    class="size-10 text-black">
                                            </div>
                                            <span class="text-white">Upload Replay Video</span>
                                        </a>
                                    </div>
                                    <div class="col-span-12 lg:col-span-4">
                                        <a href="{{ isset($selectedSubject) ? route('tutor.material.create', $selectedSubject->slug) : '#' }}"
                                            class="bg-gray-700 rounded-[21px] p-5 flex flex-col items-center gap-5 shadow-1">
                                            <div
                                                class="bg-gray-50 rounded-full w-[92px] h-[92px] flex items-center justify-center">
                                                <img src="/frontend/assets/icons/archive.svg" alt="Icon"
                                                    class="size-10 text-black">
                                            </div>
                                            <span class="text-white">Upload Study Notes And References</span>
                                        </a>
                                    </div>
                                    <div class="col-span-12 lg:col-span-4">
                                        <a href="{{ isset($selectedSubject) ? route('tutor.quiz.create', $selectedSubject->slug) : '#' }}"
                                            class="bg-gray-700 rounded-[21px] p-5 flex flex-col items-center gap-5 shadow-1">
                                            <div
                                                class="bg-gray-50 rounded-full w-[92px] h-[92px] flex items-center justify-center">
                                                <img src="/frontend/assets/icons/notification.svg" alt="Icon"
                                                    class="size-10 text-black">
                                            </div>
                                            <span class="text-white">Create Quizzes</span>
                                        </a>
                                    </div>
                                    <div class="col-span-12">
                                        <a href="{{ isset($selectedSubject) ? route('tutor.class.index', $selectedSubject->slug) : '#' }}"
                                            class="block bg-gray-50 hover:bg-gray-200 rounded-full text-center text-sm px-5 py-3 w-full cursor-pointer">
                                            <span class="text-black text-[16px] font-semibold">View All Classes</span>
                                        </a>
                                    </div>
                                </div>
                            </section>
                        </div>
                        <div class="w-full pt-10 space-y-10">
                            <section class="w-full py-10">
                                <div class="grid grid-cols-12 gap-10">
                                    <div class="col-span-12 lg:col-span-6">
                                        <div class="rounded-[21px] border border-white h-full">
                                            <div class="rounded-t-[21px] bg-[#2C2C2C] p-5 text-center">
                                                <span>Students Performance Overview</span>
                                            </div>
                                            <div class="p-4">
                                                <div class="flex items-center justify-between py-6 px-5">
                                                    <div class="flex flex-col">
                                                        <span class="text-gray-200">Average Quiz Score:</span>
                                                        <span class="text-[#EAEAEA]">{{ $overview['avgScore'] }}%</span>
                                                    </div>
                                                    <div class="flex flex-col">
                                                        <span class="text-gray-200">Average Attendance:</span>
                                                        <span class="text-[#EAEAEA]">{{ $overview['attendanceRate'] }}%</span>
                                                    </div>
                                                    <div class="flex flex-col">
                                                        <span class="text-gray-200">Replay Views:</span>
                                                        <span class="text-[#EAEAEA]">{{ $overview['replayRate'] }}%</span>
                                                    </div>
                                                </div>
                                                <a href="{{ isset($selectedSubject) ? route('tutor.overview.performance.index', $selectedSubject->slug) : '#' }}"
                                                    class="block bg-gray-50 hover:bg-gray-200 rounded-full text-center text-sm px-5 py-3 w-full cursor-pointer">
                                                    <span class="text-black text-[16px] font-semibold">View Detailed Report</span>
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-span-12 lg:col-span-6">
                                        <div class="rounded-[21px] border border-white">
                                            <div class="rounded-t-[21px] bg-[#2C2C2C] p-5 text-center">
                                                <span>Students Subscription Overview</span>
                                            </div>
                                            <div class="grid grid-cols-12 gap-5 p-4">
                                                <div class="col-span-5 border-r border-white/10">
                                                    <div
                                                        class="flex flex-col items-center justify-center text-center px-5 p-4">
                                                        <span class="text-white">Total Students Enrolled</span>
                                                        <span class="text-white text-[60px]">{{ $allCount }}</span>
                                                    </div>
                                                </div>
                                                <div class="col-span-7">
                                                    <div class="flex flex-col justify-between h-full">
                                                        <div class="flex items-center justify-between py-6 px-5">
                                                            <div class="flex flex-col">
                                                                <span class="text-gray-200">Active:</span>
                                                                <span class="text-green-100">{{ $activeCount }}</span>
                                                            </div>
                                                            <div class="flex flex-col">
                                                                <span class="text-gray-200">Expired:</span>
                                                                <span class="text-red-100">{{ $expiredCount }}</span>
                                                            </div>
                                                            <div class="flex flex-col">
                                                                <span class="text-gray-200">Expiring Soon:</span>
                                                                <span
                                                                    class="text-[#FDBA10]">{{ $expiringSoonCount }}</span>
                                                            </div>
                                                        </div>
                                                        <a href="{{ isset($selectedSubject) ? route('tutor.overview.subscription.index', $selectedSubject->slug) : '#' }}"
                                                            class="block bg-gray-50 hover:bg-gray-200 rounded-full text-center text-center text-sm px-5 py-3 w-full cursor-pointer">
                                                            <span class="text-black text-[16px] font-semibold">View
                                                                Enrolment
                                                                List</span>
                                                        </a>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </section>
                        </div>
                    @else
                        <div class="w-full h-full flex items-center justify-center py-20">
                            <div class="flex flex-col items-center">
                                <img src="/frontend/assets/images/no-class-displayed.svg" alt="Illustration"
                                    class="mb-10">
                                <h6 class="text-[15px] text-gray-100 font-semibold mb-1">No Classes Displayed</h6>
                                <p class="text-[#939393] font-normal">Select A Subject To View And Manage Your Classes</p>
                            </div>
                        </div>
                    @endif
                </div>

            </div>


        </div>
    </section>
@endsection

@push('styles')
    <style>
        .selected-date {
            border: 2px solid #FFD700 !important;
            border-radius: 8px;
            background-color: rgba(255, 215, 0, 0.15);
            transition: all 0.2s ease-in-out;
        }
    </style>
@endpush

@push('scripts')
    <link href="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.9/index.global.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.9/index.global.min.js"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const calendarEl = document.getElementById('calendar');
            const coloredDates = @json($coloredDates);
            let selectedCell = null;

            const calendar = new FullCalendar.Calendar(calendarEl, {
                initialView: 'dayGridMonth',
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
                    const color = coloredDates[dateStr];
                    if (color) {
                        const dateNumber = info.el.querySelector('.fc-daygrid-day-number');
                        if (dateNumber) {
                            dateNumber.style.color = color;
                            dateNumber.style.fontWeight = '700';
                        }
                    }
                },

                dateClick: function(info) {
                    const date = info.dateStr;
                    const subjectSlug = "{{ $selectedSubject->slug ?? '' }}";

                    if (selectedCell) {
                        selectedCell.classList.remove('selected-date');
                    }

                    selectedCell = info.dayEl;
                    selectedCell.classList.add('selected-date');

                    fetch(`/tutor/schedule/${subjectSlug}/${date}`)
                        .then(response => response.json())
                        .then(data => {
                            const container = document.querySelector('#schedule-container');
                            container.innerHTML = '';

                            if (data.schedules.length > 0) {
                                data.schedules.forEach(s => {
                                    const colorMap = {
                                        live: 'bg-green-100 text-black',
                                        scheduled: 'bg-blue-200 text-black',
                                        completed: 'bg-white text-black',
                                        canceled: 'bg-red-100 text-black'
                                    };

                                    const colorClass = colorMap[s.status] ||
                                        'bg-[#424242] text-white';
                                    const label = s.status.charAt(0).toUpperCase() + s
                                        .status.slice(1);

                                    container.innerHTML += `
                                <div class="flex flex-col border-b border-white/10 pb-5 mb-5">
                                    <div class="flex items-center justify-between mb-4">
                                        <div class="flex items-center gap-3">
                                            <span>${s.class_name}</span>
                                            <span class="w-[150px] inline-flex items-center justify-center ${colorClass} px-2 py-2 font-medium rounded-full">${label}</span>
                                        </div>
                                        <span>${s.subject}</span>
                                    </div>
                                    <div class="flex items-center gap-3 mb-3">
                                        <img src="/frontend/assets/icons/clock.svg" alt="Icon" class="size-4">
                                        <span class="text-gray-200 text-[13px]">${s.time}</span>
                                    </div>
                                    <div class="flex items-center justify-between">
                                        <span>Topic: ${s.topic}</span>
                                        <a href="#" class="bg-gray-50 hover:bg-gray-200 rounded-full text-sm px-5 py-3 cursor-pointer w-full lg:w-[150px] text-center">
                                            <span class="text-black text-[16px] font-semibold">View</span>
                                        </a>
                                    </div>
                                </div>`;
                                });
                            } else {
                                container.innerHTML =
                                    `<div class="text-gray-400 text-center py-10">No schedules for ${date}</div>`;
                            }
                        })
                        .catch(error => console.error('Error fetching schedules:', error));
                }
            });

            calendar.render();
        });
    </script>

    <script>
        document.getElementById('subjectSelect').addEventListener('change', function() {
            if (this.value) {
                window.location.href = this.value;
            }
        });
    </script>
@endpush
