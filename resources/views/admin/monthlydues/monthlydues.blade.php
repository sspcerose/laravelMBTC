@extends('layout.layout')

@include('layouts.adminNav')

<script src="https://code.jquery.com/jquery-3.5.1.js"></script>
<link href="https://cdn.datatables.net/v/dt/dt-2.1.8/b-3.1.2/r-3.0.3/datatables.min.css" rel="stylesheet">
<script src="https://cdn.datatables.net/v/dt/dt-2.1.8/b-3.1.2/r-3.0.3/datatables.min.js"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">

<body class="font-inter">
    <div class="lg:pl-20 lg:pr-10">
        <div class="pt-24 lg:pt-28 p-4 flex flex-col md:flex-row justify-between items-center">
            <h1 class="text-black p-6 pl-4 text-center md:text-left font-extrabold text-3xl">Monthly Dues</h1>
            <div class="flex justify-between px-5">
                <a href="{{ route('admin.monthlydues.viewallmonthlydues')}}" class=" pt-4 mr-2">
                    <button class="bg-green-600 hover:bg-green-400 text-white flex items-center py-3 px-4 rounded-xl">
                        SEE ALL
                    </button>
                </a>
                
                <div class="px-4 lg:px-0 pt-4 lg:pr-5">
                <!-- Notification Icon -->
                <div class="relative">
                @if($paymentnotifcount == 0)
                    <button id="notification-icon" class="relative p-4 bg-gray-500 text-white rounded-lg focus:outline-none">
                    <i class="fa-solid fa-bell"></i>
                @else
                <button id="notification-icon" class="relative p-4 bg-blue-500 text-white rounded-lg focus:outline-none">
                        <i class="fa-solid fa-bell"></i>
                        <span id="notification-badge" class="absolute top-0 right-0 inline-flex items-center justify-center w-5 h-5 text-xs font-bold text-white bg-red-500 rounded-full">{{ $paymentnotifcount }}</span>
                @endif
                    </button>

                    <!-- Notification Container -->
                    <div id="notification-container" class="hidden absolute right-0 mt-2 w-64 bg-white shadow-lg rounded-lg overflow-hidden z-10 border-2 border-gray-500">
                    <ul class="p-4 max-h-96 overflow-y-auto" id="notification-list">
                    <li class="text-sm text-gray-700 border-b p-2">
                    @if($paymentss->isEmpty()) 
                        <span class="font-bold text-center">No Notification</span><br>
                    @else
                        @foreach($paymentss as $paymentts)
                            @if($paymentts->status == 'update')
                                    
                                    <span class="font-bold">{{ $paymentts->member->name }} {{ $paymentts->member->last_name }}</span> wants an update on its <span class="font-bold">{{ \Carbon\Carbon::parse($paymentts->dues->date)->format('F Y') }} due</span>
                                    </li>
                            @endif
                        @endforeach
                            </ul>
                            @endif
                        </div>
                        
                        </div>
                       
            </div>
            
                </div>
                   
                
                </div>
                

        <div class="bg-neutral-300 mx-4 rounded-3xl p-2 items-center mb-4">
            <div class="overflow-x-auto bg-neutral-100 px-2 md:px-4 lg:py-2 rounded-2xl" id="largeTable">
            @if($payments->isEmpty())
            <p class="text-center">NO MONTHLY DUES YET</p>
            @else

            <table class="min-w-full" id="myTable">
                <thead>
                    <tr>
                        <th class="py-3 px-4">ID</th>
                        <th></th>
                        <th class="py-3 px-4">MEMBER NAME</th>
                        <th class="py-3 px-4">LAST PAYMENT</th>
                        <th class="py-3 px-4">AMOUNT</th>
                        <th class="py-3 px-4">CURRENT DUE</th>
                        <th class="py-3 px-4">STATUS</th>
                        <th class="py-3 px-4">ACTION</th>
                    </tr>
                </thead>
                <tbody class="text-sm text-gray-600" id="tableBody">
                    @if($payments->isEmpty())
                    <tr>
                        <td colspan="5">No payments found.</td>
                    </tr>
                    @else
                    @foreach($payments as $payment)
                    <tr>
                        <td class="py-3 px-4">($payment->id)</td>
                        @if($payment->status == 'update')
                        <td class="py-3 px-4"><i class="fas fa-bell bg-orange-500 text-white p-3 rounded"></i></td>
                        @else
                        <td class="py-3 px-4"><i class="fas fa-bell bg-gray-500 text-white p-3 rounded"></i></td>
                        @endif
                        <td class="py-3 px-4">{{ $payment->member->name }} {{ $payment->member->last_name }}</td>
                        <td class="py-3 px-4">{{ $payment->last_payment ? \Carbon\Carbon::parse($payment->last_payment)->format('F d, Y') : '-' }}</td>
                        <td class="py-3 px-4">₱{{ number_format($payment->dues->amount, 2) }}</td>
                        <td class="py-3 px-4 font-bold text-blue-500">{{ \Carbon\Carbon::parse($payment->dues->date)->format('F Y') }}</td>
                        <td class="py-3 px-4">
                            @if($payment->status == 'paid')
                            <span class="font-bold text-green-500">Paid</span>
                            @else
                            <span class="font-bold text-red-500">Unpaid</span>
                            @endif
                        <td class="py-3 px-4" id="monthlyduesTd">
                            @if($payment->status == 'paid')
                            <!-- <button class="bg-red-500 text-white py-1 px-3 rounded hover:bg-red-600">Undo</button> -->
                            <span class="font-bold text-green-500">Payment Verified</span>
                            @else
                            <form method="POST" action="{{ url('admin/monthlydues/monthlydues/' . $payment->id) }}" class="paidForm">
                                @csrf
                                <input type="hidden" name="payment_id" value="{{ $payment->id }}">
                                <button type="button" class="bg-blue-500 text-white py-1 px-3 rounded hover:bg-blue-600 triggerPaid">Paid</button>
                            </form>

                            <div class="mt-3 relative flex flex-col p-3 text-sm bg-blue-100 border border-blue-600 rounded-md hidden paidAlert">
                            <svg class="w-6 h-6 text-gray-800 dark:text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.529 9.988a2.502 2.502 0 1 1 5 .191A2.441 2.441 0 0 1 12 12.582V14m-.01 3.008H12M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z"/>
                                    </svg>
                                                Are you sure that {{ $payment->member->name }} paid monthly due?
                                                <div class="flex justify-end mt-2">
                                                    <button class="bg-gray-600 text-white py-1 px-3 mr-2 rounded-lg hover:bg-gray-500 cancelButton">
                                                        Back
                                                    </button>
                                                    <button class="bg-green-600 text-white py-1 px-3 rounded-lg hover:bg-green-500 yesButton">
                                                        Yes
                                                    </button>
                                                </div>
                                            </div>
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
                                    columns: [2, 3, 4, 5, 6] 
                                }
                            },
                            {
                                extend: 'excel',
                                exportOptions: {
                                    columns: [2, 3, 4, 5, 6] 
                                }
                            },
                            {
                                extend: 'csv',
                                exportOptions: {
                                    columns: [2, 3, 4, 5, 6] 
                                }
                            },
                            {
                                extend: 'pdf',
                                exportOptions: {
                                    ccolumns: [2, 3, 4, 5, 6] 
                                }
                            },
                            {
                                extend: 'print',
                                exportOptions: {
                                    columns: [2, 3, 4, 5, 6] 
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
        // Trigger paid confirmation
        if (e.target.classList.contains('triggerPaid')) {
            let paidForm = e.target.closest('.paidForm');
            let paidAlert = paidForm.nextElementSibling;
            paidAlert.classList.remove('hidden'); 
            document.getElementById('monthlyduesTd').style.width = '25%'; 
            e.target.style.display = 'none'; 
        }

        // Close the paid confirmation (cancel button)
        if (e.target.classList.contains('cancelButton')) {
            let paidAlert = e.target.closest('.paidAlert');
            paidAlert.classList.add('hidden');
            document.getElementById('monthlyduesTd').style.width = '';
            let paidForm = paidAlert.previousElementSibling;
            let paidButton = paidForm.querySelector('.triggerPaid');
            if (paidButton) {
                paidButton.style.display = ''; 
            }
        }

        // Confirm paid and display success message
        if (e.target.classList.contains('yesButton')) {
            let paidAlert = e.target.closest('.paidAlert');
            let paidForm = paidAlert.previousElementSibling;

            if (paidForm) {
                e.preventDefault(); 
                
                if (!paidForm.querySelector('.successMessageAlert')) {
                    let successMessage = document.createElement('div');
                    successMessage.setAttribute('role', 'alert');
                    successMessage.className = 'successMessageAlert mt-3 relative flex w-full p-3 text-sm text-white bg-blue-500 rounded-md';
                    successMessage.innerHTML = `<svg class="w-6 h-6 text-white-800 dark:text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                                                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 11h2v5m-2 0h4m-2.592-8.5h.01M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z"/>
                                                </svg>
                                                Payment Confirmed Successfully!`;

                    let bookingTd = paidForm.closest('td');
                    bookingTd.appendChild(successMessage); 
                    paidAlert.classList.add('hidden'); 

                    setTimeout(function () {
                        successMessage.remove();
                        paidForm.submit(); 
                    }, 1000);
                }
            }
        }
    });

    let seenNotifications = [];
        let notificationCount = 0;

        async function fetchNotifications() {

            const newNotifications = notifications.filter(notification => !seenNotifications.includes(notification.id));
            notificationCount = newNotifications.length;
            updateNotificationBadge(notificationCount);
            displayNotifications(newNotifications);
        }

        function updateNotificationBadge(count) {
            const badge = document.getElementById('notification-badge');
            badge.textContent = count > 0 ? count : ''; 
        }

        function displayNotifications(notifications) {
            const container = document.getElementById('notification-list');
            container.innerHTML = '';
            notifications.forEach(notification => {
                const li = document.createElement('li');
                li.className = "py-2 border-b cursor-pointer hover:bg-gray-100";
                li.textContent = notification.message;
                li.onclick = () => markAsSeen(notification.id);
                container.appendChild(li);
            });
        }

        function markAsSeen(notificationId) {
            seenNotifications.push(notificationId);
        }

        document.getElementById('notification-icon').addEventListener('click', () => {
            const container = document.getElementById('notification-container');
            container.classList.toggle('hidden');
            fetchNotifications();
        });


</script>

</body>

</html>
