@extends('layout.layout')

@include('layouts.adminNav')

<script src="https://code.jquery.com/jquery-3.5.1.js"></script>
<link href="https://cdn.datatables.net/v/dt/dt-2.1.8/b-3.1.2/r-3.0.3/datatables.min.css" rel="stylesheet">
<script src="https://cdn.datatables.net/v/dt/dt-2.1.8/b-3.1.2/r-3.0.3/datatables.min.js"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">

<body class="font-inter">
    <div class="lg:pl-20 lg:pr-10">
        <div class="pt-24 lg:pt-28 lg:pr-6 flex justify-between items-center">
            <h1 class="text-black p-4 pl-4 text-center md:text-left font-extrabold text-3xl">Archived Vehicles</h1>
            <div class="flex">
                <button class="bg-red-600 hover:bg-red-400 text-white flex items-center py-3 px-4 rounded-xl mr-3" onclick="window.location.href='{{ route('admin.vehicle.vehicle') }}';">
                    Back
                </button>
            </div>
        </div>

        
        <div class="bg-neutral-300 mx-4 rounded-3xl p-2 items-center mb-4">
        <div class="overflow-x-auto bg-neutral-100 px-2 md:px-4 lg:py-2 rounded-2xl" id="largeTable">
        @if($viewVehicles->isEmpty())
            <p class="text-center">NO ARCHIVES YET</p>
        @else
            <table class="min-w-full" id="myTable">
                <thead>
                <tr class="text-left text-sm text-neutral-950 uppercase tracking-wider">
                        <th class="py-3 px-4">Owner</th>
                        <th class="py-3 px-4">Type</th>
                        <th class="py-3 px-4">Plate Number</th>
                        <th class="py-3 px-4">Capacity</th>
                        <th class="py-3 px-4">Action</th>
                    </tr>
                </thead>
                <tbody class="text-sm text-gray-600" id="tableBody">
                    @if($viewVehicles->isEmpty())
                        <tr>
                            <td colspan="5" class="text-center py-4">No vehicles found</td>
                        </tr>
                    @else
                        @foreach($viewVehicles as $viewVehicle)
                            <tr>
                                <td class="py-3 px-4">{{ $viewVehicle->member->name }} {{ $viewVehicle->member->last_name }}</td>
                                <td class="py-3 px-4">{{ $viewVehicle->type }}</td>
                                <td class="py-3 px-4">{{ $viewVehicle->plate_num }}</td>
                                <td class="py-3 px-4">{{ $viewVehicle->capacity }}</td>
                                <td class="py-3 px-4" id="unarchiveTd">
                                    <form action="{{ url('admin/vehicle/archivevehicle/' . $viewVehicle->id) }}" method="POST" class="inline-block unarchiveForm">
                                        @csrf
                                        <input type="hidden" name="action" value="archive">
                                        <input type="hidden" name="archive" value="1">
                                        <button type="button" class="text-orange-500 hover:text-orange-400 triggerUnarchive"> <i class="fas fa-archive"></i> Unarchive</button>
                                    </form>

                                        <!-- Custom confirmation alert (initially hidden) -->
                                        <div class="mt-3 relative flex flex-col p-3 text-sm text-gray-800 bg-blue-100 border border-blue-600 rounded-md hidden unarchiveAlert">
                                            <svg class="w-6 h-6 text-gray-800 dark:text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.529 9.988a2.502 2.502 0 1 1 5 .191A2.441 2.441 0 0 1 12 12.582V14m-.01 3.008H12M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z"/>
                                            </svg>
                                                Are you sure you want to unarchive this vehicle?
                                            <div class="flex justify-end mt-2">
                                                <button class="bg-gray-600 text-white py-1 px-3 mr-2 rounded-lg hover:bg-gray-500 cancelButton">
                                                    Back
                                                </button>
                                                <button class="bg-green-600 text-white py-1 px-3 rounded-lg hover:bg-green-500 yesButton">
                                                    Yes
                                                </button>
                                            </div>
                                        </div>
                                </td>
                            </tr>
                        @endforeach
                    @endif
                </tbody>
            </table>
            @endif
        </div>
    </div>
</div>

<script>
        $(document).ready(function () {
            $('#myTable').DataTable({
                responsive: true
            });
        });

        document.addEventListener('click', function (e) {
    // Trigger cancel confirmation
    if (e.target.classList.contains('triggerUnarchive')) {
        let unarchiveForm = e.target.closest('.unarchiveForm');
        let unarchiveAlert = unarchiveForm.nextElementSibling;
        unarchiveAlert.classList.remove('hidden');
        document.getElementById('unarchiveTd').style.width = '25%'; 
        e.target.style.display = 'none';
    }

    // Close the cancel confirmation
    if (e.target.classList.contains('cancelButton')) {
        let unarchiveAlert = e.target.closest('.unarchiveAlert');
        unarchiveAlert.classList.add('hidden');
        document.getElementById('unarchiveTd').style.width = '';
        let unarchiveForm = unarchiveAlert.previousElementSibling;
        let cancelButton = unarchiveForm.querySelector('.triggerUnarchive');
        if (cancelButton) {
            cancelButton.style.display = '';
        }
    }

    if (e.target.classList.contains('yesButton')) {
        let unarchiveAlert = e.target.closest('.unarchiveAlert');
        let unarchiveForm = unarchiveAlert.previousElementSibling;

        if (unarchiveForm) {
            e.preventDefault(); 
        
            if (!unarchiveForm.querySelector('.successMessageAlert')) {
                let successMessage = document.createElement('div');
                successMessage.setAttribute('role', 'alert');
                successMessage.className = 'successMessageAlert mt-3 relative flex w-full p-3 text-sm text-white bg-blue-500 rounded-md';
                successMessage.innerHTML = `<svg class="w-6 h-6 text-white-800 dark:text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 11h2v5m-2 0h4m-2.592-8.5h.01M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z"/>
                                            </svg>
                                            Successfully Unarchived the Vehicle!`;

                let unarchiveTd = unarchiveForm.closest('td');
                unarchiveTd.appendChild(successMessage); 
                unarchiveAlert.classList.add('hidden');

                setTimeout(function () {
                    successMessage.remove();
                    unarchiveForm.submit(); 
                    // unarchiveAlert.classList.add('hidden');
                }, 1000);
            }
        }
    }
});
        </script>
</body>