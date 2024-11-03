@extends('layout.layout')

@include('ForTesting.layout.adminNav')

<script src="https://code.jquery.com/jquery-3.5.1.js"></script>
<link href="https://cdn.datatables.net/v/dt/dt-2.1.8/b-3.1.2/r-3.0.3/datatables.min.css" rel="stylesheet">
<script src="https://cdn.datatables.net/v/dt/dt-2.1.8/b-3.1.2/r-3.0.3/datatables.min.js"></script>

<body class="font-inter">
    <div class="lg:pl-20 lg:pr-10">
        <h1 class="text-black pt-24 lg:pt-28 p-4 pl-4 text-center md:text-left font-extrabold text-3xl">Driver Schedule</h1>

        <div class="bg-neutral-300 mx-4 rounded-3xl p-2 items-center mb-4">
            <div class="booking-data" data-bookings='[
                {"id": "booking1", "customerName": "Juan Pedro", "destination": "Cebu", "dateRange": "October 1 - October 7, 2024", "driver": "Driver 1"},
                {"id": "booking2", "customerName": "Maria Clara", "destination": "Davao", "dateRange": "October 10 - October 15, 2024", "driver": "Driver 2"},
                {"id": "booking3", "customerName": "Jose Maria", "destination": "Manila", "dateRange": "October 20 - October 25, 2024", "driver": "Driver 1"},
                {"id": "booking4", "customerName": "Alfredo Santos", "destination": "Iloilo", "dateRange": "November 1 - November 5, 2024", "driver": "Driver 3"},
                {"id": "booking5", "customerName": "Emma Watson", "destination": "Boracay", "dateRange": "November 10 - November 15, 2024", "driver": "Driver 2"},
                {"id": "booking6", "customerName": "Luis Miguel", "destination": "Bohol", "dateRange": "November 20 - November 25, 2024", "driver": "Driver 1"},
                {"id": "booking7", "customerName": "Rosa Martinez", "destination": "Leyte", "dateRange": "December 1 - December 7, 2024", "driver": "Driver 3"},
                {"id": "booking8", "customerName": "Liam Johnson", "destination": "Cagayan de Oro", "dateRange": "December 10 - December 15, 2024", "driver": "Driver 2"},
                {"id": "booking9", "customerName": "Sophia Lee", "destination": "Zamboanga", "dateRange": "December 20 - December 25, 2024", "driver": "Driver 1"},
                {"id": "booking10", "customerName": "Noah Brown", "destination": "Baguio", "dateRange": "January 1 - January 5, 2025", "driver": "Driver 3"},
                {"id": "booking11", "customerName": "Olivia White", "destination": "Dumaguete", "dateRange": "January 10 - January 15, 2025", "driver": "Driver 1"},
                {"id": "booking12", "customerName": "Lucas Green", "destination": "Naga", "dateRange": "January 20 - January 25, 2025", "driver": "Driver 2"},
                {"id": "booking13", "customerName": "Isabella Davis", "destination": "Legazpi", "dateRange": "February 1 - February 5, 2025", "driver": "Driver 3"},
                {"id": "booking14", "customerName": "Mason Clark", "destination": "Tagaytay", "dateRange": "February 10 - February 15, 2025", "driver": "Driver 1"},
                {"id": "booking15", "customerName": "Mia Lewis", "destination": "Samar", "dateRange": "February 20 - February 25, 2025", "driver": "Driver 2"},
                {"id": "booking16", "customerName": "Ethan Walker", "destination": "Quezon City", "dateRange": "March 1 - March 5, 2025", "driver": "Driver 3"},
                {"id": "booking17", "customerName": "Charlotte Hall", "destination": "Cavite", "dateRange": "March 10 - March 15, 2025", "driver": "Driver 1"},
                {"id": "booking18", "customerName": "James King", "destination": "Rizal", "dateRange": "March 20 - March 25, 2025", "driver": "Driver 2"},
                {"id": "booking19", "customerName": "Amelia Young", "destination": "Laguna", "dateRange": "April 1 - April 5, 2025", "driver": "Driver 3"},
                {"id": "booking20", "customerName": "Oliver Scott", "destination": "Pangasinan", "dateRange": "April 10 - April 15, 2025", "driver": "Driver 1"}
            ]'>
            </div>

            <div class="overflow-x-auto bg-neutral-100 px-2 md:px-4 lg:py-2 rounded-2xl" id="largeTable">
                <table class="min-w-full" id="bookingTable">
                    <thead>
                        <tr class="text-left text-sm text-neutral-950 uppercase tracking-wider">
                            <th class="py-3 px-4">Customer Name</th>
                            <th class="py-3 px-4">Destination</th>
                            <th class="py-3 px-4">Date Range</th>
                            <th class="py-3 px-4">Driver</th>
                            <th class="py-3 px-4">Actions</th>
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
                    <td class="py-3 px-4">${booking.customerName}</td>
                    <td class="py-3 px-4">${booking.destination}</td>
                    <td class="py-3 px-4">${booking.dateRange}</td>
                    <td class="py-3 px-4">
                        <select class="border rounded p-1" onchange="updateConfirmation(this)">
                            <option value="Driver 1">Driver 1</option>
                            <option value="Driver 2">Driver 2</option>
                            <option value="Driver 3">Driver 3</option>
                        </select>
                    </td>
                    <td class="py-3 px-4">
                        <div class="mt-2">
                            <button class="bg-green-500 text-white py-1 px-2 rounded" onclick="confirmBooking(this)">Confirm</button>
                            <button class="bg-red-500 text-white py-1 px-2 rounded" onclick="rejectBooking(this)">Reject</button>
                        </div>
                    </td>
                `;
                tableBody.appendChild(row);
            });

            // Initialize DataTable
            $('#bookingTable').DataTable({
                responsive: true // Enable responsive plugin
            });
        }

        function confirmBooking(button) {
    const cell = button.closest('td');
    cell.setAttribute('data-confirmation', 'Confirmed');

    // Set the cell's HTML content to "Confirmed" and remove the button
    cell.innerHTML = 'Confirmed';
}

function rejectBooking(button) {
    const cell = button.closest('td');
    cell.setAttribute('data-confirmation', 'Rejected');

    // Set the cell's HTML content to "Rejected" and remove the button
    cell.innerHTML = 'Rejected';
}


        populateBookings(bookingData);
    </script>
</body>