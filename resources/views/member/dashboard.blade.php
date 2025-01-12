@extends('layout.layout')

@include('layouts.memberNav')

<script src="https://code.jquery.com/jquery-3.5.1.js"></script>
<link href="https://cdn.datatables.net/v/dt/dt-2.1.8/b-3.1.2/r-3.0.3/datatables.min.css" rel="stylesheet">
<script src="https://cdn.datatables.net/v/dt/dt-2.1.8/b-3.1.2/r-3.0.3/datatables.min.js"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">

<body class="font-inter">
<div class="lg:pl-20 lg:pr-10">
    <div class="pt-24 lg:pt-28 lg:pr-6 flex justify-between items-center">
    @if($memberType->type == 'Owner')
        <h1 class="text-black p-4 text-center md:text-left font-extrabold text-3xl">Possible Trips Using Your Vehicle</h1>
    @else
        <h1 class="text-black p-4 text-center md:text-left font-extrabold text-3xl">Upcoming Trips</h1>
    
        
        <div class="relative">
                    @if($schednotifcount == 0)
                        <button id="notification-icon" class="relative p-4 bg-gray-500 text-white rounded-lg focus:outline-none">
                        <i class="fa-solid fa-bell"></i>
                        <!-- <span id="notification-badge" class="absolute top-0 right-0 inline-flex items-center justify-center w-5 h-5 text-xs font-bold text-white bg-red-500 rounded-full">0</span> -->
                    @else
                        <button id="notification-icon" class="relative p-4 bg-blue-500 text-white rounded-lg focus:outline-none">
                        <i class="fa-solid fa-bell"></i>
                        <span id="notification-badge" class="absolute top-0 right-0 inline-flex items-center justify-center w-5 h-5 text-xs font-bold text-white bg-red-500 rounded-full">{{$schednotifcount}}</span>
                    @endif
                    </button>

                    <!-- Notification Container -->
                    <div id="notification-container" class="hidden absolute right-0 mt-2 w-64 bg-white shadow-lg rounded-lg overflow-hidden z-10 border-2 border-gray-500">
                    <ul class="p-4 max-h-96 overflow-y-auto" id="notification-list">
                    <div class="text-sm border-b p-2"><span class="font-bold text-red-700">Reminder:</span> <span>All drivers receive this notification, but the schedule is assigned to the first one who accepts it</span><br><br></div>
                    
                    @if($scheduless->isEmpty())
                        <li class="text-sm text-gray-700 border-b p-2">
                        <span class="font-bold text-center">No Notification</span><br>
                    @else
                        @foreach($scheduless as $aschedule)
                            <li class="text-sm text-gray-700 border-b p-2">
                                    <!-- <span class="font-bold">Schedule</span><br> -->
                                    <span class="font-bold">Customer Name: {{ $aschedule->booking->user->name }} {{ $aschedule->booking->user->last_name }}</span><br>
                                    <span class="">Pick Up Location: {{$aschedule->booking->location }}</span><br>
                                    <span class="">Destination: {{ $aschedule->booking->tariff->destination }}</span><br>
                                    <span class="">Start Date: {{ \Carbon\Carbon::parse($aschedule->booking->start_date)->format('F d, Y') }}</span><br>
                                    <span class="">End Date: {{ \Carbon\Carbon::parse($aschedule->booking->end_date)->format('F d, Y') }}</span><br>
                                    <form action="{{ route('member.optionschedule.accept', $aschedule->id) }}" method="POST" class="acceptForm" style="display:inline;">
                                                @csrf
                                                <button type="button" class="bg-green-500 text-white py-1 px-3 rounded hover:bg-green-600 triggerConfirm">Accept</button>
                                            </form>
                                    </li>
                        @endforeach
                        </ul>
                        @endif
                    </div>
                </div>
                @endif
    </div>

    <div class="bg-neutral-300 mx-4 rounded-3xl p-2 items-center mb-4">

        <!-- Large Screen Table -->
        <div class="overflow-x-auto bg-neutral-100 px-2 md:px-4 lg:py-2 rounded-2xl" id="largeTable">
        @if($schedules->isEmpty())
            @if($memberType->type != 'Owner')
                <p class="text-center">NO SCHEDULE FOR YOU YET</p>
            @else
                <p class="text-center">NO TRIPS USING YOUR VEHICLE YET</p>
            @endif
        @else
            <table class="min-w-full" id="myTable">
                <thead>
                    <tr class="text-left text-sm text-neutral-950 uppercase tracking-wider">
                        <th class="py-3 px-4">ID</th>
                        <th class="py-3 px-4">Customer Name</th>
                        <th class="py-3 px-4">Destination</th>
                        <th class="py-3 px-4">Pick-Up  Time</th>
                        <th class="py-3 px-4">Pick-Up Location</th>
                        <th class="py-3 px-4">Contact Number</th>
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
                                <td class="py-6 px-4">{{ $schedule->booking->time }}</td>
                                <td class="py-6 px-4">{{ $schedule->booking->location }}</td>
                                <td class="py-6 px-4">{{ $schedule->booking->user->mobile_num }}</td>   
                                <td class="py-6 px-4">{{ \Carbon\Carbon::parse($schedule->booking->start_date)->format('F d, Y') }}</td>
                                <td class="py-6 px-4">{{ \Carbon\Carbon::parse($schedule->booking->end_date)->format('F d, Y') }}</td>

                                @if($memberType->type != 'Owner')
                                <td class="py-3 px-4" id="scheduleTd">
                                    @if ($schedule->driver_status == 'cancelled')
                                        <span class="font-bold text-red-600">Declined</span>
                                    @elseif ($schedule->driver_status == 'accepted')
                                        <span class="font-bold text-green-600">Accepted</span>
                                    @elseif ($schedule->cust_status == 'cancelled')
                                        <span class="font-bold text-red-600">Customer Cancelled</span>
                                    @else
                                        <form action="{{ route('member.schedule.accept', $schedule->id) }}" method="POST" class="acceptForm" style="display:inline;">
                                            @csrf
                                            <button type="button" class="bg-green-500 text-white py-1 px-3 rounded hover:bg-green-600 triggerConfirm">Accept</button>
                                        </form>
                                        

                                        <form action="{{ route('member.schedule.cancel', $schedule->id) }}" method="POST" class="cancelForm" style="display:inline;">
                                            @csrf
                                            <button type="button" class="bg-red-500 text-white py-1 px-3 rounded hover:bg-red-600 ml-2 triggerCancel">Decline</button>
                                        </form>

                                        
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


</script>

<script>
    document.addEventListener('click', function (e) {
    // Accept action
    if (e.target.classList.contains('triggerConfirm')) {
        Swal.fire({
            title: 'Are you sure?',
            text: "You want to accept this schedule.",
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#28a745',
            cancelButtonColor: '#6c757d',
            confirmButtonText: 'Yes, Accept'
        }).then((result) => {
            if (result.isConfirmed) {
                e.target.closest('.acceptForm').submit();
                Swal.fire({
                    title: "Accepted!",
                    text: "The schedule has been accepted.",
                    icon: "success"
                });
            }
        });
    }

    // Reject action
    if (e.target.classList.contains('triggerCancel')) {
        Swal.fire({
            title: 'Are you sure?',
            text: "You want to decline this schedule.",
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#6c757d',
            confirmButtonText: 'Yes, Decline'
        }).then((result) => {
            if (result.isConfirmed) {
                e.target.closest('.cancelForm').submit();
                Swal.fire({
                    title: "Declined!",
                    text: "The schedule has been declined.",
                    icon: "error"
                });
            }
        });
    }
});
</script>
