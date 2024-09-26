<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Vehicle</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/tailwindcss/2.2.19/tailwind.min.css" rel="stylesheet">
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="//cdn.datatables.net/2.1.7/css/dataTables.dataTables.min.css">
    <style>
        body {
            padding: 30px;
        }

        .container {
            margin-top: 10px;
        }

        .table-container {
            margin-top: 30px;
        }

        .header-cell {
            padding-left: 10px;
            padding-right: 10px;
            background-color: #f8f9fa;
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="mb-4">
            <a href="{{ route('admin.dashboard') }}" class="bg-red-500 text-white py-2 px-4 rounded">Back</a>
        </div>

        <h2 class="text-center text-2xl font-bold">Monthly Dues</h2>

        <!-- Search -->
        <div>
            <br>
            <form action="{{ url('admin/monthlydues/monthlydues') }}" method="GET">
                <input type="search" name="monthlyduesSearch" placeholder="Search">
                <input type="submit" value="Search">
            </form>
        </div>

        <div class="table-container">
            <table class="min-w-full bg-white" id="myTable">
                <thead>
                    <tr>
                        <th class="py-2 border-2 border-black header-cell">Member</th>
                        <th class="py-2 border-2 border-black header-cell">Last Payment</th>
                        <th class="py-2 border-2 border-black header-cell">Amount</th>
                        <th class="py-2 border-2 border-black header-cell">Current Due</th>
                        <th class="py-2 border-2 border-black header-cell">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @if($payments->isEmpty())
                    <tr>
                        <td colspan="5">No payments found.</td>
                    </tr>
                    @else
                    @foreach($payments as $payment)
                    <tr>
                        <td class="py-2 border-2 border-black header-cell">{{ $payment->member->name }} {{ $payment->member->last_name }}</td>
                        <td class="py-2 border-2 border-black header-cell">{{ $payment->last_payment ? \Carbon\Carbon::parse($payment->last_payment)->format('F d, Y') : '-' }}</td>
                        <td class="py-2 border-2 border-black header-cell">₱{{ number_format($payment->dues->amount, 2) }}</td>
                        <td class="py-2 border-2 border-black header-cell">{{ \Carbon\Carbon::parse($payment->dues->date)->format('F Y') }}</td>
                        <td class="py-2 border-2 border-black header-cell">
                            @if($payment->status == 'paid')
                            <span class="text-success">Paid</span>
                            @else
                            <form method="POST" action="{{ url('admin/monthlydues/monthlydues/' . $payment->id) }}">
                                @csrf
                                <input type="hidden" name="payment_id" value="{{ $payment->id }}">
                                <button type="submit" class="btn btn-success btn-sm">Confirm Payment</button>
                            </form>
                            @endif
                        </td>
                    </tr>
                    @endforeach
                    @endif
                </tbody>
            </table>
        </div>
    </div>

<script src="https://code.jquery.com/jquery-3.7.1.min.js" integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
<script src="//cdn.datatables.net/2.1.7/js/dataTables.min.js"></script>

<script>
    let table = new DataTable('#myTable');
</script>

</body>

</html>
