<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>
    <link rel="stylesheet" href="//cdn.datatables.net/2.1.7/css/dataTables.dataTables.min.css">

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    {{ __("You're logged in!") }}
                    <div class="py-12">              
                    
                        <h2>Schedule</h2>
                        <table class="table table-bordered table-striped" id="myTable">
                            <thead>
                                <tr>
                                    <th>Customer Name</th>
                                    <th>Mobile Num</th>
                                    <th>Location</th>
                                    <th>Destination</th>
                                    <th>Start Date</th>
                                    <th>End Date</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                            @if($schedules->isEmpty())
                                    <tr>
                                        <td colspan="7">No schedules found</td>
                                    </tr>
                            @else
                                @foreach($schedules as $schedule)
                                    <tr>
                                        <td>{{ $schedule->booking->user->name }} {{ $schedule->booking->user->last_name }}</td>
                                        <td>{{ $schedule->booking->user->mobile_num }}</td>
                                        <td>{{ $schedule->booking->location}}</td>
                                        <td>{{ $schedule->booking->tariff->destination }}</td>
                                        <td>{{ \Carbon\Carbon::parse($schedule->booking->start_date)->format('F d, Y') }}</td>
                                        <td>{{ \Carbon\Carbon::parse($schedule->booking->end_date)->format('F d, Y') }}</td>
                                
                                        <td>
                                            @if ($schedule->driver_status == 'cancelled')
                                                <span class="text-danger">Cancelled</span>
                                            @elseif ($schedule->driver_status == 'accepted')
                                                <span class="text-success">Accepted</span>
                                            @elseif ($schedule->cust_status == 'cancelled')
                                                <span class="text-danger">Customer Cancelled</span>
                                            @else
                                                        <form action="{{ route('schedule.accept', $schedule->id) }}" method="POST" style="display:inline;">
                                                            @csrf
                                                            <button type="submit" class="btn btn-success btn-sm">Accept</button>
                                                        </form>
                                                        <form action="{{ route('schedule.cancel', $schedule->id) }}" method="POST" style="display:inline;">
                                                            @csrf
                                                            <button type="submit" class="btn btn-danger btn-sm">Cancel</button>
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
            </div>
        </div>
    </div>

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

<script src="https://code.jquery.com/jquery-3.7.1.min.js" integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>
    <script src="//cdn.datatables.net/2.1.7/js/dataTables.min.js"></script>
     <script>
        let table = new DataTable('#myTable');
     </script>
    
</x-app-layout>
