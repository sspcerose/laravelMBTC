@extends('layout.layout')

@include('layouts.adminNav')

<script src="https://code.jquery.com/jquery-3.5.1.js"></script>
<link href="https://cdn.datatables.net/v/dt/dt-2.1.8/b-3.1.2/r-3.0.3/datatables.min.css" rel="stylesheet">
<script src="https://cdn.datatables.net/v/dt/dt-2.1.8/b-3.1.2/r-3.0.3/datatables.min.js"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">

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
                        <th class="py-3 px-4">Mobile Number</th>
                        <th class="py-3 px-4">Email</th>
                        <th class="py-3 px-4">Date of Registration</th>
                        <th class="py-3 px-4">Type</th>
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
                        <td class="py-3 px-4">{{ $viewmember->date_joined }}</td>
                        <td class="py-3 px-4">{{ $viewmember->type }}</td>
                        <td class="py-3 px-4" id="unarchiveTd">
                            <form action="{{url('admin/member/archivemember/' . $viewmember->id)}}" method="POST" class="unarchiveForm" style="display:inline-block;">
                                @csrf
                                <button type="button" class="font-bold text-orange-500 hover:text-orange-400 triggerUnarchive"> <i class="fas fa-archive"></i> Unarchive</button>
                            </form>

                            <!-- Custom confirmation alert (initially hidden) -->
                            <div class="mt-3 relative flex flex-col p-3 text-sm text-gray-800 bg-blue-100 border border-blue-600 rounded-md hidden unarchiveAlert">
                            <svg class="w-6 h-6 text-gray-800 dark:text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.529 9.988a2.502 2.502 0 1 1 5 .191A2.441 2.441 0 0 1 12 12.582V14m-.01 3.008H12M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z"/>
                                    </svg>
                                                Are you sure you want to unarchive this member?
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
    // Trigger archive confirmation
    if (e.target.classList.contains('triggerUnarchive')) {
        let unarchiveForm = e.target.closest('.unarchiveForm');
        let unarchiveAlert = unarchiveForm.nextElementSibling;
        document.getElementById('unarchiveTd').style.width = '25%'; 
        unarchiveAlert.classList.remove('hidden'); 
        e.target.style.display = 'none'; 
    }

    // Close the confirmation (cancel button)
    if (e.target.classList.contains('cancelButton')) {
        let unarchiveAlert = e.target.closest('.unarchiveAlert');
        unarchiveAlert.classList.add('hidden'); 
        document.getElementById('unarchiveTd').style.width = '';
        let unarchiveTd = unarchiveAlert.closest('td');
        let unarchiveForm = unarchiveAlert.previousElementSibling;

        let archiveButton = unarchiveForm.querySelector('.triggerUnarchive');
        if (archiveButton) {
            archiveButton.style.display = ''; 
        }

    }

    // Confirm archiving and display success message
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
                                            Successfully Unarchived the Member!`;

                let unarchiveTd = unarchiveForm.closest('td');
                unarchiveTd.appendChild(successMessage); 
                unarchiveAlert.classList.add('hidden'); 

                setTimeout(function () {
                    successMessage.remove();
                    unarchiveForm.submit(); 
                }, 1000);
            }
        }
    }
});
        </script>
</body>

</html>
