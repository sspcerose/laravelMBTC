@extends('layout.layout')

<body style="padding: 32px;">
    <h1 style="text-align: center; font-weight: bold; font-size: 24px; text-transform: uppercase;">
        Manibela ng Buhay Transport Cooperative
    </h1>
    <h2 style="text-align: center; font-size: 18px;">
        Caanawan, San Jose City, Nueva Ecija
    </h2>

    <h2 style="text-align: center; font-size: 14px; font-weight: 600; margin-top: 18px;">
        RESERVATION SUMMARY REPORT
    </h2>
    <p style="color: gray; margin-top: 16px; font-size: 10px;">
        Date: {{ \Carbon\Carbon::parse($date)->format('F d, Y') }}
    </p>
    <p style="color: #4A5568; font-size: 10px;">
        Filtered from: {{ \Carbon\Carbon::parse($filterStartDate )->format('F d, Y') ?? 'N/A' }} 
        to {{ \Carbon\Carbon::parse($filterEndDate )->format('F d, Y') ?? 'N/A' }}
    </p>
    
    <div style="margin-top: 24px;">
        <h2 style="font-size: 12px; font-weight: 600;">Booking Statistics</h2>

        <div style="display: flex; justify-content: space-between; width: 100%;">
    <div style="width: 42%;">
        <span style="font-size: 12px; font-weight: 600;">Total no. of Bookings:</span>
        <span style="font-size: 12px; font-weight: 600; margin-left: 20px;">{{ $totalYearlyBookings }}</span>
    </div>

    <div style="width: 54%;">
        <table style="width: 100%; border-collapse: collapse; font-size: 12px;">
            <thead>
                <tr>
                    <!-- <th style="border: 1px solid black; padding: 8px; text-align: center;">Statistic</th>
                    <th style="border: 1px solid black; padding: 8px; text-align: center;">Value</th> -->
                </tr>
            </thead>
            <tbody>
                <td style="border: 1px solid black; padding: 2px 2px 2px 9px; font-weight: 600; width: 70%">Accepted:</td>
                    <td style="border: 1px solid black; padding: 2px; text-align: center; width: 30%">{{ $acceptedCount }}</td>
                </tr>
                <tr>
                    <td style="border: 1px solid black; padding: 2px 2px 2px 9px; font-weight: 600;">Rejected:</td>
                    <td style="border: 1px solid black; padding: 2px; text-align: center">{{ $rejectedCount }}</td>
                </tr>
                <tr>
                    <td style="border: 1px solid black; padding: 2px 2px 2px 9px; font-weight: 600;">Customer-Cancelled:</td>
                    <td style="border: 1px solid black; padding: 2px; text-align: center">{{ $cancelledCount }}</td>
                </tr>
            </tbody>
        </table>
    </div>
</div>

    <div style="margin-top: 24px;">
        <h2 style="font-size: 12px; font-weight: 600;">Revenue</h2>
        <table style="width: 100%; border-collapse: collapse; font-size: 12px; border: 1px solid black;">
            <thead>
                <tr>
                    <!-- <th style="border: 1px solid black; padding: 8px; text-align: center;">Revenue Type</th>
                    <th style="border: 1px solid black; padding: 8px; text-align: center;">Amount</th> -->
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td style="border-top: 1px solid black; padding: 2px 2px 2px 9px; font-weight: 600; width: 69%">Total Revenue: </td>
                    <td style="border-top: 1px solid black; padding: 2px; text-align: left; width: 31%">PHP {{ number_format($totalRevenue, 2) }}</td>
                </tr>
                <tr>
                    <td style="border-top: 1px solid black; padding: 2px 2px 2px 9px; font-weight: 600;">From Cancelled Bookings:</td>
                    <td style="border-top: 1px solid black; padding: 2px; text-align: left">PHP {{ number_format($cancelledRevenue, 2) }}</td>
                </tr>
                <tr>
                    <td style="border-top: 1px solid black; padding: 2px 2px 2px 9px; font-weight: 600;">Overall Total:</td>
                    <td style="border-top: 1px solid black; padding: 2px; text-align: left; font-weight: 600;">PHP {{ number_format($overallTotalRevenue, 2) }}</td>
                </tr>
            </tbody>
        </table>
    </div>

    <div style="margin-top: 24px;">
        <h2 style="font-size: 12px; font-weight: 600;">Monthly Revenue</h2>
        <table style="width: 100%; border-collapse: collapse; font-size: 12px; table-layout: fixed;">
        <thead>
            <tr>
                <th style="border: 1px solid black; padding: 2px; font-weight: 600; width: 33%;">Month</th>
                <th style="border: 1px solid black; padding: 2px; font-weight: 600; width: 33%;">Total Reservations</th>
                <th style="border: 1px solid black; padding: 2px; font-weight: 600; width: 33%;">Revenue</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($monthlyBookings as $month => $count)
                <tr>
                    <td style="border: 1px solid black; padding: 2px;">{{ \Carbon\Carbon::create()->month($month)->format('F') }}</td>
                    <td style="border: 1px solid black; padding: 2px; text-align: center;">{{ $count }}</td>
                    <td style="border: 1px solid black; padding: 2px;">PHP {{ number_format($monthlySales[$month] ?? 0, 2) }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
    </div>

    <div style="margin-top: 24px;">
    <h2 style="font-size: 12px; font-weight: 600;">Reservation Highlights</h2>

    <div style="display: flex; justify-content: space-between; width: 100%; margin-top: 1rem;">
        <!-- Top Destinations Section -->
        <div style="width: 42%;">
            <h3 style="font-size: 12px; font-weight: 600; margin-bottom: 8px;">Top Destinations</h3>
        
            <ol style="list-style-type: decimal; padding-left: 1rem; margin: 0;">
                @foreach ($topDestinations as $destination)
                    <li style="margin-bottom: 5px; font-size: 12px;">
                        {{ $destination->destination }} – {{ $destination->count }} Bookings
                    </li>
                @endforeach
            </ol>
        </div>

        <!-- Peak Days Section -->
        <div style="width: 54%;">
            <h3 style="font-size: 12px; font-weight: 600; margin-bottom: 8px;">Peak Days</h3>
            <table style="width: 100%; border-collapse: collapse; border: 1px solid black; font-size: 12px">
                <thead>
                <tr style="font-weight: bold;">
                        <th style="border: 1px solid black; padding: 2px; font-weight: 600; width: 50%;">Date</th>
                        <th style="border: 1px solid black; padding: 2px; font-weight: 600; width: 50%;">Total Reservations</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($topStartDates as $startDate)
                        <tr>
                            <td style="padding: 2px; border-top: 1px solid black; text-align: left; width: 50%;">{{\Carbon\Carbon::parse($startDate->start_date)->format('F d, Y') }}</td>
                            <td style="padding: 2px; border-top: 1px solid black; text-align: center; width: 50%;">{{ $startDate->booking_count }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>



<div style="display: flex; justify-content: space-between; width: 100%; margin-top: 1rem;">
        <!-- Top Destinations Section -->
        <div style="width: 42%;">
    <h2 style="font-size: 12px; font-weight: 600; margin-bottom: 8px;">Top Customers</h2>
    <ol style="list-style-type: decimal; padding-left: 1rem; margin-top: 0.5rem;">
        @foreach ($topCustomers as $customer)
            <li style="margin-bottom: 5px; font-size: 12px;">
                {{ $customer['name'] }} {{ $customer['last_name'] }} – {{ $customer['booking_count'] }} bookings
            </li>
        @endforeach
    </ol>
</div>

<div style="width: 54%;">
    <h3 style="font-size: 12px; font-weight: 600; margin-bottom: 8px;">Common Pickup Locations</h3>
    <table  style="width: 100%; border-collapse: collapse; border: 1px solid black; font-size: 12px">
        <thead>
            <tr style="font-weight: 600;">
                <th style="border: 1px solid black; padding: 2px; font-weight: 600; width: 50%;">Barangay</th>
                <th style="border: 1px solid black; padding: 2px; font-weight: 600; width: 50%;">Pickup Count</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($topPickUpLocations as $barangay)
                <tr>
                    <td style="padding: 2px; border-top: 1px solid black; text-align: left; width: 50%;">{{ $barangay->location }}</td>
                    <td style="padding: 2px; border-top: 1px solid black; text-align: center; width: 50%;">{{ $barangay->booking_count }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>

    <script>
        window.onload = function() {
            window.print();
        };
    </script>
</body>
