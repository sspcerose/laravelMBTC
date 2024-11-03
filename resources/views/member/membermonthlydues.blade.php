@extends('layout.layout')

@include('layouts.MemberNav')

<script src="https://code.jquery.com/jquery-3.5.1.js"></script>
<link href="https://cdn.datatables.net/v/dt/dt-2.1.8/b-3.1.2/r-3.0.3/datatables.min.css" rel="stylesheet">
<script src="https://cdn.datatables.net/v/dt/dt-2.1.8/b-3.1.2/r-3.0.3/datatables.min.js"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">

<body class="font-inter">
    <div class="lg:pl-20 lg:pr-10">
        <div class="pt-24 lg:pt-28 lg:pr-6 flex justify-between items-center">
            <h1 class="text-black p-4 pl-4 text-center md:text-left font-extrabold text-3xl">Monthly Dues</h1>
        </div>

        <div class="bg-neutral-300 mx-4 rounded-3xl p-2 items-center mb-4">
            <div class="overflow-x-auto bg-neutral-100 px-2 md:px-4 lg:py-2 rounded-2xl" id="largeTable">
                @if($membermonthlydues->isEmpty())
                    <p class="text-center">NO MONTHLY DUE FOR YOU YET</p>
                @else
                    <table class="min-w-full" id="myTable">
                        <thead>
                        <tr class="text-left text-sm text-neutral-950 uppercase tracking-wider">
                                <th class="py-3 px-4">ID</th>
                                <th class="py-3 px-4">Last Payment</th>
                                <th class="py-3 px-4">Amount</th>
                                <th class="py-3 px-4">Current Due</th>
                                <th class="py-3 px-4">Status</th>
                                <th class="py-3 px-4">Confirmation</th>
                            </tr>
                        </thead>
                        <tbody class="text-sm text-gray-600" id="tableBody">
                            @if($membermonthlydues->isEmpty())
                            <tr>
                                <td colspan="8" class="text-center py-4">No payments found.</td>
                            </tr>
                            @else
                            @foreach($membermonthlydues as $membermonthlydue)
                            <tr>
                                <td class="py-3 px-4">($membermonthlydue->id)</td>
                                <td class="py-3 px-4">{{ $membermonthlydue->last_payment ? \Carbon\Carbon::parse($membermonthlydue->last_payment)->format('F d, Y') : '-' }}</td>
                                <td class="py-3 px-4">₱{{ number_format($membermonthlydue->dues->amount, 2) }}</td>
                                <td class="py-3 px-4">{{ \Carbon\Carbon::parse($membermonthlydue->dues->date)->format('F Y') }}</td>
                                <td class="py-3 px-4">
                                    @if($membermonthlydue->status == 'paid')
                                    <span class="font-bold text-green-500">Paid</span>
                                    @else
                                    <span class="font-bold text-red-500">Unpaid</span>
                                    @endif
                                </td>
                                    @if($membermonthlydue->status == 'paid')
                                    <td class="py-3 px-4">
                                    <span class="font-bold text-green-500">Payment Verified</span></td>
                                    @elseif($membermonthlydue->status == 'update')
                                    <td class="py-3 px-4">
                                    <span class="font-bold text-yellow-500">Update is sent</span></td>
                                    @else
                                    <td class="py-3 px-4">
                                    <form action="{{ url('member/membermonthlydues/' . $membermonthlydue->id) }}" method="POST" class="updateForm" style="display:inline;">
                                        @csrf
                                        <button class="bg-green-500 text-white py-1 px-3 rounded hover:bg-green-600" type="submit">Send Update</button>
                                    </form>

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

<script>
    $(document).ready(function () {
        $('#myTable').DataTable({
            responsive: true,
            order: [[0, 'desc']],
                columnDefs: [
                    { targets: 0, visible: false }
                ]
        });
    });
    </script>

</body>


