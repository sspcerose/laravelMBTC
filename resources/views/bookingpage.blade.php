<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Booking</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/tailwindcss/2.2.19/tailwind.min.css" rel="stylesheet">
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="//cdn.datatables.net/1.11.5/css/jquery.dataTables.min.css">
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
        <a href="{{ route('dashboard') }}" class="bg-red-500 text-white py-2 px-4 rounded">Back</a>
    </div>

    <div class="container mx-auto">
        <h2 class="text-center text-2xl font-bold">Booking</h2>
    </div>

    <div class="table-container">
    <table id="myTable" class="min-w-full bg-white">
        <thead>
            <tr>
                <th class="py-2 border-2 border-black header-cell">Driver Name</th>
                <th class="py-2 border-2 border-black header-cell">Location</th>
                <th class="py-2 border-2 border-black header-cell">Destination</th>
                <th class="py-2 border-2 border-black header-cell">Start Date</th>
                <th class="py-2 border-2 border-black header-cell">End Date</th>
                <th class="py-2 border-2 border-black header-cell">Status</th>
                <th class="py-2 border-2 border-black header-cell">Price</th>
                <th class="py-2 border-2 border-black header-cell">Receipt</th>
                <th class="py-2 border-2 border-black header-cell">Action</th>
            </tr>
        </thead>
        <tbody>
        @if($bookings->isNotEmpty())
            @foreach($bookings as $booking)
                <tr>
                    <td class="py-2 border-2 border-black header-cell">
                    @if ($booking->schedule->isNotEmpty())
                        @foreach ($booking->schedule as $schedule)
                            {{ $schedule->driver->member->name }} {{ $schedule->driver->member->last_name }}
                        @endforeach
                    @else
                        <p>No schedule available for this booking.</p>
                    @endif
                    </td>
                    <td class="py-2 border-2 border-black header-cell">{{ $booking->location }}</td>
                    <td class="py-2 border-2 border-black header-cell">{{ $booking->destination }}</td>
                    <td class="py-2 border-2 border-black header-cell">{{ $booking->start_date }}</td>
                    <td class="py-2 border-2 border-black header-cell">{{ $booking->end_date }}</td>
                    <td class="py-2 border-2 border-black header-cell">{{ $booking->status }}</td>
                    <td class="py-2 border-2 border-black header-cell">₱{{ $booking->price }}</td>
                    <td class="py-2 border-2 border-black header-cell">
                    <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#imageModal" data-receipt="{{ asset('img/' . $booking->receipt) }}">
                                View Receipt
                    </button>
                    </td>
                    <td class="py-2 border-2 border-black header-cell">
                    @if ($booking->status === "Cancelled")
                        <span class='text-danger'>Cancelled</span>
                    @else
                        <form action="{{ route('cancelbooking', $booking->id) }}" method="POST" class="inline-block">
                        
                                        @csrf
                                        <input type='hidden' name='schedule_id' value='{{ $booking->id }}'>
                               
                                    <button type='submit' class='btn btn-danger btn-sm'>Cancel</button>
                                    </form>
                        
                        @endif
                    </td>
                </tr>
            @endforeach
        @else
            <tr><td colspan='8'>No bookings found</td></tr>
        @endif
        </tbody>
    </table>
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
<script src="//cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>

<script>
    $(document).ready(function() {
    
        $('#myTable').DataTable();

     
        $('#imageModal').on('show.bs.modal', function (event) {
            var button = $(event.relatedTarget); 
            var receipt = button.data('receipt'); 
            $('#modalImage').attr('src', receipt); 
        });
    });
</script>
</body>
</html>
