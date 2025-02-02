

<body style="padding: 32px; font-family: Arial, sans-serif;">
    <div style="text-align: center;">
        <h1 style="font-size: 24px; font-weight: bold; text-transform: uppercase; margin-bottom: 10px;">
            Manibela ng Buhay Transport Cooperative
        </h1>
        <h2 style="font-size: 18px; margin-bottom: 10px;">
            Caanawan, San Jose City, Nueva Ecija
        </h2>
        <h3 style="font-size: 14px; font-weight: 600; margin-top: 18px;">
            RESERVATION SUMMARY REPORT
        </h3>
        <p style="color: gray; font-size: 10px; margin-top: 16px; text-align: left; ">
            Date: {{ \Carbon\Carbon::parse($date)->format('F d, Y') }}
        </p>
        <p style="font-size: 10px; text-align: left;">
            Filtered from: {{ \Carbon\Carbon::parse($filterStartDate )->format('F d, Y') ?? 'N/A' }} 
            to {{ \Carbon\Carbon::parse($filterEndDate )->format('F d, Y') ?? 'N/A' }}
        </p>
    </div>

    <div style="margin-top: 24px;">
    <h2 style="font-size: 12px; font-weight: 600;">Booking Statistics</h2>
    <div style="display: flex; justify-content: space-between; margin-bottom: 16px;">
        <div style="width: 30%;">
            <p style="font-size: 12px; font-weight: 600;">Total no. of Bookings: {{ $totalYearlyBookings }}</p>
            <!-- <p style="font-size: 12px; font-weight: 600;">{{ $totalYearlyBookings }}</p> -->
        </div>
        <div style="width: 50%; border: 1px solid #000;">
            <table style="width: 100%; border-collapse: collapse; font-size: 12px;">
                <tbody>
                    <tr>
                        <td style="border: 1px solid black; padding: 4px 8px; font-weight: 600; width: 60%;">Accepted:</td>
                        <td style="border: 1px solid black; padding: 4px 8px; text-align: center; width: 40%;">{{ $acceptedCount }}</td>
                    </tr>
                    <tr>
                        <td style="border: 1px solid black; padding: 4px 8px; font-weight: 600;">Rejected:</td>
                        <td style="border: 1px solid black; padding: 4px 8px; text-align: center;">{{ $rejectedCount }}</td>
                    </tr>
                    <tr>
                        <td style="border: 1px solid black; padding: 4px 8px; font-weight: 600;">Customer-Cancelled:</td>
                        <td style="border: 1px solid black; padding: 4px 8px; text-align: center;">{{ $cancelledCount }}</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>

    <div style="margin-top: 24px;">
        <h2 style="font-size: 12px; font-weight: 600;">Revenue</h2>
        <table style="width: 100%; border-collapse: collapse; font-size: 12px; border: 1px solid black;">
            <thead>
                <tr>
                    <th style="border: 1px solid black; padding: 8px; text-align: left;">Revenue Type</th>
                    <th style="border: 1px solid black; padding: 8px; text-align: left;">Amount</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td style="border: 1px solid black; padding: 8px; font-weight: 600;">Total Revenue:</td>
                    <td style="border: 1px solid black; padding: 8px;">PHP {{ number_format($totalRevenue, 2) }}</td>
                </tr>
                <tr>
                    <td style="border: 1px solid black; padding: 8px; font-weight: 600;">From Cancelled Bookings:</td>
                    <td style="border: 1px solid black; padding: 8px;">PHP {{ number_format($cancelledRevenue, 2) }}</td>
                </tr>
                <tr>
                    <td style="border: 1px solid black; padding: 8px; font-weight: 600;">Overall Total:</td>
                    <td style="border: 1px solid black; padding: 8px; font-weight: 600;">PHP {{ number_format($overallTotalRevenue, 2) }}</td>
                </tr>
            </tbody>
        </table>
    </div>

    <div style="margin-top: 24px;">
        <h2 style="font-size: 12px; font-weight: 600;">Monthly Revenue</h2>
        <table style="width: 100%; border-collapse: collapse; font-size: 12px;">
            <thead>
                <tr>
                    <th style="border: 1px solid black; padding: 8px;">Month</th>
                    <th style="border: 1px solid black; padding: 8px;">Total Reservations</th>
                    <th style="border: 1px solid black; padding: 8px;">Revenue</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($monthlyBookings as $month => $count)
                    <tr>
                        <td style="border: 1px solid black; padding: 8px;">{{ \Carbon\Carbon::create()->month($month)->format('F') }}</td>
                        <td style="border: 1px solid black; padding: 8px; text-align: center;">{{ $count }}</td>
                        <td style="border: 1px solid black; padding: 8px;">PHP {{ number_format($monthlySales[$month] ?? 0, 2) }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div style="margin-top: 24px;">
        <h2 style="font-size: 12px; font-weight: 600;">Reservation Highlights</h2>

        <div style="display: flex; justify-content: space-between; width: 100%; margin-top: 1rem;">
            <div style="width: 45%;">
                <h3 style="font-size: 12px; font-weight: 600; margin-bottom: 8px;">Top Destinations</h3>
                <ol style="list-style-type: decimal; padding-left: 1rem;">
                    @foreach ($topDestinations as $destination)
                        <li style="font-size: 12px; margin-bottom: 5px;">
                            {{ $destination->destination }} – {{ $destination->count }} Bookings
                        </li>
                    @endforeach
                </ol>
            </div>

            <div style="width: 50%;">
                <h3 style="font-size: 12px; font-weight: 600; margin-bottom: 8px;">Peak Days</h3>
                <table style="width: 100%; border-collapse: collapse; border: 1px solid black; font-size: 12px;">
                    <thead>
                        <tr>
                            <th style="border: 1px solid black; padding: 8px;">Date</th>
                            <th style="border: 1px solid black; padding: 8px;">Total Reservations</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($topStartDates as $startDate)
                            <tr>
                                <td style="border: 1px solid black; padding: 8px;">{{ \Carbon\Carbon::parse($startDate->start_date)->format('F d, Y') }}</td>
                                <td style="border: 1px solid black; padding: 8px; text-align: center;">{{ $startDate->booking_count }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div style="display: flex; justify-content: space-between; width: 100%; margin-top: 1rem;">
        <div style="width: 45%;">
            <h3 style="font-size: 12px; font-weight: 600;">Top Customers</h3>
            <ol style="list-style-type: decimal; padding-left: 1rem;">
                @foreach ($topCustomers as $customer)
                    <li style="font-size: 12px; margin-bottom: 5px;">
                        {{ $customer['name'] }} {{ $customer['last_name'] }} – {{ $customer['booking_count'] }} bookings
                    </li>
                @endforeach
            </ol>
        </div>

        <div style="width: 50%;">
            <h3 style="font-size: 12px; font-weight: 600;">Common Pickup Locations</h3>
            <table style="width: 100%; border-collapse: collapse; border: 1px solid black; font-size: 12px;">
                <thead>
                    <tr>
                        <th style="border: 1px solid black; padding: 8px;">Barangay</th>
                        <th style="border: 1px solid black; padding: 8px; text-align: center;">Total Bookings</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($topPickUpLocations as $barangay)
                        <tr>
                            <td style="border: 1px solid black; padding: 8px;">{{ $barangay->location }}</td>
                            <td style="border: 1px solid black; padding: 8px; text-align: center">{{ $barangay->booking_count }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</body>
