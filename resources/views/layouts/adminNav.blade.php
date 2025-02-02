@extends('layout.layout')

@if(Auth::guard('admin')->check())
<head>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css"
        integrity="sha384-k6RqeWeci5ZR/Lv4MR0sA0FfDOMXQ8cBO5Tmy8Z+5QoV9hD0HnQn4E9E5mCkF1" crossorigin="anonymous">

        <script src="https://code.jquery.com/jquery-3.5.1.js"></script>
        <link href="https://cdn.datatables.net/v/dt/dt-2.1.8/b-3.1.2/r-3.0.3/datatables.min.css" rel="stylesheet">
        <script src="https://cdn.datatables.net/v/dt/dt-2.1.8/b-3.1.2/r-3.0.3/datatables.min.js"></script>
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>

<nav class="bg-slate-900 shadow fixed w-full z-50">
    <div class="py-3 px-4 md:px-10">
        <div class="flex justify-start items-center space-x-4">
            <div>
                <button id="menu-toggle" class="text-gray-500 focus:outline-none">
                    <svg class="w-7 h-7 md:w-9 md:h-9" fill="none" stroke="white" viewBox="0 0 24 24"
                        xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="square" stroke-linejoin="miter" stroke-width="3"
                            d="M4 6h16M4 12h16M4 18h16"></path>
                    </svg>
                </button>
            </div>

            <div class="sidebar-brand flex items-center justify-between w-full">
                <!-- <h1 class="text-xl lg:text-3xl font-bold text-white">2</h1> -->
                <img width="65" height="65" src="{{ asset('img/system/17.png') }}" alt="User Icon" class="mr-2" /> <!--<span class="text-white font-bold">MBTC</span> -->
                <div class="flex items-center ml-auto">
                    <img width="20" height="20" src="{{ asset('img/system/memNav.png') }}" alt="User Icon" class="mr-2" />
                    <p class="text-sm font-bold text-white">Admin</p>
                </div>
            </div>

            
        </div>
    </div>
</nav>

<!-- Icon-Only Sidebar (Always Visible on Large Screens) -->
<div id="icon-sidebar"
    class="hidden lg:block fixed inset-y-0 left-0 z-40 bg-gray-800 bg-opacity-90 transition-opacity duration-300"
    style="top: 4rem;">
    <div class="absolute w-16 h-full bg-neutral-200 flex flex-col items-center pt-10 space-y-5">
        <a href="{{ route('admin.dashboard') }}"
            class="tab-link text-slate-900 hover:text-slate-400 transition-colors duration-300 flex items-center justify-center w-10 h-10 rounded-lg"
            data-tab="Home">
            <i class="fas fa-home fa-xl"></i> <!-- Home -->
        </a>
        <a href="{{ route('admin.booking.booking') }}"
            class="tab-link text-slate-900 hover:text-slate-400 transition-colors duration-300 flex items-center justify-center w-10 h-10 rounded-lg"
            data-tab="Booking">
            <i class="fas fa-calendar-alt fa-xl"></i> <!-- Booking -->
        </a>
        <a href="{{ route('admin.schedule.schedule') }}"
            class="tab-link text-slate-900 hover:text-slate-400 transition-colors duration-300 flex items-center justify-center w-10 h-10 rounded-lg"
            data-tab="Schedule">
            <i class="fas fa-user-clock fa-xl"></i> <!-- Tarrifs -->
        </a>
        <a href="{{ route('admin.member.member') }}"
            class="tab-link text-slate-900 hover:text-slate-400 transition-colors duration-300 flex items-center justify-center w-10 h-10 rounded-lg"
            data-tab="Members">
            <i class="fas fa-users fa-xl"></i> <!-- Members -->
        </a>
        <a href="{{ route('admin.monthlydues.monthlydues')}}"
            class="tab-link text-slate-900 hover:text-slate-400 transition-colors duration-300 flex items-center justify-center w-10 h-10 rounded-lg"
            data-tab="Monthly Dues">
            <i class="fas fa-dollar-sign fa-xl"></i> <!-- Monthly Dues -->
        </a>
        <a href="{{ route('admin.tariff.tariff') }}"
            class="tab-link text-slate-900 hover:text-slate-400 transition-colors duration-300 flex items-center justify-center w-10 h-10 rounded-lg"
            data-tab="Tarrifs">
            <i class="fas fa-chart-bar fa-xl"></i> <!-- Tarrifs -->
        </a>
        <a href="{{ route('admin.vehicle.vehicle') }}"
            class="tab-link text-slate-900 hover:text-slate-400 transition-colors duration-300 flex items-center justify-center w-10 h-10 rounded-lg"
            data-tab="Vehicle">
            <i class="fas fa-car fa-xl"></i> <!-- Vehicle -->
        </a>
        <!-- <a href="{{ route('admin.customer.customer') }}"
            class="tab-link text-slate-900 hover:text-slate-400 transition-colors duration-300 flex items-center justify-center w-10 h-10 rounded-lg"
            data-tab="Customers">
            <i class="fa-solid fa-user-group fa-xl"></i> Customers
        </a> -->

        <a href="{{ route('admin.logout') }}" class="tab-link text-red-700 hover:text-red-400 transition-colors duration-300 flex items-center justify-center w-10 h-10 rounded-lg" data-tab="Logout"
            onclick="event.preventDefault(); document.getElementById('logout-form').submit();"><i class="fas fa-sign-out-alt fa-xl"></i> </a>

        <form id="logout-form" method="POST" action="{{ route('admin.logout') }}" class="tab-link text-red-700 hover:text-red-400 transition-colors duration-300 flex items-center justify-center w-10 h-10 rounded-lg" data-tab="Logout">
            @csrf
        </form>

    </div>
</div>

<!-- Original Sidebar (Hidden by Default) -->
<div id="sidebar" class="fixed inset-y-0 left-0 z-40 bg-gray-800 bg-opacity-75 hidden transition-opacity duration-300" style="top: 4rem;">
    <div class="absolute text-xl font-semibold top-0 left-0 w-64 h-full bg-neutral-200 p-5">
        <ul class="mt-4 space-y-2">
            <li>
                <a href="{{ route('admin.dashboard') }}" class="tab-link text-slate-900 hover:text-slate-400 p-2 rounded-lg font-bold bg-slate-900 flex items-center" data-tab="Home">
                    <i class="fas fa-home mr-4 w-5"></i> Home
                </a> <!-- Set as active -->
            </li>
            <li>
                <a href="{{ route('admin.booking.booking') }}" class="tab-link block text-slate-900 hover:text-slate-400 p-2 rounded-lg font-bold flex items-center" data-tab="Booking">
                    <i class="fas fa-calendar-alt mr-4 w-5"></i> Booking
                </a>
            </li>
            <li>
                <a href="{{ route('admin.schedule.schedule') }}" class="tab-link block text-slate-900 hover:text-slate-400 p-2 rounded-lg font-bold flex items-center" data-tab="Schedule">
                    <i class="fas fa-user-clock mr-4 w-5"></i> Driver's Schedule
                </a>
            </li>
            <li>
                <a href="{{ route('admin.member.member') }}" class="tab-link block text-slate-900 hover:text-slate-400 p-2 rounded-lg font-bold flex items-center" data-tab="Members">
                    <i class="fas fa-users mr-4 w-5"></i> Members
                </a>
            </li>
            <li>
                <a href="{{ route('admin.monthlydues.monthlydues')}}" class="tab-link block text-slate-900 hover:text-slate-400 p-2 rounded-lg font-bold flex items-center" data-tab="Monthly Dues">
                    <i class="fas fa-dollar-sign mr-4 w-5"></i> Monthly Dues
                </a>
            </li>
            <li>
                <a href="{{ route('admin.tariff.tariff') }}" class="tab-link block text-slate-900 hover:text-slate-400 p-2 rounded-lg font-bold flex items-center" data-tab="Tarrifs">
                    <i class="fas fa-chart-bar mr-4 w-5"></i> Tarrifs
                </a>
            </li>
            <li>
                <a href="{{ route('admin.vehicle.vehicle') }}" class="tab-link block text-slate-900 hover:text-slate-400 p-2 rounded-lg font-bold flex items-center" data-tab="Vehicle">
                    <i class="fas fa-car mr-4 w-5"></i> Vehicle
                </a>
            </li>
            <li>
                <a href="{{ route('admin.customer.customer') }}" class="tab-link block text-slate-900 hover:text-slate-400 p-2 rounded-lg font-bold flex items-center" data-tab="Customers">
                    <i class="fa-solid fa-user-group mr-4 w-5"></i> Customers
                </a>
            </li>
            <li>
            <a href="{{ route('admin.logout') }}" class="tab-link text-red-700 hover:text-red-400 transition-colors duration-300 flex items-center justify-center w-10 h-10 rounded-lg" data-tab="Logout"
            onclick="event.preventDefault(); document.getElementById('logout-form').submit();"><i class="fas fa-sign-out-alt fa-xl"></i> </a>
                
            <form id="logout-form" method="POST" action="{{ route('admin.logout') }}" class="tab-link text-red-700 hover:text-red-400 transition-colors duration-300 flex items-center justify-center w-10 h-10 rounded-lg" data-tab="Logout">
                @csrf
            </form>

            </li>
        </ul>
    </div>
</div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.7.1/jszip.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/vfs-fonts/2.0.3/vfs_fonts.js"></script>
<script src="https://cdn.datatables.net/buttons/2.2.3/js/buttons.html5.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.2.3/js/buttons.print.min.js"></script>

<script>
    const menuToggle = document.getElementById('menu-toggle');
    const sidebar = document.getElementById('sidebar');
    const tabLinks = document.querySelectorAll('.tab-link');
    const iconLinks = document.querySelectorAll('#icon-sidebar .tab-link');

    let activeTab = localStorage.getItem('activeTab') || 'Home'; 

    
    function setInitialActiveState() {
        const initialTab = document.querySelector(`.tab-link[data-tab="${activeTab}"]`);
        if (initialTab) {
            updateActiveState(initialTab);
        }
    }

    // Toggle only the expanded sidebar
    menuToggle.addEventListener('click', () => {
        sidebar.classList.toggle('hidden'); 
    });

    function updateActiveState(clickedTab) {
        const activeTabName = clickedTab.getAttribute('data-tab');

        // Reset all tab links in both sidebars
        tabLinks.forEach(tab => {
            tab.classList.remove('text-white', 'bg-slate-900', 'rounded-lg');
            tab.classList.add('text-slate-900');
        });

        iconLinks.forEach(icon => {
            icon.classList.remove('text-white', 'bg-slate-900');
            icon.classList.add('text-slate-900');
        });

        // Highlight the clicked tab in the expanded sidebar
        clickedTab.classList.add('text-white', 'bg-slate-900', 'rounded-lg');

        // Update the corresponding tab in the icon sidebar
        const iconTab = document.querySelector(`#icon-sidebar .tab-link[data-tab="${activeTabName}"]`);
        if (iconTab) {
            iconTab.classList.add('text-white', 'bg-slate-900');
            iconTab.classList.remove('text-slate-900');
        }

        // Update the expanded sidebar tab state
        const expandedTab = document.querySelector(`#sidebar .tab-link[data-tab="${activeTabName}"]`);
        if (expandedTab) {
            expandedTab.classList.add('bg-slate-900', 'text-white', 'rounded-lg');
            expandedTab.classList.remove('text-slate-900');
        }

        // Save the active tab to localStorage
        localStorage.setItem('activeTab', activeTabName);
    }

    // Set the active tab when clicking on the icon sidebar
    iconLinks.forEach(iconLink => {
        iconLink.addEventListener('click', function() {
            updateActiveState(this);
        });
    });

    // Set the active tab when clicking on the expanded sidebar
    tabLinks.forEach(link => {
        link.addEventListener('click', function() {
            updateActiveState(this);
        });
    });

    // Call to set the initial state
    setInitialActiveState();
</script>
@endif