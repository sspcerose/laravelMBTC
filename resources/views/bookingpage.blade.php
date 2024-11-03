@extends('layout.layout')

@include('layouts.navigation')

<script src="https://code.jquery.com/jquery-3.5.1.js"></script>
<link href="https://cdn.datatables.net/v/dt/dt-2.1.8/b-3.1.2/r-3.0.3/datatables.min.css" rel="stylesheet">
<script src="https://cdn.datatables.net/v/dt/dt-2.1.8/b-3.1.2/r-3.0.3/datatables.min.js"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">

<body class="font-inter">
    <div class="max-w-5xl mx-auto">
        <div class="pt-24 lg:pt-28 flex justify-between items-center">
            <h1 class="text-black p-4 pl-5 text-center md:text-left font-extrabold text-3xl lg:text-5xl">Bookings</h1>
        </div>

        <div class="bg-neutral-300 mx-4 rounded-3xl p-2 items-center mb-4">
            <div class="overflow-x-auto bg-neutral-100 px-2 md:px-4 lg:py-2 rounded-2xl" id="largeTable">
            @if($bookings->isEmpty())
            <p class="text-center">NO BOOKING YET</p>
            @else
                <table class="min-w-full" id="myTable">
                    <thead>
                        <tr class="text-left text-sm text-neutral-950 uppercase tracking-wider">
                            <th class="py-3 px-4">ID</th>
                            <th class="py-3 px-4">Driver</th>
                            <th class="py-3 px-4">Location</th>
                            <th class="py-3 px-4">Destination</th>
                            <th class="py-3 px-4">Date</th>
                            <th class="py-3 px-4">Price</th>
                            <th class="py-3 px-4">Receipt</th>
                            <th class="py-3 px-4">Action</th>
                        </tr>
                    </thead>
                    <tbody class="text-sm text-gray-600" id="tableBody">
                        @if($bookings->isNotEmpty())
                            @foreach($bookings as $booking)
                                <tr>
                                    <td class="py-3 px-4">{{ $booking->id }}</td>
                                    <td class="py-3 px-4"  style="width: 20%">
                                    @if ($booking->schedule->isNotEmpty())
    @php
        // Get the latest schedule by sorting in descending order of 'created_at'
        $latestSchedule = $booking->schedule->sortByDesc('created_at')->first();
    @endphp

    @if ($latestSchedule && $latestSchedule->driver)
        {{-- Check if the driver_status is "accepted" --}}
        @if ($latestSchedule->driver_status === 'accepted')
            {{-- Display the driver's name if driver_status is "accepted" --}}
            {{ $latestSchedule->driver->member->name }} {{ $latestSchedule->driver->member->last_name }}
        @else
            {{-- Display "No Driver Yet" if driver_status is not "accepted" --}}
            <p>No Driver Yet</p>
        @endif
    @else
        <p>No Driver Yet</p>
    @endif
@else
    <p>No Driver Yet</p>
@endif
                                    </td>
                                    <td class="py-3 px-4">{{ $booking->location }}</td>
                                    <td class="py-3 px-4">{{ $booking->destination }}</td>
                                    <td class="py-3 px-4">{{ \Carbon\Carbon::parse($booking->start_date)->format('F d') }} - {{ \Carbon\Carbon::parse($booking->end_date)->format('d, Y') }}</td>
                                    <td class="py-3 px-4">₱{{ $booking->price }}</td>
                                    <td class="py-3 px-4" style="width: 15%">
                                        <button type="button" class="bg-blue-500 text-white py-1 px-3 rounded hover:bg-blue-600" data-receipt="{{ asset('img/' . $booking->receipt) }}" onclick="openModal(this)">
                                            View Receipt
                                        </button>
                                    </td>
                                    <td class="py-3 px-4" id="bookingTd">
                                        @if($booking->status === "cancelled")
                                            <span class='font-bold text-red-500'>Cancelled</span>
                                        @elseif($booking->status == "active" && \Carbon\Carbon::today()->gt($booking->end_date))
                                            <span class="font-bold text-green-500">Completed</span>
                                        @else
                                            <form action="{{ route('cancelbooking', $booking->id) }}" method="POST" class="inline-block cancelForm">
                                                @csrf
                                                <input type='hidden' name='schedule_id' value='{{ $booking->id }}'>
                                                <button type="button" id="c-button" class="bg-red-600 text-white py-1 px-3 rounded-lg hover:bg-red-500 focus:outline-none triggerCancel">
                                                    Cancel
                                                </button>
                                            </form>
                                            <!-- Custom confirmation alert (initially hidden) -->
                                            <div class="mt-3 relative flex flex-col p-3 text-sm bg-blue-100 border border-blue-600 rounded-md hidden cancelAlert">
                                            <svg class="w-6 h-6 text-gray-800 dark:text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                                                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.529 9.988a2.502 2.502 0 1 1 5 .191A2.441 2.441 0 0 1 12 12.582V14m-.01 3.008H12M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z"/>
                                                </svg>
                                                Are you sure you want to cancel this {{ $booking->destination }} trip?
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
                        @else
                            <tr><td colspan="7">No bookings found</td></tr>
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

    <script>
        $(document).ready(function () {
            $('#myTable').DataTable({
                responsive: true,
                order: [[0, 'desc']],
                columnDefs: [
                    { targets: 0, visible: false }
                ]
            });
        });

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
    // Trigger cancel prompt
    if (e.target.classList.contains('triggerCancel')) {
        let cancelForm = e.target.closest('.cancelForm');
        let cancelAlert = cancelForm.nextElementSibling;
        cancelAlert.classList.remove('hidden');
        document.getElementById('bookingTd').style.width = '50%'; 
        e.target.style.display = 'none';
    }

    // Close the cancel prompt
    if (e.target.classList.contains('cancelButton')) {
        let cancelAlert = e.target.closest('.cancelAlert');
        cancelAlert.classList.add('hidden');
        document.getElementById('bookingTd').style.width = '';
        let cancelForm = cancelAlert.previousElementSibling;
        let cancelButton = cancelForm.querySelector('.triggerCancel');
        if (cancelButton) {
            cancelButton.style.display = '';
        }
    }

    // Confirm cancellation and display success message
    if (e.target.classList.contains('yesButton')) {
        let cancelAlert = e.target.closest('.cancelAlert');
        let cancelForm = cancelAlert.previousElementSibling;

        if (cancelForm) {
            e.preventDefault(); 
            
            // Create a success alert if it doesn't exist yet
            if (!cancelForm.querySelector('.successMessageAlert')) {
                let successMessage = document.createElement('div');
                successMessage.setAttribute('role', 'alert');
                successMessage.className = 'successMessageAlert mt-3 relative flex w-full p-3 text-sm text-white bg-blue-500 rounded-md';
                successMessage.innerHTML = `<svg class="w-6 h-6 text-white-800 dark:text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 11h2v5m-2 0h4m-2.592-8.5h.01M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z"/>
                                            </svg>
                                            Successfully Cancelled the Booking!`;

                let bookingTd = cancelForm.closest('td');
                bookingTd.appendChild(successMessage); 
                cancelAlert.classList.add('hidden');

                
                setTimeout(function () {
                    successMessage.remove();
                    cancelForm.submit(); 
                    // cancelAlert.classList.add('hidden');
                }, 1000);
            }
        }
    }
});
    </script>

    <style>
        @media (max-width: 768px) {
            #bookingsTableContainer {
                display: none;
            }
            .lg\\:block {
                display: none;
            }
            .lg\\:hidden {
                display: block;
            }
        }
    </style>
</body>
</html>
