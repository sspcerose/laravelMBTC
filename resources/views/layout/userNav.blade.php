<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Responsive Navbar</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>

<body class="bg-gray-100">

    <nav class="bg-white shadow relative">

        <div class="container mx-auto px-32 pt-14 pb-5">
            <div class="flex items-center justify-between h-16">
                <div class="text-xl font-bold">MBTC</div>
                <div class="md:hidden">
                    <button id="menu-toggle" class="text-gray-500 focus:outline-none">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16m-7 6h7"></path>
                        </svg>
                    </button>
                </div>
                <div class="hidden md:flex space-x-4">
                    <a href="#" class="text-gray-700 hover:text-gray-900">Home</a>
                    <a href="#" class="text-gray-700 hover:text-gray-900">Booking</a>
                    <button id="profile-toggle" class="text-gray-500 focus:outline-none">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16m-7 6h7"></path>
                        </svg>
                    </button>
                </div>
            </div>
        </div>

        <!-- Mobile Menu -->
        <div id="mobile-menu" class="hidden md:hidden">
            <a href="#" class="block px-4 py-2 text-gray-700 hover:bg-gray-200">Home</a>
            <a href="#" class="block px-4 py-2 text-gray-700 hover:bg-gray-200">Booking</a>
            <a href="#" class="block px-4 py-2 text-gray-700 hover:bg-gray-200">Profile</a>
            <a href="#" class="block px-4 py-2 text-gray-700 hover:bg-gray-200">Log Out</a>
        </div>

        <!-- Profile Menu -->
        <div id="profile-menu" class="hidden absolute w-40 right-52 bg-white shadow-lg">
            <a href="#" class="block px-4 py-2 text-gray-700 hover:bg-gray-200">Profile</a>
            <a href="#" class="block px-4 py-2 text-gray-700 hover:bg-gray-200">Log Out</a>
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
