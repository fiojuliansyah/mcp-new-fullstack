@extends('layouts.app')

@section('content')
    <section class="w-full bg-primary text-white px-4 py-10">
        <div class="w-full max-w-screen-xl mx-auto">
            <!-- WELCOME BACK -->
            <div class="flex items-center border-b border-zinc-800 space-x-5">
                <img src="/frontend/assets/images/parent-profile-vector.svg" alt="Tutor Avatar" class="w-28" />
                <div>
                    <p class="text-sm text-gray-400">Parent</p>
                    <h1 class="text-xl md:text-4xl font-bold">My Profile</h1>
                </div>
            </div>
            <!-- PROFILE -->
            <div class="space-y-10 divide-y divide-zinc-700">
                <!-- FORM -->
                <div class="pt-10">
                    <!-- HEADING -->
                    <div class="grid grid-cols-1 md:grid-cols-4 gap-5 mb-6">
                        <h2 class="flex items-center space-x-3 text-lg">
                            <svg class="w-4" viewBox="0 0 20 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path
                                    d="M17.7642 5.2339L15.0074 6.25421C14.9126 7.51597 15.1358 8.75453 14.7512 9.98219C13.278 14.6868 6.39287 14.7421 4.83315 10.0599C4.41627 8.8091 4.64772 7.54325 4.55225 6.25421L0.609352 4.75034C0.00359716 4.42637 0.0406841 3.5411 0.687647 3.29352L9.52122 0L10.0789 0.0102304L19.0478 3.374C19.3081 3.55951 19.3871 3.77299 19.4138 4.08331C19.2971 5.97458 19.5629 8.03568 19.4138 9.90716C19.3596 10.5844 18.7854 11.1437 18.1268 10.7195C18.0389 10.6629 17.7635 10.3443 17.7635 10.2625V5.2339H17.7642ZM16.2182 4.00897L9.75817 1.66279L3.34074 4.03421L9.79732 6.44723C11.8323 5.62538 13.9387 4.96791 15.9744 4.14879C16.0437 4.12082 16.2065 4.08604 16.2189 4.00897H16.2182ZM13.3343 6.86736C12.1324 7.24929 10.9814 7.80992 9.76298 8.12434L6.22597 6.86736V8.93527C6.22597 9.21149 6.54671 9.94399 6.69986 10.2018C8.08582 12.5412 11.561 12.5071 12.8975 10.1363C13.0362 9.89011 13.3343 9.19376 13.3343 8.93596V6.86804V6.86736Z"
                                    fill="white" />
                                <path
                                    d="M17.9891 23.1346C17.8675 23.0207 17.783 22.8324 17.7652 22.6674C17.6835 21.9178 17.8229 21.0325 17.7672 20.2659C17.6368 18.4633 16.1038 17.1279 14.5551 16.4043C14.2584 16.2658 13.3731 15.89 13.0902 15.8594C13.0325 15.8532 12.9899 15.8532 12.9473 15.8989C12.2976 16.4643 11.7028 17.2568 11.0414 17.7902C10.641 18.1128 10.1232 18.2976 9.60123 18.2505C8.38904 18.1414 7.46598 16.5489 6.55117 15.8594C4.49902 16.3634 1.9723 17.9204 1.79785 20.2175C1.74222 20.9534 1.92353 22.102 1.78549 22.7581C1.63096 23.4926 0.464778 23.6058 0.215471 22.8058C0.0266017 22.2015 0.145418 20.0518 0.275909 19.3725C0.747738 16.9254 3.6522 14.8356 5.988 14.3132C7.71804 13.9258 8.43917 15.6425 9.5978 16.5687C9.70494 16.6451 9.85878 16.6444 9.96455 16.5687C10.6143 16.0019 11.2083 15.2148 11.8663 14.676C12.6595 14.0267 13.3566 14.2198 14.2378 14.5233C16.5935 15.3342 19.0413 17.1982 19.3613 19.809C19.4492 20.5272 19.4842 21.7957 19.4066 22.5078C19.3242 23.2689 18.5674 23.6788 17.9891 23.1346Z"
                                    fill="white" />
                            </svg>
                            <span>Basic Profile</span>
                        </h2>
                        <div class="w-full md:col-span-3">
                            <p class="text-sm text-zinc-500">Account Created: Jan 2024</p>
                        </div>
                    </div>
                    <!-- FORM -->
                    <form method="POST" action="{{ route('parent.profile.update') }}" enctype="multipart/form-data">
                        @csrf
                        <div class="grid grid-cols-12 gap-5">
                            <div class="col-span-12 lg:col-span-2">
                                <div class="flex flex-col gap-3 items-center">
                                    <div
                                        class="bg-gray-50 rounded-full w-[138px] h-[138px] flex items-center justify-center overflow-hidden">
                                        <img src="{{ $user->avatar_url ?? '/frontend/assets/icons/student-black.svg' }}"
                                            alt="User Avatar" class="w-full h-full object-cover" id="avatarPreview" />
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
                                                    <img src="/frontend/assets/icons/angle-down.svg" alt="Icon">
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
                <div class="w-full pt-10">
                    <div class="grid grid-cols-1 md:grid-cols-4 gap-5">
                        <h2 class="flex items-center space-x-3 text-lg">
                            <svg class="w-4" viewBox="0 0 20 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path
                                    d="M17.7642 5.2339L15.0074 6.25421C14.9126 7.51597 15.1358 8.75453 14.7512 9.98219C13.278 14.6868 6.39287 14.7421 4.83315 10.0599C4.41627 8.8091 4.64772 7.54325 4.55225 6.25421L0.609352 4.75034C0.00359716 4.42637 0.0406841 3.5411 0.687647 3.29352L9.52122 0L10.0789 0.0102304L19.0478 3.374C19.3081 3.55951 19.3871 3.77299 19.4138 4.08331C19.2971 5.97458 19.5629 8.03568 19.4138 9.90716C19.3596 10.5844 18.7854 11.1437 18.1268 10.7195C18.0389 10.6629 17.7635 10.3443 17.7635 10.2625V5.2339H17.7642ZM16.2182 4.00897L9.75817 1.66279L3.34074 4.03421L9.79732 6.44723C11.8323 5.62538 13.9387 4.96791 15.9744 4.14879C16.0437 4.12082 16.2065 4.08604 16.2189 4.00897H16.2182ZM13.3343 6.86736C12.1324 7.24929 10.9814 7.80992 9.76298 8.12434L6.22597 6.86736V8.93527C6.22597 9.21149 6.54671 9.94399 6.69986 10.2018C8.08582 12.5412 11.561 12.5071 12.8975 10.1363C13.0362 9.89011 13.3343 9.19376 13.3343 8.93596V6.86804V6.86736Z"
                                    fill="white" />
                                <path
                                    d="M17.9891 23.1346C17.8675 23.0207 17.783 22.8324 17.7652 22.6674C17.6835 21.9178 17.8229 21.0325 17.7672 20.2659C17.6368 18.4633 16.1038 17.1279 14.5551 16.4043C14.2584 16.2658 13.3731 15.89 13.0902 15.8594C13.0325 15.8532 12.9899 15.8532 12.9473 15.8989C12.2976 16.4643 11.7028 17.2568 11.0414 17.7902C10.641 18.1128 10.1232 18.2976 9.60123 18.2505C8.38904 18.1414 7.46598 16.5489 6.55117 15.8594C4.49902 16.3634 1.9723 17.9204 1.79785 20.2175C1.74222 20.9534 1.92353 22.102 1.78549 22.7581C1.63096 23.4926 0.464778 23.6058 0.215471 22.8058C0.0266017 22.2015 0.145418 20.0518 0.275909 19.3725C0.747738 16.9254 3.6522 14.8356 5.988 14.3132C7.71804 13.9258 8.43917 15.6425 9.5978 16.5687C9.70494 16.6451 9.85878 16.6444 9.96455 16.5687C10.6143 16.0019 11.2083 15.2148 11.8663 14.676C12.6595 14.0267 13.3566 14.2198 14.2378 14.5233C16.5935 15.3342 19.0413 17.1982 19.3613 19.809C19.4492 20.5272 19.4842 21.7957 19.4066 22.5078C19.3242 23.2689 18.5674 23.6788 17.9891 23.1346Z"
                                    fill="white" />
                            </svg>
                            <span>Linked Children</span>
                        </h2>
                    </div>
                    <div class="w-full grid grid-cols-1 md:grid-cols-6 gap-5 pt-5">
                        @forelse ($user->children as $child)

                            <div class="w-full md:col-span-2 bg-gray-600 rounded-md px-4 py-3">
                                <div class="w-full flex items-center space-x-3 lg:space-x-5">
                                    <div
                                        class="w-12 min-w-12 h-12 lg:w-16 lg:min-w-16 lg:min-h-16 flex justify-center items-center bg-white rounded-full">
                                        <img src="{{ $child->avatar_url ?? 'path/to/default/avatar.svg' }}" 
                                            alt="{{ $child->name }}'s Avatar" class="rounded-full w-full h-full object-cover"/>
                                    </div>
                                    <div class="space-y-2">
                                        <p class="text-sm text-zinc-100 font-semibold">{{ $child->name }}</p>
                                    </div>
                                </div>
                                <div
                                    class="w-full flex flex-row sm:flex-col lg:flex-row justify-between items-center space-y-2 lg:space-y-0 pt-5">
                                    {{-- Asumsi 'form' adalah relasi, dan 'form' punya 'name' --}}
                                    <p class="text-sm text-zinc-500">{{ $child->form->name ?? 'N/A' }} ({{ date('Y') }})</p>
                                    <a href="{{ route('parent.dashboard.child', $child->slug) }}"
                                        class="w-auto inline-flex justify-center items-center space-x-2 text-sm bg-white text-black border border-zinc-300 rounded-full px-10 py-3">
                                        <span>View Progress</span>
                                    </a>
                                </div>
                            </div>
                            
                        @empty
                            <div class="w-full md:col-span-4 p-5 text-center bg-gray-600 rounded-md">
                                <p class="text-zinc-400">Anda belum menautkan anak manapun.</p>
                            </div>
                        @endforelse
                        <div
                            class="w-full flex flex-col justify-center items-center space-y-3 hovee bg-gray-600 border border-gray-secondary rounded-md px-4 py-3">
                            <div class="w-14 h-14 flex justify-center items-center bg-white text-black rounded-full">
                                <iconify-icon icon="ic:round-plus" width="24" height="24"></iconify-icon>
                            </div>
                            <span class="text-sm text-white text-center">Link Another Child</span>
                        </div>
                    </div>
                </div>
                <div class="w-full pt-10">
                    <!-- HEADING -->
                    <div class="grid grid-cols-1 md:grid-cols-4 gap-5">
                        <h2 class="flex items-center space-x-3 text-lg">
                            <svg class="w-6" xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                viewBox="0 0 24 24">
                                <path fill="currentColor"
                                    d="M19.9 12.66a1 1 0 0 1 0-1.32l1.28-1.44a1 1 0 0 0 .12-1.17l-2-3.46a1 1 0 0 0-1.07-.48l-1.88.38a1 1 0 0 1-1.15-.66l-.61-1.83a1 1 0 0 0-.95-.68h-4a1 1 0 0 0-1 .68l-.56 1.83a1 1 0 0 1-1.15.66L5 4.79a1 1 0 0 0-1 .48L2 8.73a1 1 0 0 0 .1 1.17l1.27 1.44a1 1 0 0 1 0 1.32L2.1 14.1a1 1 0 0 0-.1 1.17l2 3.46a1 1 0 0 0 1.07.48l1.88-.38a1 1 0 0 1 1.15.66l.61 1.83a1 1 0 0 0 1 .68h4a1 1 0 0 0 .95-.68l.61-1.83a1 1 0 0 1 1.15-.66l1.88.38a1 1 0 0 0 1.07-.48l2-3.46a1 1 0 0 0-.12-1.17ZM18.41 14l.8.9l-1.28 2.22l-1.18-.24a3 3 0 0 0-3.45 2L12.92 20h-2.56L10 18.86a3 3 0 0 0-3.45-2l-1.18.24l-1.3-2.21l.8-.9a3 3 0 0 0 0-4l-.8-.9l1.28-2.2l1.18.24a3 3 0 0 0 3.45-2L10.36 4h2.56l.38 1.14a3 3 0 0 0 3.45 2l1.18-.24l1.28 2.22l-.8.9a3 3 0 0 0 0 3.98m-6.77-6a4 4 0 1 0 4 4a4 4 0 0 0-4-4m0 6a2 2 0 1 1 2-2a2 2 0 0 1-2 2" />
                            </svg>
                            <span>My Preferences</span>
                        </h2>
                    </div>
                    <div class="w-full grid grid-cols-1 md:grid-cols-3 pt-5">

                        <div
                            class="w-full md:col-span-2 flex flex-col md:flex-row space-y-3 md:space-y-0 justify-between items-center md:space-x-5 p-5 md:p-10">
                            <div class="w-full md:w-auto flex justify-start items-center space-x-3 md:space-x-5">
                                <svg width="32" height="22" viewBox="0 0 37 25" fill="none"
                                    xmlns="http://www.w3.org/2000/svg">
                                    <g clip-path="url(#clip0_1_2161)">
                                        <path
                                            d="M36.9048 2.78V22.22C36.6287 23.753 35.4791 24.8643 33.8412 24.9965L3.14668 25C1.41951 24.8748 0.180542 23.7043 0.0163083 22.0452C0.0740608 15.5061 -0.105513 8.94435 0.107449 2.41739C0.472012 1.01217 1.65413 0.107826 3.14668 0L33.8412 0.00347826C35.3753 0.10087 36.7307 1.29652 36.9048 2.78ZM33.1581 2.08522H3.7621L16.3702 14.2017C17.6218 15.1791 19.2984 15.1791 20.5509 14.2017L33.1581 2.08522ZM2.17661 21.387L11.4657 12.4748L2.17661 3.61304V21.387ZM34.7436 21.387V3.61304L25.4545 12.4748L34.7436 21.387ZM33.1581 22.9148L23.8374 14.033C22.1545 15.7043 20.7639 17.1391 18.1326 17.0165C15.7891 16.9078 14.6024 15.4243 13.0305 14.033L3.7621 22.9148H33.1581Z"
                                            fill="white" />
                                        <g clip-path="url(#clip1_1_2161)">
                                            <path
                                                d="M34.5237 1.19141L20.7375 14.6621C19.3679 15.7488 17.5356 15.7488 16.1661 14.6621L2.38086 1.19141H34.5237Z"
                                                fill="white" />
                                        </g>
                                    </g>
                                    <defs>
                                        <clipPath id="clip0_1_2161">
                                            <rect width="36.9048" height="25" fill="white" />
                                        </clipPath>
                                        <clipPath id="clip1_1_2161">
                                            <rect width="32.1429" height="14.2857" fill="white"
                                                transform="translate(2.38086 1.19141)" />
                                        </clipPath>
                                    </defs>
                                </svg>
                                <div>
                                    <h1>Email Alert</h1>
                                    <span class="text-sm text-zinc-500">Get class updates via WhatsApp</span>
                                </div>
                            </div>
                            <div class="w-full md:w-auto flex justify-end items-center">
                                <button class="w-full sm:w-32 h-10 flex bg-zinc-500 rounded-full"></button>
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
