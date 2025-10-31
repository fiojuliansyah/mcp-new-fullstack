<nav class="w-full h-20 md:h-24 bg-zinc-50 px-4">
    <div class="w-full h-full max-w-screen-xl mx-auto flex items-center justify-between">
        <div class="flex items-center">
            <button id="openSidebarBtn" class="lg:hidden inline-flex items-center justify-center w-10 h-10 rounded-md hover:bg-zinc-200 focus:outline-none" aria-label="Open sidebar">
                <iconify-icon icon="majesticons:menu" width="22" height="22"></iconify-icon>
            </button>
            <img src="/frontend/assets/images/mcp-logo-2.png" alt="" class="h-16" />
        </div>

        <div class="relative flex items-center space-x-3" x-data="{ open: false }">
            <span class="text-zinc-500 font-medium text-sm">{{ Auth::user()->name }}</span>
            <button
                @click="open = !open"
                class="w-10 h-10 flex items-center justify-center transition-all duration-300 hover:bg-gray-800 bg-black rounded-full ring-2 ring-black ring-offset-2">
                @if (Auth::user()->avatar_url)
                <img src="{{ Auth::user()->avatar_url }}" class="rounded-full" alt="">
                @else
                <svg class="w-4" viewBox="0 0 20 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path
                        d="M17.7642 5.2339L15.0074 6.25421C14.9126 7.51597 15.1358 8.75453 14.7512 9.98219C13.278 14.6868 6.39287 14.7421 4.83315 10.0599C4.41627 8.8091 4.64772 7.54325 4.55225 6.25421L0.609352 4.75034C0.00359716 4.42637 0.0406841 3.5411 0.687647 3.29352L9.52122 0L10.0789 0.0102304L19.0478 3.374C19.3081 3.55951 19.3871 3.77299 19.4138 4.08331C19.2971 5.97458 19.5629 8.03568 19.4138 9.90716C19.3596 10.5844 18.7854 11.1437 18.1268 10.7195C18.0389 10.6629 17.7635 10.3443 17.7635 10.2625V5.2339H17.7642ZM16.2182 4.00897L9.75817 1.66279L3.34074 4.03421L9.79732 6.44723C11.8323 5.62538 13.9387 4.96791 15.9744 4.14879C16.0437 4.12082 16.2065 4.08604 16.2189 4.00897H16.2182ZM13.3343 6.86736C12.1324 7.24929 10.9814 7.80992 9.76298 8.12434L6.22597 6.86736V8.93527C6.22597 9.21149 6.54671 9.94399 6.69986 10.2018C8.08582 12.5412 11.561 12.5071 12.8975 10.1363C13.0362 9.89011 13.3343 9.19376 13.3343 8.93596V6.86804V6.86736Z"
                        fill="white" />
                    <path
                        d="M17.9891 23.1346C17.8675 23.0207 17.783 22.8324 17.7652 22.6674C17.6835 21.9178 17.8229 21.0325 17.7672 20.2659C17.6368 18.4633 16.1038 17.1279 14.5551 16.4043C14.2584 16.2658 13.3731 15.89 13.0902 15.8594C13.0325 15.8532 12.9899 15.8532 12.9473 15.8989C12.2976 16.4643 11.7028 17.2568 11.0414 17.7902C10.641 18.1128 10.1232 18.2976 9.60123 18.2505C8.38904 18.1414 7.46598 16.5489 6.55117 15.8594C4.49902 16.3634 1.9723 17.9204 1.79785 20.2175C1.74222 20.9534 1.92353 22.102 1.78549 22.7581C1.63096 23.4926 0.464778 23.6058 0.215471 22.8058C0.0266017 22.2015 0.145418 20.0518 0.275909 19.3725C0.747738 16.9254 3.6522 14.8356 5.988 14.3132C7.71804 13.9258 8.43917 15.6425 9.5978 16.5687C9.70494 16.6451 9.85878 16.6444 9.96455 16.5687C10.6143 16.0019 11.2083 15.2148 11.8663 14.676C12.6595 14.0267 13.3566 14.2198 14.2378 14.5233C16.5935 15.3342 19.0413 17.1982 19.3613 19.809C19.4492 20.5272 19.4842 21.7957 19.4066 22.5078C19.3242 23.2689 18.5674 23.6788 17.9891 23.1346Z"
                        fill="white" />
                </svg>
                @endif
            </button>
            <iconify-icon icon="majesticons:chevron-down" width="24" height="24" @click="open = !open"></iconify-icon>

            <div
                x-cloak
                x-show="open"
                @click.away="open = false"
                x-transition
                class="absolute right-0 top-14 w-48 bg-white border border-gray-100 rounded-xl shadow-lg overflow-hidden z-50">
                <div class="px-4 py-3 border-b border-gray-100">
                    <p class="text-sm font-semibold text-gray-800">{{ Auth::user()->name }}</p>
                    <p class="text-xs text-gray-500">{{ Auth::user()->email }}</p>
                </div>
                <ul class="text-sm text-gray-700">
                    <li>
                        @if (Auth::user()->account_type === 'student')
                            <a href="{{ route('student.profile') }}" class="block px-4 py-2 hover:bg-gray-100">Profile</a>
                        @endif
                    </li>
                    <li>
                        @if (Auth::user()->account_type === 'student')
                            <a href="{{ route('student.dashboard') }}" class="block px-4 py-2 hover:bg-gray-100">Dashboard</a>   
                        @elseif (Auth::user()->account_type === 'parent')
                            <a href="#" class="block px-4 py-2 hover:bg-gray-100">Dashboard</a>   
                        @else
                            <a href="{{ route('tutor.dashboard') }}" class="block px-4 py-2 hover:bg-gray-100">Dashboard</a>   
                        @endif
                    </li>
                    <li>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="w-full text-left px-4 py-2 text-red-600 hover:bg-gray-100">
                                Logout
                            </button>
                        </form>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</nav>
