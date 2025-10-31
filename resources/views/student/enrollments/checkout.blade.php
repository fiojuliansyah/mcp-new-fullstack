@extends('layouts.app')

@section('content')
    <section class="w-full bg-primary text-white px-4 py-10">
        <div class="w-full max-w-screen-xl mx-auto">
            <div class="flex flex-col lg:flex-row justify-between gap-5 lg:items-end border-b border-white/10">
                <div class="flex items-center gap-3">
                    <img src="/frontend/assets/images/student-profile-vector.svg" alt="Student Avatar" class="w-28" />
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

            <div class="space-y-10 divide-y divide-zinc-700">
                <form id="enrollment-form" method="POST" action="{{ route('student.enrollment.store') }}">
                    @csrf
                    <div class="pt-10">
                        <div class="grid grid-cols-12 gap-10 mb-10">
                            <div class="col-span-12">
                                <div class="flex items-center gap-10">
                                    <a href="#"
                                        class="bg-white rounded-full w-8 h-8 flex items-center justify-center cursor-pointer">
                                        <img src="/frontend/assets/icons/arrow-left.svg" alt="Icon" class="size-4">
                                    </a>
                                    <h6 class="text-[20px] text-gray-75 font-semibold">Normal Class</h6>
                                </div>
                            </div>

                            <div class="col-span-12 grid grid-cols-12 gap-10">
                                <div class="col-span-12 lg:col-span-8">
                                    <div class="bg-gray-990 rounded-[21px] py-5 px-8">
                                        <div class="flex items-end mb-3">
                                            <h6 class="text-[15px] text-purple-100 font-bold">Important Notes</h6>
                                        </div>
                                        <div class="flex flex-col mb-3">
                                            <span class="text-white font-bold">What is “Join Waitlist”?</span>
                                            <p class="text-[#494949] text-[12px]">If a class is full, you can still enrol by
                                                joining the waitlist. You'll be added automatically when a slot becomes
                                                available.</p>
                                        </div>
                                        <div class="flex flex-col">
                                            <span class="text-white font-bold">Joining Late? No Problem! (Pro Rated)</span>
                                            <p class="text-[#494949] text-[12px]">Even if you join halfway (Pro Rated),
                                                you’ll still get full access to all video recordings, notes, and learning
                                                materials from earlier classes.</p>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-span-12 lg:col-span-4">
                                    <div class="bg-gray-990 rounded-[21px] py-5 px-8 h-full" id="subscription-includes">
                                        <h6 class="text-[15px] text-purple-100 font-bold mb-5">Your Subscription Includes:
                                        </h6>
                                        <div class="grid grid-cols-12 gap-5" id="includes-list">
                                            <div class="col-span-12 text-gray-400 text-[12px] italic">
                                                Select a plan to view included features
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="pt-10">
                        <div class="grid grid-cols-12 gap-10">
                            <div class="col-span-12 lg:col-span-8 pr-5 border-r border-[#1F1F1F]">
                                <section class="flex flex-col gap-5 mb-8">
                                    <div class="flex gap-3">
                                        <span class="text-gray-100 font-semibold">Select Total Subjects</span>
                                        <span class="text-gray-250">How many live classes do you want weekly?</span>
                                    </div>

                                    <select id="total-subjects"
                                        class="bg-gray-1000 border border-gray-950 text-gray-75 placeholder:text-gray-500 rounded-[14px] w-full lg:w-[50%] px-4 py-3"
                                        name="total_subjects">
                                        <option value="">Choose</option>
                                        <option value="1">1 Class</option>
                                        <option value="2">2 Classes</option>
                                        <option value="3">3 Classes</option>
                                        <option value="4">4 Classes</option>
                                        <option value="5">5 Classes</option>
                                    </select>
                                </section>

                                <section class="flex flex-col gap-5 border-b border-[#1F1F1F] pb-10 mb-10">
                                    <div class="flex gap-3">
                                        <span class="text-gray-100 font-semibold">Choose Your Plan</span>
                                        <span class="text-gray-250">How long do you want to subscribe?</span>
                                    </div>

                                    <div class="grid grid-cols-12 gap-8">
                                        @forelse ($plans as $plan)
                                            <div class="col-span-6 lg:col-span-4">
                                                <label
                                                    class="flex items-center gap-3 bg-gray-925 text-white rounded-[15px] py-3 px-6 cursor-pointer">
                                                    <input type="radio" name="plan_id" value="{{ $plan->id }}"
                                                        class="hidden peer" required />
                                                    <span
                                                        class="w-6 h-6 rounded-full border-2 border-gray-300 flex items-center justify-center peer-checked:border-white peer-checked:bg-white">
                                                        <span
                                                            class="w-3 h-3 rounded-full bg-black peer-checked:bg-black"></span>
                                                    </span>
                                                    <span class="text-base">{{ $plan->name }}</span>
                                                </label>
                                            </div>
                                        @empty
                                            <div class="col-span-12 text-gray-400 italic">No plans available.</div>
                                        @endforelse
                                    </div>
                                </section>

                                <section class="flex flex-col gap-5 mb-5">
                                    <div class="flex flex-col lg:flex-row gap-3">
                                        <span class="text-gray-100 font-semibold">Choose Subjects & Tutors</span>
                                        <span class="text-gray-250">You can choose multiple tutors & groups as long as the
                                            timing doesn’t clash</span>
                                    </div>
                                    <div class="flex flex-col lg:flex-row items-center justify-between gap-5 mb-5">
                                        <div class="flex flex-col lg:flex-row lg:items-center gap-3 w-full">
                                            <label class="text-gray-200 w-[100px]">Filter By:</label>
                                            <div class="w-full lg:w-[400px]">
                                                <select id="filter-form"
                                                    class="bg-gray-1000 border border-gray-950 text-white placeholder:text-gray-500 rounded-[14px] block w-full lg:w-[250px] px-4 py-3">
                                                    <option value="">Choose Subject</option>
                                                    @foreach ($classrooms->unique('subject_id') as $classroom)
                                                        <option value="{{ $classroom->subject->id }}">{{ $classroom->subject->name }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        <button type="button"
                                            class="relative p-[2px] bg-gradient-to-r from-[#B9048D] to-[#FE9D01] rounded-full flex items-center justify-center">
                                            <div
                                                class="bg-black hover:bg-gradient-to-r from-[#B9048D] to-[#FE9D01] text-white hover:text-black rounded-full text-sm px-5 py-3 w-full lg:w-[230px] cursor-pointer">
                                                <span class="text-[16px] font-semibold">Use AI Suggestion</span>
                                            </div>
                                        </button>
                                    </div>

                                    <div class="grid grid-cols-12 gap-10">
                                        @forelse ($schedules as $schedule)
                                            <div class="col-span-12 lg:col-span-4">
                                                <div class="bg-gray-900 rounded-[21px] text-white flex flex-col h-full schedule-item"
                                                    data-schedule-id="{{ $schedule->id }}" 
                                                    data-subject-id="{{ $schedule->classroom->subject->id ?? '' }}">
                                                    <div
                                                        class="relative w-full h-[500px] lg:h-[250px] overflow-hidden rounded-t-[13px]">
                                                        <img src="/frontend/assets/images/sample/image-1.png" alt="Image"
                                                            class="w-full h-full object-cover object-top">
                                                        <div class="absolute bottom-0 p-2 w-full">
                                                            <div class="flex items-center justify-between">
                                                                <span
                                                                    class="text-gray-75 text-[34px] font-bebas">{{ $schedule->classroom->name }}</span>
                                                                <div class="flex flex-col items-end text-[12px]">
                                                                    <span>Students</span>
                                                                    <span>70 / 82</span>
                                                                </div>
                                                            </div>
                                                            <span
                                                                class="w-full inline-flex items-center justify-center bg-green-900 px-2 py-2 font-medium text-green-100 rounded-full">Available</span>
                                                        </div>
                                                    </div>

                                                    <div class="px-3 pt-4 pb-5 flex-1">
                                                        <span
                                                            class="block text-[15px] text-gray-50">{{ $schedule->classroom->user->name }}</span>
                                                        <div class="flex items-center gap-2 text-sm text-gray-50 mt-1">
                                                            <img src="/frontend/assets/icons/calendar.svg" alt="Icon"
                                                                class="size-4">
                                                            <span
                                                                class="text-gray-50">{{ \Carbon\Carbon::parse($schedule->time)->format('D, h:i A') }}</span>
                                                        </div>
                                                    </div>

                                                    <div
                                                        class="w-full flex items-center justify-between rounded-b-[21px] bg-gray-800 py-4 px-3 schedule-card-footer">
                                                        <span
                                                            class="schedule-price text-white text-lg sm:text-xl font-semibold"
                                                            data-base-price="0">RM - </span>

                                                        <label class="relative flex items-center cursor-pointer">
                                                            <input type="checkbox"
                                                                class="peer hidden schedule-checkbox" />
                                                            <span
                                                                class="w-6 h-6 flex items-center justify-center rounded-md 
                                                                border border-white bg-black peer-checked:bg-white
                                                                peer-checked:border-gray-400 transition-all duration-200">
                                                                <svg width="800px" height="800px" viewBox="0 0 24 24"
                                                                    fill="none" xmlns="http://www.w3.org/2000/svg">
                                                                    <path d="M15.5 8.99999L9.41421 15.0858"
                                                                        stroke="#020202" stroke-width="2"
                                                                        stroke-linecap="round" />
                                                                    <path d="M7 13.0858L9.41422 15.0858" stroke="#020202"
                                                                        stroke-width="2" stroke-linecap="round" />
                                                                </svg>
                                                            </span>
                                                        </label>
                                                    </div>
                                                </div>
                                            </div>
                                        @empty
                                            <div class="col-span-12 text-gray-400 italic">No schedules available.</div>
                                        @endforelse
                                    </div>
                                </section>
                            </div>

                            <div class="col-span-12 lg:col-span-4" id="payment-details">
                                <div class="flex items-center justify-between gap-3 mb-6">
                                    <h6 class="text-[20px] text-gray-75 font-semibold">Payment Details</h6>
                                    <span class="text-gray-75">{{ \Carbon\Carbon::now()->format('d M | D') }}</span>
                                </div>
                                <div class="flex items-center justify-between gap-3 border-b border-white mb-6 pb-5">
                                    <span class="text-[#868484]">Subject Details</span>
                                    <span class="text-[#868484]">Classes: 0</span>
                                </div>

                                <div class="flex flex-col gap-5 mb-3">
                                    <div class="text-gray-400 text-center italic">Choose a plan and select classes first.
                                    </div>
                                </div>

                                <div
                                    class="flex items-center justify-between bg-black rounded-[21px] text-gray-75 px-5 py-7 mb-5">
                                    <span>Total</span>
                                    <span>RM 0.00</span>
                                </div>

                                <button type="submit"
                                    class="block bg-gray-50 hover:bg-gray-200 rounded-full text-center text-sm px-5 py-3 w-full cursor-pointer">
                                    <span class="text-black text-[16px] font-semibold">Continue</span>
                                </button>

                                <input type="hidden" name="schedule_day" id="selectedSchedulesInput"
                                    value="" />

                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </section>
@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const planRadios = document.querySelectorAll('input[name="plan_id"]');
            const scheduleCheckboxes = document.querySelectorAll('.schedule-checkbox');
            const totalSubjectsSelect = document.getElementById('total-subjects');
            const priceElements = document.querySelectorAll('.schedule-price');
            const includesList = document.getElementById('includes-list');
            const paymentDetailsContainer = document.getElementById('payment-details');
            const subjectDetailsContainer = paymentDetailsContainer.querySelector('.flex.flex-col.gap-5.mb-3');
            const totalElement = paymentDetailsContainer.querySelector('.bg-black span:last-child');
            const classCountElement = paymentDetailsContainer.querySelector('.border-b span:last-child');
            const selectedSchedulesInput = document.getElementById('selectedSchedulesInput');
            const filterSelect = document.getElementById('filter-form');
            const scheduleItems = document.querySelectorAll('.schedule-item');

            const schedulesData = @json($schedules);
            const plans = @json($plans);

            let selectedPlan = null;
            let maxSelectable = 0;

            totalSubjectsSelect.addEventListener('change', function() {
                maxSelectable = parseInt(this.value) || 0;
                scheduleCheckboxes.forEach(cb => cb.checked = false);
                resetScheduleUI();
                updatePaymentDetails();
            });

            planRadios.forEach(radio => {
                radio.addEventListener('change', function() {
                    selectedPlan = plans.find(p => p.id == this.value);
                    if (!selectedPlan) return;
                    updatePrices();
                    updateIncludes();
                    updatePaymentDetails();
                });
            });

            scheduleCheckboxes.forEach(checkbox => {
                checkbox.addEventListener('change', function() {
                    const checkedCount = document.querySelectorAll('.schedule-checkbox:checked')
                        .length;

                    if (checkedCount > maxSelectable && maxSelectable > 0) {
                        this.checked = false;
                        alert(
                            `You can only select up to ${maxSelectable} class${maxSelectable > 1 ? 'es' : ''}.`);
                        return;
                    }

                    const footer = this.closest('.schedule-card-footer');
                    const card = checkbox.closest('[data-schedule-id]').querySelector(
                        '.bg-gray-900');
                    const priceText = footer.querySelector('.schedule-price');

                    if (this.checked) {
                        footer.classList.replace('bg-gray-800', 'bg-white');
                        priceText.classList.replace('text-white', 'text-gray-800');
                        if (card) card.classList.add('ring-2', 'ring-white');
                    } else {
                        footer.classList.replace('bg-white', 'bg-gray-800');
                        priceText.classList.replace('text-gray-800', 'text-white');
                        if (card) card.classList.remove('ring-2', 'ring-white');
                    }

                    updatePaymentDetails();
                });
            });

            function updatePrices() {
                const price = selectedPlan ? parseFloat(selectedPlan.price) : null;
                priceElements.forEach(priceEl => {
                    priceEl.textContent = price !== null ? `RM${price.toFixed(2)}` : 'RM -';
                    priceEl.dataset.basePrice = price !== null ? price : 0;
                });
            }

            function updateIncludes() {
                let html = '';
                if (!selectedPlan) {
                    includesList.innerHTML =
                        `<div class="col-span-12 text-gray-400 text-[12px] italic">Select a plan to view included features</div>`;
                    return;
                }
                if (selectedPlan.is_weekly_live_classes === 'yes') html += featureHTML('play.svg',
                    'Weekly Live Classes');
                if (selectedPlan.is_materials === 'yes') html += featureHTML('folder-solid.svg',
                    'Notes & Materials');
                if (selectedPlan.replay_day) html += featureHTML('playback.svg',
                    `${selectedPlan.replay_day} Days Replay Access`);
                if (selectedPlan.is_quizzes === 'yes') html += featureHTML('quiz.svg', 'Quizzes');
                includesList.innerHTML = html ||
                    `<div class="col-span-12 text-gray-400 text-[12px] italic">No features available for this plan.</div>`;
            }

            function featureHTML(icon, text) {
                return ` <div class="col-span-6 flex items-center gap-5"> <img src="/frontend/assets/icons/${icon}" alt="Icon" class="size-5"> <span class="text-white text-[12px]">${text}</span> </div>`;
            }

            function resetScheduleUI() {
                scheduleCheckboxes.forEach(cb => {
                    cb.checked = false;
                    const footer = cb.closest('.schedule-card-footer');
                    const card = cb.closest('[data-schedule-id]').querySelector('.bg-gray-900');
                    const priceText = footer.querySelector('.schedule-price');

                    if (footer) footer.classList.replace('bg-white', 'bg-gray-800');
                    if (priceText) priceText.classList.replace('text-gray-800', 'text-white');
                    if (card) card.classList.remove('ring-2', 'ring-white');
                });
            }

            function scheduleItemHTML(schedule, price) {
                const formattedTime = schedule.formatted_time; 

                return `
                    <div class="flex justify-between border-b border-[#1F1F1F] pb-3">
                        <div class="flex flex-col text-gray-75">
                            <h6 class="text-[20px] font-semibold">${schedule.classroom.user.name}</h6> 
                            <span class="mb-2 text-[14px] text-gray-50">${schedule.classroom.name}</span>
                            <span>${formattedTime}</span> 
                        </div>
                        <div class="flex flex-col items-end gap-3 text-gray-75">
                            <span>RM${price.toFixed(2)}</span>
                        </div>
                    </div>
                `;
            }

            function updatePaymentDetails() {
                const checkedSchedules = document.querySelectorAll('.schedule-checkbox:checked');
                let total = 0;
                let scheduleHtml = '';
                let selectedScheduleIds = [];

                if (!selectedPlan || maxSelectable === 0) {
                    subjectDetailsContainer.innerHTML =
                        `<div class="text-gray-400 text-center italic">Choose the number of subjects and a plan first.</div>`;
                    totalElement.textContent = 'RM 0.00';
                    classCountElement.textContent = 'Classes: 0';
                    selectedSchedulesInput.value = '';
                    return;
                }

                const planPrice = parseFloat(selectedPlan.price);

                checkedSchedules.forEach(checkbox => {
                    const card = checkbox.closest('[data-schedule-id]');
                    const scheduleId = card.dataset.scheduleId;
                    const schedule = schedulesData.find(s => s.id == scheduleId);

                    if (schedule) {
                        total += planPrice;
                        scheduleHtml += scheduleItemHTML(schedule, planPrice);
                        selectedScheduleIds.push(scheduleId);
                    }
                });

                selectedSchedulesInput.value = JSON.stringify(selectedScheduleIds); 

                subjectDetailsContainer.innerHTML = scheduleHtml ||
                    `<div class="text-gray-400 text-center italic">No classes selected.</div>`;
                totalElement.textContent = `RM${total.toFixed(2)}`;
                classCountElement.textContent = `Classes: ${checkedSchedules.length}`;
                
            }

            updatePaymentDetails();

            filterSelect.addEventListener('change', function () {
                const selectedSubjectId = this.value.trim();
                console.log("Selected Subject ID:", selectedSubjectId);

                scheduleItems.forEach(item => {
                    const itemSubjectId = item.dataset.subjectId?.toString().trim() || "";
                    console.log("Schedule:", item.dataset.scheduleId, "Subject:", itemSubjectId);

                    if (!selectedSubjectId || itemSubjectId === selectedSubjectId) {
                        item.style.display = "";
                    } else {
                        item.style.display = "none";
                    }
                });
            });

        });
    </script>
@endpush
