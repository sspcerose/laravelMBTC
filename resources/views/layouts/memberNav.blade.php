@extends('layout.layout')

@if(Auth::guard('member')->check())
<head>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" integrity="sha384-k6RqeWeci5ZR/Lv4MR0sA0FfDOMXQ8cBO5Tmy8Z+5QoV9D0HnQn4E9E5mCkF1" crossorigin="anonymous">
</head>

<nav class="bg-slate-900 shadow fixed w-full z-50">
    <div class="py-3 px-4 md:px-10">
        <div class="flex justify-start items-center space-x-4">
            <div>
                <button id="menu-toggle" class="text-gray-500 focus:outline-none">
                    <svg class="w-7 h-7 md:w-9 md:h-9" fill="none" stroke="white" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="square" stroke-linejoin="miter" stroke-width="3" d="M4 6h16M4 12h16M4 18h16"></path>
                    </svg>
                </button>
            </div>
            <div class="sidebar-brand flex items-center justify-between w-full">
                <!-- <h1 class="text-xl font-bold text-white">MBTC</h1> -->
                <img width="65" height="65" src="{{ asset('img/system/17.png') }}" alt="User Icon" class="mr-2" />
                <div class="flex items-center ml-auto">
                    <img width="20" height="20" src="{{ asset('img/system/memNav.png') }}" alt="User Icon" class="mr-2" />
                    <!-- <p class="text-sm font-bold text-white">Driver {{ Auth::guard('member')->user()->name }}</p> -->
                    @if($memberType->type != 'Owner')
                    <p class="text-sm font-bold text-white">Driver {{ Auth::guard('member')->user()->name }}</p>
                    @else
                    <p class="text-sm font-bold text-white">Owner {{ Auth::guard('member')->user()->name }}</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</nav>

<!-- Icon-Only Sidebar (Visible by Default on Medium Screens) -->
<div id="icon-sidebar" class="hidden md:block fixed inset-y-0 left-0 z-40 bg-gray-800 bg-opacity-90 transition-opacity duration-300" style="top: 4rem;">
    <div class="absolute w-16 h-full bg-neutral-200 flex flex-col items-center pt-10 space-y-5">
    @if($memberType->type != 'Owner')
        <a href="{{ route('member.dashboard') }}" class="tab-link text-slate-900 hover:text-slate-400 transition-colors duration-300 flex items-center justify-center w-10 h-10 rounded-lg" data-tab="Home">
            <i class="fas fa-home fa-xl"></i>
        </a>
        <a href="{{ route('member.membermonthlydues') }}" class="tab-link text-slate-900 hover:text-slate-400 transition-colors duration-300 flex items-center justify-center w-10 h-10 rounded-lg" data-tab="Booking">
            <i class="fas fa-calendar-check fa-xl"></i>
        </a>
        <a href="{{ route('member.profile.edit') }}" class="tab-link text-slate-900 hover:text-slate-400 transition-colors duration-300 flex items-center justify-center w-10 h-10 rounded-lg" data-tab="Profile">
            <i class="fas fa-user fa-xl"></i>
        </a>
        <a href="{{ route('member.logout') }}" class="tab-link text-slate-900 hover:text-slate-400 transition-colors duration-300 flex items-center justify-center w-10 h-10 rounded-lg"
            onclick="event.preventDefault(); document.getElementById('logout-form').submit();"><i class="fas fa-sign-out-alt fa-xl"></i></a>
    @else
        <a href="{{ route('member.membermonthlydues') }}" class="tab-link text-slate-900 hover:text-slate-400 transition-colors duration-300 flex items-center justify-center w-10 h-10 rounded-lg" data-tab="Booking">
            <i class="fas fa-calendar-check fa-xl"></i>
        </a>
        <a href="{{ route('member.profile.edit') }}" class="tab-link text-slate-900 hover:text-slate-400 transition-colors duration-300 flex items-center justify-center w-10 h-10 rounded-lg" data-tab="Profile">
            <i class="fas fa-user fa-xl"></i>
        </a>
        <a href="{{ route('member.logout') }}" class="tab-link text-slate-900 hover:text-slate-400 transition-colors duration-300 flex items-center justify-center w-10 h-10 rounded-lg"
            onclick="event.preventDefault(); document.getElementById('logout-form').submit();"><i class="fas fa-sign-out-alt fa-xl"></i></a>
    @endif
    </div>
</div>

<form id="logout-form" method="POST" action="{{ route('member.logout') }}">
    @csrf
</form>
    
<!-- Original Sidebar (Hidden by Default) -->
<div id="sidebar" class="fixed inset-y-0 left-0 z-40 bg-gray-800 bg-opacity-75 hidden transition-opacity duration-300" style="top: 4rem;">
    <div class="absolute text-xl font-semibold top-0 left-0 w-56 h-full bg-neutral-200 p-5">
        <ul class="mt-4 space-y-2">
        @if($memberType->type != 'Owner')
            <li>
                <a href="{{ route('member.dashboard') }}" class="tab-link block text-slate-900 hover:text-slate-400 p-2 rounded-lg font-bold" data-tab="Home"><i class="fas fa-home mr-4 w-5"></i>Home</a>
            </li>
            <li>
                <a href="{{ route('member.membermonthlydues') }}" class="tab-link block text-slate-900 hover:text-slate-400 p-2 rounded-lg font-bold" data-tab="Booking"><i class="fas fa-user mr-4 w-5"></i>Monthly Dues</a>
            </li>
            <li>
                <a href="{{ route('member.profile.edit') }}" class="tab-link block text-slate-900 hover:text-slate-400 p-2 rounded-lg font-bold" data-tab="Profile"><i class="fas fa-user mr-4 w-5"></i>Profile</a>
            </li>
            <li>
                <a href="{{ route('member.logout') }}" class="tab-link text-red-700 hover:text-red-400 transition-colors duration-300 flex items-center justify-center w-10 h-10 rounded-lg" onclick="event.preventDefault(); document.getElementById('logout-form').submit();" data-tab="Logout"><i class="fas fa-sign-out-alt fa-xl"></i></a>
            </li>
        @else
        <li>
                <a href="{{ route('member.membermonthlydues') }}" class="tab-link block text-slate-900 hover:text-slate-400 p-2 rounded-lg font-bold" data-tab="Booking"><i class="fas fa-user mr-4 w-5"></i>Monthly Dues</a>
            </li>
            <li>
                <a href="{{ route('member.profile.edit') }}" class="tab-link block text-slate-900 hover:text-slate-400 p-2 rounded-lg font-bold" data-tab="Profile"><i class="fas fa-user mr-4 w-5"></i>Profile</a>
            </li>
            <li>
                <a href="{{ route('member.logout') }}" class="tab-link text-red-700 hover:text-red-400 transition-colors duration-300 flex items-center justify-center w-10 h-10 rounded-lg" onclick="event.preventDefault(); document.getElementById('logout-form').submit();" data-tab="Logout"><i class="fas fa-sign-out-alt fa-xl"></i></a>
            </li>
        @endif
        </ul>
    </div>
</div>

<script>
    const menuToggle = document.getElementById('menu-toggle');
    const sidebar = document.getElementById('sidebar');
    const iconSidebar = document.getElementById('icon-sidebar');
    const tabLinks = document.querySelectorAll('.tab-link');

    // Toggle the sidebars
    menuToggle.addEventListener('click', () => {
        iconSidebar.classList.toggle('hidden'); // Toggle icon-only sidebar visibility
        sidebar.classList.toggle('hidden'); // Toggle original sidebar visibility
    });

    // Function to update active state for both sidebars
    function updateActiveState(clickedTab) {
        const activeTabName = clickedTab.getAttribute('data-tab');

        // Reset colors for all icon tabs
        tabLinks.forEach(tab => {
            tab.classList.remove('text-white', 'bg-slate-900'); 
            tab.classList.add('text-slate-900'); 
        });

        // Reset colors for all expanded sidebar tabs
        document.querySelectorAll('#sidebar .tab-link').forEach(tab => {
            tab.classList.remove('bg-slate-900', 'text-white'); // Remove previous active background
            tab.classList.add('text-slate-900'); // Reset to default icon color
        });

        // Add active color and background to the clicked icon
        clickedTab.classList.add('text-white', 'bg-slate-900', 'rounded-lg'); 

        // Sync with the expanded sidebar
        const expandedTab = document.querySelector(`#sidebar .tab-link[data-tab="${activeTabName}"]`);
        if (expandedTab) {
            expandedTab.classList.add('bg-slate-900', 'text-white'); 
            expandedTab.classList.remove('text-slate-900'); 
        }
    }

    // Set the initial active state for the tab based on the current URL
    function setInitialActiveState() {
        const currentPath = window.location.pathname;

        // Find the tab link that matches the current path
        const matchingTab = Array.from(tabLinks).find(link => link.href.includes(currentPath));

        if (matchingTab) {
            updateActiveState(matchingTab); // Update to active state for the matching tab
        } else {
            // If no match is found, default to Home
            const homeTab = document.querySelector('.tab-link[data-tab="Home"]');
            if (homeTab) {
                updateActiveState(homeTab); // Set default to Home tab
            }
        }
    }

    // Set the active tab when clicked
    tabLinks.forEach(link => {
        link.addEventListener('click', function() {
            updateActiveState(this); 
        });
    });

    // Sync the expanded sidebar tab clicks with the icon sidebar
    document.querySelectorAll('#sidebar .tab-link').forEach(link => {
        link.addEventListener('click', function() {
            const correspondingIconTab = document.querySelector(`.tab-link[data-tab="${this.getAttribute('data-tab')}"]`);
            updateActiveState(correspondingIconTab); 
        });
    });

    // Function to handle screen resizing
    const handleResize = () => {
        const mediaQuery = window.matchMedia("(max-width: 767px)"); 
        if (mediaQuery.matches) {
            iconSidebar.classList.add('hidden'); // Hide icon sidebar on small screens
            sidebar.classList.add('hidden'); // Optionally hide the original sidebar
        } else {
            iconSidebar.classList.remove('hidden'); // Show icon sidebar on medium+ screens
        }
    };

    setInitialActiveState(); // Set initial active state
    handleResize(); // Set initial visibility based on screen size

    // Listen for window resize events
    window.addEventListener('resize', handleResize);
</script>
@endif
