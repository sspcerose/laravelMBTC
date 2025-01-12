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
                        <th class="py-3 px-4">ID</th>
                        <th class="py-3 px-4">Owner Name</th>
                        <th class="py-3 px-4">Vehicle Type</th>
                        <th class="py-3 px-4">Plate Number</th>
                        <th class="py-3 px-4">Seat Capacity</th>
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
                                <td class="py-3 px-4">{{ $viewVehicle->id }}</td>
                                <td class="py-3 px-4">{{ $viewVehicle->member->name }} {{ $viewVehicle->member->last_name }}</td>
                                <td class="py-3 px-4">{{ $viewVehicle->type }}</td>
                                <td class="py-3 px-4">{{ $viewVehicle->plate_num }}</td>
                                <td class="py-3 px-4">{{ $viewVehicle->capacity }}</td>
                                <td class="py-3 px-4" id="unarchiveTd">
                                    <form action="{{ url('admin/vehicle/archivevehicle/' . $viewVehicle->id) }}" method="POST" class="inline-block unarchiveForm">
                                        @csrf
                                        <input type="hidden" name="action" value="archive">
                                        <input type="hidden" name="archive" value="1">
                                        <button type="button" class="font-bold text-orange-500 hover:text-orange-400 triggerUnarchive"> <i class="fas fa-archive"></i> Unarchive</button>
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

<!-- JS for Exporting files -->
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
                                    columns: [1, 2, 3, 4] // Specify the column indices to export (starting at 0)
                                }
                            },
                            {
                                extend: 'excel',
                                exportOptions: {
                                    columns: [1, 2, 3, 4]
                                }
                            },
                            {
                                extend: 'csv',
                                exportOptions: {
                                    columns: [1, 2, 3, 4]
                                }
                            },
                            {
                                extend: 'pdf',
                                exportOptions: {
                                    ccolumns: [1, 2, 3, 4]
                                }
                            },
                            {
                                extend: 'print',
                                exportOptions: {
                                    columns: [1, 2, 3, 4]
                                }
                            }
                        ]
                    }
                ]
            }
        }
    });
});
        </script>
<script>
     document.addEventListener('click', function (e) {
    // Accept action
    if (e.target.classList.contains('triggerUnarchive')) {
        Swal.fire({
            title: 'Are you sure?',
            text: "You want to unarchive this vehicle.",
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#28a745',
            cancelButtonColor: '#6c757d',
            confirmButtonText: 'Yes, Unarchive it!'
        }).then((result) => {
            if (result.isConfirmed) {
                e.target.closest('.unarchiveForm').submit();
                Swal.fire({
                    title: "Unarchived!",
                    text: "The vehicle has been unarchived.",
                    icon: "success"
                });
            }
        });
    }
});
</script>        
</body>