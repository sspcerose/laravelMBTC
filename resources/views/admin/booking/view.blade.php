@extends('layout.layout')

@include('layouts.adminNav')

<body class="font-inter">
    <div class="lg:pl-20 lg:pr-10">
        <div class="pt-24 lg:pt-28 flex justify-between items-center">
            <h1 class="text-black p-4 pl-4 text-center md:text-left text-3xl"><span class="font-extrabold">Bookings</span>/{{ $viewSpecific->user->name }} {{ $viewSpecific->user->last_name }} </h1>
            
            <div class="px-4 lg:px-0 pt-4 lg:pr-5">
            <div class="flex">
                    <button class="bg-red-600 hover:bg-red-400 text-white flex items-center py-3 px-4 rounded-xl" onclick="window.location.href='{{ route('admin.booking.booking') }}';">
                        Back
                    </button>
            </div>

            
              
        </div>
        </div>
        <div class="bg-neutral-300 mx-4 md:mx-auto max-w-4xl rounded-3xl p-2 md:p-3 items-center mb-4">
            <div class="overflow-x-auto bg-neutral-100 px-2 md:px-4 lg:py-2 rounded-2xl" id="largeTable">
            <div class="bg-neutral-100 p-4 rounded-2xl flex flex-col md:flex-row gap-6">
           
            <div class="w-full md:w-1/3">
                    <img src="{{ asset('img/' . $viewSpecific->receipt) }}" alt="Proof of Payment" class="rounded-lg w-full h-96">
                    <p class="font-bold text-center mt-2">Proof of Payment</p>
                </div>
                
                            <!-- Booking Details -->
                    <div class="w-full md:w-2/3 space-y-2">
                    <p><span class="font-bold">Customer Name: </span>{{ $viewSpecific->user->name }} {{ $viewSpecific->user->last_name }}</p>
                    <p><span class="font-bold">Start Date: </span>{{ \Carbon\Carbon::parse( $viewSpecific->start_date)->format('F d, Y') }}</p>
                    <p><span class="font-bold">End Date: </span>{{ \Carbon\Carbon::parse( $viewSpecific->start_date)->format('F d, Y') }}</p>
                    <p><span class="font-bold">Pick-up Time: </span>{{ $viewSpecific->time }}</p>
                    <p><span class="font-bold">Pick-up Location: </span>{{ $viewSpecific->location }}</p>
                    <p><span class="font-bold">Destination: </span>{{ $viewSpecific->destination }}</p>
                    <p><span class="font-bold">Total Fare: </span>{{ $viewSpecific->price }}</p>
                   
                    <p><span class="font-bold">Status: </span> @if($viewSpecific->status == "accepted" && (\Carbon\Carbon::today()->lt($viewSpecific->start_date)))
                            <span class="font-bold text-yellow-500">Upcoming</span>
                            @elseif($viewSpecific->status == "accepted" && \Carbon\Carbon::today()->between($viewSpecific->start_date, $viewSpecific->end_date))
                            <span class="font-bold text-blue-500">On going</span>
                            @elseif($viewSpecific->status == "accepted" && \Carbon\Carbon::today()->gt($viewSpecific->end_date))
                            <span class="font-bold text-green-500">Completed</span>
                            @elseif($viewSpecific->status == "rejected")
                            <span class="font-bold text-red-500">Rejected</span>
                            @elseif($viewSpecific->status == "active")
                            <span class="font-bold text-yellow-500">Pending...</span>
                            @else
                            <span class="font-bold text-red-600">Customer Cancelled</span>
                            @endif</p>
                    <hr class="border-t-4 border-neutral-700 mt-4">

                    <!-- Driver Assignment -->
                    <div class="flex items-center gap-2">
                        
                        @foreach ($viewBookings as $viewBooking)
                        
                         @if($viewBooking->status == "accepted")
                         <label for="driver" class="font-bold">Driver:</label>
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
                                                <button type="button" class="bg-green-500 text-white py-1 px-2 rounded triggerAssign">Assign Driver</button>
                                            </form>
                                    @endif
                                @endif
                                @endif
                                @endforeach
                               
                            
                    </div>
                    @if($viewBooking->status == "accepted")
                    <p><span class="font-bold mb-2">Driver Status:</span>
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
                            @endif</p>
                    
                    

                    <!-- Driver Status -->
                    <div class="mt-4">

                        <div class="flex gap-4">
                        
                           
                            @if($viewBooking->status == "active")
                            <p>
                                <!-- <span class="font-bold mb-2">Accept/Decline</span> -->
                            <form action="{{ route('booking.accept', $viewBooking->id) }}" method="POST" class="acceptForm" style="display:inline;">
                                @csrf
                                <button type="button" class="bg-green-500 text-white py-1 px-3 rounded hover:bg-green-600 triggerConfirm">Accept</button>
                            </form>

                            <form action="{{ route('booking.reject', $viewBooking->id) }}" method="POST" class="cancelForm" style="display:inline;">
                                @csrf
                                <button type="button" class="bg-red-500 text-white py-1 px-3 rounded hover:bg-red-600 triggerCancel">Reject</button>
                            </form>
                            @endif

                            </p>
                        </div>
                    </div>
                </div>
            </div>

</div>

</div>


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

<script>
document.addEventListener('click', function (e) {
    //Driver
    if (e.target.classList.contains('triggerAssign')) {
        const form = e.target.closest('.assignForm'); 
        Swal.fire({
            title: 'Are you sure?',
            text: "You want to assign this driver.",
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#28a745',
            cancelButtonColor: '#d33',
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
        const form = e.target.closest('.findDriverForm'); 
        Swal.fire({
            title: 'Are you sure?',
            text: "You want to find a driver for this booking.",
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#28a745',
            cancelButtonColor: '#d33',
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

