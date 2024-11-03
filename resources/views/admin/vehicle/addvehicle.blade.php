@extends('layout.layout')
@include('layouts.adminNav')

<script src="https://code.jquery.com/jquery-3.5.1.js"></script>
<link href="https://cdn.datatables.net/v/dt/dt-2.1.8/b-3.1.2/r-3.0.3/datatables.min.css" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">

@if (session('success'))
    <meta http-equiv="refresh" content="1;url={{ route('admin.vehicle.vehicle') }}">
@endif

<body class="font-inter">
    <div class="lg:pl-20 lg:pr-10 flex flex-col items-center">
        <div class="pt-24 lg:pt-28 lg:pr-6 items-center">
            <h1 class="text-black p-8 pl-4 text-center font-extrabold text-3xl">Add Vehicle</h1>
        </div>

        <div class="items-center mx-auto p-3">
            <!-- Success Message Container -->
            @if (session('success'))
                <div id="successMessage" class="successMessageAlert mt-3 mb-3 flex w-full mx-auto p-3 text-sm text-white bg-green-500 rounded-md">
                    <svg class="w-6 h-6 text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 11h2v5m-2 0h4m-2.592-8.5h.01M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z"/>
                    </svg>
                    <span class="ml-2">{{ session('success') }}</span>
                </div>
            @endif

            <form id="vehicleForm" action="{{ url('admin/vehicle/addvehicle') }}" method="POST" class="max-w-md">
                @csrf
                <div class="flex flex-col justify-center">
                    <select name="id" id="id" class="mb-4 bg-neutral-100 rounded-md px-3 py-2 w-full" required>
                        @foreach($activeMembers as $activeMember)
                            <option value="{{ $activeMember->id }}">{{ $activeMember->name }} {{ $activeMember->last_name }}</option>
                        @endforeach
                    </select>

                    <input type="text" class="mb-4 bg-neutral-100 rounded-md px-3 py-2 w-full" id="type" name="type" required placeholder="Vehicle Type">
                    <input type="text" class="mb-4 bg-neutral-100 rounded-md px-3 py-2 w-full" id="plate_num" name="plate_num" required placeholder="Plate Number">
                    <input type="number" class="mb-4 bg-neutral-100 rounded-md px-3 py-2 w-full" id="capacity" name="capacity" required placeholder="Seat Capacity">
                </div>

                <div class="flex justify-between pb-40 lg:pb-0 space-x-2">
                    <button class="w-full px-20 py-2 bg-red-600 text-gray-50 font-semibold rounded-lg hover:bg-red-400 focus:outline-none focus:ring-2 focus:ring-red-400" type="button" onclick="window.history.back();">
                        Cancel
                    </button>
                    <button id="submitButton" class="w-full px-20 py-2 bg-green-400 text-gray-50 font-semibold rounded-lg hover:bg-green-300 focus:outline-none focus:ring-2 focus:green-400" type="submit">
                        <input type="hidden" id="status" value="active" name="status">
                        Add
                    </button>
                </div>
            </form>
        </div>
    </div>
</body>
