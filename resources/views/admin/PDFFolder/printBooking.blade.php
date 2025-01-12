@extends('layout.layout')

<body class="p-8">
    <h1 class="text-center font-bold">Manibela ng Buhay Trnasport Cooperative</h1>
    <h1 class="text-center font-bold">Caanawan, San Jose City, Philippines</h1>
    <p class="text-gray-700">Date: {{ $date }}</p>

    <div class="mt-6">
        <h2 class="text-xl font-semibold">Statistics</h2>
        <ul class="list-disc pl-6">
            <li>Total Bookings ({{ \Carbon\Carbon::create()->month($date)->format('F') }}): {{ $totalMonthlyBookings }}</li>
            <li>Total Bookings ({{ date('Y') }}): {{ $totalYearlyBookings }}</li>
            <li>Total Revenue (Excluding Cancelled): PHP {{ number_format($totalRevenue, 2) }}</li>
            <li>Revenue from Cancelled Bookings: PHP {{ number_format($cancelledRevenue, 2) }}</li>
            <li>Total Cancelled Bookings: {{ $cancelledCount }}</li>
        </ul>
    </div>

    <div class="mt-6">
        <h2 class="text-xl font-semibold">Top 5 Destinations</h2>
        <ol class="list-decimal pl-6">
            @foreach ($topDestinations as $destination)
                <li>{{ $destination->destination }} - {{ $destination->count }} bookings</li>
            @endforeach
        </ol>
    </div>

    <div class="mt-6">
        <h2 class="text-xl font-semibold">Monthly Bookings ({{ date('Y') }})</h2>
        <ul class="list-disc pl-6">
            @foreach ($monthlyBookings as $month => $count)
                <li>{{ \Carbon\Carbon::create()->month($month)->format('F') }}: {{ $count }}</li>
            @endforeach
        </ul>
    </div>

    <div class="mt-6">
        <h2 class="text-xl font-semibold">Monthly Sales (Accepted Bookings)</h2>
        <ul class="list-disc pl-6">
            @foreach ($monthlySales as $month => $sales)
                <li>{{ \Carbon\Carbon::create()->month($month)->format('F') }}: PHP {{ number_format($sales, 2) }}</li>
            @endforeach
        </ul>
    </div>

    <script>
        window.onload = function() {
            window.print();
        };
    </script>
</body>


