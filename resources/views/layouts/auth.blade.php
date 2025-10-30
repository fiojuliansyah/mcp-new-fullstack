<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MCP Website</title>
    <!-- FONTS -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Bebas+Neue&family=Inter:ital,opsz,wght@0,14..32,100..900;1,14..32,100..900&family=Rethink+Sans:ital,wght@0,400..800;1,400..800&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="/asset/js/tailwind.config.js"></script>
    <script src="/asset/css/app.css"></script>
    <!-- SWIPERJS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" />
    <script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>
    <!-- IKON -->
    <script src="https://code.iconify.design/iconify-icon/1.0.5/iconify-icon.min.js"></script>
    <link rel="stylesheet" href="/frontend/assets/css/app.css" />
    @stack('styles')
</head>
<body class="bg-black text-white">
    <nav class="w-full fixed top-0 left-0 z-50 font-inter p-4">
        <div class="w-full max-w-screen-xl mx-auto flex items-center justify-between">
            <img src="/frontend/assets/images/main-logo.png" alt="" class="h-16" />
            <!-- NAV LIST -->
            <ul class="flex items-center space-x-3">
                <li>
                    <a href="#" class="text-xs uppercase">Help</a>
                </li>
            </ul>
        </div>
    </nav>

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
                <div class="w-full grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-y-10 md:gap-y-0 items-end">
                    <!-- LEFT FORM -->
                    <form action="#" method="POST" class="w-full">
                        <label for="subscribe" class="text-sm text-zinc-500 pb-2">Newsletter</label>
                        <div class="relative w-full h-10 md:h-12">
                            <input type="text" id="subscribe" placeholder="Enter your email" class="w-full h-full flex rounded-full text-sm text-black border px-4" />
                            <button class="absolute top-0 right-0 w-32 h-full text-sm bg-black text-white text-center rounded-full">Subscribe</button>
                        </div>
                    </form>
                    <!-- CENTER SOSMED -->
                    <div class="w-full flex md:justify-end md:items-end space-x-3">
                        <a href="#" class="w-10 h-10 flex border border-black rounded-full transition-all duration-300 hover:bg-black hover:bg-black/50"></a>
                        <a href="#" class="w-10 h-10 flex border border-black rounded-full transition-all duration-300 hover:bg-black hover:bg-black/50"></a>
                        <a href="#" class="w-10 h-10 flex border border-black rounded-full transition-all duration-300 hover:bg-black hover:bg-black/50"></a>
                        <a href="#" class="w-10 h-10 flex border border-black rounded-full transition-all duration-300 hover:bg-black hover:bg-black/50"></a>
                    </div>
                    <!-- RIGHT COPYRIGHT -->
                    <div class="w-full flex md:justify-end text-black md:col-span-2 lg:col-span-1 md:pt-10 lg:pt-0">
                        <p class="text-sm text-end">©  2025 copyright. All right reserved</p>
                    </div>
                </div>
            </div>
        </div>
    </footer>

    @stack('scripts')
</body>
</html>