<!-- @extends('layout.layout') -->



<body style="font-family: sans-serif; background-color: white; color: #333; padding: 20px;">
    <div style="max-width: 800px; margin: 0 auto;">
        <h1 style="font-size: 24px; font-weight: bold; text-align: center; margin-bottom: 20px;">{{ $title }}</h1>
        <h2 style="font-size: 18px; text-align: center; margin-bottom: 20px;">{{ $date }}</h2>

        <section style="margin-bottom: 30px;">
            <h2 style="font-size: 18px; font-weight: bold;">Total Members</h2>
            <p>Total Members: {{ $totalMembers }}</p>
            <p>Active Members: {{ $activeMembers }}</p>
            <p>Inactive Members: {{ $inactiveMembers }}</p>
        </section>

        <section style="margin-bottom: 30px;">
    <h2 style="font-size: 18px; font-weight: bold; margin-bottom: 10px;">Members by Type</h2>
    <div style="font-size: 14px; line-height: 1.6;">
        @foreach ($membersByType as $type)
            <p><strong>{{ $type->type }}:</strong> {{ $type->members_count }}</p>
        @endforeach
    </div>
</section>

        <section style="margin-bottom: 30px;">
            <h2 style="font-size: 18px; font-weight: bold;">Vehicles Owned by Members</h2>
            <p>Total Vehicles Owned: {{ $totalVehicles }}</p>
        </section>

        <section style="margin-bottom: 30px;">
            <h2 style="font-size: 18px; font-weight: bold;">Total Amount Per Dues</h2>
            <table style="width: 100%; border-collapse: collapse; border: 1px solid #ccc;">
                <thead>
                    <tr>
                        <th style="padding: 6px; text-align: center; border: 1px solid #ccc; font-size: 10px;">Month</th>
                        <th style="padding: 6px; text-align: center; border: 1px solid #ccc; font-size: 10px;">No. of Members Paid</th>
                        <th style="padding: 6px; text-align: center; border: 1px solid #ccc; font-size: 10px;">Total Amount Collected</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($tableData as $month => $data)
                        <tr>
                            <td style="padding: 2px; border-top: 1px solid #ccc; font-size: 9px;">{{ $month }}</td>
                            <td style="padding: 2px; border-top: 1px solid #ccc; font-size: 9px;">{{ $data['members_paid'] }}</td>
                            <td style="padding: 2px; border-top: 1px solid #ccc; font-size: 9px;">PHP {{ number_format($data['total_paid'], 2) }}</td>
                        </tr>
                    @endforeach
                </tbody>
                <tfoot>
                    <tr>
                        <th style="padding: 8px; text-align: left; border: 1px solid #ccc;">Overall Total</th>
                        <th style="padding: 8px; border: 1px solid #ccc;">{{ $overallTotalMembers }}</th>
                        <th style="padding: 8px; border: 1px solid #ccc;">PHP {{ number_format($overallTotalAmount, 2) }}</th>
                    </tr>
                </tfoot>
            </table>
        </section>

        <section style="margin-bottom: 30px;">
    <table style="width: 100%; border-collapse: collapse;">
        <tr>
            <!-- First Table (Top Drivers with Most Schedules) -->
            <td style="width: 50%; text-align: center; vertical-align: top;">
                <h2 style="font-size: 10px; font-weight: bold;">Top 5 Drivers with the Most Schedules</h2>
                <table style="border-collapse: collapse; width: 100%;">
                    <thead>
                        <tr>
                            <th style="padding: 6px; text-align: center; border: 1px solid #ccc; font-size: 10px;">Driver Name</th>
                            <th style="padding: 6px; text-align: center; border: 1px solid #ccc; font-size: 10px;">Total Schedules</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($topDrivers as $driver)
                            <tr>
                                <td style="padding: 6px; font-size: 9px;">{{ $driver->member->name }} {{ $driver->member->last_name }}</td>
                                <td style="padding: 6px; font-size: 9px;">{{ $driver->schedule->count() }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </td>

            <!-- Second Table (Top Drivers with Most Paid Monthly Dues) -->
            <td style="width: 50%; text-align: center; vertical-align: top;">
                <h2 style="font-size: 10px; font-weight: bold;">Top 5 Drivers with the Most Paid Monthly Dues</h2>
                <table style="border-collapse: collapse; width: 100%;">
                    <thead>
                        <tr>
                            <th style="padding: 6px; text-align: center; border: 1px solid #ccc; font-size: 10px;">Driver Name</th>
                            <th style="padding: 6px; text-align: center; border: 1px solid #ccc; font-size: 10px;">Total Paid Monthly Dues</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($topPaid as $payment)
                            <tr>
                                <td style="padding: 6px; font-size: 9px;">{{ $payment->member->name }} {{ $payment->member->last_name }}</td>
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
                    <tr>
                        <th style="padding: 6px; text-align: center; border: 1px solid #ccc; font-size: 10px;">Name</th>
                        <th style="padding: 6px; text-align: center; border: 1px solid #ccc; font-size: 10px">Member Type</th>
                        <th style="padding: 6px; text-align: center; border: 1px solid #ccc; font-size: 10px">Total Schedule for This Year</th>
                        <th style="padding: 6px; text-align: center; border: 1px solid #ccc; font-size: 10px">No. of Accepted Schedules</th>
                        <th style="padding: 6px; text-align: center; border: 1px solid #ccc; font-size: 10px">No. of Not Accepted Schedules</th>
                        <th style="padding: 6px; text-align: center; border: 1px solid #ccc; font-size: 10px">Member Status</th>
                        <th style="padding: 6px; text-align: center; border: 1px solid #ccc; font-size: 10px">No. of Vehicles</th>
                        <th style="padding: 6px; text-align: center; border: 1px solid #ccc; font-size: 10px">Vehicles (Plate Numbers)</th>
                        <th style="padding: 6px; text-align: center; border: 1px solid #ccc; font-size: 10px">No. of Paid Payments</th>
                        <th style="padding: 6px; text-align: center; border: 1px solid #ccc; font-size: 10px">Current Month Payment Status</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($members as $member)
                     @if ($member->member_status == 'inactive')
                        <tr style="background-color: #F88379;">
                    @else
                        <tr style="background-color: #AFE1AF;">
                    @endif
                            <td style="padding: 2px; border-top: 1px solid #ccc; font-size: 9px; width: 20%;">{{ $member->name }}</td>
                            <td style="padding: 2px; border-top: 1px solid #ccc; font-size: 9px;">{{ $member->type }}</td>
                            <td style="padding: 2px; border-top: 1px solid #ccc; font-size: 9px;">
                                @if ($member->driver->count())
                                    {{ $member->driver->first()->schedule()->whereYear('created_at', $currentYear)->count() }}
                                @else
                                    0
                                @endif
                            </td>
                            <td style="padding: 2px; border-top: 1px solid #ccc; font-size: 9px;">
                                @if ($member->driver->count())
                                    {{ $member->driver->first()->schedule()->where('driver_status', 'Accepted')->count() }}
                                @elseif($member->type == 'Owner')
                                    Not Applicable
                                @else
                                    0
                                @endif
                            </td>
                            <td style="padding: 2px; border-top: 1px solid #ccc; font-size: 9px;">
                                @if ($member->driver->count())
                                    {{ $member->driver->first()->schedule()->where('driver_status', 'Cancelled')->count() }}
                                @elseif($member->type == 'Owner')
                                    Not Applicable
                                @else
                                   0
                                @endif
                            </td>
                            <td style="padding: 2px; border-top: 1px solid #ccc; font-size: 9px;">{{ $member->member_status }}</td>
                            <td style="padding: 2px; border-top: 1px solid #ccc; font-size: 9px;">
                                @if ($member->vehicle->isEmpty() || !$member->owner)
                                    Not Applicable
                                @else
                                    {{ $member->vehicle->count() }}
                                @endif
                            </td>
                            <td style="padding: 2px; border-top: 1px solid #ccc; font-size: 9px;">
                                @if ($member->vehicle->isEmpty() || !$member->owner)
                                    Not Applicable
                                @else
                                    @foreach ($member->vehicle as $vehicle)
                                        {{ $vehicle->plate_num }}@if (!$loop->last), @endif
                                    @endforeach
                                @endif
                            </td>
                            <td style="padding: 2px; border-top: 1px solid #ccc; font-size: 9px;">{{ $member->paid_payments_count }}</td>
                            <td style="padding: 2px; border-top: 1px solid #ccc; font-size: 9px;">{{ $member->current_month_status }}</td>
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