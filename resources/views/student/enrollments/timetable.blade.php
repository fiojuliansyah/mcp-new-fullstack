@extends('layouts.app')

@section('content')
<section class="w-full bg-primary text-white px-4 py-10">
    <div class="w-full max-w-screen-xl mx-auto">
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
                <span class="text-white text-[15px] font-medium">> Subject Enrolment</span>
            </div>
        </div>

        <div class="space-y-10 divide-y divide-zinc-700">
            <div class="pt-10">
                <div class="grid grid-cols-12 gap-10 mb-10">
                    <div class="col-span-12">
                        <div class="flex flex-col lg:flex-row justify-between gap-10">
                            <div class="flex items-center gap-10">
                                <a href="{{ route('student.enrollment.checkout') }}"
                                    class="bg-white rounded-full w-8 h-8 flex items-center justify-center cursor-pointer">
                                    <img src="/frontend/assets/icons/arrow-left.svg" alt="Icon" class="size-4">
                                </a>
                                <div>
                                    <h6 class="text-[20px] text-gray-75 font-semibold">Review Your Timetable</h6>
                                    <span class="text-[#F2F2F2BF]">Below is your selected class schedule overview.</span>
                                </div>
                            </div>
                            <div class="flex flex-col lg:items-end">
                                <div class="text-[#868484] mb-5">
                                    <span class="mr-10">Subjects: {{ count($classroomIds) }}</span>
                                    <span>Classes: {{ count($scheduleDays) }}</span>
                                </div>
                                <a href="{{ route('student.enrollment.summary', $subscription->id) }}"
                                    class="block bg-gray-50 hover:bg-gray-200 rounded-full text-center text-sm px-5 py-3 w-full lg:w-[20rem] cursor-pointer">
                                    <span class="text-black text-[16px] font-semibold">Continue</span>
                                </a>
                            </div>
                        </div>
                    </div>
                    <div class="col-span-12">
                        <div class="bg-white rounded-xl text-black p-5">
                            <div id="calendar"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection

@push('styles')
<link href='https://cdn.jsdelivr.net/npm/fullcalendar@6.1.10/index.global.min.css' rel='stylesheet' />
@endpush

@push('scripts')
<script src='https://cdn.jsdelivr.net/npm/fullcalendar@6.1.10/index.global.min.js'></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    var calendarEl = document.getElementById('calendar');

    var subjectColors = {};
    var colors = ['#3b82f6', '#10b981', '#f59e0b', '#ef4444', '#8b5cf6', '#14b8a6', '#f43f5e', '#22d3ee'];
    var colorIndex = 0;

    @foreach($classrooms as $classroom)
        var subject = "{{ $classroom->subject?->name }}";
        if (!subjectColors[subject]) {
            subjectColors[subject] = colors[colorIndex % colors.length];
            colorIndex++;
        }
    @endforeach

    var events = @json($calendarEvents).map(function(event) {
        var color = subjectColors[event.extendedProps.subject] || '#3b82f6';
        event.backgroundColor = color;
        event.borderColor = color;
        return event;
    });

    var calendar = new FullCalendar.Calendar(calendarEl, {
        initialView: 'timeGridWeek',
        headerToolbar: {
            left: 'prev,next today',
            center: 'title',
            right: 'timeGridWeek,timeGridDay'
        },
        slotMinTime: '07:00:00',
        slotMaxTime: '22:00:00',
        allDaySlot: true,
        height: 'auto',
        eventMinHeight: 100,
        events: events,
        eventClick: function(info) {
            alert('Subject: ' + info.event.extendedProps.subject + '\n' +
                  'Classroom: ' + info.event.extendedProps.classroom + '\n' +
                  'Time: ' + info.event.start.toLocaleString());
        },
        eventContent: function(arg) {
            return {
                html: `
                <div class="fc-event-main-frame p-3 rounded-xl" style="font-size: 14px; line-height: 1.4;">
                    <div class="fc-event-title-container">
                        <div class="fc-event-title font-semibold mb-1">
                            ${arg.event.extendedProps.user}
                        </div>
                        <div class="text-xs opacity-90">
                            ${arg.event.extendedProps.subject}
                        </div>
                        <div class="text-xs opacity-90">
                            ${arg.event.extendedProps.classroom}
                        </div>
                    </div>
                </div>`
            };
        }
    });

    calendar.render();
});
</script>
@endpush