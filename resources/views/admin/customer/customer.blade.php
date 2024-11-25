@extends('layout.layout')

@include('layouts.adminNav')

<script src="https://code.jquery.com/jquery-3.5.1.js"></script>
<link href="https://cdn.datatables.net/v/dt/dt-2.1.8/b-3.1.2/r-3.0.3/datatables.min.css" rel="stylesheet">
<script src="https://cdn.datatables.net/v/dt/dt-2.1.8/b-3.1.2/r-3.0.3/datatables.min.js"></script>

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">


<body class="font-inter">
    <div class="lg:pl-20 lg:pr-10">
        <div class="pt-24 lg:pt-28 p-4 flex flex-col md:flex-row justify-between items-center">
            <h1 class="text-black p-6 pl-4 text-center md:text-left font-extrabold text-3xl">Customer List</h1>
            
        </div>

        <div class="bg-neutral-300 mx-4 rounded-3xl p-2 items-center mb-4">
        <div class="overflow-x-auto bg-neutral-100 px-2 md:px-4 lg:py-2 rounded-2xl" id="largeTable">
        @if($users->isEmpty())
            <p class="text-center">NO CUSTOMERS YET</p>
        @else
            <table class="min-w-full" id="myTable">
                <thead>
                    <tr>
                        <th class="py-3 px-4">ID</th>
                        <th class="py-3 px-4">CUSTOMER NAME</th>
                        <th class="py-3 px-4">CONTACT NUMBER</th>
                        <th class="py-3 px-4">NO. OF BOOKINGS MADE</th>
                        <th class="py-3 px-4">LAST BOOKING DATE</th>
                    </tr>
                </thead>
                <tbody class="text-sm text-gray-600" id="tableBody">
                    @if($users->isEmpty())
                    <tr>
                        <td colspan="8" class="text-center py-4">No customer found</td>
                    </tr>
                    @else
                    @foreach($users as $user)
                    <tr>
                        <td class="py-3 px-4">{{ $user->id }}</td>
                        <td class="py-3 px-4">{{ $user->name }} {{ $user->last_name }}</td>
                        <td class="py-3 px-4">{{ $user->mobile_num }}</td>
                        <td class="py-3 px-4">{{ $bookingCounts[$user->id] ?? 0 }}</td>
                        <td class="py-3 px-4">
                        @if(isset($latestBookings[$user->id]))
                           {{ \Carbon\Carbon::parse($latestBookings[$user->id]->start_date)->format('F d, Y')}} - {{\Carbon\Carbon::parse($latestBookings[$user->id]->end_date)->format('F d, Y')}}
                        @else
                           No bookings yet
                        @endif
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
                                    columns: [1, 2, 3, 4, 5, 6]
                                }
                            },
                            {
                                extend: 'excel',
                                exportOptions: {
                                    columns: [1, 2, 3, 4, 5, 6]
                                }
                            },
                            {
                                extend: 'csv',
                                exportOptions: {
                                    columns: [1, 2, 3, 4, 5, 6]
                                }
                            },
                            {
                                extend: 'pdf',
                                exportOptions: {
                                    ccolumns: [1, 2, 3, 4, 5, 6]
                                }
                            },
                            {
                                extend: 'print',
                                exportOptions: {
                                    columns: [1, 2, 3, 4, 5, 6]
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
</body>

</html>
