@extends('layout.layout')

@include('layouts.adminNav')

<body class="font-inter">
    <div class="lg:pl-20 lg:pr-10">
        <div class="pt-24 lg:pt-28 flex justify-between items-center">
            <h1 class="text-black p-4 pl-4 text-center md:text-left text-sm md:text-xl"><span class="font-extrabold">Member</span>/ {{$viewSpecific->name}} {{$viewSpecific->last_name}} </h1>
            <div class="px-2 lg:px-0 pt-4 pb-4">
                <div class="flex">
                    <button class="bg-red-600 hover:bg-red-400 text-white flex items-center py-3 px-4 rounded-xl" onclick="window.location.href='{{ route('admin.member.member') }}';">
                        Back
                    </button>
                </div>
            </div>
        </div>

        <div class="bg-neutral-300 mx-4 rounded-3xl p-2 md:p-6 items-center mb-4">

            <!-- MEMBER INFO -->
            <div class="overflow-x-auto bg-neutral-100 p-4 md:p-8 pb-4 rounded-2xl">
                <div class="text-left grid grid-cols-1 lg:grid-cols-2 gap-2 w-full flex-1">
                    <!-- Member Name -->
                    <div class="flex flex-col">
                        <label class="font-bold text-neutral-700 w-full">Member Name:</label>
                        <p class="bg-neutral-50 rounded p-2 w-full">{{ $viewSpecific->name }} {{ $viewSpecific->last_name }}</p>
                    </div>

                    <!-- TIN -->
                    <div class="flex flex-col">
                        <label class="font-bold text-neutral-700 w-full">TIN:</label>
                        <p class="bg-neutral-50 rounded p-2 w-full">{{ $viewSpecific->tin }}</p>
                    </div>

                    <!-- Contact Number -->
                    <div class="flex flex-col">
                        <label class="font-bold text-neutral-700 w-full">Contact Number:</label>
                        <p class="bg-neutral-50 rounded p-2 w-full">{{ $viewSpecific->mobile_num }}</p>
                    </div>

                    <!-- Email -->
                    <div class="flex flex-col">
                        <label class="font-bold text-neutral-700 w-full">Email:</label>
                        <p class="text-sm bg-neutral-50 rounded p-2 w-full">{{ $viewSpecific->email }}</p>
                    </div>

                    <!-- Date of Registration -->
                    <div class="flex flex-col">
                        <label class="font-bold text-neutral-700 w-full">Date of Registration:</label>
                        <p class="bg-neutral-50 rounded p-2 w-full">{{ $viewSpecific->date_joined }}</p>
                    </div>

                    <!-- Member Type -->
                    <div class="flex flex-col">
                        <label class="font-bold text-neutral-700 w-full">Member Type:</label>
                        <p class="bg-neutral-50 rounded p-2 w-full">{{ $viewSpecific->type }}</p>
                    </div>
                </div>
            </div>



            <!-- SCHEDULE -->
            <div>
                <h3 class="text-lg font-bold py-4">Schedule for this Month</h3>
                <div class="overflow-x-auto bg-white shadow rounded-2xl">
                    <table class="min-w-full bg-white bg-neutral-200 table-auto">
                        <thead class="bg-neutral-100">
                            <tr>
                                <th class="py-3 px-6 bg-neutral-50-b text-left text-sm font-medium text-neutral-600">Customer Name</th>
                                @if($viewSpecific->type == "Driver")
                                <th class="py-3 px-6 border-b text-left text-sm font-medium text-neutral-600">Destination</th>
                                <th class="py-3 px-6 border-b text-left text-sm font-medium text-neutral-600">Pick-up Location</th>
                                <th class="py-3 px-6 border-b text-left text-sm font-medium text-neutral-600">Pick-up Time</th>
                                @endif
                                @if($viewSpecific->type == "Owner")
                                <th class="py-3 px-6 border-b text-left text-sm font-medium text-neutral-600">Driver Name</th>
                                @endif
                                <th class="py-3 px-6 border-b text-left text-sm font-medium text-neutral-600">Start Date</th>
                                <th class="py-3 px-6 border-b text-left text-sm font-medium text-neutral-600">End Date</th>
                                <th class="py-3 px-6 border-b text-left text-sm font-medium text-neutral-600">Vehicle</th>
                                <th class="py-3 px-6 border-b text-left text-sm font-medium text-neutral-600">Driver Status</th>
                                <th class="py-3 px-6 border-b text-left text-sm font-medium text-neutral-600">Customer Status</th>
                                <th class="py-3 px-6 border-b text-left text-sm font-medium text-neutral-600">Schedule Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($schedules as $schedule)
                            <tr>
                                <td class="py-2 px-6 border-b text-sm text-neutral-700">{{ $schedule['customer_name'] }}</td>
                                @if($viewSpecific->type == "Driver")
                                <td class="py-2 px-6 border-b text-sm text-neutral-700">{{ $schedule['destination'] }}</td>
                                <td class="py-2 px-6 border-b text-sm text-neutral-700">{{ $schedule['location'] }}</td>
                                <td class="py-2 px-6 border-b text-sm text-neutral-700">{{ $schedule['time'] }}</td>
                                @endif
                                @if($viewSpecific->type == "Owner")
                                <td class="py-2 px-6 border-b text-sm text-neutral-700">{{ $schedule['driver_name'] }}</td>
                                @endif
                                <td class="py-2 px-6 border-b text-sm text-neutral-700">{{ \Carbon\Carbon::parse($schedule['start_date'])->format('F d, Y') }}</td>
                                <td class="py-2 px-6 border-b text-sm text-neutral-700">{{ \Carbon\Carbon::parse($schedule['end_date'])->format('F d, Y') }}</td>
                                <td class="py-2 px-6 border-b text-sm text-neutral-700">{{ $schedule['vehicle_name'] }}</td>

                                <!-- Driver Status -->
                                <td class="py-2 px-6 border-b text-sm font-bold
                    @if($schedule['driver_status'] == 'cancelled') text-red-500
                    @elseif($schedule['driver_status'] == 'optionscheduled' || $schedule['driver_status'] == 'scheduled') text-yellow-500
                    @else text-green-500
                    @endif">
                                    @if($schedule['driver_status'] == "cancelled") Cancelled
                                    @elseif($schedule['driver_status'] == "optionscheduled" || $schedule['driver_status'] == "scheduled") Waiting for response
                                    @else Accepted
                                    @endif
                                </td>

                                <!-- Customer Status -->
                                <td class="py-2 px-6 border-b text-sm font-bold
                    @if($schedule['cust_status'] == 'cancelled') text-red-500
                    @else text-green-500
                    @endif">
                                    @if($schedule['cust_status'] == 'cancelled') Customer Cancelled
                                    @else Active
                                    @endif
                                </td>

                                <!-- Schedule Status -->
                                <td class="py-2 px-6 border-b text-sm
                    @if(\Carbon\Carbon::today()->lt($schedule['start_date'])) text-yellow-500
                    @elseif(\Carbon\Carbon::today()->between($schedule['start_date'], $schedule['end_date'])) text-blue-500
                    @elseif(\Carbon\Carbon::today()->gt($schedule['end_date'])) text-green-500
                    @endif">
                                    @if(\Carbon\Carbon::today()->lt($schedule['start_date'])) Upcoming
                                    @elseif(\Carbon\Carbon::today()->between($schedule['start_date'], $schedule['end_date'])) Ongoing
                                    @else Completed
                                    @endif
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="10" class="text-center py-4 text-neutral-700">No schedules found for this member.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- VEHICLES -->
            <div>
                @if ($vehicles->isNotEmpty())
                <div class="mt-6 ">
                    <h3 class="text-lg font-bold py-4">Vehicle Information</h3>
                    <div class="overflow-x-auto bg-white shadow rounded-2xl">
                        <table class="min-w-full overflow-x-auto bg-white shadow rounded-lg">
                            <thead class="bg-neutral-100 rounded-lg">
                                <tr>
                                    <th class="py-2 px-4 border-b text-left text-sm font-medium text-neutral-600">Type</th>
                                    <th class="py-2 px-4 border-b text-left text-sm font-medium text-neutral-600">Plate Number</th>
                                    <th class="py-2 px-4 border-b text-left text-sm font-medium text-neutral-600">Capacity</th>
                                    <th class="py-2 px-4 border-b text-left text-sm font-medium text-neutral-600">Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($vehicles as $vehicle)
                                <tr>
                                    <td class="py-2 px-4 border-b text-sm text-neutral-700">{{ $vehicle->type }}</td>
                                    <td class="py-2 px-4 border-b text-sm text-neutral-700">{{ $vehicle->plate_num }}</td>
                                    <td class="py-2 px-4 border-b text-sm text-neutral-700">{{ $vehicle->capacity }}</td>
                                    <td class="py-2 px-4 border-b text-sm text-neutral-700">{{ $vehicle->status }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
                @else
                @endif
            </div>

            <!-- MONTHLY DUES -->
            <div class="mt-6">

                <div class="mb-4 flex justify-between">
                    <h3 class="text-lg font-bold py-4">Monthly Due</h3>
                    <button id="show-current" class="px-4 my-2 bg-blue-500 text-white rounded-xl hidden" onclick="showCurrent()">Current Month</button>
                    <button id="show-all" class="px-4 my-2 bg-neutral-500 text-white rounded-xl" onclick="showAll()">See All</button>
                </div>

                <!-- MONTHLY DUES table-->
                <div class="overflow-x-auto bg-white shadow rounded-2xl">
                    <table id="current-month-table" class="min-w-full bg-white border border-neutral-200">
                        <thead class="bg-neutral-100">
                            <tr>
                                <th class="py-2 px-4 border-b text-left text-sm font-medium text-neutral-600">Last Payment</th>
                                <th class="py-2 px-4 border-b text-left text-sm font-medium text-neutral-600">Amount</th>
                                <th class="py-2 px-4 border-b text-left text-sm font-medium text-neutral-600">Current Due</th>
                                <th class="py-2 px-4 border-b text-left text-sm font-medium text-neutral-600">Status</th>
                                <th class="py-2 px-4 border-b text-left text-sm font-medium text-neutral-600">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($currentMonthDues as $due)
                            <tr>
                                <td class="py-2 px-4 border-b text-sm text-neutral-700">{{ \Carbon\Carbon::parse($due['last_payment'])->format('F d, Y') }}</td>
                                <td class="py-2 px-4 border-b text-sm text-neutral-700">₱{{ number_format($due['amount'], 2) }}</td>
                                <td class="py-2 px-4 border-b text-sm text-neutral-700">{{ $due['month'] }}</td>
                                <td class="py-2 px-4 border-b text-sm text-neutral-700">{{ $due['status'] }}</td>
                                <td class="py-2 px-4 border-b text-sm text-neutral-700">
                                    @if($due['status'] == "paid")
                                    <p>No Action Required</p>
                                    @else
                                    <form method="POST" action="{{ url('admin/monthlydues/monthlydues/' . $due['id']) }}" class="paidForm">
                                        @csrf
                                        <input type="hidden" name="payment_id" value="{{ $due['id'] }}">
                                        <button type="button" class="bg-blue-500 text-white py-1 px-3 rounded hover:bg-blue-600 triggerPaid">Paid</button>
                                    </form>
                                    @endif
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <!-- ALL DUES table-->
                <div class="overflow-x-auto bg-white shadow rounded-2xl">
                    <table id="all-dues-table" class="min-w-full bg-white border border-neutral-200 hidden">
                        <thead class="bg-neutral-100">
                            <tr>
                                <th class="py-2 px-4 border-b text-left text-sm font-medium text-neutral-600">Last Payment</th>
                                <th class="py-2 px-4 border-b text-left text-sm font-medium text-neutral-600">Amount</th>
                                <th class="py-2 px-4 border-b text-left text-sm font-medium text-neutral-600">Current Due</th>
                                <th class="py-2 px-4 border-b text-left text-sm font-medium text-neutral-600">Status</th>
                                <th class="py-2 px-4 border-b text-left text-sm font-medium text-neutral-600">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($allDues as $due)
                            <tr>
                                <td class="py-2 px-4 border-b text-sm text-neutral-700">{{ \Carbon\Carbon::parse($due['last_payment'])->format('F d, Y') }}</td>
                                <td class="py-2 px-4 border-b text-sm text-neutral-700">₱{{ number_format($due['amount'], 2) }}</td>
                                <td class="py-2 px-4 border-b text-sm text-neutral-700">{{ $due['month'] }}</td>
                                <td class="py-2 px-4 border-b text-sm text-neutral-700">{{ $due['status'] }}</td>
                                <td class="py-2 px-4 border-b text-sm text-neutral-700">
                                    @if($due['status'] == "paid")
                                    <p>No Action Required</p>
                                    @else
                                    <form method="POST" action="{{ url('admin/monthlydues/monthlydues/' . $due['id']) }}" class="paidForm">
                                        @csrf
                                        <input type="hidden" name="payment_id" value="{{ $due['id'] }}">
                                        <button type="button" class="bg-blue-500 text-white py-1 px-3 rounded hover:bg-blue-600 triggerPaid">Paid</button>
                                    </form>
                                    @endif
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>


            </div>
        </div>

    </div>

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
        document.addEventListener('click', function(e) {
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