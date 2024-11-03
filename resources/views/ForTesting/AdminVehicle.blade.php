@extends('layout.layout')

@include('ForTesting.layout.adminNav')

<script src="https://code.jquery.com/jquery-3.5.1.js"></script>
<link href="https://cdn.datatables.net/v/dt/dt-2.1.8/b-3.1.2/r-3.0.3/datatables.min.css" rel="stylesheet">
<script src="https://cdn.datatables.net/v/dt/dt-2.1.8/b-3.1.2/r-3.0.3/datatables.min.js"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">

<body class="font-inter">
    <div class="lg:pl-20 lg:pr-10">
        <div class="pt-24 lg:pt-28 flex justify-between items-center">
            <h1 class="text-black p-4 pl-4 text-center md:text-left font-extrabold text-3xl">Vans</h1>
            <div class="flex lg:px-5">
                <form action="{{ route('ForTesting.AddVehicle')}}" class="pt-4 md:mr-2">
                    <button class="bg-green-600 hover:bg-green-400 text-white flex items-center py-3 px-4 rounded-xl">
                        <i class="fas fa-plus mr-2"></i>
                        Add
                    </button>
                </form>
                <form action="" class="px-4 lg:px-0 pt-4">
                    <button class="bg-slate-700 hover:bg-slate-500 text-white flex items-center py-4 px-4 rounded-xl">
                        <i class="fas fa-edit"></i> <!-- Notification icon -->
                    </button>
                </form>
            </div>
        </div>

        <div class="bg-neutral-300 mx-4 rounded-3xl p-2 items-center mb-4">
            <div class="booking-data" data-bookings='[
                {"id": "van1", "ownerName": "Juan Pedro", "vanType": "Minivan", "vanBrand": "Toyota", "plateNumber": "ABC123", "seatCapacity": 10},
                {"id": "van2", "ownerName": "Maria Clara", "vanType": "SUV", "vanBrand": "Honda", "plateNumber": "DEF456", "seatCapacity": 7},
                {"id": "van3", "ownerName": "Jose Maria", "vanType": "Van", "vanBrand": "Mitsubishi", "plateNumber": "GHI789", "seatCapacity": 12},
                {"id": "van4", "ownerName": "Ana Teresa", "vanType": "Pickup", "vanBrand": "Ford", "plateNumber": "JKL012", "seatCapacity": 5},
                {"id": "van5", "ownerName": "Carlos Alberto", "vanType": "Minibus", "vanBrand": "Mercedes", "plateNumber": "MNO345", "seatCapacity": 20}
            ]'>
            </div>

            <div class="overflow-x-auto bg-neutral-100 px-2 md:px-4 lg:py-2 rounded-2xl" id="largeTable">
                <table class="min-w-full" id="bookingTable">
                    <thead>
                        <tr class="text-left text-sm text-neutral-950 uppercase tracking-wider">
                            <th class="py-3 px-4">Owner Name</th>
                            <th class="py-3 px-4">Vehicle Type</th>
                            <th class="py-3 px-4">Vehicle Brand</th>
                            <th class="py-3 px-4">Plate Number</th>
                            <th class="py-3 px-4">Seat Capacity</th>
                        </tr>
                    </thead>
                    <tbody class="text-sm text-gray-600" id="tableBody">
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <script>
        const bookingData = JSON.parse(document.querySelector('.booking-data').dataset.bookings); // Parse booking data

        function populateBookings(data) {
            const tableBody = document.getElementById('tableBody');
            data.forEach(van => {
                const row = document.createElement('tr');
                row.innerHTML = `
                    <td class="py-3 px-4">${van.ownerName}</td>
                    <td class="py-3 px-4">${van.vanType}</td>
                    <td class="py-3 px-4">${van.vanBrand}</td>
                    <td class="py-3 px-4">${van.plateNumber}</td>
                    <td class="py-3 px-4">${van.seatCapacity}</td>
                `;
                tableBody.appendChild(row);
            });

            // Initialize DataTable
            $('#bookingTable').DataTable({
                responsive: true // Enable responsive plugin
            });
        }

        populateBookings(bookingData); // Initial population
    </script>
</body>