<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Schedule</title>
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="//cdn.datatables.net/2.1.7/css/dataTables.dataTables.min.css">
    <style>
        body {
            background-color: #f8f9fa;
        }
        .container {
            margin-top: 50px;
        }
        .confirmed {
            color: green;
        }
        .paid {
            color: green;
        }
        .unpaid {
            color: red;
        }
    </style>
</head>
<body>
<div class="container">
        <a href="{{ url('admin/dashboard') }}" class="btn btn-danger mb-4">Back</a>
    <h1 class="mt-5">Schedule</h1>
    <div class="card mt-3">
        <div class="card-body">
            <table class="table table-striped" id="myTable">
                <thead>
                    <tr>
                        <th>Customer Name</th>
                        <th>Destination</th>
                        <th>Date</th>
                        <th>Driver</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                @foreach($viewBookings as $viewBooking)
                    <tr>
                        <td>{{ $viewBooking->user->name }} {{ $viewBooking->user->last_name }}</td>
                        <td>{{ $viewBooking->destination }}</td>
                        <td>{{ date('M d, Y', strtotime($viewBooking->start_date)) }} - {{ date('M d, Y', strtotime($viewBooking->end_date)) }}</td>
                        <td>
                        @if($viewBooking->schedule->isNotEmpty())
                            @php
                                // Get the most recent schedule for the booking
                                $latestSchedule = $viewBooking->schedule->first();
                            @endphp
                            @if($latestSchedule->driver_status == 'cancelled')
                                {{-- If the driver status is "cancelled", show the driver selection dropdown --}}
                                <form action="{{ url('admin/schedule/schedule') }}" method="POST">
                                    @csrf
                                    <input type="hidden" name="booking_id" value="{{ $viewBooking->id }}">
                                    <select name="driver_id" class="form-control">
                                        @foreach($drivers as $driver)
                                            {{-- Loop through each driver's collection --}}
                                            @foreach($driver->driver as $individualDriver)
                                                <option value="{{ $individualDriver->id }}">
                                                    {{ $driver->name }} {{ $driver->last_name }}
                                                </option>
                                            @endforeach
                                        @endforeach
                                    </select>
                                    <button type="submit" class="btn btn-primary mt-2">Assign Driver</button>
                                </form>
                            @elseif(!empty($latestSchedule->driver))
                                {{-- If a driver is already assigned and not cancelled, show the driver name --}}
                                {{ $latestSchedule->driver->member->name }} {{ $latestSchedule->driver->member->last_name }}
                            @endif
                        @else
                            {{-- If no driver is assigned, show the driver selection dropdown --}}
                            <form action="{{ url('admin/schedule/schedule') }}" method="POST">
                                @csrf
                                <input type="hidden" name="booking_id" value="{{ $viewBooking->id }}">
                                <select name="driver_id" class="form-control">
                                    @foreach($drivers as $driver)
                                        {{-- Loop through each driver's collection --}}
                                        @foreach($driver->driver as $individualDriver)
                                            <option value="{{ $individualDriver->id }}">
                                                {{ $driver->name }} {{ $driver->last_name }}
                                            </option>
                                        @endforeach
                                    @endforeach
                                </select>
                                <button type="submit" class="btn btn-primary mt-2">Assign Driver</button>
                            </form>
                        @endif
                        </td>
                        <td>
                            {{-- Show status whether a driver is assigned or not --}}
                            @if($viewBooking->schedule->isNotEmpty())
                                @if($latestSchedule->driver_status == 'accepted')
                                    <span class="text-success">Driver Accepted</span>
                                @elseif($latestSchedule->driver_status == 'scheduled')
                                    <span class="text-success">Assigned</span>
                                @elseif($latestSchedule->driver_status == 'cancelled')
                                    <span class="text-danger">Driver Cancelled, Assign New One</span>
                                @endif
                            @else
                                <span class="text-danger">To be Assigned</span>
                            @endif
                        </td>
                    </tr>
                    @endforeach

                    </tbody>
            </table>
        </div>
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
