@extends('layout.layout')

@include('ForTesting.layout.memberNav')

<script src="https://code.jquery.com/jquery-3.5.1.js"></script>
<link href="https://cdn.datatables.net/v/dt/dt-2.1.8/b-3.1.2/r-3.0.3/datatables.min.css" rel="stylesheet">
<script src="https://cdn.datatables.net/v/dt/dt-2.1.8/b-3.1.2/r-3.0.3/datatables.min.js"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">

<body class="font-inter">
    <div class="lg:pl-20 lg:pr-10">
        <div class="pt-24 lg:pt-28 lg:pr-6 flex justify-between items-center">
            <h1 class="text-black p-4 pl-4 text-center md:text-left font-extrabold text-3xl">Monthly Dues</h1>
        </div>

        <div class="bg-neutral-300 mx-4 rounded-3xl p-2 items-center mb-4">
            <div class="monthly-dues-data" data-dues='[{"id": "dues1", "name": "Juan Pedro", "amount": "₱500", "paymentDate": "October 1, 2024", "status": "unpaid"},{"id": "dues2", "name": "Maria Clara", "amount": "₱600", "paymentDate": "October 10, 2024", "status": "paid"},{"id": "dues3", "name": "Jose Maria", "amount": "₱550", "paymentDate": "October 20, 2024", "status": "unpaid"},{"id": "dues4", "name": "Ana Teresa", "amount": "₱700", "paymentDate": "November 1, 2024", "status": "paid"}]'></div>

            <div class="overflow-x-auto bg-neutral-100 px-2 md:px-4 lg:py-2 rounded-2xl" id="largeTable">
                <table class="min-w-full" id="duesTable">
                    <thead>
                        <tr class="text-left text-sm text-neutral-950 uppercase tracking-wider">
                            <th class="py-3 px-4">Date</th>
                            <th class="py-3 px-4">Amount</th>
                            <th class="py-3 px-4">Payment Date</th>
                            <th class="py-3 px-4">Status</th>
                            <th class="py-3 px-4">Confirmation</th>
                        </tr>
                    </thead>
                    <tbody class="text-sm text-gray-600" id="tableBody">
                    </tbody>
                </table>
            </div>

        </div>
    </div>

    <script>
        const duesData = JSON.parse(document.querySelector('.monthly-dues-data').dataset.dues);

        function populateDues(data) {
            const tableBody = document.getElementById('tableBody');
            data.forEach(dues => {
                const row = document.createElement('tr');
                row.innerHTML = `
                    <td class="py-3 px-4">${dues.paymentDate}</td>
                    <td class="py-3 px-4">${dues.amount}</td>
                    <td class="py-3 px-4">${dues.paymentDate}</td>
                    <td class="py-3 px-4">${dues.status}</td>
                    <td class="py-3 px-4" id="confirmation-${dues.id}">
                        ${dues.status === 'unpaid' ? `
                            <form action="" method="POST" onsubmit="event.preventDefault(); confirmPayment('${dues.id}', this)">
                                <input type="hidden" name="status" value="confirmed">
                                <button type="submit" class="bg-green-500 text-white py-1 px-3 rounded hover:bg-green-600">Confirm</button>
                            </form>` :
                            'Confirmed'}
                    </td>
                `;
                tableBody.appendChild(row);
            });

            $('#duesTable').DataTable({
                responsive: true
            });
        }

        function confirmPayment(id, form) {
            const confirmationCell = document.getElementById(`confirmation-${id}`);
            confirmationCell.innerHTML = 'Confirmed';
        }

        populateDues(duesData);
    </script>
</body>