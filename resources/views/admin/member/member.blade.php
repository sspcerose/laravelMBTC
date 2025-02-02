@extends('layout.layout')

@include('layouts.adminNav')

<script src="https://code.jquery.com/jquery-3.5.1.js"></script>
<link href="https://cdn.datatables.net/v/dt/dt-2.1.8/b-3.1.2/r-3.0.3/datatables.min.css" rel="stylesheet">
<script src="https://cdn.datatables.net/v/dt/dt-2.1.8/b-3.1.2/r-3.0.3/datatables.min.js"></script>

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">


<body class="font-inter">
    <div class="lg:pl-20 lg:pr-10">
        <div class="pt-24 lg:pt-28 p-4 flex flex-col md:flex-row justify-between items-center">
            <h1 class="text-black p-6 pl-4 text-center md:text-left font-extrabold text-3xl">Member List</h1>
            

            <div class="flex justify-between px-5">
                <a href="{{ route('admin.member.auth.register') }}" class=" pt-4 mr-2">
                    <button class="bg-green-600 hover:bg-green-400 text-white flex items-center py-3 px-4 rounded-xl">
                        <i class="fas fa-plus mr-2"></i>
                        Add
                    </button>
                </a>
                <a href="{{ url('/admin/member/archivemember') }}" class="pt-4">
                <div class="relative group">
                    <button class="bg-orange-400 hover:bg-orange-300 text-white flex items-center py-4 px-4 rounded-xl">
                        <i class="fas fa-archive"></i> 
                    </button>
                    <!-- Tooltip -->
                        <div class="absolute bottom-full left-1/2 transform -translate-x-1/2 mb-2 hidden group-hover:block bg-white text-black text-xs rounded-lg px-3 py-1 shadow-lg whitespace-nowrap">
                            Archived Members
                        </div>
                    </div>
                </a>
                <!-- <a href="" class="pt-4 mr-2 ml-2">
                <button class="bg-blue-600 hover:bg-blue-400 text-white flex items-center py-3 px-4 rounded-xl">
                <i class="fa-solid fa-file-export mr-2"></i>
                        Report
                    </button>
                </a> -->

                <p class="pt-4 mr-2 ml-2">
                <button class="bg-blue-600 hover:bg-blue-400 text-white flex items-center py-3 px-4 rounded-xl" onclick="openFilterModal('export')">
                    <i class="fa-solid fa-file-export mr-2"></i>
                    Report
                </button> </p>

                <p class="pt-4 mr-2">
                <button class="bg-teal-600 hover:bg-teal-400 text-white flex items-center py-3 px-4 rounded-xl" onclick="openFilterModal('print')">
                    <i class="fa-solid fa-print mr-2"></i>Print
                </button>
                </p>
            </div>
        </div>

        <div class="bg-neutral-300 mx-4 rounded-3xl p-2 items-center mb-4">
        <div class="overflow-x-auto bg-neutral-100 px-2 md:px-4 lg:py-2 rounded-2xl" id="largeTable">
        @if($viewmembers->isEmpty())
            <p class="text-center">NO MEMBERS YET</p>
        @else
            <table class="min-w-full" id="myTable">
                <thead>
                    <tr>
                        <th class="py-3 px-4">ID</th>
                        <th class="py-3 px-4">MEMBER NAME</th>
                        <th class="py-3 px-4">TIN</th>
                        <th class="py-3 px-4">CONTACT NUMBER</th>
                        <th class="py-3 px-4">EMAIL</th>
                        <th class="py-3 px-4">DATE OF REGISTRATION</th>
                        <th class="py-3 px-4">MEMBER TYPE</th>
                        <th class="py-3 px-4">ACTION</th>
                        <th>VIEW DETAILS</th>
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
                        <td class="py-3 px-4">{{ $viewmember->id }}</td>
                        <td class="py-3 px-4">{{ $viewmember->name }} {{ $viewmember->last_name }}</td>
                        <td class="py-3 px-4">{{ $viewmember->tin }}</td>
                        <td class="py-3 px-4">{{ $viewmember->mobile_num }}</td>
                        <td class="py-3 px-4">{{ $viewmember->email }}</td>
                        <!-- @if($viewmember->created_at == $viewmember->updated_at)
                        <td class="py-3 px-4">{{ $viewmember->email }}<span class="font-bold text-red-700"> (not yet verified)</span></td>
                        @else
                        <td class="py-3 px-4">{{ $viewmember->email }}<span class="font-bold text-green-700"> (verified)</td>
                        @endif -->
                        <td class="py-3 px-4">{{\Carbon\Carbon::parse($viewmember->date_joined)->format('F d, Y')}}</td>
                        <td class="py-3 px-4">{{ $viewmember->type }}</td>
                        <td class="py-3 px-4" id="archiveTd">
                            <form action="{{ url('admin/member/member/' . $viewmember->id . '/archive') }}" method="POST" class="inline-block archiveForm">
                                @csrf
                                <button type="button" class="font-bold text-orange-500 hover:text-orange-400 triggerArchive"><i class="fas fa-archive"></i> Archive</button>
                            </form>

                        </td>
                        <td><a href="{{ url('admin/member/viewmember/' . $viewmember->id) }}" class="bg-amber-600 text-white py-1 px-3 rounded hover:bg-amber-600">More Details</a></td>
                    </tr>
                    @endforeach
                    @endif
                </tbody>
            </table>
            @endif
        </div>
    </div>
    </div>

<!-- Tailwind Modal -->
<!-- Modal Background and Content -->
<div id="filterModal" class="fixed inset-0 hidden z-50 flex items-center justify-center">
    <!-- Background Overlay -->
    <div class="absolute inset-0 bg-gray-800 bg-opacity-50"></div>

    <!-- Modal Content -->
    <div class="relative z-10 bg-white rounded-lg p-6 w-96 mx-auto mt-20">
        <h3 class="text-lg font-bold mb-4">Filter by Date</h3>
        <div class="mb-4">
            <label for="filter_start_date" class="block text-sm font-medium">Start Date</label>
            <input type="date" id="filter_start_date" class="w-full border rounded-md p-2" />
        </div>
        <div class="mb-4">
            <label for="filter_end_date" class="block text-sm font-medium">End Date</label>
            <input type="date" id="filter_end_date" class="w-full border rounded-md p-2" />
        </div>
        <div class="flex justify-end space-x-2">
            <button class="bg-gray-500 hover:bg-gray-400 text-white px-4 py-2 rounded-md" onclick="closeFilterModal()">Cancel</button>
            <button id="filterConfirmBtn" class="bg-blue-600 hover:bg-blue-400 text-white px-4 py-2 rounded-md">Confirm</button>
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
        order: [[0, 'asc']],
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

<script>
     document.addEventListener('click', function (e) {
    // Accept action
    if (e.target.classList.contains('triggerArchive')) {
        Swal.fire({
            title: 'Are you sure?',
            text: "You want to archive this member.",
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
                    text: "The member has been archived.",
                    icon: "success"
                });
            }
        });
    }
});
</script>        

<!-- <script>
    function printPage() {
            // Open the print view page in a new window
            var width = 1000;
            var height = 600;
            var left = (window.innerWidth / 2) - (width / 2);
            var top = (window.innerHeight / 2) - (height / 2);

            // Open a new window with the calculated position and size
            var printWindow = window.open("{{ route('printallMember') }}", "_blank", 
                "width=" + width + ",height=" + height + ",left=" + left + ",top=" + top);

            // Optionally, you can wait for the window to load and trigger the print functionality
            printWindow.onload = function() {
                printWindow.print();
            };
        }
</script> -->

<script>
    let actionType = '';

    function openFilterModal(type) {
        actionType = type; // 'export' or 'print'
        document.getElementById('filterModal').classList.remove('hidden');
    }

    function closeFilterModal() {
        document.getElementById('filterModal').classList.add('hidden');
    }

   document.getElementById('filterConfirmBtn').addEventListener('click', function () {
    const startDate = document.getElementById('filter_start_date').value;
    const endDate = document.getElementById('filter_end_date').value;

    console.log("Start Date:", startDate); // Log the start date
    console.log("End Date:", endDate);

    if (!startDate || !endDate) {
        alert('Please select both start and end dates.');
        return;
    }

    // URL encode the date parameters to ensure proper handling
    const encodedStartDate = encodeURIComponent(startDate);
    const encodedEndDate = encodeURIComponent(endDate);

    if (actionType === 'export') {
        // Ensure the URL query parameters are properly encoded
        window.location.href = `{{ route('downloadmemberPDF') }}?startDate=${encodedStartDate}&endDate=${encodedEndDate}`;
    } else if (actionType === 'print') {
        printPage(encodedStartDate, encodedEndDate);
    }

    closeFilterModal();
});

function printPage(startDate, endDate) {
    var width = 1000;
    var height = 600;
    var left = (window.innerWidth / 2) - (width / 2);
    var top = (window.innerHeight / 2) - (height / 2);

    // Use the encoded date parameters
    var printUrl = `{{ route('printallMember') }}?startDate=${startDate}&endDate=${endDate}`;
    var printWindow = window.open(printUrl, "_blank", 
        "width=" + width + ",height=" + height + ",left=" + left + ",top=" + top);

    printWindow.onload = function () {
        printWindow.print();
    };
}
</script>
</body>

</html>
