@extends('layout.layout')

@include('ForTesting.layout.userNav')

<script src="https://code.jquery.com/jquery-3.5.1.js"></script>
<link href="https://cdn.datatables.net/v/dt/dt-2.1.8/b-3.1.2/r-3.0.3/datatables.min.css" rel="stylesheet">
<script src="https://cdn.datatables.net/v/dt/dt-2.1.8/b-3.1.2/r-3.0.3/datatables.min.js"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">

<body class="font-inter">
    <div class="max-w-5xl mx-auto">
        <div class="pt-24 lg:pt-28 flex justify-between items-center">
            <h1 class="text-black p-4 pl-5 text-center md:text-left font-extrabold text-3xl lg:text-5xl">Bookings</h1>
        </div>

        <div class="bg-neutral-300 mx-4 rounded-3xl p-2 items-center mb-4">
            <div class="booking-data" data-bookings='[{"id": "booking1", "driver": "Juan Pedro", "destination": "Cebu", "dateRange": "October 1 - October 7, 2024", "status": "pending"},{"id": "booking2", "driver": "Maria Clara", "destination": "Davao", "dateRange": "October 10 - October 15, 2024", "status": "confirmed"},{"id": "booking3", "driver": "Jose Maria", "destination": "Manila", "dateRange": "October 20 - October 25, 2024", "status": "pending"},{"id": "booking4", "driver": "Ana Teresa", "destination": "Baguio", "dateRange": "November 1 - November 5, 2024", "status": "confirmed"},{"id": "booking5", "driver": "Carlos Alberto", "destination": "Iloilo", "dateRange": "November 10 - November 15, 2024", "status": "pending"}]'>
            </div>

            <div class="overflow-x-auto bg-neutral-100 px-2 md:px-4 lg:py-2 rounded-2xl" id="largeTable">
                <table class="min-w-full" id="bookingTable">
                    <thead>
                        <tr class="text-left text-sm text-neutral-950 uppercase tracking-wider">
                            <th class="py-3 px-4">Driver</th>
                            <th class="py-3 px-4">Destination</th>
                            <th class="py-3 px-4">Date Range</th>
                            <th class="py-3 px-4">Status</th>
                            <th class="py-3 px-4">Action</th>
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
            data.forEach(booking => {
                const row = document.createElement('tr');
                row.innerHTML = `
                    <td class="py-3 px-4">${booking.driver}</td>
                    <td class="py-3 px-4">${booking.destination}</td>
                    <td class="py-3 px-4">${booking.dateRange}</td>
                    <td class="py-3 px-4">${booking.status}</td>
                    <td class="py-3 px-4">
                        ${booking.status === 'confirmed' ?
                            `<button class="bg-gray-400 text-white py-1 px-3 rounded-lg cursor-not-allowed" disabled>
                                Cancel
                            </button>` :
                            `<button class="bg-red-600 text-white py-1 px-3 rounded-lg hover:bg-red-500 focus:outline-none">
                                Cancel
                            </button>`
                        }
                    </td>
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