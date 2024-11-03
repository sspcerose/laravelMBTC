@extends('layout.layout')

@include('layouts.adminNav')

<script src="https://code.jquery.com/jquery-3.5.1.js"></script>
<link href="https://cdn.datatables.net/v/dt/dt-2.1.8/b-3.1.2/r-3.0.3/datatables.min.css" rel="stylesheet">
<script src="https://cdn.datatables.net/v/dt/dt-2.1.8/b-3.1.2/r-3.0.3/datatables.min.js"></script>

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">


<body class="font-inter">
    <div class="lg:pl-20 lg:pr-10">
        <div class="pt-24 lg:pt-28 p-4 flex flex-col md:flex-row justify-between items-center">
            <h1 class="text-black p-6 pl-4 text-center md:text-left font-extrabold text-3xl">Members List</h1>
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
                        <i class="fas fa-archive"></i> <!-- Notification icon -->
                    </button>
                    <!-- Tooltip -->
                        <div class="absolute bottom-full left-1/2 transform -translate-x-1/2 mb-2 hidden group-hover:block bg-white text-black text-xs rounded-lg px-3 py-1 shadow-lg whitespace-nowrap">
                            Archived Members
                        </div>
                    </div>
                </a>
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
                        <th class="py-3 px-4">PHONE NUMBER</th>
                        <th class="py-3 px-4">EMAIL</th>
                        <th class="py-3 px-4">DATE OF REGISTRATION</th>
                        <th class="py-3 px-4">TYPE</th>
                        <th class="py-3 px-4">ACTION</th>
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
                        <td class="py-3 px-4">{{\Carbon\Carbon::parse($viewmember->date_joined)->format('F d, Y')}}</td>
                        <td class="py-3 px-4">{{ $viewmember->type }}</td>
                        <td class="py-3 px-4" id="archiveTd">
                            <form action="{{ url('admin/member/member/' . $viewmember->id . '/archive') }}" method="POST" class="inline-block archiveForm">
                                @csrf
                                <button type="button" class="font-bold text-orange-500 hover:text-orange-400 triggerArchive"><i class="fas fa-archive"></i> Archive</button>
                            </form>

                            <!-- Custom confirmation alert (initially hidden) -->
                            <div class="mt-3 relative flex flex-col p-3 text-sm text-gray-800 bg-blue-100 border border-blue-600 rounded-md hidden archiveAlert">
                            <svg class="w-6 h-6 text-gray-800 dark:text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.529 9.988a2.502 2.502 0 1 1 5 .191A2.441 2.441 0 0 1 12 12.582V14m-.01 3.008H12M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z"/>
                                    </svg>

                                                Are you sure you want to archive this member?
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
                responsive: true,
                order: [[0, 'asc']],
                columnDefs: [
                    { targets: 0, visible: false } 
                ]
            });
        });

        document.addEventListener('click', function (e) {
    // Trigger archive confirmation
    if (e.target.classList.contains('triggerArchive')) {
        let archiveForm = e.target.closest('.archiveForm');
        let archiveAlert = archiveForm.nextElementSibling;
        document.getElementById('archiveTd').style.width = '25%'; 
        archiveAlert.classList.remove('hidden');
        e.target.style.display = 'none'; 
    }

    // Close the confirmation (cancel button)
    if (e.target.classList.contains('cancelButton')) {
        let archiveAlert = e.target.closest('.archiveAlert');
        archiveAlert.classList.add('hidden');
        document.getElementById('archiveTd').style.width = '';
        let archiveTd = archiveAlert.closest('td');
        let archiveForm = archiveAlert.previousElementSibling;

        let archiveButton = archiveForm.querySelector('.triggerArchive');
        if (archiveButton) {
            archiveButton.style.display = ''; 
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
                                            Successfully Archived the Member!`;

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
</body>

</html>
