<body style="font-family: sans-serif; background-color: white; color: #333; padding: 20px;">
    <div style="max-width: 800px; margin: 0 auto;">
        <h1 style="font-size: 24px; font-weight: bold; text-align: center; margin-bottom: 20px;">{{ $title }}</h1>
    <p style="color: #4a5568; margin-top: 8px;">Date: {{ $date }}</p>
    

    <div style="margin-top: 24px;">
        <h2 style="font-size: 18px; font-weight: bold; margin-bottom: 8px;">Statistics</h2>
        <ul style="list-style-type: disc; padding-left: 24px;">
            <li>Total Bookings ({{ \Carbon\Carbon::create()->month($date)->format('F') }}): {{ $totalMonthlyBookings }}</li>
            <li>Total Bookings ({{ date('Y') }}): {{ $totalYearlyBookings }}</li>
            <li>Total Revenue (Excluding Cancelled): PHP {{ number_format($totalRevenue, 2) }}</li>
            <li>Revenue from Cancelled Bookings: PHP {{ number_format($cancelledRevenue, 2) }}</li>
            <li>Total Cancelled Bookings: {{ $cancelledCount }}</li>
        </ul>
    </div>

    <div style="margin-top: 24px;">
        <h2 style="font-size: 18px; font-weight: bold; margin-bottom: 8px;">Top 5 Destinations</h2>
        <ol style="list-style-type: decimal; padding-left: 24px;">
            @foreach ($topDestinations as $destination)
                <li>{{ $destination->destination }} - {{ $destination->count }} bookings</li>
            @endforeach
        </ol>
    </div>

    <div style="margin-top: 24px;">
        <h2 style="font-size: 18px; font-weight: bold; margin-bottom: 8px;">Monthly Bookings ({{ date('Y') }})</h2>
        <ul style="list-style-type: disc; padding-left: 24px;">
            @foreach ($monthlyBookings as $month => $count)
                <li>{{ \Carbon\Carbon::create()->month($month)->format('F') }}: {{ $count }}</li>
            @endforeach
        </ul>
    </div>

    <div style="margin-top: 24px;">
        <h2 style="font-size: 18px; font-weight: bold; margin-bottom: 8px;">Monthly Sales (Accepted Bookings)</h2>
        <ul style="list-style-type: disc; padding-left: 24px;">
            @foreach ($monthlySales as $month => $sales)
                <li>{{ \Carbon\Carbon::create()->month($month)->format('F') }}: PHP {{ number_format($sales, 2) }}</li>
            @endforeach
        </ul>
    </div>
    
   
</body>