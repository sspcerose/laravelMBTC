@extends('layout.layout')

@include('ForTesting.layout.adminNav')

<script src="https://code.jquery.com/jquery-3.5.1.js"></script>
<link href="https://cdn.datatables.net/v/dt/dt-2.1.8/b-3.1.2/r-3.0.3/datatables.min.css" rel="stylesheet">
<script src="https://cdn.datatables.net/v/dt/dt-2.1.8/b-3.1.2/r-3.0.3/datatables.min.js"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">

<body class="font-inter">
    <div class="lg:pl-20 lg:pr-10">

        <div class="pt-24 lg:pt-28 flex justify-between items-center">
            <h1 class="text-black p-4 pl-4 text-center md:text-left font-extrabold text-3xl">Tariffs</h1>
            <div class="flex lg:px-5">
                <form action="{{ route('ForTesting.AddTariff')}}" class="pt-4 md:mr-2">
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
            <div class="tariff-data" data-tariffs='[
                {"destination": "Ilocos City", "rate": 1000, "succeedingRate": 500},
                {"destination": "Cebu City", "rate": 1200, "succeedingRate": 600},
                {"destination": "Davao City", "rate": 1100, "succeedingRate": 550},
                {"destination": "Iloilo City", "rate": 1050, "succeedingRate": 525},
                {"destination": "Baguio City", "rate": 1500, "succeedingRate": 750},
                {"destination": "Tagaytay City", "rate": 1300, "succeedingRate": 650},
                {"destination": "Boracay", "rate": 2000, "succeedingRate": 1000},
                {"destination": "Palawan", "rate": 1800, "succeedingRate": 900}
            ]'></div>

            <div class="overflow-x-auto bg-neutral-100 px-2 md:px-4 lg:py-2 rounded-2xl" id="largeTable">
                <table class="min-w-full" id="tariffTable">
                    <thead>
                        <tr class="text-left text-sm text-neutral-950 uppercase tracking-wider">
                            <th class="py-3 px-4">Destination</th>
                            <th class="py-3 px-4">Rate</th>
                            <th class="py-3 px-4">Succeeding Rate</th>
                        </tr>
                    </thead>
                    <tbody class="text-sm text-gray-600" id="tableBody">
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <script>
        const tariffData = JSON.parse(document.querySelector('.tariff-data').dataset.tariffs); // Parse tariff data

        function populateTariffs(data) {
            const tableBody = document.getElementById('tableBody');
            data.forEach(tariff => {
                const row = document.createElement('tr');
                row.innerHTML = `
                    <td class="py-3 px-4">${tariff.destination}</td>
                    <td class="py-3 px-4">₱ ${tariff.rate}</td>
                    <td class="py-3 px-4">₱ ${tariff.succeedingRate}</td>
                `;
                tableBody.appendChild(row);
            });

            // Initialize DataTable
            $('#tariffTable').DataTable({
                responsive: true // Enable responsive plugin
            });
        }

        populateTariffs(tariffData); // Initial population
    </script>
</body>