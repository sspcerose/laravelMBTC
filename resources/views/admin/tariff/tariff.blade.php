@extends('layout.layout')

@include('layouts.adminNav')

<script src="https://code.jquery.com/jquery-3.5.1.js"></script>
<link href="https://cdn.datatables.net/v/dt/dt-2.1.8/b-3.1.2/r-3.0.3/datatables.min.css" rel="stylesheet">
<script src="https://cdn.datatables.net/v/dt/dt-2.1.8/b-3.1.2/r-3.0.3/datatables.min.js"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">

<body class="font-inter">
    <div class="lg:pl-20 lg:pr-10">

    <div class="pt-24 lg:pt-28 p-4 flex flex-col md:flex-row justify-between items-center">
            <h1 class="text-black p-4 pl-4 text-center md:text-left font-extrabold text-3xl">Tariffs</h1>
            <div class="flex justify-between px-5">
                <a href="{{ url('admin/tariff/addtariff') }}" class=" pt-4 mr-2">
                <div class="relative group">
                    <button class="bg-green-600 hover:bg-green-400 text-white flex items-center py-3 px-4 rounded-xl">
                        <i class="fas fa-plus mr-2"></i>
                        Add
                        <!-- Tooltip -->
                        <div class="absolute bottom-full left-1/2 transform -translate-x-1/2 mb-2 hidden group-hover:block bg-white text-black text-xs rounded-lg px-3 py-1 shadow-lg whitespace-nowrap">
                            Add New Tariff
                        </div>
                    </button>
                </div>
                </a>
                <a href="{{ url('admin/tariff/archivetariff') }}" class="pt-4">
                <div class="relative group">
                    <button class="bg-orange-400 hover:bg-orange-300 text-white flex items-center py-4 px-4 rounded-xl">
                        <i class="fas fa-archive"></i> <!-- Notification icon -->
                    </button>
                    <!-- Tooltip -->
                    <div class="absolute bottom-full left-1/2 transform -translate-x-1/2 mb-2 hidden group-hover:block bg-white text-black text-xs rounded-lg px-3 py-1 shadow-lg whitespace-nowrap">
                            Archived Tariffs
                    </div>
                </div>
                </a>
            </div>
        </div>

        <!-- <div class="mb-4">
            <a href="{{ url('admin/tariff/addtariff') }}" class="bg-green-500 text-white py-2 px-4 rounded hover:bg-green-600">Add Tariff</a>
            <a href="{{ url('admin/tariff/archivetariff') }}" class="bg-red-500 text-white py-2 px-4 rounded hover:bg-red-600">Archive</a>
        </div> -->
        <div class="bg-neutral-300 mx-4 rounded-3xl p-2 items-center mb-4">
        <div class="overflow-x-auto bg-neutral-100 px-2 md:px-4 lg:py-2 rounded-2xl" id="largeTable">
        @if($viewtariffs->isEmpty())
            <p class="text-center">NO TARIFF YET</p>
        @else
            <table class="min-w-full" id="myTable">
                <thead>
                <tr class="text-center text-sm text-neutral-950 uppercase tracking-wider">
                        <th class="py-3 px-4">ID</th>
                        <th class="py-3 px-4">DESTINATION</th>
                        <th class="py-3 px-4">RATE</th>
                        <th class="py-3 px-4">SUCCEEDING RATE</th>
                        <th class="py-3 px-4">ACTION</th>
                    </tr>
                </thead>
                <tbody class="text-sm text-gray-600" id="tableBody">
                    @if($viewtariffs->isEmpty())
                    <tr>
                        <td colspan="4" class="text-center py-4">No tariffs found</td>
                    </tr>
                    @else

                    @foreach($viewtariffs as $viewtariff)
                    <tr>
                        <td class="py-3 px-4">{{ $viewtariff->id}}</td>
                        <td class="py-3 px-4">{{ $viewtariff->destination }}</td>
                        <td class="py-3 px-4">₱ {{ $viewtariff->rate }}</td>
                        <td class="py-3 px-4">₱ {{ $viewtariff->succeeding }}</td>
                        <td class="py-3 px-4" id="archiveTd">
                                <a href="{{ url('admin/tariff/updatetariff/' . $viewtariff->id) }}" class="bg-slate-700 hover:bg-slate-500 text-white py-2 px-4 rounded-xl inline-block mr-2 editLink">
                                    <div class="relative group">
                                    <i class="fas fa-edit"></i>
                                    <!-- Tooltip -->
                                    <div class="absolute bottom-full left-1/2 transform -translate-x-1/2 mb-2 hidden group-hover:block bg-white text-black text-xs rounded-lg px-3 py-1 shadow-lg whitespace-nowrap">
                                        Update a Tariff
                                    </div>
                                </div>
                                </a>
                                <form action="{{ url('admin/tariff/' . $viewtariff->id . '/archive') }}" method="POST" class="inline-block archiveForm">
                                    @csrf
                                    <div class="relative group">
                                    <input type="hidden" name="action" value="archive">
                                    <button type="button" class="font-bold text-orange-500 hover:text-orange-400 triggerArchive"> <i class="fas fa-archive"></i>  Archive</button>
                                    <!-- Tooltip -->
                                        <div class="absolute bottom-full left-1/2 transform -translate-x-1/2 mb-2 hidden group-hover:block bg-white text-black text-xs rounded-lg px-3 py-1 shadow-lg whitespace-nowrap">
                                            Archive a Member
                                        </div>
                                </div>
                                </form>

                                
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

<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.7.1/jszip.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/vfs-fonts/2.0.3/vfs_fonts.js"></script>
<script src="https://cdn.datatables.net/buttons/2.2.3/js/buttons.html5.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.2.3/js/buttons.print.min.js"></script>

    <script>
        $(document).ready(function () {
            $('#myTable').DataTable({
                responsive: true,
                order: [[0, 'desc']],
                columnDefs: [
                    { targets: 0, visible: false } 
                ],
                layout: {
            topStart: {
                buttons: [
                    {
                        extend: 'collection',
                        text: 'Export As',
                        buttons: [
                            {
                                extend: 'copy',
                                exportOptions: {
                                    columns: [1, 2, 3]
                                }
                            },
                            {
                                extend: 'excel',
                                exportOptions: {
                                    columns: [1, 2, 3]
                                }
                            },
                            {
                                extend: 'csv',
                                exportOptions: {
                                    columns: [1, 2, 3]
                                }
                            },
                            {
                                extend: 'pdf',
                                exportOptions: {
                                    ccolumns: [1, 2, 3]
                                }
                            },
                            {
                                extend: 'print',
                                exportOptions: {
                                    columns: [1, 2, 3]
                                }
                            }
                        ]
                    }
                ]
            }
        }
    });
});

        document.addEventListener('click', function (e) {
    // Trigger archive confirmation
        if (e.target.classList.contains('triggerArchive')) {
            let archiveForm = e.target.closest('.archiveForm');
            let archiveAlert = archiveForm.nextElementSibling;
            
            archiveAlert.classList.remove('hidden'); 
            e.target.style.display = 'none'; 

            let archiveTd = archiveForm.closest('td');
            let editLink = archiveTd.querySelector('.editLink');
            if (editLink) {
                editLink.style.display = 'none'; 
            }
        }

        // Close the confirmation (cancel button)
        if (e.target.classList.contains('cancelButton')) {
            let archiveAlert = e.target.closest('.archiveAlert');
            archiveAlert.classList.add('hidden'); 

            let archiveTd = archiveAlert.closest('td');
            let archiveForm = archiveAlert.previousElementSibling;

            let archiveButton = archiveForm.querySelector('.triggerArchive');
            if (archiveButton) {
                archiveButton.style.display = ''; 
            }

            let editLink = archiveTd.querySelector('.editLink');
            if (editLink) {
                editLink.style.display = ''; 
            }
        }

    // Confirm archiving and display success message
        if (e.target.classList.contains('yesButton')) {
            let archiveAlert = e.target.closest('.archiveAlert');
            let archiveForm = archiveAlert.previousElementSibling;

            if (archiveForm) {
                e.preventDefault(); 
                
                if (!archiveForm.querySelector('.successMessageAlert')) {
                    let successMessage = document.createElement('div');
                    successMessage.setAttribute('role', 'alert');
                    successMessage.className = 'successMessageAlert mt-3 relative flex w-full p-3 text-sm text-white bg-blue-500 rounded-md';
                    successMessage.innerHTML = `<svg class="w-6 h-6 text-white-800 dark:text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                                                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 11h2v5m-2 0h4m-2.592-8.5h.01M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z"/>
                                                </svg>
                                                Successfully Archived the Tariff!`;

                    let archiveTd = archiveForm.closest('td');
                    archiveTd.appendChild(successMessage); 
                    archiveAlert.classList.add('hidden'); 

                    setTimeout(function () {
                        successMessage.remove();
                        archiveForm.submit(); 
                    }, 1000);
                }
            }
        }
    });
    </script>  
<script>
     document.addEventListener('click', function (e) {
    // Accept action
    if (e.target.classList.contains('triggerArchive')) {
        Swal.fire({
            title: 'Are you sure?',
            text: "You want to archive this tariff.",
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#28a745',
            cancelButtonColor: '#6c757d',
            confirmButtonText: 'Yes, Archive it!'
        }).then((result) => {
            if (result.isConfirmed) {
                e.target.closest('.archiveForm').submit();
                Swal.fire({
                    title: "Archived!",
                    text: "The tariff has been archived.",
                    icon: "success"
                });
            }
        });
    }
});
</script> 
</body>

</html>
