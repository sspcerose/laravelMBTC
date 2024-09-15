<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Vehicle</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/tailwindcss/2.2.19/tailwind.min.css" rel="stylesheet">
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
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

    <div class="container mx-auto">
        <h2 class="text-center text-2xl font-bold">Booking</h2>
    </div>

    <div>
        <br>
        <form action="{{ url('admin/booking/booking') }}" method="GET">
            <input type="search" name="bookingSearch" placeholder="Search">
            <input type="submit" value="Search">
        </form>
    </div>

    <div class="table-container">
        <table class="min-w-full bg-white">
            <thead>
                <tr>
                    <th class="py-2 border-2 border-black header-cell">Customer</th>
                    <th class="py-2 border-2 border-black header-cell">Location</th>
                    <th class="py-2 border-2 border-black header-cell">Destination</th>
                    <th class="py-2 border-2 border-black header-cell">Passenger</th>
                    <th class="py-2 border-2 border-black header-cell">Date</th>
                    <th class="py-2 border-2 border-black header-cell">Price</th>
                    <th class="py-2 border-2 border-black header-cell">Receipt</th>
                </tr>
            </thead>
            <tbody>
                @if($viewBookings->isEmpty())
                    <tr>
                        <td colspan="7" class="text-center py-4">No bookings found</td>
                    </tr>
                @else
                    @foreach($viewBookings as $viewBooking)
                    <tr>
                        <td class="py-2 border-2 border-black header-cell">
                            {{ $viewBooking->user->name }} {{ $viewBooking->user->last_name }}
                        </td>
                        <td class="py-2 border-2 border-black header-cell">{{ $viewBooking->location }}</td>
                        <td class="py-2 border-2 border-black header-cell">{{ $viewBooking->destination }}</td>
                        <td class="py-2 border-2 border-black header-cell">{{ $viewBooking->passenger }}</td>
                        <td class="py-2 border-2 border-black header-cell">
                            {{ \Carbon\Carbon::parse($viewBooking->start_date)->format('F d, Y') }} - {{ \Carbon\Carbon::parse($viewBooking->end_date)->format('F d, Y') }}
                        </td>
                        <td class="py-2 border-2 border-black header-cell">₱{{ $viewBooking->price }}.00</td>
                        <td class="py-2 border-2 border-black header-cell">
                            <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#imageModal" data-receipt="{{ asset('img/' . $viewBooking->receipt) }}">
                                View Receipt
                            </button>
                        </td>
                    </tr>
                    @endforeach
                @endif
            </tbody>
        </table>
    </div>
</div>

<!-- Modal -->
<div class="modal fade" id="imageModal" tabindex="-1" role="dialog" aria-labelledby="imageModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="imageModalLabel">Receipt</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body text-center">
                <img id="modalImage" src="" alt="Receipt" class="img-fluid" />
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

<script>
    $(document).ready(function() {
        $('#imageModal').on('show.bs.modal', function (event) {
            var button = $(event.relatedTarget);
            var receipt = button.data('receipt');
            var modalImage = document.getElementById("modalImage");
            modalImage.src = receipt;
        });
    });
</script>

</body>
</html>
