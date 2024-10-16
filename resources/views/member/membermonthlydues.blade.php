@include('layouts.MemberNav')


<div class="py-12">
    <div class="container">
        <div class="mb-4">
            <a href="{{ route('member.dashboard') }}" class="bg-red-500 text-white py-2 px-4 rounded">Back</a>
        </div>

        <h2 class="text-center text-2xl font-bold">Monthly Dues</h2>


        <div class="table-container">
            <table class="min-w-full bg-white" id="myTable">
                <thead>
                    <tr>
                        <th class="py-2 border-2 border-black header-cell">Last Payment</th>
                        <th class="py-2 border-2 border-black header-cell">Amount</th>
                        <th class="py-2 border-2 border-black header-cell">Current Due</th>
                        <th class="py-2 border-2 border-black header-cell">Status</th>
                        <th class="py-2 border-2 border-black header-cell">Send Notification</th>
                    </tr>
                </thead>
                <tbody>
                    @if($membermonthlydues->isEmpty())
                    <tr>
                        <td colspan="5">No payments found.</td>
                    </tr>
                    @else
                    @foreach($membermonthlydues as $membermonthlydue)
                    <tr>
                        <td class="py-2 border-2 border-black header-cell">{{ $membermonthlydue->last_payment ? \Carbon\Carbon::parse($membermonthlydue->last_payment)->format('F d, Y') : '-' }}</td>
                        <td class="py-2 border-2 border-black header-cell">₱{{ number_format($membermonthlydue->dues->amount, 2) }}</td>
                        <td class="py-2 border-2 border-black header-cell">{{ \Carbon\Carbon::parse($membermonthlydue->dues->date)->format('F Y') }}</td>
                        <td class="py-2 border-2 border-black header-cell">
                            @if($membermonthlydue->status == 'paid')
                            <span class="text-success">Paid</span>
                            @else
                            <span class="text-danger">Not Yet Paid</span>
                            @endif
                        </td>
                            @if($membermonthlydue->status == 'paid')
                            <td class="py-2 border-2 border-black header-cell">
                            <span>Payment Verified</span></td>
                            @else
                            <td class="py-2 border-2 border-black header-cell">
                            <button class="btn btn-success">Send</button>
                            @endif
                        </td>

                    </tr>
                    @endforeach
                    @endif
                </tbody>
            </table>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

    <script src="https://code.jquery.com/jquery-3.7.1.min.js" integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>
    <script src="//cdn.datatables.net/2.1.7/js/dataTables.min.js"></script>
     <script>
        let table = new DataTable('#myTable');
     </script>

</body>

</html>
