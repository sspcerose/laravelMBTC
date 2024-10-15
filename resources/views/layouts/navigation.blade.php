<nav class="bg-white shadow fixed w-full z-50">
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
            @if(!Auth::guard('admin')->check() && !Auth::guard('member')->check())
            <div class="hidden font-semibold lg:flex space-x-6">
                <a href="{{ route('dashboard') }}" class="text-gray-900 hover:text-gray-500">Home</a>
                <a href="{{ route('booking') }}" class="text-gray-900 hover:text-gray-500">Booking</a>
                <button id="profile-toggle" class="text-gray-500 focus:outline-none">
                    <img width="20" height="20" src="{{ asset('img/userNav.png') }}" alt="User Icon" />
                </button>
            </div>
            @endif

            @if(Auth::guard('member')->check())
            <div class="hidden font-semibold lg:flex space-x-6">
                <a href="{{ route('member.dashboard') }}" class="text-gray-900 hover:text-gray-500">Home</a>
                <a href="{{ route('member.membermonthlydues') }}" class="text-gray-900 hover:text-gray-500">Monthly Dues</a>
                <button id="profile-toggle" class="text-gray-500 focus:outline-none">
                    <img width="20" height="20" src="{{ asset('img/userNav.png') }}" alt="User Icon" />
                </button>
            </div>
            
            @endif
        </div>
    </div>
    

    <!-- Mobile Menu -->
    @if(!Auth::guard('admin')->check() && !Auth::guard('member')->check())
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

    @if(Auth::guard('member')->check())
    <div id="mobile-menu" class="hidden lg:hidden fixed left-0 w-full bg-white z-50">
        <a href="{{ route('member.dashboard') }}" class="block px-4 py-2 text-gray-700 hover:bg-gray-200">Home</a>
        <a href="{{ route('member.membermonthlydues') }}" class="block px-4 py-2 text-gray-700 hover:bg-gray-200">Monthly Dues</a>
        <a href="{{ route('member.profile.edit') }}" class="block px-4 py-2 text-gray-700 hover:bg-gray-200">Member Profile</a>
        <a href="{{ route('member.logout') }}" class="block px-4 pt-2 pb-6 text-gray-700 hover:bg-gray-200"
            onclick="event.preventDefault(); document.getElementById('logout-form').submit();">Log Out</a>
    </div>
    

    <!-- Profile Menu -->
    <div id="profile-menu" class="hidden absolute top-15 w-40 md:right-10 xl:right-52 bg-white shadow-lg">
        <a href="{{ route('member.profile.edit') }}" class="block px-4 py-2 text-gray-700 hover:bg-gray-200">Member Profile</a>
        <a href="{{ route('member.logout') }}" class="block px-4 pt-2 pb-4 text-gray-700 hover:bg-gray-200"
            onclick="event.preventDefault(); document.getElementById('logout-form').submit();">Log Out</a>
    </div>

    <form id="logout-form" method="POST" action="{{ route('member.logout') }}">
        @csrf
    </form>
    @endif

    @if(Auth::guard('admin')->check())
    <div id="mobile-menu" class="hidden lg:hidden fixed left-0 w-full bg-white z-50">
        <a href="{{ route('admin.dashboard') }}" class="block px-4 py-2 text-gray-700 hover:bg-gray-200">Home</a>
        <a href="{{ route('admin.logout') }}" class="block px-4 pt-2 pb-6 text-gray-700 hover:bg-gray-200"
            onclick="event.preventDefault(); document.getElementById('logout-form').submit();">Log Out</a>
    </div>
    

    <!-- Profile Menu -->
    <div id="profile-menu" class="hidden absolute top-15 w-40 md:right-10 xl:right-52 bg-white shadow-lg">
        <a href="{{ route('admin.logout') }}" class="block px-4 pt-2 pb-4 text-gray-700 hover:bg-gray-200"
            onclick="event.preventDefault(); document.getElementById('logout-form').submit();">Log Out</a>
    </div>

    <form id="logout-form" method="POST" action="{{ route('admin.logout') }}">
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
