@extends('layout.layout')


@include('layouts.adminNav')

<script src="https://code.jquery.com/jquery-3.5.1.js"></script>
<link href="https://cdn.datatables.net/v/dt/dt-2.1.8/b-3.1.2/r-3.0.3/datatables.min.css" rel="stylesheet">
<script src="https://cdn.datatables.net/v/dt/dt-2.1.8/b-3.1.2/r-3.0.3/datatables.min.js"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">

<body class="font-inter">
    <div class="lg:pl-20 lg:pr-10">
        <div class="pt-24 lg:pt-28 flex justify-between items-center">
            <h1 class="text-black p-4 pl-4 text-center md:text-left font-extrabold text-3xl">Driver Schedule</h1>
            
        </div>
        <div class="bg-neutral-300 mx-4 rounded-3xl p-2 items-center mb-4">
            <div class="overflow-x-auto bg-neutral-100 px-2 md:px-4 lg:py-2 rounded-2xl" id="largeTable">
            @if($viewBookings->isEmpty())
                <p class="text-center">NOTHING TO SCHEDULE YET</p>
            @else
                <table class="min-w-full" id="myTable">
                    <thead>
                        <tr class="text-left text-sm text-neutral-950 uppercase tracking-wider">
                            <th class="py-3 px-4">ID</th>
                            <th class="py-3 px-4">CUSTOMER NAME</th>
                            <th class="py-3 px-4">DESTINATION</th>
                            <th class="py-3 px-4">PICK-UP LOCATION</th>
                            <th class="py-3 px-4">START DATE</th>
                            <th class="py-3 px-4">END DATE</th>
                            <th class="py-3 px-4">PROOF OF PAYMENT</th>
                            <th class="py-3 px-4">DRIVER</th>
                            <th class="py-3 px-4">STATUS</th>
                        </tr>
                    </thead>
                    <tbody class="text-sm text-gray-600" id="tableBody">
                    @foreach($viewBookings as $viewBooking)
                        <tr>
                            <td class="py-3 px-4">{{ $viewBooking->id }}</td>
                            <td class="py-3 px-4">{{ $viewBooking->user->name }} {{ $viewBooking->user->last_name }}</td>
                            <td class="py-3 px-4">{{ $viewBooking->destination }}</td>
                            <td class="py-3 px-4">{{ $viewBooking->location }}</td>
                            <td class="py-3 px-4">{{ date('F d, Y', strtotime($viewBooking->start_date)) }}</td>
                            <td class="py-3 px-4">{{ date('F d, Y', strtotime($viewBooking->end_date)) }}</td>
                            <td class="py-3 px-4">
                            <button type="button" class="bg-blue-500 text-white text-center py-1 px-3 rounded hover:bg-blue-600"  data-receipt="{{ asset('img/' . $viewBooking->receipt) }}" onclick="openModal(this)">
                                        View
                                    </button>
                            </td>
                            <td class="py-3 px-4" id="scheduleTd">
                                @if($viewBooking->schedule->isNotEmpty())
                                    @php
                                        $latestSchedule = $viewBooking->schedule->first();
                                    @endphp
                                    @if($latestSchedule->driver_status == 'cancelled' || $latestSchedule->driver_status == 'conflict')
                                        @if($drivers->isEmpty())
                                        <form action="{{ url('admin/schedule/optionschedule') }}" method="POST" class="findDriverForm">
                                            @csrf
                                            <input type="hidden" name="booking_id" value="{{ $viewBooking->id }}">
                                            <button type="button" class="bg-green-500 text-white py-1 px-2 rounded triggerFindDriver">Find A Driver</button>
                                        </form>
                                                
                                        @else
                                        <form action="{{ url('admin/schedule/schedule') }}" method="POST" class="assignForm">
                                            @csrf
                                            <input type="hidden" name="booking_id" value="{{ $viewBooking->id }}">
                                            <select name="driver_id" class="border rounded p-1 mb-1">
                                            <!-- <option class="" disabled selected>Select Driver</option> -->
                                             @dd($drivers)
                                                @foreach($drivers as $driver)
                                                    @foreach($driver->driver as $individualDriver)
                                                        <option value="{{ $individualDriver->id }}">
                                                            {{ $driver->name }} {{ $driver->last_name }}
                                                        </option>
                                                    @endforeach
                                                @endforeach
                                            </select>
                                            <button type="button" class="bg-green-500 text-white py-1 px-2 rounded triggerAssign">Assign Driver</button>
                                        </form>
                                        
                                        @endif
                                    @elseif(!empty($latestSchedule->driver))
                                    <div class="flex items-center space-x-2">
                                        @if($latestSchedule->cust_status == 'inactive')
                                        <p class="border rounded p-1 mb-1">
                                            {{ $latestSchedule->driver->member->name }} {{ $latestSchedule->driver->member->last_name }}
                                        </p>
                                        @elseif($latestSchedule->driver_status == 'optionscheduled')
                                        <p class="">
                                            <span class="font-bold text-blue-500">Finding a Driver...</span>
                                        </p>
                                        @else
                                        <!-- <p class="border rounded p-1 mb-1"> -->
                                        <p class="">
                                            {{ $latestSchedule->driver->member->name }} {{ $latestSchedule->driver->member->last_name }}
                                        </p>
                                        <!-- <button type="submit" class="bg-red-500 text-white py-1 px-2 rounded mb-1">Undo</button> -->
                                        @endif
                                    </div>
                                    @endif
                                @else
                                    @if($drivers->isEmpty())
                                    <form action="{{ url('admin/schedule/optionschedule') }}" method="POST" class="findDriverForm">
                                            @csrf
                                            <input type="hidden" name="booking_id" value="{{ $viewBooking->id }}">
                                            <button type="button" class="bg-green-500 text-white py-1 px-2 rounded triggerFindDriver">Find A Driver</button>
                                        </form>
                                    @else
                                    <form action="{{ url('admin/schedule/schedule') }}" method="POST" class="assignForm">
                                        @csrf
                                        <input type="hidden" name="booking_id" value="{{ $viewBooking->id }}">
                                        <select name="driver_id" class="border rounded p-1 mb-1">
                                        <!-- <option class="" disabled selected>Select Driver</option> -->
                                            @foreach($drivers as $driver)
                                                @foreach($driver->driver as $individualDriver)
                                                    <option value="{{ $individualDriver->id }}">
                                                        {{ $driver->name }} {{ $driver->last_name }}
                                                    </option>
                                                @endforeach
                                            @endforeach
                                        </select>
                                        <button type="button" class="bg-green-500 text-white py-1 px-2 rounded mb-1 triggerAssign">Assign Driver</button>
                                     </form>
                                    @endif
                                @endif
                            </td>
                            <td class="py-3 px-4">
                                @if($viewBooking->schedule->isNotEmpty())
                                    @if($latestSchedule->driver_status == 'accepted')
                                        <span class="font-bold text-green-500">Driver Accepted</span>
                                    @elseif($latestSchedule->driver_status == 'scheduled')
                                        <span class="font-bold text-blue-500">Scheduled</span>
                                    @elseif($latestSchedule->cust_status == 'inactive')
                                        <span class="font-bold text-red-500">Customer Cancelled</span>
                                    @elseif($latestSchedule->driver_status == 'cancelled')
                                        <span class="font-bold text-red-500">Driver Cancelled, Schedule New One</span>
                                    @elseif($latestSchedule->driver_status == 'conflict')
                                        <span class="font-bold text-red-500">Cannot Schedule Driver <span class="text-red-500 underline uppercase">{{ $driver->name }} {{ $driver->last_name }}</span> because of conflicting date</span>
                                    @elseif($latestSchedule->driver_status == 'optionscheduled')
                                        <span class="font-bold text-blue-500">Finding a Driver...</span>
                                    @endif
                                @else
                                    <span class="font-bold text-yellow-500">To be Scheduled</span>
                                @endif
                            </td>
                        </tr>
                    @endforeach
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
                                    columns: [1, 2, 3, 4, 5, 7, 8] 
                                }
                            },
                            {
                                extend: 'excel',
                                exportOptions: {
                                    columns: [1, 2, 3, 4, 5, 7, 8] 
                                }
                            },
                            {
                                extend: 'csv',
                                exportOptions: {
                                    columns: [1, 2, 3, 4, 5, 7, 8] 
                                }
                            },
                            {
                                extend: 'pdf',
                                exportOptions: {
                                    ccolumns: [1, 2, 3, 4, 5, 7, 8] 
                                }
                            },
                            {
                                extend: 'print',
                                exportOptions: {
                                    columns: [1, 2, 3, 4, 5, 7, 8] 
                                }
                            }
                        ]
                    }
                ]
            }
        }
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
    // Handle "Assign Driver" button click
    if (e.target.classList.contains('triggerAssign')) {
        const form = e.target.closest('.assignForm'); // Get the form to submit
        Swal.fire({
            title: 'Are you sure?',
            text: "You want to assign this driver.",
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#28a745',
            cancelButtonColor: '#6c757d',
            confirmButtonText: 'Yes, Assign'
        }).then((result) => {
            if (result.isConfirmed) {
                e.target.closest('.assignForm').submit();
                Swal.fire({
                    title: "Scheduled!",
                    text: "The driver has been scheduled.",
                    icon: "success"
                });
            }
        });
    }

    if (e.target.classList.contains('triggerFindDriver')) {
        const form = e.target.closest('.findDriverForm'); // Get the form to submit
        Swal.fire({
            title: 'Are you sure?',
            text: "You want to find a driver for this booking.",
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#28a745',
            cancelButtonColor: '#6c757d',
            confirmButtonText: 'Yes, Find Driver'
        }).then((result) => {
            if (result.isConfirmed) {
                e.target.closest('.findDriverForm').submit();
                Swal.fire({
                    title: "Finding a Driver!",
                    text: "Finding a Driver.",
                    icon: "success"
                });
            }
        });
    }
});
</script>        
</body>

