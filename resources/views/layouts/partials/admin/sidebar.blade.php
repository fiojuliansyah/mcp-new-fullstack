<div class="w-full bg-gray-1000 hidden lg:block">
    <div class="sticky top-24">
        <nav class="overflow-hidden">
            <ul id="admin-sidebar">
                <li>
                    <a href="{{ route('admin.dashboard') }}" data-sidebar-link="dashboard" class="flex items-center gap-3 px-4 py-4 hover:bg-[#1E1E1F] transition">
                        <iconify-icon icon="solar:home-2-bold" class="text-white" width="20" height="20"></iconify-icon>
                        <span class="text-sm">Dashboard</span>
                    </a>
                </li>
                <li>
                    <details class="group" id="menu-user-management">
                        <summary class="flex items-center gap-3 px-4 py-4 cursor-pointer hover:bg-[#1E1E1F] transition list-none" data-sidebar-link="user-management">
                            <iconify-icon icon="solar:users-group-rounded-bold" class="text-white" width="20" height="20"></iconify-icon>
                            <span class="text-sm flex-1">User Management</span>
                            <iconify-icon icon="majesticons:chevron-down" class="transition group-open:rotate-180" width="18" height="18"></iconify-icon>
                        </summary>
                        <div class="bg-white/[0.03]">
                            <a href="{{ route('admin.users.student') }}" data-sidebar-link="user-management:students" class="block px-12 py-2 text-sm hover:bg-[#1E1E1F]">Students</a>
                            <a href="{{ route('admin.users.parents.index') }}" data-sidebar-link="user-management:parents" class="block px-12 py-2 text-sm hover:bg-[#1E1E1F]">Parents</a>
                            <a href="{{ route('admin.users.tutors.index') }}" data-sidebar-link="user-management:tutors" class="block px-12 py-2 text-sm hover:bg-[#1E1E1F]">Tutors</a>
                        </div>
                    </details>
                </li>
                <li>
                    <details class="group" id="menu-user-management">
                        <summary class="flex items-center gap-3 px-4 py-4 cursor-pointer hover:bg-[#1E1E1F] transition list-none" data-sidebar-link="subscriptions">
                            <iconify-icon icon="solar:cash-out-bold" class="text-white" width="20" height="20"></iconify-icon>
                            <span class="text-sm flex-1">Subscriptions</span>
                            <iconify-icon icon="majesticons:chevron-down" class="transition group-open:rotate-180" width="18" height="18"></iconify-icon>
                        </summary>
                        <div class="bg-white/[0.03]">
                            <a href="{{ route('admin.subscriptions.index') }}" data-sidebar-link="subscriptions:subscription" class="block px-12 py-2 text-sm hover:bg-[#1E1E1F]">Subscriptions</a>
                            <a href="{{ route('admin.plans.index') }}" data-sidebar-link="subscriptions:plans" class="block px-12 py-2 text-sm hover:bg-[#1E1E1F]">Plans</a>
                            <a href="{{ route('admin.coupons.index') }}" data-sidebar-link="subscriptions:coupons" class="block px-12 py-2 text-sm hover:bg-[#1E1E1F]">Coupons</a>
                        </div>
                    </details>
                </li>
                <li>
                    <a href="{{ route('admin.classrooms.index') }}" data-sidebar-link="classes" class="flex items-center gap-3 px-4 py-4 hover:bg-[#1E1E1F] transition">
                        <iconify-icon icon="solar:alarm-play-bold" class="text-white" width="20" height="20"></iconify-icon>
                        <span class="text-sm">Classes</span>
                    </a>
                </li>
                <li>
                    <details class="group">
                        <summary class="flex items-center gap-3 px-4 py-4 cursor-pointer hover:bg-[#1E1E1F] transition list-none" data-sidebar-link="study-materials">
                            <iconify-icon icon="solar:documents-bold" class="text-white" width="20" height="20"></iconify-icon>
                            <span class="text-sm flex-1">Study Materials</span>
                            <iconify-icon icon="majesticons:chevron-down" class="transition group-open:rotate-180" width="18" height="18"></iconify-icon>
                        </summary>
                        <div class="bg-white/[0.03]">
                            <a href="{{ route('admin.replays.index') }}" data-sidebar-link="study-materials:replays" class="block px-12 py-2 text-sm hover:bg-[#1E1E1F]">Replay Video</a>
                            <a href="{{ route('admin.materials.index') }}" data-sidebar-link="study-materials:materials" class="block px-12 py-2 text-sm hover:bg-[#1E1E1F]">Notes & Materials</a>
                        </div>
                    </details>
                </li>
                <li>
                    <a href="report-1.1.html" data-sidebar-link="report" class="flex items-center gap-3 px-4 py-4 hover:bg-[#1E1E1F] transition">
                        <iconify-icon icon="solar:home-2-bold" class="text-white" width="20" height="20"></iconify-icon>
                        <span class="text-sm">Report</span>
                    </a>
                </li>
                <li>
                    <a href="#" data-sidebar-link="customer-support" class="flex items-center gap-3 px-4 py-4 hover:bg-[#1E1E1F] transition">
                        <iconify-icon icon="solar:home-2-bold" class="text-white" width="20" height="20"></iconify-icon>
                        <span class="text-sm">Customer Support</span>
                    </a>
                </li>
            </ul>
        </nav>
    </div>
</div>