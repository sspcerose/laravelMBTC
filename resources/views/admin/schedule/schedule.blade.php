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
                                        <form action="{{ url('admin/schedule/optionschedule') }}" method="POST">
                                            @csrf
                                            <input type="hidden" name="booking_id" value="{{ $viewBooking->id }}">
                                            <button type="submit" class="bg-green-500 text-white py-1 px-2 rounded">Find A Driver</button>
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
                                            <button type="button" class="bg-green-500 text-white py-1 px-2 rounded triggerAssign">Assign Driver</button>
                                        </form>
                                        <div class="mt-3 relative flex flex-col p-3 text-sm text-gray-800 bg-blue-100 border border-blue-600 rounded-md hidden cancelAlert">
                                        <svg class="w-6 h-6 text-gray-800 dark:text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.529 9.988a2.502 2.502 0 1 1 5 .191A2.441 2.441 0 0 1 12 12.582V14m-.01 3.008H12M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z"/>
                                            </svg>
                                                Are you sure you want to assign this driver?
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
                                    <form action="{{ url('admin/schedule/optionschedule') }}" method="POST">
                                            @csrf
                                            <input type="hidden" name="booking_id" value="{{ $viewBooking->id }}">
                                            <button type="submit" class="bg-green-500 text-white py-1 px-2 rounded">Find A Driver</button>
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
                                    <div class="mt-3 relative flex flex-col p-3 text-sm text-gray-800 bg-blue-100 border border-blue-600 rounded-md hidden cancelAlert">
                                    <svg class="w-6 h-6 text-gray-800 dark:text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.529 9.988a2.502 2.502 0 1 1 5 .191A2.441 2.441 0 0 1 12 12.582V14m-.01 3.008H12M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z"/>
                                            </svg>
                                                Are you sure you want to assign this driver?
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

    document.addEventListener('click', function (e) {
    // Trigger cancel confirmation
        if (e.target.classList.contains('triggerAssign')) {
            let assignForm = e.target.closest('.assignForm');
            let cancelAlert = assignForm.nextElementSibling;
            cancelAlert.classList.remove('hidden');
            document.getElementById('scheduleTd').style.width = '25%'; 
            e.target.style.display = 'none';
        }
        // Close the cancel confirmation
        if (e.target.classList.contains('cancelButton')) {
            let cancelAlert = e.target.closest('.cancelAlert');
            cancelAlert.classList.add('hidden');
            document.getElementById('scheduleTd').style.width = '';
            let assignForm = cancelAlert.previousElementSibling;
            let cancelButton = assignForm.querySelector('.triggerAssign');
            if (cancelButton) {
                cancelButton.style.display = '';
            }
        }
        // Confirm cancellation and display success message
        if (e.target.classList.contains('yesButton')) {
            let cancelAlert = e.target.closest('.cancelAlert');
            let assignForm = cancelAlert.previousElementSibling;

            if (assignForm) {
                e.preventDefault(); 
                
                if (!assignForm.querySelector('.successMessageAlert')) {
                    let successMessage = document.createElement('div');
                    successMessage.setAttribute('role', 'alert');
                    successMessage.className = 'successMessageAlert mt-3 relative flex w-full p-3 text-sm text-white bg-blue-500 rounded-md';
                    successMessage.innerHTML = `<svg class="w-6 h-6 text-white-800 dark:text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                                                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 11h2v5m-2 0h4m-2.592-8.5h.01M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z"/>
                                                </svg>
                                                Successfully Assigned the Driver!`;
                    let scheduleTd = assignForm.closest('td');
                    scheduleTd.appendChild(successMessage); 
                    cancelAlert.classList.add('hidden');
                    setTimeout(function () {
                        successMessage.remove();
                        assignForm.submit(); 
                        // cancelAlert.classList.add('hidden');
                    }, 1000);
                }
            }
        }
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
</body>

