<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MCP Website</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Bebas+Neue&family=Inter:ital,opsz,wght@0,14..32,100..900;1,14..32,100..900&family=Rethink+Sans:ital,wght@0,400..800;1,400..800&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="/frontend/asset/js/tailwind.config.js"></script>
    <script src="/frontend/asset/css/app.css"></script>
    <script src="https://code.iconify.design/iconify-icon/1.0.5/iconify-icon.min.js"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" />
    <script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>

    <link rel="stylesheet" href="/frontend/assets/css/app.css" />
</head>
<body class="bg-black text-white">

    <nav class="w-full fixed top-0 left-0 z-50 font-inter p-4">
        <div class="w-full max-w-screen-xl mx-auto flex items-center justify-between">
            <img src="/frontend/assets/images/main-logo.png" alt="" class="h-16" />
            <!-- NAV LIST -->
            <ul class="flex items-center space-x-3">
                <li>
                    <a href="/" class="text-xs uppercase">Home</a>
                </li>
                <li>
                    <a href="/timetable.html" class="text-xs uppercase">Timetable</a>
                </li>
                <li>
                    <a href="/classess.html" class="text-xs uppercase">Classes</a>
                </li>
                <li>
                    <a href="{{ route('register') }}" class="text-xs uppercase">Register</a>
                </li>
                <li>
                    <a href="{{ route('started') }}" class="text-xs uppercase">Login</a>
                </li>
            </ul>
        </div>
    </nav>

    <header class="relative w-full h-screen md:max-h-[800px] flex items-center font-inter overflow-hidden px-4">
        <img src="/frontend/assets/images/header-bg.svg" alt="" class="w-full absolute top-0 right-0 -z-10" />
        <div class="w-full max-w-screen-lg mx-auto space-y-3">
            <h1 class="text-4xl md:text-7xl font-bold">Hello, there!</h1>
            <p class="md:text-2xl font-normal max-w-2xl">Welcome to MC+ live tuition, the largest online tuition platform in Malaysia</p>
            <a href="" class="inline-flex justify-center items-center border-2 rounded-full px-6 py-1.5">Get Started</a>
        </div>
    </header>

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

    <script>
        const swiperClassPreview = new Swiper('.swiper-class-previews', {
            direction: 'horizontal',
            loop: true,
            autoplay: {
                delay: 1000,
                disableOnInteraction: false,
                pauseOnMouseEnter: false
            },
            initialSlide: 1,
            breakpoints: {
                640: {
                    slidesPerView: 1,
                    spaceBetween: 5
                },
                768: {
                    slidesPerView: 2,
                    spaceBetween: 10,
                },
                1024: {
                    slidesPerView: 4,
                    spaceBetween: 20
                }
            }
        });

        const swiperQuickStudy = new Swiper('.swiper-quick-study', {
            direction: 'horizontal',
            loop: true,
            autoplay: {
                delay: 1000,
                disableOnInteraction: false,
                pauseOnMouseEnter: false
            },
            initialSlide: 1,
            breakpoints: {
                640: {
                    slidesPerView: 1,
                    spaceBetween: 5
                },
                768: {
                    slidesPerView: 2,
                    spaceBetween: 10,
                },
                1024: {
                    slidesPerView: 4,
                    spaceBetween: 20
                }
            }
        });

        const swiperNewsUpdate = new Swiper('.swiper-news-update', {
            direction: 'horizontal',
            loop: true,
            autoplay: {
                delay: 1000,
                disableOnInteraction: false,
                pauseOnMouseEnter: false
            },
            initialSlide: 1,
            breakpoints: {
                640: {
                    slidesPerView: 1,
                    spaceBetween: 5
                },
                768: {
                    slidesPerView: 2,
                    spaceBetween: 10,
                },
                1024: {
                    slidesPerView: 3,
                    spaceBetween: 20
                }
            }
        });
    </script>
</body>
</html>