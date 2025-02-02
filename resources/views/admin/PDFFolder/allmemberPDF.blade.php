<!-- @extends('layout.layout') -->
<!DOCTYPE html>
 <html lang="en">
 <head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <style>
   @media print {
        tr.inactive {
            background-color: #F88379 !important; /* Light Red */
        }
        tr.active {
            background-color: #AFE1AF !important; /* Light Green */
        }
    }

    /* Optional: Ensure colors show properly on screen too */
    tr.inactive {
        background-color: #F88379; /* Light Red */
    }
    tr.active {
        background-color: #AFE1AF; /* Light Green */
    }
</style>
 </head>


 <body style="padding: 32px;">
    <h1 style="text-align: center; font-weight: bold; font-size: 24px; text-transform: uppercase;">
        Manibela ng Buhay Transport Cooperative
    </h1>
    <h2 style="text-align: center; font-size: 18px;">
        Caanawan, San Jose City, Nueva Ecija
    </h2>

    <h2 style="text-align: center; font-size: 14px; font-weight: 600; margin-top: 18px;">
        MEMBER SUMMARY REPORT
    </h2>
    <p style="color: gray; margin-top: 16px; font-size: 10px;">
        Date: {{ \Carbon\Carbon::parse($date)->format('F d, Y') }}
    </p>


    <div style="margin-top: 24px;">
        <h2 style="font-size: 12px; font-weight: 600;">Member Statistics</h2>

        <div style="display: flex; justify-content: space-between; width: 100%;">
            <div style="width: 42%;">
                <span style="font-size: 12px; font-weight: 600;">Total no. of Members:</span>
                <span style="font-size: 12px; font-weight: 600; margin-left: 20px;">{{ $totalMembers }}</span>
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
                    <td style="border: 1px solid black; padding: 2px 2px 2px 9px; font-weight: 600; width: 70%">Active Members</td>
                        <td style="border: 1px solid black; padding: 2px; text-align: center; width: 30%">{{ $allactiveMembers }}</td>
                    </tr>
                    <tr>
                        <td style="border: 1px solid black; padding: 2px 2px 2px 9px; font-weight: 600;">Inactive Members</td>
                        <td style="border: 1px solid black; padding: 2px; text-align: center">{{ $allinactiveMembers }}</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>


    <div style="margin-top: 24px;">
        <h2 style="font-size: 12px; font-weight: 600;">Members by Type</h2>
        <table style="width: 100%; border-collapse: collapse; font-size: 12px; border: 1px solid black;">
            <thead>
                <tr>
                    <th style="border-top: 1px solid black; padding: 8px; text-align: center;"></th>
                    <th style="border-top: 1px solid black; padding: 8px; text-align: center;"></th>
                    <th style="border: 1px solid black; padding: 8px; text-align: center;">Active</th>
                </tr>
            </thead>
            <tbody>
    @foreach ($membersByType as $type)
        <tr>
            <td style="border-top: 1px solid black; padding: 2px 2px 2px 9px; font-weight: 600; width: 33%">
                <p><strong>{{ $type->type }}:</strong> </p>
            </td>
            <td style="border: 1px solid black; padding: 2px; text-align: center; width: 33%">{{ $type->total_count }}</td>
            <td style="border: 1px solid black; padding: 2px; text-align: center; width: 33%">{{ $type->active_count }}</td>
        </tr>
    @endforeach
</tbody>
        </table>
    </div>

    <div style="margin-top: 10x;">
                <span style="font-size: 12px; font-weight: 600;">Total Vehicles Owned by Members:</span>
                <span style="font-size: 12px; font-weight: 600; margin-left: 20px;">{{ $totalVehicles }}</span>
            </div>


    <p style="color: #4A5568; font-size: 10px; margin-top: 15px;">
        Filtered from: {{ \Carbon\Carbon::parse($filterStartDate )->format('F d, Y') ?? 'N/A' }} 
        to {{ \Carbon\Carbon::parse($filterEndDate )->format('F d, Y') ?? 'N/A' }}
    </p>
    
        <section style="margin-bottom: 30px;">
        <h2 style="font-size: 12px; font-weight: 600;">Monthly Dues</h2>
            <table  style="width: 100%; border-collapse: collapse; font-size: 12px; table-layout: fixed;">
                <thead>
                    <tr>
                        <th style="border: 1px solid black; padding: 2px; font-weight: 600; width: 33%;">Month</th>
                        <th style="border: 1px solid black; padding: 2px; font-weight: 600; width: 33%;">No. of Members Paid</th>
                        <th style="border: 1px solid black; padding: 2px; font-weight: 600; width: 33%;">Total Amount Collected</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($tableData as $month => $data)
                        <tr>
                            <td style="border: 1px solid black; padding: 2px;">{{ $month }}</td>
                            <td style="border: 1px solid black; padding: 2px; text-align: center;">{{ $data['members_paid'] }}</td>
                            <td style="border: 1px solid black; padding: 2px;">PHP {{ number_format($data['total_paid'], 2) }}</td>
                        </tr>
                    @endforeach
                </tbody>
                <tfoot>
                    <tr>
                    <th style="border: 1px solid black; border-right: none; padding: 2px; font-weight: 600; width: 33%; text-align: left">Overall Total:</th>
                        <th style="border: 1px solid black; border-right: none; border-left: none;padding: 2px; font-weight: 600; width: 33%;"></th>
                        <th style="border: 1px solid black; padding: 2px; font-weight: 600; width: 33%; text-align: left">PHP {{ number_format($overallTotalAmount, 2) }}</th>
                    </tr>
                </tfoot>
            </table>
        </section>

        <section style="margin-bottom: 30px;">
    <table style="width: 100%; border-collapse: collapse;">
        <tr>
            <!-- First Table (Top Drivers with Most Schedules) -->
            <td style="width: 50%; text-align: center; vertical-align: top; padding-right: 10px;">
                <h2 style="font-size: 12px; font-weight: 600;">Top Drivers with the Most Schedules</h2>
                <table style="border-collapse: collapse; width: 100%;">
                    <thead>
                        <tr>
                            <th style="padding: 6px; text-align: center; border: 1px solid black; font-size: 10px;">Driver Name</th>
                            <th style="padding: 6px; text-align: center; border: 1px solid black; font-size: 10px;">Total Schedules</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($topDrivers as $driver)
                            <tr>
                                <td style="padding: 6px; font-size: 9px; text-align: left">{{ $driver->member->name }} {{ $driver->member->last_name }}</td>
                                <td style="padding: 6px; font-size: 9px;">{{ $driver->schedule->count() }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </td>

            <!-- Second Table (Top Drivers with Most Paid Monthly Dues) -->
            <td style="width: 50%; text-align: center; vertical-align: top; padding-left: 10px;">
                <h2 style="font-size: 12px; font-weight: 600;">Top Drivers with the Most Paid Monthly Dues</h2>
                <table style="border-collapse: collapse; width: 100%;">
                    <thead>
                        <tr>
                            <th style="padding: 6px; text-align: center; border: 1px solid black; font-size: 10px;">Driver Name</th>
                            <th style="padding: 6px; text-align: center; border: 1px solid black; font-size: 10px;">Total Paid Monthly Dues</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($topPaid as $payment)
                            <tr>
                                <td style="padding: 6px; font-size: 9px; text-align: left;">{{ $payment->member->name }} {{ $payment->member->last_name }}</td>
                                <td style="padding: 6px; font-size: 9px;">{{ $payment->paid_count }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </td>
        </tr>
    </table>
</section>


        <section style="margin-bottom: 30px;">
            <h2 style="font-size: 18px; font-weight: bold;">Members Information</h2>
            Legend: 
    <span style="display: inline-block; width: 10px; height: 10px; background-color: #AFE1AF; margin-right: 5px;"></span> Active
    / 
    <span style="display: inline-block; width: 10px; height: 10px; background-color: #F88379; margin-right: 5px;"></span> Inactive

            </span>
            <table style="width: 100%; border-collapse: collapse;">
    <thead>
    <table style="border-collapse: collapse; width: 100%;">
    <tr>
        <th rowspan="2" style="padding: 6px; text-align: center; border: 1px solid black; font-size: 10px;">Name</th>
        <th rowspan="2" style="padding: 6px; text-align: center; border: 1px solid black; font-size: 10px;">Member Type</th>
        <th colspan="3" style="padding: 6px; text-align: center; border: 1px solid black; font-size: 10px;">Schedule</th>
        <th colspan="2" style="padding: 6px; text-align: center; border: 1px solid black; font-size: 10px;">Vehicles</th>
        <th colspan="3" style="padding: 6px; text-align: center; border: 1px solid black; font-size: 10px;">Monthly Dues</th>
    </tr>
    <tr>
        <th style="padding: 6px; text-align: center; border: 1px solid black; font-size: 8px;">Total</th>
        <th style="padding: 6px; text-align: center; border: 1px solid black; font-size: 8px;">Accepted</th>
        <th style="padding: 6px; text-align: center; border: 1px solid black; font-size: 8px;">Declined</th>
        <th style="padding: 6px; text-align: center; border: 1px solid black; font-size: 8px;">Total</th>
        <th style="padding: 6px; text-align: center; border: 1px solid black; font-size: 8px;">Plate No.</th>
        <th style="padding: 6px; text-align: center; border: 1px solid black; font-size: 8px;">Total Paid</th>
        <th style="padding: 6px; text-align: center; border: 1px solid black; font-size: 8px;">Total Unpaid</th>
        <th style="padding: 6px; text-align: center; border: 1px solid black; font-size: 8px;">Current Month</th>
    </tr>
    </thead>
    <tbody>
        @foreach ($members as $member)
        <tr style="background-color: {{ $member->member_status == 'inactive' ? '#F88379' : '#AFE1AF' }};">
            <td style="padding: 2px; border-top: 1px solid black; font-size: 9px; width: 20%;">{{ $member->name }}</td>
            <td style="padding: 2px; border-top: 1px solid black; font-size: 9px;">{{ $member->type }}</td>
            <td style="padding: 2px; border-top: 1px solid black; font-size: 9px; text-align: center;">
            @if ($member->driver->count())
                {{ 
                    $member->driver->first()->schedule()
                        ->where('cust_status', '!=', 'inactive') // Add the cust_status filter
                        ->whereHas('booking', function($query) use ($startDate, $endDate) {
                            // Filter bookings based on start_date between startDate and endDate
                            $query->whereBetween('start_date', [$startDate, $endDate]);
                        })
                        ->count() 
                }}
            @elseif($member->type == 'Owner')
                Not Applicable
            @else
                0
            @endif
            </td>
            <td style="padding: 2px; border-top: 1px solid black; font-size: 9px; text-align: center;">
            @if ($member->driver->count())
                {{ 
                    $member->driver->first()->schedule()
                        ->where('driver_status', 'accepted')
                        ->where('cust_status', '!=', 'inactive') // Add the cust_status filter
                        ->whereHas('booking', function($query) use ($startDate, $endDate) {
                            // Filter bookings based on start_date between startDate and endDate
                            $query->whereBetween('start_date', [$startDate, $endDate]);
                        })
                        ->count() 
                }}
            @elseif($member->type == 'Owner')
                Not Applicable
            @else
                0
            @endif
            </td>
            <td style="padding: 2px; border-top: 1px solid black; font-size: 9px; text-align: center;">
            @if ($member->driver->count())
                {{ 
                    $member->driver->first()->schedule()
                        ->where('driver_status', 'cancelled')
                        ->where('cust_status', '!=', 'inactive') // Add the cust_status filter
                        ->whereHas('booking', function($query) use ($startDate, $endDate) {
                            // Filter bookings based on start_date between startDate and endDate
                            $query->whereBetween('start_date', [$startDate, $endDate]);
                        })
                        ->count() 
                }}
            @elseif($member->type == 'Owner')
                Not Applicable
            @else
                0
            @endif
            </td>
            <!-- <td style="padding: 2px; border-top: 1px solid black; font-size: 9px;">{{ $member->member_status }}</td> -->
            <td style="padding: 2px; border-top: 1px solid black; font-size: 9px; text-align: center">
                @if ($member->vehicle->isEmpty() || !$member->owner)
                    Not Applicable
                @else
                    {{ $member->vehicle->count() }}
                @endif
            </td>
            <td style="padding: 2px; border-top: 1px solid black; font-size: 9px;">
                @if ($member->vehicle->isEmpty() || !$member->owner)
                    Not Applicable
                @else
                    @foreach ($member->vehicle as $vehicle)
                        {{ $vehicle->plate_num }}@if (!$loop->last), @endif
                    @endforeach
                @endif
            </td>
            <td style="padding: 2px; border-top: 1px solid black; font-size: 9px; text-align: center">{{ $member->paid_payments_count }}</td>
            <td style="padding: 2px; border-top: 1px solid black; font-size: 9px; text-align: center">{{ $member->unpaid_payments_count }}</td>
            <td style="padding: 2px; border-top: 1px solid black; font-size: 9px;">{{ $member->current_month_status }}</td>
        </tr>
        @endforeach
    </tbody>
</table>

        </section>

        <section style="margin-top: 40px; text-align: center; font-size: 12px; color: #666;">
            <p>Summary: This report covers the total number of members, their types, the number of trips assigned to each driver, total vehicles owned by members, total dues, and monthly dues status. It also includes the member's schedule details and the status of their vehicles.</p>
        </section>
    </div>
</body>
</html>