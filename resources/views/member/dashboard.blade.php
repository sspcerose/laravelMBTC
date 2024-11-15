@extends('layout.layout')

@include('layouts.MemberNav')

<script src="https://code.jquery.com/jquery-3.5.1.js"></script>
<link href="https://cdn.datatables.net/v/dt/dt-2.1.8/b-3.1.2/r-3.0.3/datatables.min.css" rel="stylesheet">
<script src="https://cdn.datatables.net/v/dt/dt-2.1.8/b-3.1.2/r-3.0.3/datatables.min.js"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">

<body class="font-inter">
<div class="lg:pl-20 lg:pr-10">
    <div class="pt-24 lg:pt-28 lg:pr-6 flex justify-between items-center">
        <h1 class="text-black p-4 text-center md:text-left font-extrabold text-3xl">Upcoming Trips</h1>

        <div class="relative">
                    <button id="notification-icon" class="relative p-4 bg-blue-500 text-white rounded-lg focus:outline-none">
                    <i class="fa-solid fa-bell"></i>
                        <span id="notification-badge" class="absolute top-0 right-0 inline-flex items-center justify-center w-5 h-5 text-xs font-bold text-white bg-red-500 rounded-full">3</span>
                    </button>

                    <!-- Notification Container -->
                    <div id="notification-container" class="hidden absolute right-0 mt-2 w-64 bg-white shadow-lg rounded-lg overflow-hidden z-10 border-2 border-gray-500">
                    <ul class="p-4 max-h-96 overflow-y-auto" id="notification-list">
                    <div class="text-sm border-b p-2"><span class="font-bold text-red-700">Reminder:</span> <span>All the drivers received this notification, but only the first one to accept can have this schedule</span><br><br></div>
                    @foreach($scheduless as $aschedule)
                                <li class="text-sm text-gray-700 border-b p-2">
                                <!-- <span class="font-bold">Schedule</span><br> -->
                                <span class="font-bold">Customer Name: {{ $aschedule->booking->user->name }} {{ $aschedule->booking->user->last_name }}</span><br>
                                <span class="">Pick Up Location: {{$aschedule->booking->location }}</span><br>
                                <span class="">Destination: {{ $aschedule->booking->tariff->destination }}</span><br>
                                <span class="">Start Date: {{ \Carbon\Carbon::parse($aschedule->booking->start_date)->format('F d, Y') }}</span><br>
                                <span class="">End Date: {{ \Carbon\Carbon::parse($aschedule->booking->end_date)->format('F d, Y') }}</span><br>
                                <form action="{{ route('optionschedule.accept', $aschedule->id) }}" method="POST" class="acceptForm" style="display:inline;">
                                            @csrf
                                            <button type="button" class="bg-green-500 text-white py-1 px-3 rounded hover:bg-green-600 triggerConfirm">Accept</button>
                                        </form>
                                        <!-- Alert 1 -->
                                        <div class="mt-3 relative flex flex-col p-3 text-sm text-gray-800 bg-green-100 border border-green-600 rounded-md hidden acceptAlert">
                                        <svg class="w-6 h-6 text-gray-800 dark:text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.529 9.988a2.502 2.502 0 1 1 5 .191A2.441 2.441 0 0 1 12 12.582V14m-.01 3.008H12M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z"/>
                                        </svg>
                                                Are you sure you want to accept the schedule?
                                                <div class="flex justify-end mt-2">
                                                    <button class="bg-gray-600 text-white py-1 px-3 mr-2 rounded-lg hover:bg-gray-500 cancelButton">
                                                        Back
                                                    </button>
                                                    <button class="bg-green-600 text-white py-1 px-3 rounded-lg hover:bg-green-500 yesButton">
                                                        Yes
                                                    </button>
                                                </div>
                                            </div>   
                                </li>
                    @endforeach
                        </ul>
                    </div>
                </div>
    </div>

    <div class="bg-neutral-300 mx-4 rounded-3xl p-2 items-center mb-4">

        <!-- Large Screen Table -->
        <div class="overflow-x-auto bg-neutral-100 px-2 md:px-4 lg:py-2 rounded-2xl" id="largeTable">
        @if($schedules->isEmpty())
        <p class="text-center">NO SCHEDULE FOR YOU YET</p>
        @else
            <table class="min-w-full" id="myTable">
                <thead>
                    <tr class="text-left text-sm text-neutral-950 uppercase tracking-wider">
                        <th class="py-3 px-4">ID</th>
                        <th class="py-3 px-4">Customer Name</th>
                        <th class="py-3 px-4">Destination</th>
                        <th class="py-3 px-4">Pick Up Location</th>
                        <th class="py-3 px-4">Mobile Number</th>
                        <th class="py-3 px-4">Start Date</th>
                        <th class="py-3 px-4">End Date</th>
                        @if($memberType->type != 'Owner')
                            <th class="py-3 px-4">Action</th>
                        @endif
                    </tr>
                </thead>
                <tbody class="text-sm text-gray-600">
                    @if($schedules->isEmpty())
                        <tr>
                            <td colspan="8" class="text-center py-4">No schedules found</td>
                        </tr>
                    @else
                        @foreach($schedules as $schedule)
                            <tr>
                                <td class="py-3 px-4"></td>
                                <td class="py-6 px-4">{{ $schedule->booking->user->name }} {{ $schedule->booking->user->last_name }}</td>
                                <td class="py-6 px-4">{{ $schedule->booking->tariff->destination }}</td>
                                <td class="py-6 px-4">{{ $schedule->booking->location }}</td>
                                <td class="py-6 px-4">{{ $schedule->booking->user->mobile_num }}</td>   
                                <td class="py-6 px-4">{{ \Carbon\Carbon::parse($schedule->booking->start_date)->format('F d, Y') }}</td>
                                <td class="py-6 px-4">{{ \Carbon\Carbon::parse($schedule->booking->end_date)->format('F d, Y') }}</td>

                                @if($memberType->type != 'Owner')
                                <td class="py-3 px-4" id="scheduleTd">
                                    @if ($schedule->driver_status == 'cancelled')
                                        <span class="font-bold text-red-600">Cancelled</span>
                                    @elseif ($schedule->driver_status == 'accepted')
                                        <span class="font-bold text-green-600">Accepted</span>
                                    @elseif ($schedule->cust_status == 'cancelled')
                                        <span class="font-bold text-red-600">Customer Cancelled</span>
                                    @else
                                        <form action="{{ route('schedule.accept', $schedule->id) }}" method="POST" class="acceptForm" style="display:inline;">
                                            @csrf
                                            <button type="button" class="bg-green-500 text-white py-1 px-3 rounded hover:bg-green-600 triggerConfirm">Accept</button>
                                        </form>
                                        <!-- Alert 1 -->
                                        <div class="mt-3 relative flex flex-col p-3 text-sm text-gray-800 bg-green-100 border border-green-600 rounded-md hidden acceptAlert">
                                        <svg class="w-6 h-6 text-gray-800 dark:text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.529 9.988a2.502 2.502 0 1 1 5 .191A2.441 2.441 0 0 1 12 12.582V14m-.01 3.008H12M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z"/>
                                        </svg>
                                                Are you sure you want to accept the schedule?
                                                <div class="flex justify-end mt-2">
                                                    <button class="bg-gray-600 text-white py-1 px-3 mr-2 rounded-lg hover:bg-gray-500 cancelButton">
                                                        Back
                                                    </button>
                                                    <button class="bg-green-600 text-white py-1 px-3 rounded-lg hover:bg-green-500 yesButton">
                                                        Yes
                                                    </button>
                                                </div>
                                            </div>

                                        <form action="{{ route('schedule.cancel', $schedule->id) }}" method="POST" class="cancelForm" style="display:inline;">
                                            @csrf
                                            <button type="button" class="bg-red-500 text-white py-1 px-3 rounded hover:bg-red-600 ml-2 triggerCancel">Cancel</button>
                                        </form>

                                        <!--prompt  -->
                                        <div class="mt-3 relative flex flex-col p-3 text-sm text-gray-800 bg-red-100 border border-red-600 rounded-md hidden cancelAlert">
                                            <svg class="w-6 h-6 text-gray-800 dark:text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.529 9.988a2.502 2.502 0 1 1 5 .191A2.441 2.441 0 0 1 12 12.582V14m-.01 3.008H12M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z"/>
                                            </svg>
                                                Are you sure you want to decline the schedule?
                                                <div class="flex justify-end mt-2">
                                                    <button class="bg-gray-600 text-white py-1 px-3 mr-2 rounded-lg hover:bg-gray-500 cancelButton">
                                                        Back
                                                    </button>
                                                    <button class="bg-green-600 text-white py-1 px-3 rounded-lg hover:bg-green-500 yesButton">
                                                        Yes
                                                    </button>
                                                </div>
                                            </div>
                                    @endif
                                </td>
                                @endif
                            </tr>
                        @endforeach
                    @endif
                </tbody>
            </table>
            @endif
        </div>
    </div>
</div>


<script>
$(document).ready(function () {
    $('#myTable').DataTable({
        responsive: true,
        order: [[0, 'desc']],
        columnDefs: [
            { targets: 0, visible: false } 
        ],
        language: {
            emptyTable: "No data available in the table" 
        }
    });
});

    document.addEventListener('click', function (e) {

    // Trigger Confirm 
    if (e.target.classList.contains('triggerConfirm')) {
        let acceptForm = e.target.closest('.acceptForm');
        let acceptAlert = acceptForm.nextElementSibling; 
        acceptAlert.classList.remove('hidden'); 

        // Hide both Confirm and Decline buttons
        acceptForm.querySelector('.triggerConfirm').style.display = 'none';
        acceptForm.closest('td').querySelector('.triggerCancel').style.display = 'none';
        
    }

    // Trigger Decline
    if (e.target.classList.contains('triggerCancel')) {
        let cancelForm = e.target.closest('.cancelForm');
        let cancelAlert = cancelForm.nextElementSibling;
        cancelAlert.classList.remove('hidden'); 

        // Hide both Cancel and Confirm buttons
        cancelForm.querySelector('.triggerCancel').style.display = 'none';
        cancelForm.closest('td').querySelector('.triggerConfirm').style.display = 'none';
    }

    // Back
    if (e.target.classList.contains('cancelButton')) {
        let alertBox = e.target.closest('.acceptAlert, .cancelAlert');
        alertBox.classList.add('hidden'); 

        let specificTd = alertBox.closest('td');
        specificTd.querySelector('.triggerConfirm').style.display = '';
        specificTd.querySelector('.triggerCancel').style.display = '';
    }

    // Submit form on "Yes" (decline)
    if (e.target.classList.contains('yesButton') && e.target.closest('.cancelAlert')) {
        let cancelAlert = e.target.closest('.cancelAlert');
        let cancelForm = cancelAlert.previousElementSibling; 
        
        if (cancelForm) {
            e.preventDefault(); 

            // Success alert
            if (!cancelForm.querySelector('.successMessageAlert')) {
                let successMessage = document.createElement('div');
                successMessage.setAttribute('role', 'alert');
                successMessage.className = 'successMessageAlert mt-3 relative flex w-full p-3 text-sm text-white bg-blue-500 rounded-md';
                successMessage.innerHTML = `<svg class="w-6 h-6 text-white-800 dark:text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 11h2v5m-2 0h4m-2.592-8.5h.01M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z"/>
                                            </svg>
                                            Successfully Cancelled the Schedule!`;

                let bookingTd = cancelForm.closest('td');
                bookingTd.appendChild(successMessage); 
                cancelAlert.classList.add('hidden');

                
                setTimeout(function () {
                    successMessage.remove();
                    cancelForm.submit(); 
                }, 1000);
            }
        }
    }

    // Accept the action on "Yes" (accept)
    if (e.target.classList.contains('yesButton') && e.target.closest('.acceptAlert')) {
        let acceptAlert = e.target.closest('.acceptAlert');
        let acceptForm = acceptAlert.previousElementSibling; 
        
        if (acceptForm) {
            e.preventDefault(); 

            // Success Alert
            if (!acceptForm.querySelector('.successMessageAlert')) {
                let successMessage = document.createElement('div');
                successMessage.setAttribute('role', 'alert');
                successMessage.className = 'successMessageAlert mt-3 relative flex w-full p-3 text-sm text-white bg-blue-500 rounded-md';
                successMessage.innerHTML = `<svg class="w-6 h-6 text-white-800 dark:text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 11h2v5m-2 0h4m-2.592-8.5h.01M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z"/>
                                            </svg>
                                            Successfully Accepted the Schedule!`;

                let bookingTd = acceptForm.closest('td');
                bookingTd.appendChild(successMessage); 
                acceptAlert.classList.add('hidden'); 

                
                setTimeout(function () {
                    successMessage.remove();
                    acceptForm.submit(); 
                }, 1000);
            }
        }
    }
});

let seenNotifications = [];
        let notificationCount = 0;

        async function fetchNotifications() {

            const newNotifications = notifications.filter(notification => !seenNotifications.includes(notification.id));
            notificationCount = newNotifications.length;
            updateNotificationBadge(notificationCount);
            displayNotifications(newNotifications);
        }

        function updateNotificationBadge(count) {
            const badge = document.getElementById('notification-badge');
            badge.textContent = count > 0 ? count : ''; 
        }

        function displayNotifications(notifications) {
            const container = document.getElementById('notification-list');
            container.innerHTML = '';
            notifications.forEach(notification => {
                const li = document.createElement('li');
                li.className = "py-2 border-b cursor-pointer hover:bg-gray-100";
                li.textContent = notification.message;
                li.onclick = () => markAsSeen(notification.id);
                container.appendChild(li);
            });
        }

        function markAsSeen(notificationId) {
            seenNotifications.push(notificationId);
        }

        document.getElementById('notification-icon').addEventListener('click', () => {
            const container = document.getElementById('notification-container');
            container.classList.toggle('hidden');
            fetchNotifications();
        });


        document.querySelectorAll('.triggerConfirm').forEach(button => {
    button.addEventListener('click', function() {
        // Show the confirmation modal
        const alert = this.closest('li').querySelector('.acceptAlert');
        alert.classList.remove('hidden');
    });
});

document.querySelectorAll('.cancelButton').forEach(button => {
    button.addEventListener('click', function() {
        // Hide the confirmation modal
        const alert = this.closest('.acceptAlert');
        alert.classList.add('hidden');
    });
});

document.querySelectorAll('.yesButton').forEach(button => {
    button.addEventListener('click', function() {
        // Submit the form to accept the schedule
        const form = this.closest('li').querySelector('.acceptForm');
        form.submit();
    });
});




</script>
