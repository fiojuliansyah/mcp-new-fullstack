<div id="mobileSidebarOverlay" class="fixed left-0 right-0 top-20 md:top-24 bottom-0 bg-transparent pointer-events-none hidden z-50"></div>
	<aside id="mobileSidebar" class="fixed left-0 top-20 md:top-24 bottom-0 z-60 w-72 max-w-[85%] bg-primary text-white transform -translate-x-full transition-transform duration-300 lg:hidden h-[calc(100vh-80px)] md:h-[calc(100vh-96px)]">
		<div class="h-full flex flex-col">
			<div class="flex items-center justify-between px-4 h-16 border-b border-white/10">
				<div class="flex items-center gap-2">
					<img src="/admin/assets/images/mcp-logo.png" alt="" class="h-10" />
					<span class="text-sm font-semibold">Menu</span>
				</div>
				<button id="closeSidebarBtn" class="inline-flex w-9 h-9 items-center justify-center rounded-md hover:bg-white/10" aria-label="Close sidebar">
					<iconify-icon icon="majesticons:close" width="20" height="20"></iconify-icon>
				</button>
			</div>
			<nav class="overflow-y-auto py-2">
				<ul class="divide-y divide-white/10 px-1">
					<li>
						<a href="dashboard-1.1.html" data-sidebar-link="dashboard" class="flex items-center gap-3 px-4 py-3 hover:bg-[#1E1E1F] rounded-md transition">
							<iconify-icon icon="solar:home-2-bold" class="text-white" width="20" height="20"></iconify-icon>
							<span class="text-sm">Dashboard</span>
						</a>
					</li>
					<li>
						<details class="group">
							<summary class="flex items-center gap-3 px-4 py-3 cursor-pointer hover:bg-[#1E1E1F] rounded-md transition list-none" data-sidebar-link="user-management">
								<iconify-icon icon="solar:users-group-rounded-bold" class="text-white" width="20" height="20"></iconify-icon>
								<span class="text-sm flex-1">User Management</span>
								<iconify-icon icon="majesticons:chevron-down" class="transition group-open:rotate-180" width="18" height="18"></iconify-icon>
							</summary>
							<div class="bg-white/[0.03]">
								<a href="#" data-sidebar-link="user-management:students" class="block px-12 py-2 text-sm hover:bg-[#1E1E1F]">Students</a>
								<a href="./user-management-parent-view-1.1.html" data-sidebar-link="user-management:parents" class="block px-12 py-2 text-sm hover:bg-[#1E1E1F]">Parents</a>
								<a href="#" data-sidebar-link="user-management:tutors" class="block px-12 py-2 text-sm hover:bg-[#1E1E1F]">Tutors</a>
							</div>
						</details>
					</li>
					<li>
						<a href="subscription-view-1.1.html" data-sidebar-link="subscriptions" class="flex items-center gap-3 px-4 py-3 hover:bg-[#1E1E1F] rounded-md transition">
							<iconify-icon icon="solar:home-2-bold" class="text-white" width="20" height="20"></iconify-icon>
							<span class="text-sm">Subscriptions</span>
						</a>
					</li>
					<li>
						<a href="classes-view-1.1.html" data-sidebar-link="classes" class="flex items-center gap-3 px-4 py-3 hover:bg-[#1E1E1F] rounded-md transition">
							<iconify-icon icon="solar:home-2-bold" class="text-white" width="20" height="20"></iconify-icon>
							<span class="text-sm">Classes</span>
						</a>
					</li>
					<li>
						<details class="group">
							<summary class="flex items-center gap-3 px-4 py-3 cursor-pointer hover:bg-[#1E1E1F] rounded-md transition list-none" data-sidebar-link="study-materials">
								<iconify-icon icon="solar:users-group-rounded-bold" class="text-white" width="20" height="20"></iconify-icon>
								<span class="text-sm flex-1">Study Materials</span>
								<iconify-icon icon="majesticons:chevron-down" class="transition group-open:rotate-180" width="18" height="18"></iconify-icon>
							</summary>
							<div class="bg-white/[0.03]">
								<a href="#" data-sidebar-link="study-materials:students" class="block px-12 py-2 text-sm hover:bg-[#1E1E1F]">Sub Menu</a>
							</div>
						</details>
					</li>
					<li>
						<a href="report-1.1.html" data-sidebar-link="report" class="flex items-center gap-3 px-4 py-3 hover:bg-[#1E1E1F] rounded-md transition">
							<iconify-icon icon="solar:home-2-bold" class="text-white" width="20" height="20"></iconify-icon>
							<span class="text-sm">Report</span>
						</a>
					</li>
					<li>
						<a href="#" data-sidebar-link="customer-support" class="flex items-center gap-3 px-4 py-3 hover:bg-[#1E1E1F] rounded-md transition">
							<iconify-icon icon="solar:home-2-bold" class="text-white" width="20" height="20"></iconify-icon>
							<span class="text-sm">Customer Support</span>
						</a>
					</li>
				</ul>
			</nav>
		</div>
	</aside>