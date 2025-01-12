@extends('layout.layout')

@include('layouts.adminNav')

<!-- <script src="https://code.jquery.com/jquery-3.5.1.js"></script>
<link href="https://cdn.datatables.net/v/dt/dt-2.1.8/b-3.1.2/r-3.0.3/datatables.min.css" rel="stylesheet">
<script src="https://cdn.datatables.net/v/dt/dt-2.1.8/b-3.1.2/r-3.0.3/datatables.min.js"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css"> -->

<body class="font-inter">
    <div class="lg:pl-20 lg:pr-10">
        <div class="pt-24 lg:pt-28 lg:pr-6 flex justify-between items-center">
            <h1 class="text-black p-4 pl-4 text-center md:text-left font-extrabold text-3xl">Archived Members</h1>
            <div class="flex">
                    <button class="bg-red-600 hover:bg-red-400 text-white flex items-center py-3 px-4 rounded-xl" onclick="window.location.href='{{ route('admin.member.member') }}';">
                        Back
                    </button>
            </div>
        </div>

        <div class="bg-neutral-300 mx-4 rounded-3xl p-2 items-center mb-4">
        <div class="overflow-x-auto bg-neutral-100 px-2 md:px-4 lg:py-2 rounded-2xl" id="largeTable">
        @if($viewmembers->isEmpty())
            <p class="text-center">NO ARCHIVES YET</p>
        @else
            <table class="min-w-full" id="myTable">
                <thead>
                    <tr class="text-left text-sm text-neutral-950 uppercase tracking-wider">
                        <th class="py-3 px-4">Member Name</th>
                        <!-- <th class="py-3 px-4">Last Name</th> -->
                        <th class="py-3 px-4">TIN</th>
                        <th class="py-3 px-4">Contact Number</th>
                        <th class="py-3 px-4">Email</th>
                        <th class="py-3 px-4">Date of Registration</th>
                        <th class="py-3 px-4">Member Type</th>
                        <th class="py-3 px-4">Action</th>
                    </tr>
                </thead>
                <tbody class="text-sm text-gray-600" id="tableBody">
                    @if($viewmembers->isEmpty())
                    <tr>
                        <td colspan="8" class="text-center py-4">No members found</td>
                    </tr>
                    @else
                    @foreach($viewmembers as $viewmember)
                    <tr>
                        <td class="py-3 px-4">{{ $viewmember->name }} {{ $viewmember->last_name }}</td>
                        <!-- <td class="py-3 px-4"></td> -->
                        <td class="py-3 px-4">{{ $viewmember->tin }}</td>
                        <td class="py-3 px-4">{{ $viewmember->mobile_num }}</td>
                        <td class="py-3 px-4">{{ $viewmember->email }}</td>
                        <td class="py-3 px-4">{{\Carbon\Carbon::parse($viewmember->date_joined)->format('F d, Y')}}</td>
                        <td class="py-3 px-4">{{ $viewmember->type }}</td>
                        <td class="py-3 px-4" id="unarchiveTd">
                            <form action="{{url('admin/member/archivemember/' . $viewmember->id)}}" method="POST" class="unarchiveForm" style="display:inline-block;">
                                @csrf
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

<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.7.1/jszip.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/vfs-fonts/2.0.3/vfs_fonts.js"></script>
<script src="https://cdn.datatables.net/buttons/2.2.3/js/buttons.html5.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.2.3/js/buttons.print.min.js"></script>
    <script>
         
    $(document).ready(function () {
    $('#myTable').DataTable({
        responsive: true,
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

<script>
     document.addEventListener('click', function (e) {
    // Accept action
    if (e.target.classList.contains('triggerUnarchive')) {
        Swal.fire({
            title: 'Are you sure?',
            text: "You want to unarchive this member.",
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
                    text: "The member has been unarchived.",
                    icon: "success"
                });
            }
        });
    }
});
</script>        
</body>

</html>
