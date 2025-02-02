@extends('layout.layout')

@include('layouts.adminNav')

<body class="font-inter">
    <div class="lg:pl-28 lg:pr-10 px-2">
        <div class="pt-20 flex justify-between items-center">
            <h1 class="text-black p-4 pl-4 text-center md:text-left text-sm md:text-xl"><span class="font-extrabold">Booking</span>/ {{ $viewSpecific->user->name }} {{ $viewSpecific->user->last_name }} </h1>

            <div class="px-4 lg:px-0 pt-4  pb-4">
                <div class="flex">
                    <button class="bg-red-600 hover:bg-red-400 text-white flex items-center py-3 px-4 rounded-xl" onclick="window.location.href='{{ route('admin.booking.booking') }}';">
                        Back
                    </button>
                </div>
            </div>
        </div>
        <div class="bg-neutral-300 p-2 rounded-3xl md:p-3 items-center mb-4">

            <div class=" bg-neutral-100 px-2 md:px-4 lg:py-2 rounded-2xl" id="largeTable">

                <div class="bg-neutral-100 rounded-2xl flex flex-col md:flex-row gap-6 pt-6 p-4 xl:pt-16 xl:p-10">
           
            <div class="w-full md:w-1/2 flex-1 ">
            <div class="bg-neutral-500 p-8 rounded-2xl">
                            <img src="{{ asset('img/' . $viewSpecific->receipt) }}" alt="Proof of Payment"
                                class="rounded-2xl h-96 object-cover cursor-pointer mx-auto"
                                onclick="openModal('{{ asset('img/' . $viewSpecific->receipt) }}')">
                            <!-- Modal -->
                            <div id="imageModal"
                                class="fixed inset-0 z-50 hidden bg-black bg-opacity-75 items-center justify-center">
                                <div class="relative">
                                    <img id="modalImage" src="" alt="Expanded Proof of Payment" class="rounded-lg p-10">
                                    <button class="absolute top-4 right-4 text-white text-xl bg-gray-800 bg-opacity-50 rounded-full px-2 py-1"
                                        onclick="closeModal()">✕</button>
                                </div>
                            </div>
                        </div>

                        <p class="font-bold text-center pt-2">Proof of Payment</p>

                    </div>
                
                     <!-- Right Section: Booking Details -->
                    <div class="w-full md:w-2/3 space-y-2">
                    <div class="text-left grid grid-cols-1 lg:grid-cols-2 gap-2 w-full flex-1">
                        <!-- Customer Name -->
                        <div class="flex flex-col">
                            <label class="font-bold text-neutral-700 w-full">Customer Name:</label>
                            <p class="bg-neutral-50 rounded-lg p-2 w-full">{{ $viewSpecific->user->name }} {{ $viewSpecific->user->last_name }}</p>
                        </div>

                        <!-- Pick-up Time -->
                        <div class="flex flex-col">
                            <label class="font-bold text-neutral-700 w-full">Pick-up Time:</label>
                            <p class="bg-neutral-50 rounded-lg p-2 w-full">{{ $viewSpecific->time }}</p>
                        </div>

                        <!-- Start Date -->
                        <div class="flex flex-col">
                            <label class="font-bold text-neutral-700 w-full">Start Date:</label>
                            <p class="bg-neutral-50 rounded-lg p-2 w-full">{{ \Carbon\Carbon::parse($viewSpecific->start_date)->format('F d, Y') }}</p>
                        </div>

                        <!-- End Date -->
                        <div class="flex flex-col">
                            <label class="font-bold text-neutral-700 w-full">End Date:</label>
                            <p class="bg-neutral-50 rounded-lg p-2 w-full">{{ \Carbon\Carbon::parse($viewSpecific->end_date)->format('F d, Y') }}</p>
                        </div>

                        <!-- Pick-up Location -->
                        <div class="flex flex-col">
                            <label class="font-bold text-neutral-700 w-full">Pick-up Location:</label>
                            <p class="bg-neutral-50 rounded-lg p-2 w-full">{{ $viewSpecific->location }}</p>
                        </div>

                        <!-- Destination -->
                        <div class="flex flex-col">
                            <label class="font-bold text-neutral-700 w-full">Destination:</label>
                            <p class="bg-neutral-50 rounded-lg p-2 w-full">{{ $viewSpecific->destination }}</p>
                        </div>

                        <!-- Total Fare -->
                        <div class="flex flex-col">
                            <label class="font-bold text-neutral-700 w-full">Total Fare:</label>
                            <p class="bg-neutral-50 rounded-lg p-2 w-full">₱{{ $viewSpecific->price }}.00</p>
                        </div>
                        
                        <!-- Remaining -->
                        <div class="flex flex-col">
                            <label class="font-bold text-neutral-700 w-full">Remaining Balance:</label>
                            <p class="bg-neutral-50 rounded-lg p-2 w-full">₱{{ $viewSpecific->remaining }}.00</p>
                        </div>
                    
                    <!-- Status -->
                    <div class="flex flex-col gap-2 lg:col-span-2">
                    <label class="font-bold text-neutral-700 w-full">Status:</label>
                    <p class="bg-neutral-50 rounded-lg p-2 w-full">
                               @if($viewSpecific->status == "accepted" && (\Carbon\Carbon::today()->lt($viewSpecific->start_date)))
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
                                @endif
                            </p>
                    <hr class="border-t-4 border-neutral-700 mt-4 lg:col-span-2">

                    <!-- Driver Assignment -->
                    <div class="flex flex-col gap-2 w-full lg:col-span-2">
                        
                        
                        @foreach ($viewBookings as $viewBooking)
                        
                         @if($viewBooking->status == "accepted")
                         <div class="flex flex-col items-center gap-2">
                                <label for="driver" class="text-neutral-700 font-bold self-center w-full md:text-left">Driver:</label>
                                @if($viewBooking->schedule->isNotEmpty())
                                    @php
                                        $latestSchedule = $viewBooking->schedule->first();
                                    @endphp
                                    
                                    @if($latestSchedule->driver_status == 'cancelled' || $latestSchedule->driver_status == 'conflict')
                                        @if($drivers->isEmpty())
                                        <form action="{{ url('admin/schedule/optionschedule') }}" method="POST" class="findDriverForm">
                                            @csrf
                                            <input type="hidden" name="booking_id" value="{{ $viewBooking->id }}">
                                            <button type="button" class="bg-neutral-50 rounded-lg p-2 w-full text-center triggerFindDriver">Find A Driver</button>
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
                                                <button type="button" class="bg-neutral-50 rounded-lg p-2 w-full text-center triggerAssign">Assign Driver</button>
                                            </form>
                                        @endif

                                    @elseif(!empty($latestSchedule->driver))
                                   <div class="flex flex-col md:flex-row items-center gap-4 w-full">
                                        @if($latestSchedule->cust_status == 'inactive')
                                        <p class="bg-neutral-50 rounded-lg p-2 w-full md:w-3/4 bg-gray-100 self-center">
                                            {{ $latestSchedule->driver->member->name }} {{ $latestSchedule->driver->member->last_name }}
                                        </p>
                                        @elseif($latestSchedule->driver_status == 'optionscheduled')
                                        <p class="text-blue-500 font-bold self-center">Finding a Driver...</p>
                                        @else
                                        <!-- <p class="border rounded p-1 mb-1"> -->
                                        <p class="bg-neutral-50 rounded-lg p-2 w-full">
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
                                            <button type="button" class="bg-green-500 text-white py-1 px-2 rounded-lg w-full text-center triggerFindDriver">Find A Driver</button>
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
                                                <button type="button" class="bg-neutral-50 rounded-lg p-2 w-full text-center triggerAssign">Assign Driver</button>
                                            </form>
                                    @endif
                                @endif
                                @endif
                                @endforeach
                                  
                    </div>
                    <!-- @if($viewBooking->status == "accepted")
                    <p><span class="font-bold text-neutral-700 w-full">Driver Status:</span>
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
                            @endif</p> -->
                    
                    

                    <!-- Driver Status -->
                    <div class="w-full lg:col-span-2">
                <div class="flex flex-col md:flex-row gap-2 lg:col-span-2 w-full">
                    @if($viewBooking->status == "active")
                    <form action="{{ route('booking.accept', $viewBooking->id) }}" method="POST" class="w-full acceptForm">
                        @csrf
                        <button type="button" class="bg-green-500 text-white py-2 px-6 rounded-lg w-full text-center triggerConfirm">
                            Accept
                        </button>
                    </form>
                    <form action="{{ route('booking.reject', $viewBooking->id) }}" method="POST" class="cancelForm w-full">
                        @csrf
                        <button type="button" class="bg-red-500 text-white py-2 px-6 rounded-lg w-full text-center triggerCancel">
                            Reject
                        </button>
                    </form>
                    @endif
                </div>
            </div>


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

