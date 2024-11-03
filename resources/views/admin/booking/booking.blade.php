@extends('layout.layout')

@include('layouts.adminNav')

<script src="https://code.jquery.com/jquery-3.5.1.js"></script>
<link href="https://cdn.datatables.net/v/dt/dt-2.1.8/b-3.1.2/r-3.0.3/datatables.min.css" rel="stylesheet">
<script src="https://cdn.datatables.net/v/dt/dt-2.1.8/b-3.1.2/r-3.0.3/datatables.min.js"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">


<body class="font-inter">
    <div class="lg:pl-20 lg:pr-10">
        <div class="pt-24 lg:pt-28 flex justify-between items-center">
            <h1 class="text-black p-4 pl-4 text-center md:text-left font-extrabold text-3xl">Bookings</h1>
            <form action="" class="px-4 lg:px-0 pt-4 lg:pr-5">
                <button class="bg-green-500 text-white flex items-center py-3 px-4 rounded-xl">
                    <i class="fas fa-bell mr-2"></i> <!-- Notification icon -->
                    Notify
                </button>
            </form>
        </div>


    <div class="bg-neutral-300 mx-4 rounded-3xl p-2 items-center mb-4">
    <div class="overflow-x-auto bg-neutral-100 px-2 md:px-4 lg:py-2 rounded-2xl" id="largeTable">
        @if($viewBookings->isEmpty())
            <p class="text-center">NO BOOKINGS YET</p>
        @else
        <table class="min-w-full" id="myTable">
            <thead>
            <tr class="text-left text-sm text-neutral-950 uppercase tracking-wider">
                    <th class="py-3 px-4">ID</th>
                    <th class="py-3 px-4" style="width: 15%;">CUSTOMER NAME</th>
                    <th class="py-3 px-4" style="width: 20%;">LOCATION</th>
                    <th class="py-3 px-4">DESTINATION</th>
                    <th class="py-3 px-4">PASSENGER</th>
                    <th class="py-3 px-4" style="width: 25%;" >DATE</th>
                    <th class="py-3 px-4">Price</th>
                    <th class="py-3 px-4" style="width: 10%;">RECEIPT</th>
                    <th class="py-3 px-4">Status</th>
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
                        <td class="py-3 px-4">{{ $viewBooking->location }}</td>
                        <td class="py-3 px-4">{{ $viewBooking->destination }}</td>
                        <td class="py-3 px-4">{{ $viewBooking->passenger }}</td>
                        <td class="py-3 px-4">
                            {{ \Carbon\Carbon::parse($viewBooking->start_date)->format('F d, Y') }} - {{ \Carbon\Carbon::parse($viewBooking->end_date)->format('F d, Y') }}
                        </td>
                        <td class="py-3 px-4">₱{{ $viewBooking->price }}.00</td>
                        <td class="py-3 px-4">
                            <button type="button" class="bg-blue-500 text-white py-1 px-3 rounded hover:bg-blue-600"  data-receipt="{{ asset('img/' . $viewBooking->receipt) }}" onclick="openModal(this)">
                                        View Receipt
                                    </button>
                        </td>
                        <td class="py-3 px-4">
                            @if($viewBooking->status == "active" && (\Carbon\Carbon::today()->lt($viewBooking->start_date)))
                            <span class="font-bold text-yellow-500">Soon</span>
                            @elseif($viewBooking->status == "active" && \Carbon\Carbon::today()->between($viewBooking->start_date, $viewBooking->end_date))
                            <span class="font-bold text-blue-500">On going</span>
                            @elseif($viewBooking->status == "active" && \Carbon\Carbon::today()->gt($viewBooking->end_date))
                            <span class="font-bold text-green-500">Completed</span>
                            @else
                            <span class="font-bold text-red-600">Cancelled</span>
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
    </script>

</body>

