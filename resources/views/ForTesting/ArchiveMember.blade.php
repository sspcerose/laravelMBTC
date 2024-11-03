@extends('layout.layout')

@include('ForTesting.layout.adminNav')

<script src="https://code.jquery.com/jquery-3.5.1.js"></script>
<link href="https://cdn.datatables.net/v/dt/dt-2.1.8/b-3.1.2/r-3.0.3/datatables.min.css" rel="stylesheet">
<script src="https://cdn.datatables.net/v/dt/dt-2.1.8/b-3.1.2/r-3.0.3/datatables.min.js"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">

<body class="font-inter">
    <div class="lg:pl-20 lg:pr-10">
        <div class="pt-24 lg:pt-28 lg:pr-6 flex justify-between items-center">
            <h1 class="text-black p-4 pl-4 text-center md:text-left font-extrabold text-3xl">Archive Members</h1>
            <div class="flex">
                <!-- <form action="" class="px-4 lg:px-0 pt-4"> -->
                    <button class="bg-red-600 hover:bg-red-400 text-white flex items-center py-3 px-4 rounded-xl" onclick="window.history.back();">
                        <!-- <i class="fas fa-save mr-3"></i> -->
                        Back
                    </button>
                <!-- </form> -->
            </div>
        </div>

        <div class="bg-neutral-300 mx-4 rounded-3xl p-2 items-center mb-4">
            <div class="monthly-dues-data"
                data-dues='[
                {"id": "member1", "name": "Juan Pedro", "phone": "0917-123-4567", "dateOfRegistration": "January 15, 2023", "status": "Active"},
                {"id": "member2", "name": "Maria Clara", "phone": "0917-234-5678", "dateOfRegistration": "February 20, 2023", "status": "Inactive"},
                {"id": "member3", "name": "Jose Maria", "phone": "0917-345-6789", "dateOfRegistration": "March 10, 2023", "status": "Active"},
                {"id": "member4", "name": "Ana Teresa", "phone": "0917-456-7890", "dateOfRegistration": "April 5, 2023", "status": "Inactive"}
            ]'>
            </div>

            <div class="overflow-x-auto bg-neutral-100 px-2 md:px-4 lg:py-2 rounded-2xl" id="largeTable">
                <table class="min-w-full" id="membersTable">
                    <thead>
                        <tr class="text-left text-sm text-neutral-950 uppercase tracking-wider">
                            <th class="py-3 px-4">Name</th>
                            <th class="py-3 px-4">Phone Number</th>
                            <th class="py-3 px-4">Date of Registration</th>
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
        const duesData = JSON.parse(document.querySelector('.monthly-dues-data').dataset.dues);

        function populateMembers(data) {
            const tableBody = document.getElementById('tableBody');
            data.forEach(member => {
                const row = document.createElement('tr');
                row.innerHTML = `
                    <td class="py-3 px-4">${member.name}</td>
                    <td class="py-3 px-4">${member.phone}</td>
                    <td class="py-3 px-4">${member.dateOfRegistration}</td>
                    <td class="py-3 px-4">${member.status}</td>
                    <td class="py-3 px-4">
                        <form action="" method="POST">
                            @csrf
                            <input type="hidden" name="member_id" value="${member.id}">
                            <button type="submit" class="text-orange-500 hover:text-orange-400">
                                <i class="fas fa-archive"></i> Archive
                            </button>
                        </form>
                    </td>
                `;
                tableBody.appendChild(row);
            });

            $('#membersTable').DataTable({
                responsive: true
            });
        }

        populateMembers(duesData);
    </script>
</body>