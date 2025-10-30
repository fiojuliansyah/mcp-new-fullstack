<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MCP WEBSITE</title>
	<!-- FONTS -->
	<link rel="preconnect" href="https://fonts.googleapis.com" />
	<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
	<link
		href="https://fonts.googleapis.com/css2?family=Bebas+Neue&family=Inter:ital,opsz,wght@0,14..32,100..900;1,14..32,100..900&family=Rethink+Sans:ital,wght@0,400..800;1,400..800&display=swap"
		rel="stylesheet" />
	<!-- TAILWINDCSS -->
	<script src="https://cdn.tailwindcss.com"></script>
	<script src="/admin/assets/js/tailwind.config.js"></script>
	<!-- ICONIFY -->
	<script src="https://code.iconify.design/iconify-icon/1.0.5/iconify-icon.min.js"></script>
	<!-- SWIPERJS -->
	<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" />
	<script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
	<!-- INTERNAL CSS -->
	<link rel="stylesheet" href="/admin/assets/css/app.css" />
	
	@stack('styles')
</head>
<body>
	@include('layouts.partials.admin.navbar')
	@include('layouts.partials.admin.aside')

	<section class="w-full min-h-[100vh] bg-primary text-white">
		<div class="w-full mx-auto">

			@include('layouts.partials.admin.header')

			
			<div class="grid grid-cols-1 lg:grid-cols-[280px_1fr]">
				
				@include('layouts.partials.admin.sidebar')
				
				@yield('content')

			</div>
		</div>
	</section>

	<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
	<script>
		(function() {
			const ACTIVE_CLASSES = ['bg-[#1E1E1F]'];
			const overlay = document.getElementById('mobileSidebarOverlay');
			const drawer = document.getElementById('mobileSidebar');
			const openBtn = document.getElementById('openSidebarBtn');
			const closeBtn = document.getElementById('closeSidebarBtn');
			function openDrawer() {
				if (!drawer || !overlay) return;
				drawer.classList.remove('-translate-x-full');
				overlay.classList.remove('hidden');
				document.body.classList.add('overflow-hidden');
			}
			function closeDrawer() {
				if (!drawer || !overlay) return;
				drawer.classList.add('-translate-x-full');
				overlay.classList.add('hidden');
				document.body.classList.remove('overflow-hidden');
			}
			openBtn && openBtn.addEventListener('click', openDrawer);
			closeBtn && closeBtn.addEventListener('click', closeDrawer);
			overlay && overlay.addEventListener('click', closeDrawer);
			document.addEventListener('keydown', (e) => { if (e.key === 'Escape') closeDrawer(); });

			// Accordion + Active state across both sidebars
			const links = Array.from(document.querySelectorAll('[data-sidebar-link]'));
			const allDetails = Array.from(document.querySelectorAll('nav details.group, aside details.group'));
			// Close all dropdowns initially (will reopen the active one below)
			allDetails.forEach(d => d.open = false);
			function clearActive() {
				links.forEach(el => el.classList.remove(...ACTIVE_CLASSES));
			}
			function setActiveByKey(key, options = { openParents: true }) {
				clearActive();
				const targets = Array.from(document.querySelectorAll('[data-sidebar-link="' + key + '"]'));
				targets.forEach(target => {
					target.classList.add(...ACTIVE_CLASSES);
					if (options.openParents) {
						const details = target.closest('details');
						if (details) details.open = true;
					}
				});
				localStorage.setItem('mcp_admin_active', key);
			}
			// Clicking summaries: toggle like accordion, keep drawer open on mobile, and only set active when opening
			const summaries = Array.from(document.querySelectorAll('summary[data-sidebar-link]'));
			summaries.forEach(sum => {
				sum.addEventListener('click', function(e) {
					const details = this.parentElement;
					const willOpen = !details.open;
					// accordion: close other details
					allDetails.forEach(d => { if (d !== details) d.open = false; });
					// manually control open to avoid double-toggle
					details.open = willOpen;
					if (willOpen) {
						const key = this.getAttribute('data-sidebar-link');
						if (key) setActiveByKey(key, { openParents: false });
					}
					e.preventDefault();
				});
			});
			// Clicking links: set active and close drawer (mobile) only for anchors
			links.forEach(el => {
				el.addEventListener('click', function(e) {
					if (this.tagName.toLowerCase() === 'summary') return; // handled above
					const key = this.getAttribute('data-sidebar-link');
					if (!key) return;
					setActiveByKey(key);
					// close drawer only when a real link clicked
					closeDrawer();
				});
			});
			const saved = localStorage.getItem('mcp_admin_active');
			if (saved) setActiveByKey(saved); else setActiveByKey('dashboard');
		})();
	</script>
    @stack('scripts')
</body>
</html>