@extends('layout.layout')

@include('layouts.adminNav')
<body class="font-inter">
    <div class="lg:pl-20 lg:pr-10">
        <div class="pt-24 lg:pt-28 flex justify-between items-center">
        <h1 class="text-black p-4 pl-4 text-center md:text-left text-3xl"><span class="font-extrabold">Member</span>/{{$viewSpecific->name}} {{$viewSpecific->last_name}} </h1>
            <div class="px-4 lg:px-0 pt-4 lg:pr-5">
            <div class="flex">
                    <button class="bg-red-600 hover:bg-red-400 text-white flex items-center py-3 px-4 rounded-xl" onclick="window.location.href='{{ route('admin.member.member') }}';">
                        Back
                    </button>
            </div>
            
              
        </div>
        </div>
<!-- MEMBER INFO -->
    <div class="bg-neutral-300 mx-4 rounded-3xl p-2 items-center mb-4">
    <div class="overflow-x-auto bg-neutral-100 px-2 md:px-4 lg:py-2 rounded-2xl">
    <p><span class="font-bold">MEMBER NAME: {{$viewSpecific->name}} {{$viewSpecific->last_name}}</span></p> <br>
    <span>TIN: {{$viewSpecific->tin}}</span> <br>
    <span>CONTACT NUMBER: {{$viewSpecific->mobile_num}}</span> <br>
    <span>EMAIL: {{$viewSpecific->email}}</span> <br>
    <span>DATE OF REGISTRATION: {{$viewSpecific->date_joined}}</span> <br>
    <span>MEMBER TYPE: {{$viewSpecific->type}}</span> <br>

<!-- VEHICLES -->
    @if ($vehicles->isNotEmpty())
    <div class="mt-6">
        <h3 class="text-lg font-bold mb-4">Vehicle Information</h3>
        <table class="min-w-full bg-white border border-gray-200">
            <thead class="bg-gray-100">
                <tr>
                    <th class="py-2 px-4 border-b text-left text-sm font-medium text-gray-600">Type</th>
                    <th class="py-2 px-4 border-b text-left text-sm font-medium text-gray-600">Plate Number</th>
                    <th class="py-2 px-4 border-b text-left text-sm font-medium text-gray-600">Capacity</th>
                    <th class="py-2 px-4 border-b text-left text-sm font-medium text-gray-600">Status</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($vehicles as $vehicle)
                    <tr>
                        <td class="py-2 px-4 border-b text-sm text-gray-700">{{ $vehicle->type }}</td>
                        <td class="py-2 px-4 border-b text-sm text-gray-700">{{ $vehicle->plate_num }}</td>
                        <td class="py-2 px-4 border-b text-sm text-gray-700">{{ $vehicle->capacity }}</td>
                        <td class="py-2 px-4 border-b text-sm text-gray-700">{{ $vehicle->status }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@else
    <p>No vehicles assigned to this member.</p>
@endif

<div class="mt-6">
<h3 class="text-lg font-bold mb-4">Monthly Due</h3>
<div class="mb-4 flex justify-end">
    <button id="show-current" class="px-4 py-2 bg-blue-500 text-white rounded hidden" onclick="showCurrent()">Current Month</button>
    <button id="show-all" class="px-4 py-2 bg-gray-500 text-white rounded" onclick="showAll()">See All</button>
</div>

<!-- MONTHLY DUES  -->
<table id="current-month-table" class="min-w-full bg-white border border-gray-200">
    <thead class="bg-gray-100">
        <tr>
            <th class="py-2 px-4 border-b text-left text-sm font-medium text-gray-600">Last Payment</th>
            <th class="py-2 px-4 border-b text-left text-sm font-medium text-gray-600">Amount</th>
            <th class="py-2 px-4 border-b text-left text-sm font-medium text-gray-600">Current Due</th>
            <th class="py-2 px-4 border-b text-left text-sm font-medium text-gray-600">Status</th>
            <th class="py-2 px-4 border-b text-left text-sm font-medium text-gray-600">Action</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($currentMonthDues as $due)
            <tr>
                <td class="py-2 px-4 border-b text-sm text-gray-700">{{ \Carbon\Carbon::parse($due['last_payment'])->format('F d, Y') }}</td>
                <td class="py-2 px-4 border-b text-sm text-gray-700">₱{{ number_format($due['amount'], 2) }}</td>
                <td class="py-2 px-4 border-b text-sm text-gray-700">{{ $due['month'] }}</td>
                <td class="py-2 px-4 border-b text-sm text-gray-700">{{ $due['status'] }}</td>
                <td class="py-2 px-4 border-b text-sm text-gray-700">
                @if( $due['status'] == "paid")
                    <p>No Action Required</p>
                @else
                <form method="POST" action="{{ url('admin/monthlydues/monthlydues/' . $due['id']) }}" class="paidForm">
                                @csrf
                                <input type="hidden" name="payment_id" value="{{ $due['id'] }}">
                                <button type="button" class="bg-blue-500 text-white py-1 px-3 rounded hover:bg-blue-600 triggerPaid">Paid</button>
                </form> 
                </td>
                @endif
            </tr>
        @endforeach
    </tbody>
</table>
</div>

<!-- All Monthly Dues -->
<table id="all-dues-table" class="min-w-full bg-white border border-gray-200 hidden">
    <thead class="bg-gray-100">
        <tr>
            <th class="py-2 px-4 border-b text-left text-sm font-medium text-gray-600">Last Payment</th>
            <th class="py-2 px-4 border-b text-left text-sm font-medium text-gray-600">Amount</th>
            <th class="py-2 px-4 border-b text-left text-sm font-medium text-gray-600">Current Due</th>
            <th class="py-2 px-4 border-b text-left text-sm font-medium text-gray-600">Status</th>
            <th class="py-2 px-4 border-b text-left text-sm font-medium text-gray-600">Action</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($allDues as $due)
            <tr>
                <td class="py-2 px-4 border-b text-sm text-gray-700">{{ \Carbon\Carbon::parse($due['last_payment'])->format('F d, Y') }}</td>
                <td class="py-2 px-4 border-b text-sm text-gray-700">₱{{ number_format($due['amount'], 2) }}</td>
                <td class="py-2 px-4 border-b text-sm text-gray-700">{{ $due['month'] }}</td>
                <td class="py-2 px-4 border-b text-sm text-gray-700">{{ $due['status'] }}</td>
                <td class="py-2 px-4 border-b text-sm text-gray-700">
                @if( $due['status'] == "paid")
                    <p>No Action Required</p>
                @else
                <form method="POST" action="{{ url('admin/monthlydues/monthlydues/' . $due['id']) }}" class="paidForm">
                                @csrf
                                <input type="hidden" name="payment_id" value="{{ $due['id'] }}">
                                <button type="button" class="bg-blue-500 text-white py-1 px-3 rounded hover:bg-blue-600 triggerPaid">Paid</button>
                            </form> 
                </td>
                @endif
            </tr>
        @endforeach
    </tbody>
</table>


<!-- SCHEDULE -->
<div class="mt-6">
<h3 class="text-lg font-bold mb-4">Schedule for this month</h3>
<table class="min-w-full bg-white border border-gray-200">
            <thead class="bg-gray-100">
                <tr>
                    <!-- <th class="border border-gray-300 px-4 py-2">Schedule ID</th> -->
                    <!-- <th class="border border-gray-300 px-4 py-2">Booking ID</th> -->
                    <th class="py-2 px-4 border-b text-left text-sm font-medium text-gray-600">Customer Name</th>
                    @if($viewSpecific->type == "Driver")
                    <th class="py-2 px-4 border-b text-left text-sm font-medium text-gray-600">Destination</th> 
                    <th class="py-2 px-4 border-b text-left text-sm font-medium text-gray-600">Pick-up Location</th>
                    <th class="py-2 px-4 border-b text-left text-sm font-medium text-gray-600">Pick-up Time</th>
                    @endif
                    @if($viewSpecific->type == "Owner")
                    <th class="py-2 px-4 border-b text-left text-sm font-medium text-gray-600">Driver Name</th>
                    @endif
                    <th class="py-2 px-4 border-b text-left text-sm font-medium text-gray-600">Start Date</th> <!-- New Column -->
                    <th class="py-2 px-4 border-b text-left text-sm font-medium text-gray-600">End Date</th>
                    <th class="py-2 px-4 border-b text-left text-sm font-medium text-gray-600">Vehicle</th>
                    <th class="py-2 px-4 border-b text-left text-sm font-medium text-gray-600">Driver Status</th>
                    <th class="py-2 px-4 border-b text-left text-sm font-medium text-gray-600">Customer Status</th> <!-- New Column -->
                    <th class="py-2 px-4 border-b text-left text-sm font-medium text-gray-600">Schedule Status</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($schedules as $schedule)
                    <tr>
                        <!-- <td class="border border-gray-300 px-4 py-2">{{ $schedule['schedule_id'] }}</td> -->
                        <!-- <td class="border border-gray-300 px-4 py-2">{{ $schedule['booking_id'] }}</td> -->
                        <td class="py-2 px-4 border-b text-sm text-gray-700">{{ $schedule['customer_name'] }}</td>
                        @if($viewSpecific->type == "Driver")
                        <td class="py-2 px-4 border-b text-sm text-gray-700">{{ $schedule['destination'] }}</td>
                        <td class="py-2 px-4 border-b text-sm text-gray-700">{{ $schedule['location'] }}</td>
                        <td class="py-2 px-4 border-b text-sm text-gray-700">{{ $schedule['time'] }}</td>
                        @endif
                        @if($viewSpecific->type == "Owner")
                        <td class="py-2 px-4 border-b text-sm text-gray-700">{{ $schedule['driver_name'] }}</td>
                        @endif
                        <td class="py-2 px-4 border-b text-sm text-gray-700">{{ \Carbon\Carbon::parse($schedule['start_date'])->format('F d, Y') }}</td>
                        <td class="py-2 px-4 border-b text-sm text-gray-700">{{ \Carbon\Carbon::parse($schedule['end_date'])->format('F d, Y') }}</td> 
                        <td class="py-2 px-4 border-b text-sm text-gray-700">{{ $schedule['vehicle_name'] }}</td>
                        @if( $schedule['driver_status'] == "cancelled")
                        <td class="py-2 px-4 border-b text-sm font-bold text-red-500">Cancelled</td>
                        @elseif( $schedule['driver_status'] == "optionscheduled")
                        <td class="py-2 px-4 border-b text-sm text-red-500">Waiting for response</td>
                        @elseif( $schedule['driver_status'] == "scheduled")
                        <td class="py-2 px-4 border-b text-sm text-red-500">Waiting for response</td>
                        @else
                        <td class="py-2 px-4 border-b text-sm text-red-500">Accepted</td>
                        @endif

                        <td class="py-2 px-4 border-b text-sm text-gray-700">
                        @if($schedule['cust_status'] == "cancelled")
                            <span class="font-bold text-red-500">Customer Cancelled</span> 
                        @else
                            <span class="font-bold text-green-500">Active</span>
                        @endif 
                        </td> <!-- New Column -->
                        <td class="py-2 px-4 border-b text-sm text-gray-700">
                        @if( $schedule['driver_status'] == "cancelled")
                            <span class="font-bold text-red-500">Driver Cancelled</span>
                        @elseif($schedule['cust_status'] == "cancelled")
                            <span class="font-bold text-red-500">Customer Cancelled</span>
                        @elseif((\Carbon\Carbon::today()->lt($schedule['start_date'])))
                            <span class="font-bold text-yellow-500">Upcoming</span>
                            @elseif(\Carbon\Carbon::today()->between($schedule['start_date'], $schedule['end_date']))
                            <span class="font-bold text-blue-500">On going</span>
                            @else(\Carbon\Carbon::today()->gt($schedule['end_date']))
                            <span class="font-bold text-green-500">Completed</span>
                        @endif
                        <td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="text-center">No schedules found for this member.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        <script>
    function showCurrent() {
        document.getElementById('current-month-table').classList.remove('hidden');
        document.getElementById('all-dues-table').classList.add('hidden');
        document.getElementById('show-current').classList.add('hidden');
        document.getElementById('show-all').classList.remove('hidden');
    }

    function showAll() {
        document.getElementById('current-month-table').classList.add('hidden');
        document.getElementById('all-dues-table').classList.remove('hidden');
        document.getElementById('show-current').classList.remove('hidden');
        document.getElementById('show-all').classList.add('hidden');
    }
</script> 

<script>
     document.addEventListener('click', function (e) {
    // Accept action
    if (e.target.classList.contains('triggerPaid')) {
        Swal.fire({
            title: 'Are you sure?',
            text: "Update its status to paid.",
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#28a745',
            cancelButtonColor: '#6c757d',
            confirmButtonText: 'Yes, Update it!'
        }).then((result) => {
            if (result.isConfirmed) {
                e.target.closest('.paidForm').submit();
                Swal.fire({
                    title: "Updated!",
                    text: "The monthly due status has been updated.",
                    icon: "success"
                });
            }
        });
    }
});
</script>
</body>
