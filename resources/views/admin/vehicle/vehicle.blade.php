@extends('layout.layout')

@include('layouts.adminNav')

<script src="https://code.jquery.com/jquery-3.5.1.js"></script>
<link href="https://cdn.datatables.net/v/dt/dt-2.1.8/b-3.1.2/r-3.0.3/datatables.min.css" rel="stylesheet">
<script src="https://cdn.datatables.net/v/dt/dt-2.1.8/b-3.1.2/r-3.0.3/datatables.min.js"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">

<body class="font-inter">
    <div class="lg:pl-20 lg:pr-10">
    <div class="pt-24 lg:pt-28 p-4 flex flex-col md:flex-row justify-between items-center">
            <h1 class="text-black p-4 pl-4 text-center md:text-left font-extrabold text-3xl">Vehicles</h1>
            <div class="flex justify-between px-5">
                <a href="{{ url('admin/vehicle/addvehicle') }}" class=" pt-4 mr-2">
                    <button class="bg-green-600 hover:bg-green-400 text-white flex items-center py-3 px-4 rounded-xl">
                        <i class="fas fa-plus mr-2"></i>
                        Add
                    </button>
                </a>
                <a href="{{ url('admin/vehicle/archivevehicle') }}" class="pt-4">
                    <div class="relative group">
                        <button class="bg-orange-400 hover:bg-orange-300 text-white flex items-center py-4 px-4 rounded-xl">
                            <i class="fas fa-archive"></i>
                        </button>

                        <!-- Tooltip -->
                        <div class="absolute bottom-full left-1/2 transform -translate-x-1/2 mb-2 hidden group-hover:block bg-white text-black text-xs rounded-lg px-3 py-1 shadow-lg">
                            Archive Vehicle
                        </div>
                    </div>
                </a>
            </div>
        </div>

    <div class="bg-neutral-300 mx-4 rounded-3xl p-2 items-center mb-4">
    <div class="overflow-x-auto bg-neutral-100 px-2 md:px-4 lg:py-2 rounded-2xl" id="largeTable">
        @if($viewVehicles->isEmpty())
            <p class="text-center">NO VEHICLE YET</p>
        @else
        <table class="min-w-full" id="myTable">
            <thead>
            <tr class="text-left text-sm text-neutral-950 uppercase tracking-wider">
                    <th class="py-3 px-4">ID</th>
                    <th class="py-3 px-4">OWNER NAME</th>
                    <th class="py-3 px-4">VEHICLE TYPE</th>
                    <th class="py-3 px-4">PLATE NUMBER</th>
                    <th class="py-3 px-4">SEAT CAPACITY</th>
                    <th class="py-3 px-4">ACTION</th>
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
                            <td class="py-3 px-4">
                                {{ $viewVehicle->member->name }} {{ $viewVehicle->member->last_name }}
                            </td>
                            <td class="py-3 px-4">{{ $viewVehicle->type }}</td>
                            <td class="py-3 px-4">{{ $viewVehicle->plate_num }}</td>
                            <td class="py-3 px-4">{{ $viewVehicle->capacity }}</td>
                            <td class="py-3 px-4" id="archiveTd">
                                <a href="{{ url('admin/vehicle/updatevehicle/' . $viewVehicle->id) }}" class="bg-slate-700 hover:bg-slate-500 text-white py-2 px-4 rounded-xl inline-block mr-2 editLink"><i class="fas fa-edit"></i></a>
                                <form action="{{ url('admin/vehicle/' . $viewVehicle->id . '/archive') }}" method="POST" class="inline-block archiveForm">
                                    @csrf
                                    <input type="hidden" name="action" value="archive">
                                    <input type="hidden" name="archive" value="1">
                                    <button type="button" class="font-bold text-orange-500 hover:text-orange-400 triggerArchive"> <i class="fas fa-archive"></i> Archive</button>
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

