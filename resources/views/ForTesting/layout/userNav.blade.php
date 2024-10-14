<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Responsive Navbar</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    @vite('resources/css/app.css')
</head>

<body class="">

    <nav class="bg-white shadow fixed w-full z-50"> <!-- Added fixed and z-50 -->
        <div class="mx-auto px-5 pt-5 pb-3 md:px-10 md:pb-5 xl:px-52">
            <div class="flex items-center justify-between">
                <div class="text-xl lg:text-xl font-bold">MBTC</div>
                <div class="lg:hidden">
                    <button id="menu-toggle" class="text-gray-500 focus:outline-none">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M4 6h16M4 12h16m-7 6h7"></path>
                        </svg>
                    </button>
                </div>
                <div class="hidden font-semibold lg:flex space-x-6">
                    <a href="#" class="text-gray-900 hover:text-gray-500">Home</a>
                    <a href="#" class="text-gray-900 hover:text-gray-500">Booking</a>
                    <button id="profile-toggle" class="text-gray-500 focus:outline-none">
                        <img width="20" height="20"
                            src="{{ asset('img/userNav.png') }}"
                            alt="user-male-circle" />
                    </button>
                </div>
            </div>
        </div>

        <!-- Mobile Menu -->
        <div id="mobile-menu" class="hidden lg:hidden fixed left-0 w-full bg-white z-50"> <!-- Use fixed and w-full -->
            <a href="#" class="block px-4 py-2 text-gray-700 hover:bg-gray-200">Home</a>
            <a href="#" class="block px-4 py-2 text-gray-700 hover:bg-gray-200">Booking</a>
            <a href="#" class="block px-4 py-2 text-gray-700 hover:bg-gray-200">Profile</a>
            <a href="#" class="block px-4 pt-2 pb-6 text-gray-700 hover:bg-gray-200">Log Out</a>
        </div>

        <!-- Profile Menu -->
        <div id="profile-menu" class="hidden absolute top-15 w-40 md:right-10 xl:right-52 bg-white shadow-lg">
            <a href="#" class="block px-4 py-2 text-gray-700 hover:bg-gray-200">Profile</a>
            <a href="#" class="block px-4 pt-2 pb-4 text-gray-700 hover:bg-gray-200">Log Out</a>
        </div>
    </nav>

    <script>
        document.getElementById('menu-toggle').onclick = () => {
            document.getElementById('mobile-menu').classList.toggle('hidden');
        };

        const profileToggle = document.getElementById('profile-toggle');
        const profileMenu = document.getElementById('profile-menu');

        profileToggle.onclick = () => {
            profileMenu.classList.toggle('hidden');
        };

        document.addEventListener('click', (event) => {
            if (!profileMenu.contains(event.target) && !profileToggle.contains(event.target)) {
                profileMenu.classList.add('hidden');
            }
        });
    </script>
</body>

</html>