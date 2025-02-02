@extends('layout.layout')

@include('layouts.adminNav')

<body class="font-inter">
    <div class="lg:pl-20 lg:pr-10">
    <div class="pt-24 lg:pt-28 p-4 flex flex-col md:flex-row justify-between items-center">
        <h1 class="text-black p-6 pl-4 text-center md:text-left font-extrabold text-3xl">Bookings</h1>
        <div class="px-4 lg:px-0 pt-4 lg:pr-5">

        <div class="flex space-x-4">
                <button class="bg-blue-600 hover:bg-blue-400 text-white flex items-center py-3 px-4 rounded-xl" onclick="openFilterModal('export')">
                    <i class="fa-solid fa-file-export mr-2"></i>
                    Report
                </button>
                <button class="bg-teal-600 hover:bg-teal-400 text-white flex items-center py-3 px-4 rounded-xl" onclick="openFilterModal('print')">
                    <i class="fa-solid fa-print mr-2"></i>Print
                </button>
            
                @if($activeBookingCount == 0)
                    <button id="notification-icon" class="relative p-4 bg-gray-500 text-white rounded-lg focus:outline-none">
                        <i class="fa-solid fa-bell"></i>
                    </button>
                @else
                    <button id="notification-icon" class="relative p-4 bg-blue-500 text-white rounded-lg focus:outline-none">
                        <i class="fa-solid fa-bell"></i>
                        <span id="notification-badge" class="absolute top-0 right-0 inline-flex items-center justify-center w-5 h-5 text-xs font-bold text-white bg-red-500 rounded-full">{{ $activeBookingCount }}</span>
                    </button>
                @endif
            </div>
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
                        @endif
                    </li>
                </ul>
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
                            <!-- <th class="py-3 px-4" style="width: 10%;">PICK-UP LOCATION</th> -->
                            <th class="py-3 px-4">DESTINATION</th>
                            <!-- <th class="py-3 px-4">NO. OF PASSENGERS</th> -->
                            <th class="py-3 px-4" style="width: 15%;">START DATE</th>
                            <th class="py-3 px-4" style="width: 15%;">END DATE</th>
                            <th class="py-3 px-4">TOTAL FARE</th>
                            <!-- <th class="py-3 px-4" style="width: 15%;">PROOF OF PAYMENT</th> -->
                            <th class="py-3 px-4">STATUS</th>
                            <!-- <th class="py-3 px-4">Action</th> -->
                            <th>View Details</th>
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
                            <!-- <td class="py-3 px-4">{{ $viewBooking->location }}</td> -->
                            <td class="py-3 px-4">{{ $viewBooking->destination }}</td>
                            <!-- <td class="py-3 px-4">{{ $viewBooking->passenger }}</td> -->
                            <td class="py-3 px-4">
                                {{ \Carbon\Carbon::parse($viewBooking->start_date)->format('F d, Y') }}
                            </td>
                            <td class="py-3 px-4">{{ \Carbon\Carbon::parse($viewBooking->end_date)->format('F d, Y') }}</td>
                            <td class="py-3 px-4">₱{{ $viewBooking->price }}.00</td>
                            <!-- <td class="py-3 px-4">
                            <button type="button" class="bg-cyan-500 text-white py-1 px-3 rounded hover:bg-cyan-600"  data-receipt="{{ asset('img/' . $viewBooking->receipt) }}" onclick="openModal(this)">
                                        View
                                    </button>
                        </td> -->
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
                            <!-- <td class="py-3 px-4" style="width: 30%;">
                                @if($viewBooking->status == "rejected")
                                <span class="font-bold text-red-600">Rejected</span>
                                @elseif($viewBooking->status == "accepted")
                                <span class="font-bold text-green-600">Accepted</span>
                                @elseif($viewBooking->status == "cancelled")
                                <span class="">No Action Required</span>
                                @else
                                <form action="{{ route('booking.accept', $viewBooking->id) }}" method="POST" class="acceptForm" style="display:inline;">
                                    @csrf
                                    <button type="button" class="bg-green-500 text-white py-1 px-3 rounded hover:bg-green-600 triggerConfirm">Accept</button>
                                </form>
                                <br> <br> Add a line break to move the Reject button to the next line -->
                                <!-- <form action="{{ route('booking.reject', $viewBooking->id) }}" method="POST" class="cancelForm" style="display:inline;">
                                    @csrf
                                    <button type="button" class="bg-red-500 text-white py-1 px-3 rounded hover:bg-red-600 triggerCancel">Reject</button>
                                </form>
                                @endif
                            </td> -->
                            <td>
                                <a href="{{ url('admin/booking/view/' . $viewBooking->id) }}" class="bg-green-500 text-white py-1 px-3 rounded hover:bg-green-400 block text-center">
                                    More Details
                                </a>
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
        <div class="bg-white rounded-lg overflow-hidden shadow-lg max-w-xl mx-1 w-full" id="modalContent">
            <div class="p-4">
                <h5 class="text-lg font-bold mb-2">Receipt</h5>
                <button type="button" class="absolute top-0 right-0 m-2 text-gray-500" onclick="closeModal()">&times;</button>
            </div>
            <div class="p-4 text-center">
                <img id="modalImage" src="" alt="Receipt" class="max-w-48 max-h-6 mx-auto" style="max-width: 50%;" />
            </div>
            <div class="p-4 text-right">
                <button type="button" class="bg-gray-500 text-white px-4 py-2 rounded hover:bg-gray-600" onclick="closeModal()">Close</button>
            </div>
        </div>
    </div>

<!-- Tailwind Modal -->
<!-- Modal Background and Content -->
<div id="filterModal" class="fixed inset-0 hidden z-50 flex items-center justify-center">
    <!-- Background Overlay -->
    <div class="absolute inset-0 bg-gray-800 bg-opacity-50"></div>

    <!-- Modal Content -->
    <div class="relative z-10 bg-white rounded-lg p-6 w-96 mx-auto mt-20">
        <h3 class="text-lg font-bold mb-4">Filter by Date</h3>
        <div class="mb-4">
            <label for="filter_start_date" class="block text-sm font-medium">Start Date</label>
            <input type="date" id="filter_start_date" class="w-full border rounded-md p-2" />
        </div>
        <div class="mb-4">
            <label for="filter_end_date" class="block text-sm font-medium">End Date</label>
            <input type="date" id="filter_end_date" class="w-full border rounded-md p-2" />
        </div>
        <div class="flex justify-end space-x-2">
            <button class="bg-gray-500 hover:bg-gray-400 text-white px-4 py-2 rounded-md" onclick="closeFilterModal()">Cancel</button>
            <button id="filterConfirmBtn" class="bg-blue-600 hover:bg-blue-400 text-white px-4 py-2 rounded-md">Confirm</button>
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



<!-- <script>
    function printPage() {
            // Open the print view page in a new window
            var width = 1000;
            var height = 600;
            var left = (window.innerWidth / 2) - (width / 2);
            var top = (window.innerHeight / 2) - (height / 2);

            // Open a new window with the calculated position and size
            var printWindow = window.open("{{ route('printBooking') }}", "_blank", 
                "width=" + width + ",height=" + height + ",left=" + left + ",top=" + top);

            // Optionally, you can wait for the window to load and trigger the print functionality
            printWindow.onload = function() {
                printWindow.print();
            };
        }
</script> -->

<script>
    let actionType = '';

    function openFilterModal(type) {
        actionType = type; // 'export' or 'print'
        document.getElementById('filterModal').classList.remove('hidden');
    }

    function closeFilterModal() {
        document.getElementById('filterModal').classList.add('hidden');
    }

   document.getElementById('filterConfirmBtn').addEventListener('click', function () {
    const startDate = document.getElementById('filter_start_date').value;
    const endDate = document.getElementById('filter_end_date').value;

    console.log("Start Date:", startDate); // Log the start date
    console.log("End Date:", endDate);

    if (!startDate || !endDate) {
        alert('Please select both start and end dates.');
        return;
    }

    // URL encode the date parameters to ensure proper handling
    const encodedStartDate = encodeURIComponent(startDate);
    const encodedEndDate = encodeURIComponent(endDate);

    if (actionType === 'export') {
        // Ensure the URL query parameters are properly encoded
        window.location.href = `{{ route('downloadbookingPDF') }}?startDate=${encodedStartDate}&endDate=${encodedEndDate}`;
    } else if (actionType === 'print') {
        printPage(encodedStartDate, encodedEndDate);
    }

    closeFilterModal();
});

function printPage(startDate, endDate) {
    var width = 1000;
    var height = 600;
    var left = (window.innerWidth / 2) - (width / 2);
    var top = (window.innerHeight / 2) - (height / 2);

    // Use the encoded date parameters
    var printUrl = `{{ route('printBooking') }}?startDate=${startDate}&endDate=${endDate}`;
    var printWindow = window.open(printUrl, "_blank", 
        "width=" + width + ",height=" + height + ",left=" + left + ",top=" + top);

    printWindow.onload = function () {
        printWindow.print();
    };
}
</script>


<script>
   document.addEventListener('click', function (e) {
    // Accept action
    if (e.target.classList.contains('triggerConfirm')) {
        Swal.fire({
            title: 'Are you sure?',
            text: "You want to accept this reservation.",
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
                    text: "The reservation has been accepted.",
                    icon: "success"
                });
            }
        });
    }

    // Reject action
    if (e.target.classList.contains('triggerCancel')) {
        Swal.fire({
            title: 'Are you sure?',
            text: "You want to reject this reservation.",
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#6c757d',
            confirmButtonText: 'Yes, Reject'
        }).then((result) => {
            if (result.isConfirmed) {
                e.target.closest('.cancelForm').submit();
                Swal.fire({
                    title: "Rejected!",
                    text: "The reservation has been rejected.",
                    icon: "error"
                });
            }
        });
    }
});

</script>
</body>

