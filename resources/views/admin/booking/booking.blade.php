@extends('layout.layout')

@include('layouts.adminNav')

<body class="font-inter">
    <div class="lg:pl-20 lg:pr-10">
        <div class="pt-24 lg:pt-28 flex justify-between items-center">
            <h1 class="text-black p-4 pl-4 text-center md:text-left font-extrabold text-3xl">Bookings</h1>
            <div class="px-4 lg:px-0 pt-4 lg:pr-5">
                 
                <div class="relative">
                @if($activeBookingCount == 0)
                    <button id="notification-icon" class="relative p-4 bg-gray-500 text-white rounded-lg focus:outline-none">
                    <i class="fa-solid fa-bell"></i>
                @else
                    <button id="notification-icon" class="relative p-4 bg-blue-500 text-white rounded-lg focus:outline-none">
                    <i class="fa-solid fa-bell"></i>
                    <span id="notification-badge" class="absolute top-0 right-0 inline-flex items-center justify-center w-5 h-5 text-xs font-bold text-white bg-red-500 rounded-full">{{ $activeBookingCount }}</span>
                @endif
                    </button>

                    <div id="notification-container" class="hidden absolute right-0 mt-2 w-64 bg-white shadow-lg rounded-lg overflow-hidden z-10 border-2 border-gray-500">
                    <ul class="p-4 max-h-96 overflow-y-auto" id="notification-list">
                    <li class="text-sm text-gray-700 border-b p-2">
                    @if($activeBookingCount == 0)
                        <span class="font-bold text-center">No Notification</span><br>
                    @else
                        @foreach($viewactiveBookings as $activeBooking)
                            <span class="font-bold">NEW RESERVATION</span><br>
                            <span class="font-bold">{{ $activeBooking->user->name }} {{ $activeBooking->user->last_name }}</span><br>
                            <span>Destination: {{ $activeBooking->destination }}</span><br>
                            <span>Start Date: {{ \Carbon\Carbon::parse($activeBooking->start_date)->format('F d, Y') }}</span><br>
                            <span>End Date: {{ \Carbon\Carbon::parse($activeBooking->end_date)->format('F d, Y') }}</span><br>
                        @endforeach
                        </ul>
                        @endif
                    </div>
                </div>
            </div>
        </div>


    <div class="bg-neutral-300 mx-4 rounded-3xl p-2 items-center mb-4">
    <div class="overflow-x-auto bg-neutral-100 px-2 md:px-4 lg:py-2 rounded-2xl" id="largeTable">
        @if($viewBookings->isEmpty())
            <p class="text-center">NO BOOKINGS YET</p>
        @else
        <table class="min-w-full" id="myTable">
            <thead>
            <tr class="text-center text-sm text-neutral-950 uppercae tracking-wider">
                    <th class="py-3 px-4">ID</th>
                    <th class="py-3 px-4" style="width: 15%;">CUSTOMER NAME</th>
                    <th class="py-3 px-4">PICK-UP TIME</th>
                    <th class="py-3 px-4" style="width: 10%;">PICK-UP LOCATION</th>
                    <th class="py-3 px-4">DESTINATION</th>
                    <th class="py-3 px-4">NO. OF PASSENGERS</th>
                    <th class="py-3 px-4" style="width: 15%;">START DATE</th>
                    <th class="py-3 px-4" style="width: 15%;">END DATE</th>
                    <th class="py-3 px-4">Total Fare</th>
                    <th class="py-3 px-4" style="width: 15%;">PROOF OF PAYMENT</th>
                    <th class="py-3 px-4">Status</th>
                    <th class="py-3 px-4">Action</th>
                </tr>
            </thead>
            <tbody class="text-sm text-gray-600" id="tableBody">
                @if($viewBookings->isEmpty())
                    <tr>
                        <td colspan="7" class="text-center py-4">No bookings found</td>
                    </tr>
                @else
                    @foreach($viewBookings as $viewBooking)
                    <tr>
                        <td class="py-3 px-4">{{ $viewBooking->id }}</td>
                        <td class="py-3 px-4">
                            {{ $viewBooking->user->name }} {{ $viewBooking->user->last_name }}
                        </td>
                        <td class="py-3 px-4">{{ $viewBooking->time }}</td>
                        <td class="py-3 px-4">{{ $viewBooking->location }}</td>
                        <td class="py-3 px-4">{{ $viewBooking->destination }}</td>
                        <td class="py-3 px-4">{{ $viewBooking->passenger }}</td>
                        <td class="py-3 px-4">
                            {{ \Carbon\Carbon::parse($viewBooking->start_date)->format('F d, Y') }}
                        </td>
                        <td class="py-3 px-4">{{ \Carbon\Carbon::parse($viewBooking->end_date)->format('F d, Y') }}</td>
                        <td class="py-3 px-4">₱{{ $viewBooking->price }}.00</td>
                        <td class="py-3 px-4">
                            <button type="button" class="bg-blue-500 text-white py-1 px-3 rounded hover:bg-blue-600"  data-receipt="{{ asset('img/' . $viewBooking->receipt) }}" onclick="openModal(this)">
                                        View
                                    </button>
                        </td>
                        <td class="py-3 px-4">
                            @if($viewBooking->status == "accepted" && (\Carbon\Carbon::today()->lt($viewBooking->start_date)))
                            <span class="font-bold text-yellow-500">Upcoming</span>
                            @elseif($viewBooking->status == "accepted" && \Carbon\Carbon::today()->between($viewBooking->start_date, $viewBooking->end_date))
                            <span class="font-bold text-blue-500">On going</span>
                            @elseif($viewBooking->status == "accepted" && \Carbon\Carbon::today()->gt($viewBooking->end_date))
                            <span class="font-bold text-green-500">Completed</span>
                            @elseif($viewBooking->status == "rejected")
                            <span class="font-bold text-red-500">Rejected</span>
                            @elseif($viewBooking->status == "active")
                            <span class="font-bold text-yellow-500">Pending...</span>
                            @else
                            <span class="font-bold text-red-600">Customer Cancelled</span>
                            @endif
                            </td>
                        <td class="py-3 px-4" style="width: 30%;">
                            @if($viewBooking->status == "rejected")
                            <span class="font-bold text-red-600">Rejected</span>
                            @elseif($viewBooking->status == "accepted")
                            <span class="font-bold text-green-600">Accepted</span>
                            @else
                            <form action="{{ route('booking.accept', $viewBooking->id) }}" method="POST" class="acceptForm" style="display:inline;">
                                            @csrf
                                            <button type="button" class="bg-green-500 text-white py-1 px-3 rounded hover:bg-green-600 triggerConfirm">Accept</button>
                                        </form>
                                        <!-- Alert 1 -->
                                        <div class="mt-3 relative flex flex-col p-3 text-sm text-gray-800 bg-green-100 border border-green-600 rounded-md hidden acceptAlert">
                                        <svg class="w-6 h-6 text-gray-800 dark:text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.529 9.988a2.502 2.502 0 1 1 5 .191A2.441 2.441 0 0 1 12 12.582V14m-.01 3.008H12M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z"/>
                                        </svg>
                                                Are you sure you want to accept the reservation?
                                                <div class="flex justify-end mt-2">
                                                    <button class="bg-gray-600 text-white py-1 px-3 mr-2 rounded-lg hover:bg-gray-500 cancelButton">
                                                        Back
                                                    </button>
                                                    <button class="bg-green-600 text-white py-1 px-3 rounded-lg hover:bg-green-500 yesButton">
                                                        Yes
                                                    </button>
                                                </div>
                                            </div>

                                        <form action="{{ route('booking.reject', $viewBooking->id) }}" method="POST" class="cancelForm" style="display:inline;">
                                            @csrf
                                            <button type="button" class="bg-red-500 text-white py-1 px-3 rounded hover:bg-red-600 ml-2 triggerCancel">Reject</button>
                                        </form>

                                        <!--prompt  -->
                                        <div class="mt-3 relative flex flex-col p-3 text-sm text-gray-800 bg-red-100 border border-red-600 rounded-md hidden cancelAlert">
                                            <svg class="w-6 h-6 text-gray-800 dark:text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.529 9.988a2.502 2.502 0 1 1 5 .191A2.441 2.441 0 0 1 12 12.582V14m-.01 3.008H12M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z"/>
                                            </svg>
                                                Are you sure you want to reject the reservation?
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

                    </tr>
                    @endforeach
                @endif
            </tbody>
        </table>
        @endif
    </div>
</div>
</div>

<!-- Modal -->
<div class="fixed inset-0 flex items-center justify-center z-50 hidden" id="imageModal" onclick="closeOnClickOutside(event)">
        <div class="bg-white rounded-lg overflow-hidden shadow-lg max-w-xl mx-4 w-full" id="modalContent">
            <div class="p-4">
                <h5 class="text-lg font-bold mb-2">Receipt</h5>
                <button type="button" class="absolute top-0 right-0 m-2 text-gray-500" onclick="closeModal()">&times;</button>
            </div>
            <div class="p-4 text-center">
                <img id="modalImage" src="" alt="Receipt" class="max-w-full h-auto mx-auto" style="max-width: 90%;" />
            </div>
            <div class="p-4 text-right">
                <button type="button" class="bg-gray-500 text-white px-4 py-2 rounded hover:bg-gray-600" onclick="closeModal()">Close</button>
            </div>
        </div>
    </div>


<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.7.1/jszip.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/vfs-fonts/2.0.3/vfs_fonts.js"></script>
<script src="https://cdn.datatables.net/buttons/2.2.3/js/buttons.html5.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.2.3/js/buttons.print.min.js"></script>
    <script>
    $(document).ready(function () {
    $('#myTable').DataTable({
        responsive: true,
        order: [[0, 'desc']],
        columnDefs: [
            { targets: 0, visible: false }
        ],
    layout: {
            topStart: {
                buttons: [
                    {
                        extend: 'collection',
                        text: 'Export As',
                        buttons: [
                            {
                                extend: 'copy',
                                exportOptions: {
                                    columns: [1, 2, 3, 4, 5, 6, 7, 8, 10] 
                                }
                            },
                            {
                                extend: 'excel',
                                exportOptions: {
                                    columns: [1, 2, 3, 4, 5, 6, 7, 8, 10]
                                }
                            },
                            {
                                extend: 'csv',
                                exportOptions: {
                                    columns: [1, 2, 3, 4, 5, 6, 7, 8, 10]
                                }
                            },
                            {
                                extend: 'pdf',
                                exportOptions: {
                                    ccolumns: [1, 2, 3, 4, 5, 6, 7, 8, 10]
                                }
                            },
                            {
                                extend: 'print',
                                exportOptions: {
                                    columns: [1, 2, 3, 4, 5, 6, 7, 8, 10]
                                }
                            }
                        ]
                    }
                ]
            }
        }
    });
});
//                 buttons: [
//                     {
//                         extend: 'collection',
//                         text: 'Export As',
//                         buttons: ['copy', 'excel', 'csv', 'pdf', 'print']
//                     }
//                 ]
//             }
//         },
//         // select: true
//     });
// });

        // Modal
        function openModal(button) {
            var receipt = $(button).data('receipt');
            $('#modalImage').attr('src', receipt);
            $('#imageModal').removeClass('hidden');
        }
        function closeModal() {
            $('#imageModal').addClass('hidden');
        }
        function closeOnClickOutside(event) {
            if (!event.target.closest('#modalContent')) {
                closeModal();
            }
        }


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
                                        Successfully Rejected the Reservation!`;

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
                                        Successfully Accepted the Reservation!`;

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


        
    </script>

</body>

