@extends('layout.layout')

@include('layouts.MemberNav')

<link rel="stylesheet" href="//cdn.datatables.net/2.1.7/css/dataTables.dataTables.min.css">

<body class="font-inter">
<div class="md:pl-20 md:pr-5">
    <h1 class="text-black pt-28 p-4 font-extrabold text-3xl">Upcoming Trips</h1>

    <div class="bg-neutral-100 mx-2 rounded-xl">

        <!-- Trip Data (Unified for Mobile and Table) -->
        <div class="trip-data" data-trips='@json($schedules)'></div> <!-- Passing trip data from the backend -->

        <!-- Mobile Cards -->
        <div class="overflow-x-auto lg:hidden p-3">
            <div class="grid grid-cols-1 gap-4">
                @foreach($schedules as $schedule)
                    <div class="bg-neutral-100 px-4 pt-4 border rounded-lg shadow">
                        <h2 class="text-lg font-semibold">{{ $schedule->booking->user->name }} {{ $schedule->booking->user->last_name }}</h2>
                        <p class="text-sm text-gray-600">Mobile Num: {{ $schedule->booking->user->mobile_num }}</p>
                        <p class="text-sm text-gray-600">Location: {{ $schedule->booking->location }}</p>
                        <p class="text-sm text-gray-600">Destination: {{ $schedule->booking->tariff->destination }}</p>
                        <p class="text-sm text-gray-600">Date: {{ \Carbon\Carbon::parse($schedule->booking->start_date)->format('F d, Y') }} - {{ \Carbon\Carbon::parse($schedule->booking->end_date)->format('F d, Y') }}</p>
                        <div class="pt-5 flex space-x-4 items-center">
                            @if ($schedule->driver_status == 'cancelled')
                                <span class="font-bold text-red-600/100 pb-4">Cancelled</span>
                            @elseif ($schedule->driver_status == 'accepted')
                                <span class="font-bold text-green-600/100 pb-4">Accepted</span>
                            @elseif ($schedule->cust_status == 'cancelled')
                                <span class="font-bold text-red-600/100 pb-4">Customer Cancelled</span>
                            @else
                                <form action="{{ route('schedule.accept', $schedule->id) }}" method="POST" style="display:inline;">
                                    @csrf
                                    <button type="submit" class="bg-green-500 text-white py-1 px-3 rounded hover:bg-green-600">Accept</button>
                                </form>
                                <form action="{{ route('schedule.cancel', $schedule->id) }}" method="POST" style="display:inline;">
                                    @csrf
                                    <button type="submit" class="bg-red-500 text-white py-1 px-3 rounded hover:bg-red-600">Cancel</button>
                                </form>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

        <!-- Large Screen Table -->
        <div class="hidden lg:block overflow-x-auto bg-neutral-100 mx-4 p-3 rounded-xl">
            <table class="min-w-full" id="myTable">
                <thead>
                    <tr class="text-left text-sm text-neutral-950 uppercase tracking-wider">
                        <th class="py-3 px-4">Customer Name</th>
                        <th class="py-3 px-4">Mobile Num</th>
                        <th class="py-3 px-4">Location</th>
                        <th class="py-3 px-4">Destination</th>
                        <th class="py-3 px-4">Start Date</th>
                        <th class="py-3 px-4">End Date</th>
                        <th class="py-3 px-4">Action</th>
                    </tr>
                </thead>
                <tbody class="text-sm text-gray-600">
                @if($schedules->isEmpty())
                    <tr>
                        <td colspan="7">No schedules found</td>
                    </tr>
                @else
                    @foreach($schedules as $schedule)
                        <tr>
                            <td class="py-6 px-4">{{ $schedule->booking->user->name }} {{ $schedule->booking->user->last_name }}</td>
                            <td class="py-6 px-4">{{ $schedule->booking->user->mobile_num }}</td>
                            <td class="py-6 px-4">{{ $schedule->booking->location }}</td>
                            <td class="py-6 px-4">{{ $schedule->booking->tariff->destination }}</td>
                            <td class="py-6 px-4">{{ \Carbon\Carbon::parse($schedule->booking->start_date)->format('F d, Y') }}</td>
                            <td class="py-6 px-4">{{ \Carbon\Carbon::parse($schedule->booking->end_date)->format('F d, Y') }}</td>
                            <td>
                                @if ($schedule->driver_status == 'cancelled')
                                    <span class="font-bold text-red-600/100">Cancelled</span>
                                @elseif ($schedule->driver_status == 'accepted')
                                    <span class="font-bold text-green-600/100">Accepted</span>
                                @elseif ($schedule->cust_status == 'cancelled')
                                    <span class="font-bold text-red-600/100">Customer Cancelled</span>
                                @else
                                    <form action="{{ route('schedule.accept', $schedule->id) }}" method="POST" style="display:inline;">
                                        @csrf
                                        <button type="submit" class="bg-green-500 text-white py-1 px-3 rounded hover:bg-green-600">Confirm</button>
                                    </form>
                                    <form action="{{ route('schedule.cancel', $schedule->id) }}" method="POST" style="display:inline;">
                                        @csrf
                                        <button type="submit" class="bg-red-500 text-white py-1 px-3 rounded hover:bg-red-600">Cancel</button>
                                    </form>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                @endif
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
    </div>
</div>

<!-- jQuery and Bootstrap JS -->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
<script src="//cdn.datatables.net/2.1.7/js/dataTables.min.js"></script>

<script>
    $(document).ready(function() {
        $('#myTable').DataTable();
    });
</script>
