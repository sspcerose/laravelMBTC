@extends('layout.layout')

@include('ForTesting.layout.adminNav')


<script src="https://code.jquery.com/jquery-3.5.1.js"></script>
<link href="https://cdn.datatables.net/v/dt/dt-2.1.8/b-3.1.2/r-3.0.3/datatables.min.css" rel="stylesheet">
<script src="https://cdn.datatables.net/v/dt/dt-2.1.8/b-3.1.2/r-3.0.3/datatables.min.js"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">


<body class="font-inter">
    <div class="lg:pl-20 lg:pr-10">
        <div class="pt-24 lg:pt-28 flex justify-between items-center">
            <h1 class="text-black p-4 pl-4 text-center md:text-left font-extrabold text-3xl">Bookings</h1>
            <form action="" class="px-4 lg:px-0 pt-4 lg:pr-5">
                <button class="bg-green-500 text-white flex items-center py-3 px-4 rounded-xl">
                    <i class="fas fa-bell mr-2"></i> <!-- Notification icon -->
                    Notify
                </button>
            </form>
        </div>



        <div class="bg-neutral-300 mx-4 rounded-3xl p-2 items-center mb-4">
            <div class="booking-data" data-bookings='[{"id": "booking1", "customerName": "Juan Pedro", "destination": "Cebu", "dateRange": "October 1 - October 7, 2024", "receipt": "001", "status": "pending"},{"id": "booking2", "customerName": "Maria Clara", "destination": "Davao", "dateRange": "October 10 - October 15, 2024", "receipt": "002", "status": "confirmed"},{"id": "booking3", "customerName": "Jose Maria", "destination": "Manila", "dateRange": "October 20 - October 25, 2024", "receipt": "003", "status": "pending"},{"id": "booking4", "customerName": "Ana Teresa", "destination": "Baguio", "dateRange": "November 1 - November 5, 2024", "receipt": "004", "status": "confirmed"},{"id": "booking5", "customerName": "Carlos Alberto", "destination": "Iloilo", "dateRange": "November 10 - November 15, 2024", "receipt": "005", "status": "pending"},{"id": "booking6", "customerName": "Maria Luisa", "destination": "Boracay", "dateRange": "November 20 - November 25, 2024", "receipt": "006", "status": "confirmed"},{"id": "booking7", "customerName": "Juanita Reyes", "destination": "Bohol", "dateRange": "December 1 - December 7, 2024", "receipt": "007", "status": "pending"},{"id": "booking8", "customerName": "Jose Luis", "destination": "Palawan", "dateRange": "December 10 - December 15, 2024", "receipt": "008", "status": "confirmed"},{"id": "booking9", "customerName": "Anna Marie", "destination": "Cavite", "dateRange": "December 20 - December 25, 2024", "receipt": "009", "status": "pending"},{"id": "booking10", "customerName": "Marco Antonio", "destination": "Samar", "dateRange": "January 1 - January 5, 2025", "receipt": "010", "status": "confirmed"},{"id": "booking11", "customerName": "Lucia Sofia", "destination": "Leyte", "dateRange": "January 10 - January 15, 2025", "receipt": "011", "status": "pending"},{"id": "booking12", "customerName": "Miguel Angel", "destination": "Batanes", "dateRange": "January 20 - January 25, 2025", "receipt": "012", "status": "confirmed"},{"id": "booking13", "customerName": "Elena Rosa", "destination": "Negros", "dateRange": "February 1 - February 5, 2025", "receipt": "013", "status": "pending"},{"id": "booking14", "customerName": "Pablo Sergio", "destination": "Zambales", "dateRange": "February 10 - February 15, 2025", "receipt": "014", "status": "confirmed"},{"id": "booking15", "customerName": "Alba Teresa", "destination": "Aurora", "dateRange": "February 20 - February 25, 2025", "receipt": "015", "status": "pending"},{"id": "booking16", "customerName": "Nina Laura", "destination": "Laguna", "dateRange": "March 1 - March 5, 2025", "receipt": "016", "status": "confirmed"},{"id": "booking17", "customerName": "Jorge Luis", "destination": "Siquijor", "dateRange": "March 10 - March 15, 2025", "receipt": "017", "status": "pending"},{"id": "booking18", "customerName": "Maria Clara", "destination": "Cagayan de Oro", "dateRange": "March 20 - March 25, 2025", "receipt": "018", "status": "confirmed"},{"id": "booking19", "customerName": "Ricardo Jose", "destination": "Pangasinan", "dateRange": "April 1 - April 5, 2025", "receipt": "019", "status": "pending"},{"id": "booking20", "customerName": "Elisa Maria", "destination": "Batangas", "dateRange": "April 10 - April 15, 2025", "receipt": "020", "status": "confirmed"}]'>

            </div>

            <div class="overflow-x-auto bg-neutral-100 px-2 md:px-4 lg:py-2 rounded-2xl" id="largeTable">
                <table class="min-w-full" id="bookingTable">
                    <thead>
                        <tr class="text-left text-sm text-neutral-950 uppercase tracking-wider">
                            <th class="py-3 px-4">Customer Name</th>
                            <th class="py-3 px-4">Destination</th>
                            <th class="py-3 px-4">Date Range</th>
                            <th class="py-3 px-4">Status</th>
                            <th class="py-3 px-4">Receipt</th>
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
                    <td class="py-3 px-4">${booking.status}</td>
                    <td class="py-3 px-4">${booking.receipt}</td>
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