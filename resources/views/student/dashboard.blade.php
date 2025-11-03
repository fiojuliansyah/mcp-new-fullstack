@extends('layouts.app')

@section('content')
    <section class="w-full bg-primary text-white px-4 py-10">
        <div class="w-full max-w-screen-xl mx-auto">
            <div class="flex flex-col lg:flex-row justify-between gap-5 lg:items-end border-b border-white/10">
                <div class="flex items-center gap-3">
                    <img src="/frontend/assets/images/student-profile-vector.svg" alt="Tutor Avatar" class="w-28" />
                    <div>
                        <span class="text-gray-250">Student Dashboard</span>
                        <h1 class="text-4xl font-bold tracking-tight text-white">Welcome Back!</h1>
                    </div>
                </div>
            </div>

            <div class="space-y-10 divide-y divide-zinc-700">
                <div class="w-full pt-10">
                    <div class="grid grid-cols-12 gap-5">
                        <div class="col-span-12 md:col-span-12 lg:col-span-8 pr-5 border-r border-gray-510">
                            <!-- UPCOMING CLASSES -->
                            <section class="mb-10">
                                <div class="flex items-center gap-3 mb-6"> <img
                                        src="/frontend/assets/icons/upcoming-classes.svg" alt="Icon" class="size-6">
                                    <h6 class="text-[20px] text-gray-75 font-semibold">Upcoming Classes</h6>
                                </div>
                                <div class="grid grid-cols-12 gap-8">
                                    @php
                                        $firstClass = null;
                                        $otherClasses = [];

                                        foreach ($subscriptions as $sub) {
                                            foreach ($sub->classrooms as $classroom) {
                                                foreach ($classroom->schedules as $schedule) {
                                                    if (
                                                        Carbon\Carbon::parse($schedule->time)->isToday() &&
                                                        !$firstClass
                                                    ) {
                                                        $firstClass = [
                                                            'classroom' => $classroom,
                                                            'schedule' => $schedule,
                                                        ];
                                                    } else {
                                                        $otherClasses[] = [
                                                            'classroom' => $classroom,
                                                            'schedule' => $schedule,
                                                        ];
                                                    }
                                                }
                                            }
                                        }
                                    @endphp


                                    @if ($firstClass)
                                        <div class="col-span-12 md:col-span-12 lg:col-span-6">
                                            <div class="grid grid-cols-12 gap-8 border border-white rounded-[21px] p-5">
                                                <div class="col-span-5">
                                                    <div
                                                        class="relative h-auto lg:h-[170px] w-[250px] lg:w-[140px] rounded-[13px]">
                                                        <img src="{{ $firstClass['classroom']->user->avatar_url ?? '/frontend/assets/images/sample/image-1.png' }}"
                                                            alt="Image"
                                                            class="h-full w-full rounded-[13px] object-cover">
                                                        <div
                                                            class="absolute w-full flex items-center gap-3 rounded-b-[13px] bg-gray-800 bottom-0 py-2 px-2">
                                                            <img src="/frontend/assets/icons/clock.svg" alt="Icon"
                                                                class="size-4">
                                                            <span class="text-white text-[10px]">
                                                                {{ \Carbon\Carbon::parse($firstClass['schedule']->time)->format('d M · h:i A') }}
                                                            </span>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-span-7 flex flex-col justify-between">
                                                    <div class="flex flex-col">
                                                        <span
                                                            class="text-white uppercase font-bebas">{{ $firstClass['schedule']->classroom->subject->name ?? 'Class' }}</span>
                                                        <span class="text-white text-xl uppercase font-bebas">
                                                            {{ $firstClass['schedule']->topic ?? '' }}
                                                        </span>
                                                    </div>
                                                    <a href="{{ route('student.classrooms.index', $firstClass['classroom']->id) }}"
                                                        class="bg-gray-50 hover:bg-gray-200 rounded-full text-sm px-5 py-3 cursor-pointer text-center">
                                                        <span class="text-black text-[16px] font-semibold">Join
                                                            Now</span>
                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                    @endif

                                    <div class="col-span-12 md:col-span-12 lg:col-span-6">
                                        <div class="flex items-center justify-between gap-3 h-full"> <button id="prevClass"
                                                class="bg-white rounded-full w-10 h-10 flex items-center justify-center cursor-pointer">
                                                <img src="/frontend/assets/icons/angle-left.svg" alt="Icon"
                                                    class="size-3"> </button>
                                            <div id="classCarousel"
                                                class="flex overflow-hidden gap-4 scroll-smooth w-[350px]">
                                                @foreach ($otherClasses as $oc)
                                                    <div
                                                        class="relative h-auto lg:h-[170px] w-[250px] lg:w-[150px] rounded-[13px] overflow-hidden flex-shrink-0">
                                                        <img src="{{ $oc['classroom']->user->avatar_url ?? '/frontend/assets/images/sample/image-1.png' }}"
                                                            alt="Image"
                                                            class="h-full w-full rounded-[13px] object-cover">

                                                        <div
                                                            class="absolute top-0 left-0 w-full bg-gradient-to-b from-black/60 to-transparent p-3">
                                                            <span class="block text-white text-[12px] uppercase font-bebas">
                                                                {{ $oc['schedule']->classroom->subject->name ?? 'Class' }}
                                                            </span>
                                                            <span
                                                                class="block text-white text-[14px] uppercase font-bebas leading-tight">
                                                                {{ $oc['schedule']->topic ?? '' }}
                                                            </span>
                                                        </div>

                                                        <div
                                                            class="absolute w-full flex items-center gap-3 rounded-b-[13px] bg-gray-800 bottom-0 py-2 px-2">
                                                            <img src="/frontend/assets/icons/clock.svg" alt="Icon"
                                                                class="size-4">
                                                            <span class="text-white text-[10px]">
                                                                {{ \Carbon\Carbon::parse($oc['schedule']->time)->format('d M · h:i A') }}
                                                            </span>
                                                        </div>
                                                    </div>
                                                @endforeach
                                            </div>

                                            <button id="nextClass"
                                                class="bg-white rounded-full w-10 h-10 flex items-center justify-center cursor-pointer">
                                                <img src="/frontend/assets/icons/angle-right.svg" alt="Icon"
                                                    class="size-3">
                                            </button>
                                        </div>

                                    </div>
                                </div>

                            </section>


                            <!-- STILL TO DO -->
                            <section>
                                <!-- HEADING -->
                                <div class="flex items-center gap-3 mb-6">
                                    <img src="/frontend/assets/icons/file.svg" alt="Icon" class="size-6">
                                    <h6 class="text-[20px] text-gray-75 font-semibold">Still To Do</h6>
                                </div>

                                <!-- TABS -->
                                <div class="tabs flex rounded-t-[21px]" data-group="group-tabs-1">
                                    <button type="button"
                                        class="tab-btn active w-full rounded-tl-[21px] px-2 py-4 text-center cursor-pointer"
                                        data-tab="#tab1" data-group="group-tabs-1">
                                        <span class="text-[15px]">Quizzes</span>
                                    </button>
                                    <button type="button"
                                        class="tab-btn w-full rounded-tr-[21px] px-2 py-4 text-center cursor-pointer"
                                        data-tab="#tab2" data-group="group-tabs-1">
                                        <span class="text-[15px]">Feedback</span>
                                    </button>
                                </div>

                                <!-- TAB ITEM -->
                                <div class="h-[60vh] lg:h-[90vh] overflow-y-auto mb-5">
                                    <!-- Tab Content Quizzes -->
                                    <div id="tab1" data-group="group-tabs-1"
                                        class="tab-content flex flex-col bg-gray-975 rounded-b-[21px] py-10 px-5">

                                        @php $hasQuiz = false; @endphp

                                        @foreach ($subscriptions as $subscription)
                                            @foreach ($subscription->classrooms as $classroom)
                                                @foreach ($classroom->schedules as $schedule)
                                                    @foreach ($schedule->quizzes as $quiz)
                                                        @php $hasQuiz = true; @endphp
                                                        <div
                                                            class="flex gap-5 border border-gray-510 rounded-[21px] p-8 mb-5">
                                                            <img src="/frontend/assets/icons/quiz.svg" alt="Icon"
                                                                class="size-14">
                                                            <div class="flex flex-col mt-2">
                                                                <div class="w-full flex items-center gap-2 py-2">
                                                                    <img src="/frontend/assets/icons/clock.svg"
                                                                        alt="Icon" class="size-4">
                                                                    <span class="text-gray-75 text-[12px]">
                                                                        {{ \Carbon\Carbon::parse($schedule->time)->format('d M · h:i A') }}
                                                                    </span>
                                                                </div>
                                                                <span
                                                                    class="text-white mb-5">{{ $quiz->title ?? ($schedule->topic ?? 'Quiz') }}</span>
                                                                <a href="{{ route('student.quizzes.show', $quiz->id) }}"
                                                                    class="bg-gray-50 hover:bg-gray-200 rounded-full text-sm px-5 py-3 w-[195px] cursor-pointer text-center">
                                                                    <span class="text-black text-[16px] font-semibold">Start
                                                                        Quiz</span>
                                                                    </a>
                                                            </div>
                                                        </div>
                                                    @endforeach
                                                @endforeach
                                            @endforeach
                                        @endforeach

                                        @if (!$hasQuiz)
                                            <div
                                                class="flex flex-col items-center justify-center gap-3 bg-black rounded-[21px] border border-gray-850 h-[250px] cursor-pointer">
                                                <img src="/frontend/assets/icons/quiz.svg" alt="Icon" class="w-16">
                                                <span class="text-[12px] text-white">No Quiz Available</span>
                                            </div>
                                        @endif
                                    </div>
                                    <!-- Tab Content Feedback -->
                                    <div id="tab2" data-group="group-tabs-1" class="tab-content">
                                        <!-- Empty -->
                                    </div>
                                </div>
                            </section>
                        </div>

                        <section class="col-span-12 md:col-span-12 lg:col-span-4">
                            <div class="flex items-center gap-3 mb-6"> <img src="/frontend/assets/icons/replay.svg"
                                    alt="Icon" class="size-6">
                                <h6 class="text-[20px] text-gray-75 font-semibold">Replay Videos</h6>
                            </div>
                            <div class="h-[70vh] lg:h-[135vh] overflow-y-auto">
                                <div class="space-y-6 pr-4">
                                    @php $hasReplay = false; @endphp

                                    @foreach ($subscriptions as $subscription)
                                        @foreach ($subscription->classrooms as $classroom)
                                            @foreach ($classroom->schedules as $schedule)
                                                @foreach ($schedule->replays as $replay)
                                                    @foreach ($replay->replayVideos as $video)
                                                        @php $hasReplay = true; @endphp
                                                        <a href="{{ route('student.replay.show', [ 'replay' => $replay->id, 'video_id' => $video->id ]) }}"
                                                            class="flex flex-col items-center justify-center gap-3 bg-black rounded-[21px] border border-gray-850 h-[250px] cursor-pointer overflow-hidden relative group">
                                                            <img data-video-thumb="{{ asset('storage/replays/' . basename($video->video_url)) }}"
                                                                class="video-thumb w-full h-full object-cover rounded-[21px]" />
                                                            <div
                                                                class="absolute inset-0 flex flex-col justify-center items-center bg-black/50 opacity-0 group-hover:opacity-100 transition-opacity duration-200">
                                                                <img src="/frontend/assets/icons/replay.svg"
                                                                    alt="Icon" class="w-10 mb-2">
                                                                <span
                                                                    class="text-[12px] text-white text-center px-2">{{ $schedule->topic ?? 'Replay' }}</span>
                                                            </div>
                                                        </a>
                                                    @endforeach
                                                @endforeach
                                            @endforeach
                                        @endforeach
                                    @endforeach

                                    @if (!$hasReplay)
                                        <div
                                            class="flex flex-col items-center justify-center gap-3 bg-black rounded-[21px] border border-gray-850 h-[250px] cursor-pointer">
                                            <img src="/frontend/assets/icons/replay.svg" alt="Icon" class="w-16">
                                            <span class="text-[12px] text-white">Replay Available Soon</span>
                                        </div>
                                    @endif
                                </div>
                            </div>

                        </section>
                    </div>
                </div>

                <!-- STUDENT LOG -->
                <div class="w-full pt-10">
                    <div class="grid grid-cols-12">
                        <div class="col-span-12 md:col-span-12 lg:col-span-12">
                            <!-- HEADING -->
                            <div class="flex items-center gap-3 mb-6 pt-5">
                                <img src="/frontend/assets/icons/toga.svg" alt="Icon" class="size-6">
                                <h6 class="text-[20px] text-gray-75 font-semibold">Student Log</h6>
                            </div>

                            <div class="grid grid-cols-12 gap-6 lg:gap-10">
                                <!-- TABS -->
                                <div class="col-span-12 lg:col-span-8 lg:pr-5">
                                    <div class="tabs flex rounded-[21px] bg-black w-full py-4" data-group="group-tabs-2">
                                        <button type="button" class="tab-btn w-full text-center cursor-pointer"
                                            data-tab="#classes-and-notes" data-group="group-tabs-2">
                                            <span class="text-white">Classes And Notes</span>
                                        </button>
                                        <button type="button" class="tab-btn w-full text-center cursor-pointer"
                                            data-tab="#my-subjects" data-group="group-tabs-2">
                                            <span class="text-white">My Subjects</span>
                                        </button>
                                        <button type="button" class="tab-btn w-full text-center cursor-pointer"
                                            data-tab="#subscription-info" data-group="group-tabs-2">
                                            <span class="text-white">Subscription Info</span>
                                        </button>
                                    </div>
                                </div>

                                <!-- FILTER -->
                                <div class="col-span-12 lg:col-span-4">
                                    <div class="flex flex-col lg:flex-row lg:items-center lg:justify-end gap-3">
                                        <label class="text-gray-200">Filter By:</label>
                                        <form class="w-full lg:w-[300px]">
                                            <select
                                                class="bg-gray-1000 border border-gray-950 text-gray-500 rounded-[14px] block w-full p-4">
                                                <option selected>Filter</option>
                                                <option value="" selected>Topic</option>
                                            </select>
                                        </form>
                                    </div>
                                </div>
                            </div>

                            <!-- TAB Classes And Notes Content -->
                            <div id="classes-and-notes" data-group="group-tabs-2"
                                class="tab-content flex flex-col gap-10 mt-10">
                                @php $hasMaterial = false; @endphp

                                @foreach ($subscriptions as $subscription)
                                    @foreach ($subscription->classrooms as $classroom)
                                        @foreach ($classroom->schedules as $schedule)
                                            @if ($schedule->materials->count())
                                                @php $hasMaterial = true; @endphp

                                                <div
                                                    class="flex flex-col lg:flex-row gap-5 justify-between border-b border-gray-510 pb-10">
                                                    <div class="flex gap-6">
                                                        <div
                                                            class="w-[100px] h-full bg-gray-925 p-5 rounded-[21px] text-white text-center">
                                                            <span>{{ \Carbon\Carbon::parse($schedule->time)->format('d M (D)') }}</span>
                                                            <div class="border border-white my-3"></div>
                                                            <span>{{ \Carbon\Carbon::parse($schedule->time)->format('h:i A') }}</span>
                                                        </div>
                                                        <div class="flex flex-col gap-2">
                                                            <h6 class="font-bold text-white">{{ $classroom->name }}</h6>
                                                            <span
                                                                class="text-white max-w-lg">{{ $schedule->topic ?? 'No topic available' }}</span>
                                                            <div class="flex items-center gap-5 mt-3">
                                                                <span class="text-gray-275">Status :</span>

                                                                @php
                                                                    $statusColors = [
                                                                        'live' => 'bg-green-900 text-green-100',
                                                                        'scheduled' => 'bg-teal-900 text-blue-200',
                                                                        'Replacement' => 'bg-green-950 text-yellow-200',
                                                                    ];
                                                                    $status = $schedule->status ?? 'scheduled';
                                                                    $statusLabel =
                                                                        strtolower($status) === 'scheduled'
                                                                            ? 'Upcoming'
                                                                            : ucfirst($status);
                                                                @endphp

                                                                <span
                                                                    class="w-[150px] inline-flex items-center justify-center px-2 py-2 font-medium rounded-full {{ $statusColors[$status] ?? 'bg-gray-700 text-white' }}">
                                                                    {{ $statusLabel }}
                                                                </span>
                                                            </div>

                                                        </div>
                                                    </div>

                                                    <div class="flex flex-col justify-end gap-3">
                                                        <div class="flex items-center gap-2">
                                                            <span class="text-white text-[15px]">
                                                                {{ $schedule->materials->count() }} Notes & Answer Key
                                                            </span>
                                                            <img src="/frontend/assets/icons/folder.svg" alt="Icon"
                                                                class="size-5">
                                                        </div>

                                                        @foreach ($schedule->materials as $material)
                                                            <a href="{{ asset('storage/' . $material->file_path) }}"
                                                                download
                                                                class="bg-gray-50 hover:bg-gray-200 rounded-full text-sm px-5 py-3 cursor-pointer text-center">
                                                                <span class="text-black text-[16px] font-semibold">
                                                                    Download {{ $material->title ?? 'Note' }}
                                                                </span>
                                                            </a>
                                                        @endforeach
                                                    </div>
                                                </div>
                                            @endif
                                        @endforeach
                                    @endforeach
                                @endforeach

                                @if (!$hasMaterial)
                                    <div class="text-center text-gray-400 py-10">
                                        <img src="/frontend/assets/icons/folder.svg" alt="Icon"
                                            class="mx-auto mb-4 w-10 opacity-60">
                                        <p>No materials available yet</p>
                                    </div>
                                @endif
                            </div>


                            <!-- TAB My Subjects -->
                            <div id="my-subjects" data-group="group-tabs-2" class="tab-content my-10 hidden">
                                @foreach ($mockMetrics as $metric)
                                <div class="bg-gray-975 rounded-[21px] p-10 mt-10 grid grid-cols-12">
                                    
                                    <div class="col-span-12 lg:col-span-4 lg:pr-10">
                                        <div class="text-white mb-3">Form {{ $metric['form'] }}</div>
                                        <div class="flex flex-col gap-3">
                                            <span class="text-gray-200">Current Subject:</span>
                                            <div
                                                class="bg-gray-1000 border border-gray-950 text-white placeholder:text-gray-500 rounded-[14px] w-full px-4 py-3 font-semibold">
                                                {{ $metric['subject'] }}
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div class="col-span-12 lg:col-span-4 border-x border-gray-510 lg:px-10 mt-6 lg:mt-0">
                                        <div class="mb-6">
                                            <div class="flex items-center gap-2 mb-2">
                                                <span class="text-gray-275 text-[15px]">Topics Covers:</span>
                                                <span class="text-white text-[15px]">{{ $metric['topics_covered'] }}/{{ $metric['total_schedules'] }}</span>
                                            </div>
                                            <div class="text-gray-275 text-[15px] mb-2">Overall Progress:</div>
                                            <div class="w-full lg:border border-[#523E06] rounded-full h-5">
                                                <div class="bg-[#523E06] h-5 rounded-full flex items-center justify-end px-3 transition-all duration-500"
                                                    style="width: {{ $metric['overall_progress'] }}%">
                                                    <span class="text-white text-[12px] font-medium">{{ $metric['overall_progress'] }}%</span>
                                                </div>
                                            </div>
                                        </div>
                                        
                                        <div class="mt-4">
                                            <div class="text-gray-275 text-[15px] mb-2">Attendance Rate:</div>
                                            <div class="w-full lg:border border-indigo-700/50 rounded-full h-5">
                                                <div class="bg-indigo-700/70 h-5 rounded-full flex items-center justify-end px-3 transition-all duration-500"
                                                    style="width: {{ $metric['attendance_rate'] }}%">
                                                    <span class="text-white text-[12px] font-medium">{{ $metric['attendance_rate'] }}%</span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div class="col-span-12 lg:col-span-4 lg:ps-10 mt-6 lg:mt-0">
                                        <div class="flex flex-col items-center mb-6">
                                            <span class="text-gray-275 text-[15px]">Avg Quiz Score:</span>
                                            <span class="text-white text-[56px] font-bold">{{ $metric['avg_quiz_score'] }}%</span>
                                        </div>

                                        <div class="flex flex-col items-center">
                                            <span class="text-gray-275 text-[15px]">Avg Replay Watch:</span>
                                            <span class="text-white text-[56px] font-bold">{{ $metric['avg_replay_watch'] }}%</span>
                                        </div>
                                    </div>
                                </div>

                                <div class="flex justify-center mt-10">
                                    <button type="button"
                                        class="bg-gray-50 hover:bg-gray-200 rounded-full text-sm px-5 py-3 w-[40%] cursor-pointer">
                                        <span class="text-black text-[16px] font-semibold">Continue Learning</span>
                                    </button>
                                </div>
                                @endforeach
                            </div>

                            <!-- TAB Subscription Info -->
                            <div id="subscription-info" data-group="group-tabs-2" class="tab-content hidden my-10">

                                @php $hasSubscription = false; @endphp

                                @foreach ($subscriptions as $subscription)
                                    @php $hasSubscription = true; @endphp
                                    <div
                                        class="border border-gray-510 rounded-[21px] p-6 flex flex-col lg:flex-row justify-between gap-5 bg-gray-925 mb-5">
                                        <div class="flex flex-col gap-2 text-white">
                                            <h6 class="text-[18px] font-semibold">
                                                {{ $subscription->name ?? 'Subscription Plan' }}</h6>
                                            <span class="text-gray-300 text-[14px]">Subscribed on:
                                                {{ \Carbon\Carbon::parse($subscription->created_at)->format('d M Y') }}</span>
                                            <span class="text-gray-300 text-[14px]">Expires on:
                                                {{ $subscription->end_date ? \Carbon\Carbon::parse($subscription->end_date)->format('d M Y') : 'No Expiration' }}</span>

                                            <div class="mt-4">
                                                <span class="text-gray-275 text-[13px]">Status :</span>
                                                @php
                                                    $status = $subscription->status ?? 'inactive';
                                                    $statusColors = [
                                                        'active' => 'bg-green-900 text-green-100',
                                                        'inactive' => 'bg-gray-700 text-white',
                                                        'expired' => 'bg-red-900 text-red-100',
                                                    ];
                                                @endphp
                                                <span
                                                    class="ml-2 inline-flex items-center justify-center px-3 py-1 rounded-full text-[13px] font-medium {{ $statusColors[$status] ?? 'bg-gray-700 text-white' }}">
                                                    {{ ucfirst($status) }}
                                                </span>
                                            </div>
                                        </div>

                                        <div class="flex flex-col justify-center items-start lg:items-end gap-3">
                                            <div class="flex items-center gap-2">
                                                <img src="/frontend/assets/icons/books.svg" alt="Icon"
                                                    class="size-5">
                                                <span class="text-white text-[15px]">
                                                    {{ $subscription->classrooms->count() ?? 0 }} Classes Included
                                                </span>
                                            </div>

                                            {{-- <button type="button"
                                                class="bg-gray-50 hover:bg-gray-200 rounded-full text-sm px-5 py-3 cursor-pointer">
                                                <span class="text-black text-[16px] font-semibold">View Details</span>
                                            </button> --}}
                                        </div>
                                    </div>
                                @endforeach

                                @if (!$hasSubscription)
                                    <div
                                        class="flex flex-col items-center justify-center gap-3 bg-black rounded-[21px] border border-gray-850 h-[250px] cursor-pointer">
                                        <img src="/frontend/assets/icons/subscription.svg" alt="Icon" class="w-16">
                                        <span class="text-[12px] text-white">No Active Subscription</span>
                                    </div>
                                @endif
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            document.querySelectorAll('[data-video-thumb]').forEach(img => {
                const video = document.createElement('video');
                video.src = img.dataset.videoThumb;
                video.crossOrigin = 'anonymous';
                video.muted = true;
                video.playsInline = true;
                video.preload = 'metadata';
                video.addEventListener('loadedmetadata', () => {
                    video.currentTime = 0.5;
                });
                video.addEventListener('seeked', () => {
                    const canvas = document.createElement('canvas');
                    canvas.width = video.videoWidth;
                    canvas.height = video.videoHeight;
                    const ctx = canvas.getContext('2d');
                    ctx.drawImage(video, 0, 0, canvas.width, canvas.height);
                    img.src = canvas.toDataURL('image/jpeg');
                    video.remove();
                });
                video.addEventListener('error', () => {
                    img.src = '/frontend/assets/images/sample/image-1.png';
                });
            });
        });
    </script>
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const carousel = document.getElementById('classCarousel');
            const next = document.getElementById('nextClass');
            const prev = document.getElementById('prevClass');
            const scrollAmount = 160;
            if (!carousel || !next || !prev) return;
            next.addEventListener('click', () => {
                carousel.scrollBy({
                    left: scrollAmount,
                    behavior: 'smooth'
                });
            });
            prev.addEventListener('click', () => {
                carousel.scrollBy({
                    left: -scrollAmount,
                    behavior: 'smooth'
                });
            });
        });
    </script>
    <script>
        $(".tab-btn").on("click", function() {
            const group = $(this).data("group");
            const target = $(this).data("tab");

            $(`.tab-content[data-group='${group}']`).addClass("hidden");
            $(target).removeClass("hidden");

            $(`.tab-btn[data-group='${group}']`).removeClass("active");
            $(this).addClass("active");
        });
    </script>
@endpush
