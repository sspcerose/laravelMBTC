@extends('layout.layout')

@include('layouts.adminNav')

<script src="https://code.jquery.com/jquery-3.5.1.js"></script>
<link href="https://cdn.datatables.net/v/dt/dt-2.1.8/b-3.1.2/r-3.0.3/datatables.min.css" rel="stylesheet">
<script src="https://cdn.datatables.net/v/dt/dt-2.1.8/b-3.1.2/r-3.0.3/datatables.min.js"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">

<body class="font-inter">
    <div class="flex items-center justify-center min-h-screen">
        <div class="pt-24 md:pt-0 max-w-md w-full mx-auto">
            <h1 class="text-black p-8 text-center font-extrabold text-3xl">Update Vehicle</h1>
            <div class="items-center">

                <!-- Success Message Container -->
                <div id="successMessage" class="hidden successMessageAlert mt-3 relative flex w-5/6 mx-auto p-3 text-sm text-white bg-green-500 rounded-md">
                    <svg class="w-6 h-6 text-white-800 dark:text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 11h2v5m-2 0h4m-2.592-8.5h.01M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z"/>
                    </svg>
                    <span class="ml-2">Vehicle successfully updated!</span>
                </div>

                <!-- Form -->
                <form id="vehicleForm" method="POST" action="{{ url('admin/vehicle/updatevehicle/' . $viewVehicles->id) }}" class="mx-10 updateVehicleForm">
                    @csrf
                    <div class="flex flex-col justify-center">
                    <label for="owner">Owner:</label>
                        <select name="member_id" id="member_id" class="mb-6 bg-neutral-100 rounded-md px-3 py-2 w-full" required>
                            @foreach($activeMembers as $activeMember)
                                <option value="{{ $activeMember->id }}" @if($viewVehicles->member_id == $activeMember->id) selected @endif>
                                    {{ $activeMember->name }} {{ $activeMember->last_name }}
                                </option>
                            @endforeach
                        </select>

                        <label for="vehicletype">Vehicle Type:</label>
                        <input type="text" id="type" name="type" class="mb-6 bg-neutral-100 rounded-md px-3 py-2 w-full" placeholder="Vehicle Type" required value="{{ $viewVehicles->type }}">
                        <label for="plate_num">Plate Number:</label>
                        <x-input-error :messages="$errors->get('plate_num')" class="" />
                        <input type="text" id="plate_num" name="plate_num" class="mb-6 bg-neutral-100 rounded-md px-3 py-2 w-full" placeholder="Plate Number" required value="{{ $viewVehicles->plate_num }}">
                        <label for="capacity">Seat Capacity:</label>
                        <x-input-error :messages="$errors->get('capacity')" class="" />
                        <input type="number" id="capacity" name="capacity" class="mb-6 bg-neutral-100 rounded-md px-3 py-2 w-full" placeholder="Capacity" required value="{{ $viewVehicles->capacity }}">
                    </div>

                    <div class="flex flex-col md:flex-row space-y-4 md:space-y-0 md:space-x-4 pb-40 lg:pb-0">
                        <button class="w-full md:w-1/2 py-2 bg-red-600 text-gray-50 font-semibold rounded-lg hover:bg-red-500 focus:outline-none focus:ring-2 focus:ring-red-400" type="button" onclick="window.history.back();">Cancel</button>

                        <input type="hidden" id="status" value="active" name="status">

                        <button class="w-full md:w-1/2 py-2 bg-yellow-400 text-gray-50 font-semibold rounded-lg hover:bg-yellow-300 focus:outline-none focus:ring-2 focus:ring-yellow-400 triggerUpdateVehicle" type="button">Update</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('click', function (e) {
    // Handle "Assign Driver" button click
    if (e.target.classList.contains('triggerUpdateVehicle')) {
        const form = e.target.closest('.updateVehicleForm'); // Get the form to submit
        Swal.fire({
            title: 'Are you sure?',
            text: "You want to update this vehicle.",
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#28a745',
            cancelButtonColor: '#6c757d',
            confirmButtonText: 'Yes, Update it!'
        }).then((result) => {
            if (result.isConfirmed) {
                e.target.closest('.updateVehicleForm').submit();
                Swal.fire({
                    title: "Updated!",
                    text: "The vehicle has been updated.",
                    icon: "success"
                });
            }
        });
    }
});  
    </script>
</body>

