@extends('layout.layout')

<head>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" integrity="sha384-k6RqeWeci5ZR/Lv4MR0sA0FfDOMXQ8cBO5Tmy8Z+5QoV9hD0HnQn4E9E5mCkF1" crossorigin="anonymous">
</head>

<nav class="bg-slate-900 shadow fixed w-full z-50">
    <div class="py-5 px-4 md:px-10">
        <div class="flex justify-start items-center space-x-4">
            <div>
                <button id="menu-toggle" class="text-gray-500 focus:outline-none">
                    <svg class="w-7 h-7 md:w-9 md:h-9" fill="none" stroke="white" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="square" stroke-linejoin="miter" stroke-width="3" d="M4 6h16M4 12h16M4 18h16"></path>
                    </svg>
                </button>
            </div>
            <div class="text-xl lg:text-3xl font-bold text-white">MBTC</div>
        </div>
    </div>
</nav>

<!-- Icon-Only Sidebar (Visible by Default on Medium Screens) -->
<div id="icon-sidebar" class="hidden md:block fixed inset-y-0 left-0 z-40 bg-gray-800 bg-opacity-90 transition-opacity duration-300" style="top: 4rem;">
    <div class="absolute w-16 h-full bg-neutral-200 flex flex-col items-center pt-10 space-y-5">
        <a href="route('member.dashboard')" class="tab-link text-slate-900 hover:text-slate-400 transition-colors duration-300 flex items-center justify-center w-10 h-10 rounded-lg" data-tab="Home">
            <i class="fas fa-home fa-xl"></i>
        </a>
        <a href="#" class="tab-link text-slate-900 hover:text-slate-400 transition-colors duration-300 flex items-center justify-center w-10 h-10 rounded-lg" data-tab="Booking">
            <i class="fas fa-calendar-check fa-xl"></i>
        </a>
        <a href="#" class="tab-link text-slate-900 hover:text-slate-400 transition-colors duration-300 flex items-center justify-center w-10 h-10 rounded-lg" data-tab="Profile">
            <i class="fas fa-user fa-xl"></i>
        </a>
    </div>
</div>

<!-- Original Sidebar (Hidden by Default) -->
<div id="sidebar" class="fixed inset-y-0 left-0 z-40 bg-gray-800 bg-opacity-75 hidden transition-opacity duration-300" style="top: 4rem;">
    <div class="absolute text-xl font-semibold top-0 left-0 w-56 h-full bg-neutral-200 p-5">
        <ul class="mt-4 space-y-2">
            <li>
                <a href="route('member.dashboard')" class="tab-link block text-slate-900 hover:text-slate-400 p-2 rounded-lg font-bold bg-slate-900" data-tab="Home">Home</a> <!-- Set as active -->
            </li>
            <li>
                <a href="#" class="tab-link block text-slate-900 hover:text-slate-400 p-2 rounded-lg font-bold" data-tab="Booking">Monthly Dues</a>
            </li>
            <li>
                <a href="#" class="tab-link block text-slate-900 hover:text-slate-400 p-2 rounded-lg font-bold" data-tab="Profile">Profile</a>
            </li>
        </ul>
    </div>
</div>

<script>
    const menuToggle = document.getElementById('menu-toggle');
    const sidebar = document.getElementById('sidebar');
    const iconSidebar = document.getElementById('icon-sidebar');
    const tabLinks = document.querySelectorAll('.tab-link');

    let activeTab = 'Home'; // Set default active tab to Home

    // Set the initial active state for both sidebars
    function setInitialActiveState() {
        const initialTab = document.querySelector('.tab-link[data-tab="Home"]');
        if (initialTab) {
            updateActiveState(initialTab); // Update to active state for Home tab
        }
    }

    // Toggle the sidebars
    menuToggle.addEventListener('click', () => {
        if (iconSidebar.classList.contains('hidden')) {
            iconSidebar.classList.remove('hidden'); // Show icon-only sidebar
            sidebar.classList.add('hidden'); // Hide original sidebar
        } else {
            iconSidebar.classList.add('hidden'); // Hide icon-only sidebar
            sidebar.classList.remove('hidden'); // Show original sidebar
        }
    });

    // Function to update active state for both sidebars
    function updateActiveState(clickedTab) {
        // Get the active tab name
        const activeTabName = clickedTab.getAttribute('data-tab');

        // Reset colors for all icon tabs
        tabLinks.forEach(tab => {
            tab.classList.remove('text-white', 'bg-slate-900'); // Remove active styles from icons
            tab.classList.add('text-slate-900'); // Reset to default icon color
        });

        // Reset colors for all expanded sidebar tabs
        document.querySelectorAll('#sidebar .tab-link').forEach(tab => {
            tab.classList.remove('bg-slate-900', 'text-white'); // Remove previous active background
            tab.classList.add('text-slate-900'); // Reset to default icon color
        });

        // Add active color and square to the clicked icon
        clickedTab.classList.add('text-white', 'bg-slate-900', 'rounded-lg'); // Set to active icon color with filled background

        // Sync with the expanded sidebar
        const expandedTab = document.querySelector(`#sidebar .tab-link[data-tab="${activeTabName}"]`);
        if (expandedTab) {
            expandedTab.classList.add('bg-slate-900', 'text-white'); // Set active in expanded sidebar
            expandedTab.classList.remove('text-slate-900'); // Remove default color for active tab
        }
    }

    // Set the active tab
    tabLinks.forEach(link => {
        link.addEventListener('click', function() {
            updateActiveState(this); // Update active state for clicked tab
            activeTab = this.getAttribute('data-tab'); // Store the name of the active tab
        });
    });

    // Sync the expanded sidebar tab clicks with the icon sidebar
    document.querySelectorAll('#sidebar .tab-link').forEach(link => {
        link.addEventListener('click', function() {
            const correspondingIconTab = document.querySelector(`.tab-link[data-tab="${this.getAttribute('data-tab')}"]`);
            updateActiveState(correspondingIconTab); // Update active state for the corresponding icon tab
            activeTab = this.getAttribute('data-tab'); // Store the name of the active tab
        });
    });

    // Function to handle screen resizing
    const handleResize = () => {
        const mediaQuery = window.matchMedia("(max-width: 767px)"); // Adjust based on your breakpoint
        if (mediaQuery.matches) {
            iconSidebar.classList.add('hidden'); // Hide icon sidebar on small screens
            sidebar.classList.add('hidden'); // Optionally hide the original sidebar
        } else {
            iconSidebar.classList.remove('hidden'); // Show icon sidebar on medium+ screens
        }
    };

    // Initial state: set active tab based on the current location (if necessary)
    setInitialActiveState(); // Set initial active state
    handleResize(); // Set initial visibility based on screen size

    // Listen for window resize events
    window.addEventListener('resize', handleResize);
</script>
