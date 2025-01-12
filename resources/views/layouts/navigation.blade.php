<nav class="bg-white shadow fixed w-full z-50">
    <div class="mx-auto px-5 pt-3 pb-0 md:px-10 md:pb-3 xl:px-52">
        <div class="flex items-center justify-between">
            <!-- <div class="text-xl lg:text-xl font-bold"><a href="{{ route('dashboard') }}">2</a></div> -->
            <img width="65" height="65" src="{{ asset('img/system/16.png') }}" alt="User Icon" class="mr-2" />
            <div class="lg:hidden">
                <button id="menu-toggle" class="text-gray-500 focus:outline-none">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M4 6h16M4 12h16m-7 6h7"></path>
                    </svg>
                </button>
            </div>
            @if(!Auth::check())
            <div class="hidden font-semibold lg:flex space-x-6">
                <a href="{{ route('login') }}" class="text-gray-900 hover:text-gray-500">Login</a>
                <a href="{{ route('register') }}" class="text-gray-900 hover:text-gray-500">Register</a>
            </div>
            @else
            <div class="hidden font-semibold lg:flex space-x-6">
                <a href="{{ route('dashboard') }}" class="text-gray-900 hover:text-gray-500">Home</a>
                <a href="{{ route('booking') }}" class="text-gray-900 hover:text-gray-500">Booking</a>
                <button id="profile-toggle" class="text-gray-500 focus:outline-none flex items-center">
                <img width="20" height="20" src="{{ asset('img/system/userNav.png') }}" alt="User Icon" />
                    <span class="text-gray-900 ml-1">{{ Auth::user()->name }}</span>
                    <!-- You can use the icon below for a dropdown -->
                    <svg class="fill-current h-4 w-4 ml-1" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                    </svg>
                </button>
            </div>
            @endif

        </div>
    </div>

    <!--  -->
    

    <!-- Mobile Menu -->

    @if(!Auth::check())
    <div id="mobile-menu" class="hidden lg:hidden fixed left-0 w-full bg-white z-50">
        <a href="{{ route('login') }}" class="block px-4 py-2 text-gray-700 hover:bg-gray-200">Login</a>
        <a href="{{ route('register') }}" class="block px-4 py-2 text-gray-700 hover:bg-gray-200">Register</a>
    </div>
    @else
    <div id="mobile-menu" class="hidden lg:hidden fixed left-0 w-full bg-white z-50">
        <a href="{{ route('dashboard') }}" class="block px-4 py-2 text-gray-700 hover:bg-gray-200">Home</a>
        <a href="{{ route('booking') }}" class="block px-4 py-2 text-gray-700 hover:bg-gray-200">Booking</a>
        <a href="{{ route('profile.edit') }}" class="block px-4 py-2 text-gray-700 hover:bg-gray-200">Profile</a>
        <a href="{{ route('logout') }}" class="block px-4 pt-2 pb-6 text-gray-700 hover:bg-gray-200"
            onclick="event.preventDefault(); document.getElementById('logout-form').submit();">Log Out</a>
    </div>
    

    <!-- Profile Menu -->
    <div id="profile-menu" class="hidden absolute top-15 w-40 md:right-10 xl:right-52 bg-white shadow-lg">
        <a href="{{ route('profile.edit') }}" class="block px-4 py-2 text-gray-700 hover:bg-gray-200">Profile</a>
        <a href="{{ route('logout') }}" class="block px-4 pt-2 pb-4 text-gray-700 hover:bg-gray-200"
            onclick="event.preventDefault(); document.getElementById('logout-form').submit();">Log Out</a>
    </div>

    <form id="logout-form" method="POST" action="{{ route('logout') }}">
        @csrf
    </form>
@endif


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
