@extends('layout.layout')

@include('layouts.navigation')

<script src="https://code.jquery.com/jquery-3.5.1.js"></script>
<link href="https://cdn.datatables.net/v/dt/dt-2.1.8/b-3.1.2/r-3.0.3/datatables.min.css" rel="stylesheet">
<script src="https://cdn.datatables.net/v/dt/dt-2.1.8/b-3.1.2/r-3.0.3/datatables.min.js"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">

<body class="font-inter">
    <div class="lg:pl-20 lg:pr-20">
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
                            <th class="py-3 px-4">Driver Contact</th>
                            <th class="py-3 px-4">Pick-Up Time</th>
                            <th class="py-3 px-4">Pick-Up Location</th>
                            <th class="py-3 px-4">Destination</th>
                            <th class="py-3 px-4">Start Date</th>
                            <th class="py-3 px-4">End Date</th>
                            <th class="py-3 px-4">Total Fare</th>
                            <th class="py-3 px-4">Remaining Bal.</th>
                            <th class="py-3 px-4">Proof of Payment</th>
                            <th class="py-3 px-4">Action</th>
                        </tr>
                    </thead>
                    <tbody class="text-sm text-gray-600" id="tableBody">
                        @if($bookings->isNotEmpty())
                            @foreach($bookings as $booking)
                                <tr>
                                    <td class="py-3 px-4">{{ $booking->id }}</td>
                                    <td class="py-3 px-4"  style="width: 15%">
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
                                    <td class="py-3 px-4">@if ($booking->schedule->isNotEmpty())
                                            @php
                                                // Get the latest schedule by sorting in descending order of 'created_at'
                                                $latestSchedule = $booking->schedule->sortByDesc('created_at')->first();
                                            @endphp
                                            @if ($latestSchedule && $latestSchedule->driver)
                                                {{-- Check if the driver_status is "accepted" --}}
                                                @if ($latestSchedule->driver_status === 'accepted')
                                                    {{-- Display the driver's name if driver_status is "accepted" --}}
                                                    {{ $latestSchedule->driver->member->mobile_num }}
                                                @else
                                                    {{-- Display "No Driver Yet" if driver_status is not "accepted" --}}
                                                    <p>No Driver Yet</p>
                                                @endif
                                            @else
                                                <p>No Driver Yet</p>
                                            @endif
                                        @else
                                            <p>No Driver Yet</p>
                                        @endif</td>
                                    <td class="py-3 px-4">{{ $booking->time }}</td>
                                    <td class="py-3 px-4">{{ $booking->location }}</td>
                                    <td class="py-3 px-4">{{ $booking->destination }}</td>
                                    <td class="py-3 px-4">{{ \Carbon\Carbon::parse($booking->start_date)->format('F d, Y') }}</td>
                                    <td class="py-3 px-4">{{ \Carbon\Carbon::parse($booking->end_date)->format('F d, Y') }}</td>
                                    <td class="py-3 px-4">₱{{ $booking->price }}.00</td>
                                    <td class="py-3 px-4">₱{{ $booking->remaining }}.00</td>
                                    <td class="py-3 px-4" >
                                        <button type="button" class="bg-blue-500 text-white py-1 px-3 rounded hover:bg-blue-600" data-receipt="{{ asset('img/' . $booking->receipt) }}" onclick="openModal(this)">
                                            View
                                        </button>
                                    </td>
                                    <td class="py-3 px-4" id="bookingTd">
                                        @if($booking->status === "cancelled")
                                            <span class='font-bold text-red-500'>Cancelled</span>
                                        @elseif($booking->status === "rejected")
                                            <span class='font-bold text-red-500'>Rejected</span>
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
    <!-- <div class="fixed inset-0 flex items-center justify-center z-50 hidden" id="imageModal" onclick="closeOnClickOutside(event)">
        <div class="bg-white rounded-lg overflow-hidden shadow-lg max-w-xl mx-4 w-full" id="modalContent">
            <div class="p-4">
                <h5 class="text-lg font-bold mb-2">Proof of Payment</h5>
                <button type="button" class="absolute top-0 right-0 m-2 text-gray-500" onclick="closeModal()">&times;</button>
            </div>
            <div class="p-4 text-center">
                <img id="modalImage" src="" alt="Receipt" class="max-w-full h-auto mx-auto" style="max-width: 90%;" />
            </div>
            <div class="p-4 text-right">
                <button type="button" class="bg-gray-500 text-white px-4 py-2 rounded hover:bg-gray-600" onclick="closeModal()">Close</button>
            </div>
        </div>
    </div> -->
    <div id="imageModal" class="fixed inset-0 z-50 hidden flex items-center justify-center bg-black bg-opacity-50" onclick="closeOnClickOutside(event)">
                                    <div class="bg-white p-2 rounded-lg w-80">
                                        <h2 class="text-center text-lg mb-4">Receipt</h2>
                                        <div class="p-4 text-center">
                                            <img id="modalImage" src="" alt="Receipt" class="w-full h-auto mb-4"  />
                                        </div>
                                        <button class="bg-blue-500 text-white px-4 py-2 rounded" onclick="closeModal()">Close</button>
                                    </div>
                                </div>

    <script>
        $(document).ready(function () {
            $('#myTable').DataTable({
                responsive: true,
                lengthMenu: [5, 10, 25, 50, 100],
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

        
    </script>

<script>
    document.addEventListener('click', function (e) {
     if (e.target.classList.contains('triggerCancel')) {
        Swal.fire({
            title: 'Are you sure?',
            html: "You are about to cancel this reservation. <span style='color: red;'>Please note that the payment cannot be refunded.</span>",
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#6c757d',
            confirmButtonText: 'Yes, Cancel'
        }).then((result) => {
            if (result.isConfirmed) {
                e.target.closest('.cancelForm').submit();
                Swal.fire({
                    title: "Cancelled!",
                    text: "The reservation has been Cancelled.",
                    icon: "error"
                });
            }
        });
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
