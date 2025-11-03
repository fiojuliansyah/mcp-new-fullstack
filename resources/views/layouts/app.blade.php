<!DOCTYPE html>
<html lang="en">

<head>
	<meta charset="UTF-8" />
	<meta name="viewport" content="width=device-width, initial-scale=1.0" />
	<title>MCP WEBSITE</title>
	<!-- FONTS -->
	<link rel="preconnect" href="https://fonts.googleapis.com" />
	<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
	<link
		href="https://fonts.googleapis.com/css2?family=Bebas+Neue&family=Inter:ital,opsz,wght@0,14..32,100..900;1,14..32,100..900&family=Rethink+Sans:ital,wght@0,400..800;1,400..800&display=swap"
		rel="stylesheet" />
	<!-- TAILWINDCSS -->
	<script src="https://cdn.tailwindcss.com"></script>
	<script src="/frontend/assets/js/tailwind.config.js"></script>
	<script src="/frontend/assets/css/app.css"></script>
	<!-- ICONIFY -->
	<script src="https://code.iconify.design/iconify-icon/1.0.5/iconify-icon.min.js"></script>
	<!-- SWIPERJS -->
	<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" />
	<script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
	<!-- INTERNAL CSS -->
	<link rel="stylesheet" href="/frontend/assets/css/app.css" />
	@stack('styles')
</head>

<body>
	@include('layouts.partials.navbar')

	@yield('content')

	<footer class="w-full bg-white">
		<div class="w-full max-w-screen-xl mx-auto px-4 py-10">
			<!-- LOGO -->
			<div class="w-full flex flex-col justify-center items-center space-y-5">
				<img src="/frontend/assets/images/main-logo.png" alt="" class="h-20" />
				<!-- MENU LIST -->
				<ul class="flex space-x-5 text-black py-3">
					<li>
						<a href="" class="hover:text-zinc-500">Home</a>
					</li>
					<li>
						<a href="" class="hover:text-zinc-500">Timetable</a>
					</li>
					<li>
						<a href="" class="hover:text-zinc-500">Classess</a>
					</li>
					<li>
						<a href="" class="hover:text-zinc-500">Inbox</a>
					</li>
					<li>
						<a href="" class="hover:text-zinc-500">FAQ</a>
					</li>
					<li>
						<a href="" class="hover:text-zinc-500">Support</a>
					</li>
				</ul>
				<!-- BOTTOM -->
				<div class="w-full grid grid-cols-12 md:grid-cols-2 lg:grid-cols-3 gap-y-10 lg:gap-y-0 lg:items-end">
					<!-- LEFT FORM -->
					<form action="#" method="POST" class="w-full col-span-12 lg:col-span-1 px-10 lg:px-0">
						<div for="subscribe" class="text-sm text-zinc-500 mb-2">Newsletter</div>
						<div class="relative w-full h-10 md:h-12">
							<input type="text" id="subscribe" placeholder="Enter your email"
								class="w-full h-full flex rounded-full text-sm text-black border px-4" />
							<button
								class="absolute top-0 right-0 w-32 h-full text-sm bg-black text-white text-center rounded-full">
								Subscribe
							</button>
						</div>
					</form>
					<!-- CENTER SOSMED -->
					<div
						class="w-full col-span-12 lg:col-span-1 flex justify-center lg:justify-end items-center lg:items-end space-x-3">
						<a href="#"
							class="w-10 h-10 flex rounded-full transition-all duration-300 hover:bg-black hover:bg-black/50">
							<img src="/frontend/assets/icons/social-media/facebook.svg" alt="Facebook">
						</a>
						<a href="#"
							class="w-10 h-10 flex rounded-full transition-all duration-300 hover:bg-black hover:bg-black/50">
							<img src="/frontend/assets/icons/social-media/instagram.svg" alt="Instagram">
						</a>
						<a href="#"
							class="w-10 h-10 flex rounded-full transition-all duration-300 hover:bg-black hover:bg-black/50">
							<img src="/frontend/assets/icons/social-media/tiktok.svg" alt="Tiktok">
						</a>
						<a href="#"
							class="w-10 h-10 flex rounded-full transition-all duration-300 hover:bg-black hover:bg-black/50">
							<img src="/frontend/assets/icons/social-media/youtube.svg" alt="Youtube">
						</a>
					</div>
					<!-- RIGHT COPYRIGHT -->
					<div
						class="w-full col-span-12 lg:col-span-1 flex items-center justify-center lg:justify-end text-black">
						<p class="text-sm">
							© 2025 copyright. All right reserved
						</p>
					</div>
				</div>
			</div>
		</div>
	</footer>

	<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
	@stack('scripts')
</body>

</html>