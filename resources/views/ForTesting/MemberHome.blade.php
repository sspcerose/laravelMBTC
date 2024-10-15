@extends('layout.layout')

@include('ForTesting.layout.memberNav')

<body class="font-inter">
    <div class="md:pl-20 md:pr-5">
        <h1 class="text-black pt-28 p-4 font-extrabold text-3xl">Upcoming Trips</h1>

        <div class="bg-neutral-100 mx-2 rounded-xl">

            <!-- Search Bar -->
            <div class="flex justify-center px-4 pt-4">
                <form action="your-search-action-url" method="GET" class="flex justify-end w-full">
                    <input type="text" name="search" placeholder="Search..." class="border rounded-lg p-2 w-full md:w-auto">
                    <button type="submit" class="hidden md:block bg-slate-900 text-white py-2 px-4 ml-2 rounded-lg hover:bg-blue-600">Search</button>
                </form>
            </div>

            <!-- Trip Data (Unified for Mobile and Table) -->
            <div class="trip-data" data-trips='[
                {"id": "trip1", "name": "John Doe", "destination": "Manila", "date": "Jan 25, 2023 - Jan 27, 2023"},
                {"id": "trip2", "name": "Jane Smith", "destination": "Cebu", "date": "Feb 10, 2023 - Feb 15, 2023"},
                {"id": "trip2", "name": "Jane Smith", "destination": "Cebu", "date": "Feb 10, 2023 - Feb 15, 2023"},
                {"id": "trip2", "name": "Jane Smith", "destination": "Cebu", "date": "Feb 10, 2023 - Feb 15, 2023"},
                {"id": "trip2", "name": "Jane Smith", "destination": "Cebu", "date": "Feb 10, 2023 - Feb 15, 2023"},
                {"id": "trip2", "name": "Jane Smith", "destination": "Cebu", "date": "Feb 10, 2023 - Feb 15, 2023"},
                 {"id": "trip1", "name": "John Doe", "destination": "Manila", "date": "Jan 25, 2023 - Jan 27, 2023"},
                {"id": "trip2", "name": "Jane Smith", "destination": "Cebu", "date": "Feb 10, 2023 - Feb 15, 2023"},
                {"id": "trip2", "name": "Jane Smith", "destination": "Cebu", "date": "Feb 10, 2023 - Feb 15, 2023"},
                {"id": "trip2", "name": "Jane Smith", "destination": "Cebu", "date": "Feb 10, 2023 - Feb 15, 2023"},
                {"id": "trip2", "name": "Jane Smith", "destination": "Cebu", "date": "Feb 10, 2023 - Feb 15, 2023"},
                {"id": "trip2", "name": "Jane Smith", "destination": "Cebu", "date": "Feb 10, 2023 - Feb 15, 2023"},
                {"id": "trip2", "name": "Jane Smith", "destination": "Cebu", "date": "Feb 10, 2023 - Feb 15, 2023"}
            ]'></div>

            <!-- Mobile Cards -->
            <div class="overflow-x-auto lg:hidden p-3">
                <div class="grid grid-cols-1 gap-4" id="mobileCards">
                    <!-- Trip Cards will be populated here -->
                </div>
            </div>

            <!-- Large Screen Table -->
            <div class="hidden lg:block overflow-x-auto bg-neutral-100 mx-4 p-3 rounded-xl" id="largeTable">
                <table class="min-w-full">
                    <thead>
                        <tr class="text-left text-sm text-neutral-950 uppercase tracking-wider">
                            <th class="py-3 px-4">Name</th>
                            <th class="py-3 px-4">Destination</th>
                            <th class="py-3 px-4">Date</th>
                            <th class="py-3 px-4">Action</th>
                        </tr>
                    </thead>
                    <tbody class="text-sm text-gray-600" id="tableBody">

                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div class="flex justify-end p-4">
                <nav aria-label="Pagination">
                    <ul class="inline-flex items-center -space-x-px">
                        <li>
                            <a href="#" class="py-2 px-3 ml-0 leading-tight text-gray-500 bg-white border border-gray-300 rounded-l-lg hover:bg-gray-100 hover:text-gray-700">
                                <span class="sr-only">Previous</span>&laquo;
                            </a>
                        </li>
                        <li>
                            <a href="#" class="py-2 px-3 leading-tight text-gray-500 bg-white border border-gray-300 hover:bg-gray-100 hover:text-gray-700">1</a>
                        </li>
                        <li>
                            <a href="#" class="py-2 px-3 leading-tight text-gray-500 bg-white border border-gray-300 hover:bg-gray-100 hover:text-gray-700">2</a>
                        </li>
                        <li>
                            <a href="#" class="py-2 px-3 leading-tight text-gray-500 bg-white border border-gray-300 hover:bg-gray-100 hover:text-gray-700">3</a>
                        </li>
                        <li>
                            <a href="#" class="py-2 px-3 leading-tight text-gray-500 bg-white border border-gray-300 rounded-r-lg hover:bg-gray-100 hover:text-gray-700">
                                <span class="sr-only">Next</span>&raquo;
                            </a>
                        </li>
                    </ul>
                </nav>
            </div>

        </div>
    </div>

    <script>
        const tripData = JSON.parse(document.querySelector('.trip-data').dataset.trips); // Parse trip data from the div

        function populateTrips() {
            const mobileCards = document.getElementById('mobileCards');
            const tableBody = document.getElementById('tableBody');

            tripData.forEach(trip => {
                // Create Mobile Card
                const card = document.createElement('div');
                card.className = 'bg-neutral-100 px-4 pt-4 border rounded-lg shadow';
                card.id = trip.id; // Unique ID for each trip
                card.innerHTML = `
                    <h2 class="text-lg font-semibold">${trip.name}</h2>
                    <p class="text-sm text-gray-600">Destination: ${trip.destination}</p>
                    <p class="text-sm text-gray-600">Date: ${trip.date}</p>
                    <div class="pt-5 flex space-x-4 items-center">
                        <form action="your-action-url" method="POST" onsubmit="event.preventDefault(); updateStatus('${trip.id}', 'confirmed', this)">
                            <input type="hidden" name="status" value="confirmed">
                            <button type="submit" class="bg-green-500 text-white py-1 px-3 rounded hover:bg-green-600">Confirm</button>
                        </form>
                        <form action="your-action-url" method="POST" onsubmit="event.preventDefault(); updateStatus('${trip.id}', 'declined', this)">
                            <input type="hidden" name="status" value="declined">
                            <button type="submit" class="bg-red-500 text-white py-1 px-3 rounded hover:bg-red-600">Decline</button>
                        </form>
                    </div>
                `;
                mobileCards.appendChild(card); // Add card to mobile section

                // Create Table Row
                const row = document.createElement('tr');
                row.className = 'border-b-2 py-3';
                row.id = trip.id; // Unique ID for each trip
                row.innerHTML = `
                    <td class="py-6 px-4">${trip.name}</td>
                    <td class="py-3 px-4">${trip.destination}</td>
                    <td class="py-3 px-4">${trip.date}</td>
                    <td class="px-4 min-w-[160px]">
                        <div class="flex justify-between w-full">
                            <div class="flex space-x-2">
                                <form action="your-action-url" method="POST" onsubmit="event.preventDefault(); updateStatus('${trip.id}', 'confirmed', this)">
                                    <input type="hidden" name="status" value="confirmed">
                                    <button type="submit" class="bg-green-500 text-white py-1 px-3 rounded hover:bg-green-600">Confirm</button>
                                </form>
                                <form action="your-action-url" method="POST" onsubmit="event.preventDefault(); updateStatus('${trip.id}', 'declined', this)">
                                    <input type="hidden" name="status" value="declined">
                                    <button type="submit" class="bg-red-500 text-white py-1 px-3 rounded hover:bg-red-600">Decline</button>
                                </form>
                            </div>
                        </div>
                    </td>
                `;
                tableBody.appendChild(row); // Add row to table
            });
        }

        populateTrips(); // Call the function to populate trips on load

        let previousStatus = {}; // Object to keep track of previous statuses

        function updateStatus(tripId, status, button) {
            const statusMessage = status === 'confirmed' ? 'Confirmed' : 'Declined';
            const previousStatusMessage = previousStatus[tripId] ? previousStatus[tripId].statusMessage : ''; // Store previous message
            previousStatus[tripId] = { status, statusMessage }; // Save current status

            // Remove buttons
            const buttonContainer = button.parentElement;
            buttonContainer.innerHTML = `
                <span class="text-sm font-bold ${status === 'confirmed' ? 'text-green-600' : 'text-red-600'}">${statusMessage}</span>
                <div class="flex justify-end"> <!-- Added flex container to position Undo button -->
                    <button class="bg-blue-500 text-white py-1 px-3 rounded hover:bg-blue-600 ml-4" onclick="undoStatus('${tripId}', '${previousStatusMessage}')">Undo</button>
                </div>
            `;

            // Update table row status
            const row = document.getElementById(tripId);
            if (row) {
                const rowButtonContainer = row.querySelector('div'); // Find the button container in the row
                rowButtonContainer.innerHTML = `
                    <span class="text-sm font-bold ${status === 'confirmed' ? 'text-green-600' : 'text-red-600'}">${statusMessage}</span>
                    <div class="flex justify-end">
                        <button class="bg-blue-500 text-white py-1 px-3 rounded hover:bg-blue-600 ml-4" onclick="undoStatus('${tripId}', '${previousStatusMessage}')">Undo</button>
                    </div>
                `;
            }
        }

        function undoStatus(tripId, previousStatusMessage) {
            const tripElement = document.getElementById(tripId);
            const previousStatusInfo = previousStatus[tripId]; // Get previous status info
            if (previousStatusInfo) {
                const { status } = previousStatusInfo; // Retrieve previous status

                // Restore buttons in mobile card
                const mobileCard = document.getElementById(tripId);
                const buttonContainerMobile = mobileCard.querySelector('div'); // Find the button container in the mobile card
                buttonContainerMobile.innerHTML = `
                    <div class="flex space-x-4 items-center">
                        <form action="your-action-url" method="POST" onsubmit="event.preventDefault(); updateStatus('${tripId}', 'confirmed', this)">
                            <input type="hidden" name="status" value="confirmed">
                            <button type="submit" class="bg-green-500 text-white py-1 px-3 rounded hover:bg-green-600">Confirm</button>
                        </form>
                        <form action="your-action-url" method="POST" onsubmit="event.preventDefault(); updateStatus('${tripId}', 'declined', this)">
                            <input type="hidden" name="status" value="declined">
                            <button type="submit" class="bg-red-500 text-white py-1 px-3 rounded hover:bg-red-600">Decline</button>
                        </form>
                    </div>
                `;

                // Restore buttons in the table row
                const buttonContainerRow = tripElement.querySelector('div'); // Find the button container in the table row
                buttonContainerRow.innerHTML = `
                    <div class="flex justify-between w-full">
                        <form action="your-action-url" method="POST" onsubmit="event.preventDefault(); updateStatus('${tripId}', 'confirmed', this)">
                            <input type="hidden" name="status" value="confirmed">
                            <button type="submit" class="bg-green-500 text-white py-1 px-3 rounded hover:bg-green-600">Confirm</button>
                        </form>
                        <form action="your-action-url" method="POST" onsubmit="event.preventDefault(); updateStatus('${tripId}', 'declined', this)">
                            <input type="hidden" name="status" value="declined">
                            <button type="submit" class="bg-red-500 text-white py-1 px-3 rounded hover:bg-red-600">Decline</button>
                        </form>
                    </div>
                `;

                delete previousStatus[tripId]; // Remove the status history after undoing
            }
        }
    </script>

</body>