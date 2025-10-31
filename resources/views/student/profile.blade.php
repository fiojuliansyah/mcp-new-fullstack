@extends('layouts.app')

@section('content')
    <section class="w-full bg-primary text-white px-4 py-10">
        <div class="w-full max-w-screen-xl mx-auto">
            <!-- HEADER -->
            <div class="flex flex-col lg:flex-row justify-between gap-5 lg:items-end border-b border-white/10">
                <!-- LEFT SECTION -->
                <div class="flex items-center gap-3">
                    <img src="/frontend/assets/images/student-profile-vector.svg" alt="Tutor Avatar" class="w-28" />
                    <div>
                        <span class="text-gray-250">Student</span>
                        <h1 class="text-4xl font-bold tracking-tight text-white">My Profile</h1>
                    </div>
                </div>
            </div>

            <!-- CONTENT -->
            <div class="space-y-10 divide-y divide-zinc-700">
                <!-- FORM -->
                <div class="w-full pt-10">
                    <div class="grid grid-cols-12">
                        <div class="col-span-12 md:col-span-12 lg:col-span-12">
                            <div class="flex items-center gap-3 mb-6">
                                <img src="/frontend/assets/icons/student.svg" alt="Icon" class="size-6" />
                                <h6 class="text-[20px] text-gray-75 font-semibold">
                                    Basic Profile
                                </h6>
                            </div>

                            <form method="POST" action="{{ route('student.profile.update') }}"
                                enctype="multipart/form-data">
                                @csrf
                                <div class="grid grid-cols-12 gap-5">
                                    <div class="col-span-12 lg:col-span-2">
                                        <div class="flex flex-col gap-3 items-center">
                                            <div
                                                class="bg-gray-50 rounded-full w-[138px] h-[138px] flex items-center justify-center overflow-hidden">
                                                <img src="{{ $user->avatar_url ?? '/frontend/assets/icons/student-black.svg' }}"
                                                    alt="User Avatar" class="w-full h-full object-cover"
                                                    id="avatarPreview" />
                                            </div>

                                            <input type="file" name="avatar_url" id="avatarInput" accept="image/*"
                                                class="hidden">

                                            <div id="editAvatarButton"
                                                class="bg-gray-50 rounded-full w-[31px] h-[31px] flex items-center justify-center cursor-pointer">
                                                <img src="/frontend/assets/icons/pencil.svg" alt="Icon"
                                                    class="size-4 text-black" />
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-span-12 lg:col-span-10">
                                        <div class="grid grid-cols-12 gap-10">
                                            <div class="col-span-12">
                                                <div class="w-full">
                                                    <label for=""
                                                        class="block mb-2 text-[15px] font-medium text-gray-200 mb-1">Full
                                                        Name</label>
                                                    <input type="text" name="name"
                                                        class="bg-gray-1000 border border-gray-950 text-gray-75 placeholder:text-gray-500 rounded-[14px] w-full px-4 py-3"
                                                        value="{{ $user->name }}" required />
                                                </div>
                                            </div>
                                            <div class="col-span-12 lg:col-span-6">
                                                <div class="w-full">
                                                    <label for=""
                                                        class="block mb-2 text-[15px] font-medium text-gray-200 mb-1">NRIC</label>
                                                    <input type="number" name="ic_number"
                                                        class="bg-gray-1000 border border-gray-950 text-gray-75 placeholder:text-gray-500 rounded-[14px] w-full px-4 py-3"
                                                        value="{{ $user->ic_number }}" />
                                                </div>
                                            </div>
                                            <div class="col-span-12 lg:col-span-6">
                                                <div class="w-full">
                                                    <label for=""
                                                        class="block mb-2 text-[15px] font-medium text-gray-200 mb-1">Email
                                                        Address</label>
                                                    <input type="email" name="email"
                                                        class="bg-gray-1000 border border-gray-950 text-gray-75 placeholder:text-gray-500 rounded-[14px] w-full px-4 py-3"
                                                        value="{{ $user->email }}" required />
                                                </div>
                                            </div>
                                            <div class="col-span-12 lg:col-span-6">
                                                <div class="w-full">
                                                    <label for=""
                                                        class="block mb-2 text-[15px] font-medium text-gray-200 mb-1">Postcode</label>
                                                    <input type="number" name="postal_code"
                                                        class="bg-gray-1000 border border-gray-950 text-gray-75 placeholder:text-gray-500 rounded-[14px] w-full px-4 py-3"
                                                        value="{{ $user->postal_code }}" />
                                                </div>
                                            </div>
                                            <div class="col-span-12 lg:col-span-6">
                                                <div class="w-full">
                                                    <label for=""
                                                        class="block mb-2 text-[15px] font-medium text-gray-200 mb-1">Password</label>
                                                    <input type="password" name="password"
                                                        class="bg-gray-1000 border border-gray-950 text-gray-75 placeholder:text-gray-500 rounded-[14px] w-full px-4 py-3"
                                                        placeholder="Password" />
                                                </div>
                                            </div>
                                            <div class="col-span-12 lg:col-span-6">
                                                <div class="w-full">
                                                    <label for="password_confirmation"
                                                        class="block mb-2 text-[15px] font-medium text-gray-200 mb-1">Confirm
                                                        Password</label>
                                                    <input type="password" name="password_confirmation"
                                                        class="bg-gray-1000 border border-gray-950 text-gray-75 placeholder:text-gray-500 rounded-[14px] w-full px-4 py-3"
                                                        placeholder="Confirm Password" />
                                                </div>
                                            </div>
                                            <div class="col-span-12 lg:col-span-6">
                                                <div class="w-full">
                                                    <label for=""
                                                        class="block mb-2 text-[15px] font-medium text-gray-200 mb-1">Preferred
                                                        language</label>
                                                    <div class="relative">
                                                        <select name="language"
                                                            class="appearance-none pr-10 bg-gray-1000 border border-gray-950 text-gray-75 placeholder:text-gray-500 rounded-[14px] w-full px-4 py-3">
                                                            <option @if ($user->language === null) selected @endif>Choose
                                                            </option>
                                                            <option value="default"
                                                                @if ($user->language === 'default') selected @endif>Default
                                                            </option>
                                                            <option value="english"
                                                                @if ($user->language === 'english') selected @endif>English
                                                            </option>
                                                        </select>
                                                        <div
                                                            class="pointer-events-none absolute right-5 top-1/2 transform -translate-y-1/2 text-white">
                                                            <img src="/frontend/assets/icons/angle-down.svg"
                                                                alt="Icon">
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-span-12 lg:col-span-6 flex items-end">
                                                <button type="submit"
                                                    class="bg-gray-50 hover:bg-gray-200 rounded-full text-sm px-8 py-3 w-full lg:w-auto">
                                                    <span class="text-black text-[16px] font-semibold">Save Change</span>
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                <!-- SUBJECT ENROLLMENT -->
                <div class="w-full pt-10">
                    <div class="grid grid-cols-12">
                        <div class="col-span-12 md:col-span-12 lg:col-span-12">
                            <!-- HEADING -->
                            <div class="flex items-center gap-3 mb-6">
                                <img src="/frontend/assets/icons/books.svg" alt="Icon" class="size-6" />
                                <h6 class="text-[20px] text-gray-75 font-semibold">
                                    Subjects Enrolled
                                </h6>
                            </div>
                            <!-- CONTENT -->
                            <div class="grid grid-cols-12 gap-5">
                                <div class="col-span-12 lg:col-span-7">
                                    <div class="h-[60vh] lg:h-[90vh] overflow-y-auto bg-gray-975 rounded-[21px]">
                                        @forelse ($subscriptions as $sub)
                                            @foreach ($sub->classrooms as $class)
                                                @foreach ($class->schedules as $schedule)
                                                    <div class="flex flex-col gap-5 p-10">
                                                        <div class="flex flex-col border-b border-gray-510 pb-5">
                                                            <div class="flex items-center justify-between mb-3">
                                                                <div class="flex flex-col">
                                                                    <span
                                                                        class="text-white text-[15px] font-bold">{{ $schedule->classroom->subject->name }}</span>
                                                                    <span
                                                                        class="text-gray-75 text-[12px]">{{ $schedule->form->name }}</span>
                                                                </div>
                                                                @if ($sub->status === 'active')
                                                                    <span
                                                                        class="w-[150px] inline-flex items-center justify-center bg-green-900 px-2 py-2 font-medium text-green-100 rounded-full">Active</span>
                                                                @elseif ($sub->status === 'expired')
                                                                    <span
                                                                        class="w-[150px] inline-flex items-center justify-center bg-red-900 px-2 py-2 font-medium text-red-100 rounded-full">Expired</span>
                                                                @else
                                                                    <span
                                                                        class="w-[150px] inline-flex items-center justify-center bg-yellow-900 px-2 py-2 font-medium text-yellow-100 rounded-full">{{ ucfirst($subscription->status) }}</span>
                                                                @endif
                                                            </div>
                                                            <div class="flex gap-3 mb-5">
                                                                <span class="text-gray-275">Tutor:</span>
                                                                <span
                                                                    class="text-white">{{ $schedule->classroom->user->name }}</span>
                                                            </div>
                                                            <div class="flex justify-between items-center">
                                                                <div>
                                                                    <div class="flex items-center gap-8 mb-2">
                                                                        <div class="flex items-center gap-2">
                                                                            <img src="/frontend/assets/icons/calendar.svg"
                                                                                alt="Icon" class="size-5" />
                                                                            <span class="text-gray-275 text-[15px]">Start
                                                                                Date:</span>
                                                                            <span
                                                                                class="text-white text-[15px]">{{ $sub->start_date->format('d M Y') ?? '-' }}</span>
                                                                        </div>
                                                                        <div class="flex items-center gap-2">
                                                                            <img src="/frontend/assets/icons/calendar.svg"
                                                                                alt="Icon" class="size-5" />
                                                                            <span class="text-gray-275 text-[15px]">Expired
                                                                                Date:</span>
                                                                            <span
                                                                                class="text-white text-[15px]">{{ $sub->end_date->format('d M Y') ?? '-' }}</span>
                                                                        </div>
                                                                    </div>
                                                                    <div class="flex items-center gap-2">
                                                                        <img src="/frontend/assets/icons/replay.svg"
                                                                            alt="Icon" class="size-5" />
                                                                        <span class="text-gray-275 text-[15px]">Replay
                                                                            Access:</span>
                                                                        <span class="text-white text-[15px]">15 July</span>
                                                                    </div>
                                                                </div>
                                                                <div class="flex flex-col items-end justify-end">
                                                                    <span class="text-white text-[15px] mb-3">Progress:
                                                                        65%</span>
                                                                    <a href="{{ route('student.classrooms.index', $schedule->classroom->id) }}"
                                                                        class="bg-gray-50 hover:bg-gray-200 rounded-full text-sm px-5 py-3 w-[195px] text-center">
                                                                        <span
                                                                            class="text-black text-[16px] font-semibold">View
                                                                            More</span>
                                                                    </a>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                @endforeach
                                            @endforeach
                                        @empty
                                        @endforelse
                                    </div>
                                </div>
                                <div class="col-span-12 lg:col-span-5">
                                    <div class="h-[90vh] overflow-y-auto border-2 border-red-100 rounded-[21px]">
                                        <div class="flex flex-col gap-5 p-10 h-full">
                                            @forelse ($expiredSubscriptions as $sub)
                                                @foreach ($sub->classrooms as $class)
                                                    @foreach ($class->schedules as $schedule)
                                                        <div class="flex flex-col gap-5 pb-8 border-b border-gray-510">
                                                            <div class="flex items-center justify-between">
                                                                <div class="flex flex-col">
                                                                    <span class="text-white text-[15px] font-bold">{{ $class->subject->name }}</span>
                                                                    <span class="text-gray-75 text-[12px]">{{ $schedule->form->name }}</span>
                                                                </div>
                                                                <span
                                                                    class="w-[150px] inline-flex items-center justify-center bg-red-900 px-2 py-2 font-medium text-red-100 rounded-full">Expired</span>
                                                            </div>
                                                            <div class="flex items-center gap-2">
                                                                <img src="/frontend/assets/icons/calendar.svg"
                                                                    alt="Icon" class="size-5" />
                                                                <span class="text-gray-275 text-[15px]">Expired
                                                                    Date:</span>
                                                                <span class="text-white text-[15px]">{{ $sub->end_date->format('d M Y') ?? '-' }}</span>
                                                            </div>
                                                            <a href="#"
                                                                class="bg-gray-50 hover:bg-gray-200 rounded-full text-sm px-5 py-3 w-full">
                                                                <span class="text-black text-[16px] font-semibold">Renew
                                                                    Now</span>
                                                            </a>
                                                        </div>
                                                    @endforeach
                                                @endforeach
                                            @empty
                                            @endforelse
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="w-full pt-10">
                    <div class="flex items-center gap-3 mb-6">
                        <img src="/frontend/assets/icons/books.svg" alt="Icon" class="size-6" />
                        <h6 class="text-[20px] text-gray-75 font-semibold">
                            Subscribe To New Subjects
                        </h6>
                    </div>
                    <div class="w-full grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-5 gap-5 pt-5 pb-20">
                        @forelse ($availableSchedules as $schedule)
                        <a href="{{ route('student.enrollment.class-type') }}">
                            <div class="w-full bg-gray-900 rounded-[21px]">
                                <div class="relative">
                                    <div class="w-full h-48 rounded-lg overflow-hidden">
                                        <img src="{{ $schedule->classroom->user->avatar_url ?? '/frontend/assets/images/person-card-example.png' }}" 
                                            alt="{{ $schedule->classroom->subject->name }}"
                                            class="w-full object-cover" />
                                        <div class="w-full absolute bottom-0 left-0 flex flex-col justify-end py-4 px-8">
                                            <h2 class="text-lg uppercase font-semibold font-bebas">
                                                {{ $schedule->classroom->subject->name }}
                                            </h2>
                                            <p class="text-xs">{{ $schedule->form->name ?? '-' }}</p>
                                        </div>
                                    </div>
                                </div>
                                <div class="w-full">
                                    <div class="p-3 pb-7">
                                        <p class="text-white">{{ $schedule->classroom->user->name }}</p>
                                        <div class="flex items-center space-x-2">
                                            <img src="/frontend/assets/icons/calendar.svg" class="size-5" />
                                            <span class="text-sm">Start {{ $schedule->time->format('d M') ?? '-' }} | {{ $plan->replay_day }} day replay</span>
                                        </div>
                                    </div>
                                    <div class="flex items-center space-x-1 bg-[#202020] rounded-b-lg p-3">
                                        <h1 class="text-lg"></h1>
                                        <span class="text-zinc-500">RM{{ $plan->price }} / month</span>
                                    </div>
                                </div>
                            </div>
                        </a>
                        @empty
                            <p class="text-gray-400 col-span-full text-center py-10">No new subjects available</p>
                        @endforelse

                        <div class="w-full flex flex-col justify-center items-center space-y-3">
                            <button class="w-12 h-12 flex justify-center items-center bg-white rounded-full">
                                <svg width="8" height="11" viewBox="0 0 5 9" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M1 1L4 4.5L1 8" stroke="black" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                                </svg>
                            </button>
                            <span class="text-sm">View More</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection


@push('scripts')
    <script>
        document.getElementById('editAvatarButton').addEventListener('click', function() {
            document.getElementById('avatarInput').click();
        });

        document.getElementById('avatarInput').addEventListener('change', function(event) {
            const file = event.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    document.getElementById('avatarPreview').src = e.target.result;
                }
                reader.readAsDataURL(file);
            }
        });
    </script>
@endpush
