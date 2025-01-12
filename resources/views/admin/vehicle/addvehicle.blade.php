@extends('layout.layout')
@include('layouts.adminNav')

<script src="https://code.jquery.com/jquery-3.5.1.js"></script>
<link href="https://cdn.datatables.net/v/dt/dt-2.1.8/b-3.1.2/r-3.0.3/datatables.min.css" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">



<body class="font-inter">
    <div class="lg:pl-20 lg:pr-10 flex flex-col items-center">
        <div class="pt-24 lg:pt-28 lg:pr-6 items-center">
            <h1 class="text-black p-8 pl-4 text-center font-extrabold text-3xl">Add Vehicle</h1>
        </div>

        <div class="items-center mx-auto p-3">
            <!-- Success Message Container -->
          

            @If($activeMembers->isEmpty())
                <span>NO MEMBER</span>
            @else
            <form id="vehicleForm" action="{{ url('admin/vehicle/addvehicle') }}" method="POST" class="max-w-md addVehicleForm">
                @csrf
                <div class="flex flex-col justify-center">
                    <select name="id" id="id" class="mb-4 bg-neutral-100 rounded-md px-3 py-2 w-full" required>
                        @foreach($activeMembers as $activeMember)
                            <option value="{{ $activeMember->id }}">{{ $activeMember->name }} {{ $activeMember->last_name }}</option>
                        @endforeach
                    </select>

                    <input type="text" class="mb-4 bg-neutral-100 rounded-md px-3 py-2 w-full" id="type" name="type" required placeholder="Vehicle Type" value="{{old('type')}}">
                    <x-input-error :messages="$errors->get('plate_num')" class="" /> 
                    <input type="text" class="mb-4 bg-neutral-100 rounded-md px-3 py-2 w-full" id="plate_num" name="plate_num" required placeholder="Plate Number" value="{{old('plate_num')}}">
                    <x-input-error :messages="$errors->get('capacity')" class="" /> 
                    <input type="number" class="mb-4 bg-neutral-100 rounded-md px-3 py-2 w-full" id="capacity" name="capacity" required placeholder="Seat Capacity" value="{{old('capacity')}}">
                </div>

                <div class="flex justify-between pb-40 lg:pb-0 space-x-2">
                    <button class="w-full px-20 py-2 bg-red-600 text-gray-50 font-semibold rounded-lg hover:bg-red-400 focus:outline-none focus:ring-2 focus:ring-red-400" type="button" onclick="window.history.back();">
                        Cancel
                    </button>
                    <button id="submitButton" class="w-full px-20 py-2 bg-green-400 text-gray-50 font-semibold rounded-lg hover:bg-green-300 focus:outline-none focus:ring-2 focus:green-400 triggerAddVehicle" type="button">
                        <input type="hidden" id="status" value="active" name="status">
                        Add
                    </button>
                </div>
            </form>
            @endif
        </div>
    </div>

    <script>
         document.addEventListener('click', function (e) {
    // Handle "Assign Driver" button click
    if (e.target.classList.contains('triggerAddVehicle')) {
        const form = e.target.closest('.addVehicleForm'); // Get the form to submit
        Swal.fire({
            title: 'Are you sure?',
            text: "You want to add this vehicle.",
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#28a745',
            cancelButtonColor: '#6c757d',
            confirmButtonText: 'Yes, Add it!'
        }).then((result) => {
            if (result.isConfirmed) {
                e.target.closest('.addVehicleForm').submit();
                Swal.fire({
                    title: "Added!",
                    text: "The vehicle has been added.",
                    icon: "success"
                });
            }
        });
    }
});
    </script>
</body>
