@extends('layout.layout')

@include('ForTesting.layout.adminNav')

<script src="https://code.jquery.com/jquery-3.5.1.js"></script>
<link href="https://cdn.datatables.net/v/dt/dt-2.1.8/b-3.1.2/r-3.0.3/datatables.min.css" rel="stylesheet">
<script src="https://cdn.datatables.net/v/dt/dt-2.1.8/b-3.1.2/r-3.0.3/datatables.min.js"></script>

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">

<body class="font-inter">
    <div class="lg:pl-20 lg:pr-10">
        <div class="pt-24 lg:pt-28 flex flex-col md:flex-row justify-between items-center">
            <h1 class="text-black p-4 pl-4 text-center md:text-left font-extrabold text-3xl">Members List</h1>
            <div class="flex justify-between px-5">
                <form action="{{ route('ForTesting.AddMember')}}" class=" pt-4 mr-2">
                    <button class="bg-green-600 hover:bg-green-400 text-white flex items-center py-3 px-4 rounded-xl">
                        <i class="fas fa-plus mr-2"></i>
                        Add
                    </button>
                </form>
                <form action="" class=" pt-4 mr-2">
                    <button class="bg-slate-700 hover:bg-slate-500 text-white flex items-center py-4 px-4 rounded-xl">
                        <i class="fas fa-edit"></i>
                    </button>
                </form>
                <form action="{{route('ForTesting.ArchiveMember') }}" class="pt-4">
                    <button class="bg-orange-400 hover:bg-orange-300 text-white flex items-center py-4 px-4 rounded-xl">
                        <i class="fas fa-archive"></i> <!-- Notification icon -->
                    </button>
                </form>
            </div>
        </div>

        <div class="bg-neutral-300 mx-4 rounded-3xl p-2 items-center mb-4">
            <div class="monthly-dues-data"
                data-dues='[
                {"id": "member1", "name": "Juan Pedro", "phone": "0917-123-4567", "dateOfRegistration": "January 15, 2023", "type": "owner", "tin": "123-456-789"},
                {"id": "member2", "name": "Maria Clara", "phone": "0917-234-5678", "dateOfRegistration": "February 20, 2023", "type": "driver", "tin": "987-654-321"},
                {"id": "member3", "name": "Jose Maria", "phone": "0917-345-6789", "dateOfRegistration": "March 10, 2023", "type": "owner/driver", "tin": "456-123-789"},
                {"id": "member4", "name": "Ana Teresa", "phone": "0917-456-7890", "dateOfRegistration": "April 5, 2023", "type": "owner", "tin": "321-654-987"}
            ]'>
            </div>

            <div class="overflow-x-auto bg-neutral-100 px-2 md:px-4 lg:py-2 rounded-2xl" id="largeTable">
                <table class="min-w-full" id="membersTable">
                    <thead>
                        <tr class="text-left text-sm text-neutral-950 uppercase tracking-wider">
                            <th class="py-3 px-4">Name</th>
                            <th class="py-3 px-4">Phone Number</th>
                            <th class="py-3 px-4">Date of Registration</th>
                            <th class="py-3 px-4">Type</th>
                            <th class="py-3 px-4">TIN</th>
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
                    <td class="py-3 px-4">${member.type}</td>
                    <td class="py-3 px-4">${member.tin}</td>
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