<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

use App\Models\Booking;
use App\Models\Member;
use App\Models\qrcode;
use App\Models\Tariff;
use App\Models\Vehicle;
use App\Models\Owner;
use App\Models\Dues;
use App\Models\Payment;
use App\Models\Schedule;
use App\Models\Driver;
use App\Models\User;
use App\Models\Admin;

class pdfController extends Controller
{

public function generatePDF()
{
    $currentMonth = Carbon::now()->month;
    $currentYear = Carbon::now()->year;

    $totalMonthlyBookings = Booking::whereMonth('start_date', $currentMonth)->count();

    $totalYearlyBookings = Booking::whereYear('start_date', $currentYear)->count();

    $totalRevenue = Booking::where('status', '!=', 'Cancelled')
                            ->where('status', '!=', 'rejected')
                            ->sum('price');

    $cancelledRevenue = Booking::where('status', 'Cancelled')
        ->selectRaw('SUM(price - remaining) as revenue')
        ->value('revenue');

    $cancelledCount = Booking::where('status', 'Cancelled')->count();

    $topDestinations = Booking::select('destination', Booking::raw('COUNT(*) as count'))
        ->groupBy('destination')
        ->orderByDesc('count')
        ->limit(5)
        ->get();
        
    $monthlyBookings = Booking::selectRaw('MONTH(start_date) as month, COUNT(*) as count')
        ->whereYear('start_date', $currentYear)
        ->groupByRaw('MONTH(start_date)')
        ->pluck('count', 'month');

    $monthlySales = Booking::where('status', 'Accepted')
        ->selectRaw('MONTH(start_date) as month, SUM(price) as total_sales')
        ->whereYear('start_date', $currentYear)
        ->groupByRaw('MONTH(start_date)')
        ->pluck('total_sales', 'month');

    

    $data = [
        'title' => 'MANIBELA NG BUHAY TRANSPORT COOPERATIVE',
        'date' => date('m/d/Y'),
        'totalMonthlyBookings' => $totalMonthlyBookings,
        'totalYearlyBookings' => $totalYearlyBookings,
        'totalRevenue' => $totalRevenue,
        'cancelledRevenue' => $cancelledRevenue,
        'cancelledCount' => $cancelledCount,
        'topDestinations' => $topDestinations,
        'monthlyBookings' => $monthlyBookings,
        'monthlySales' => $monthlySales,
    ];


    $pdf = Pdf::loadView('admin.PDFFolder.bookingPDF', $data);
    return $pdf->download('invoice.pdf');
    
}
    public function printBooking()
    {
    $currentMonth = Carbon::now()->month;
    $currentYear = Carbon::now()->year;

    
    $totalMonthlyBookings = Booking::whereMonth('start_date', $currentMonth)->count();

    $totalYearlyBookings = Booking::whereYear('start_date', $currentYear)->count();

    $totalRevenue = Booking::where('status', '!=', 'Cancelled')
                            ->where('status', '!=', 'rejected')
                            ->sum('price');

    $cancelledRevenue = Booking::where('status', 'Cancelled')
        ->selectRaw('SUM(price - remaining) as revenue')
        ->value('revenue');

    $cancelledCount = Booking::where('status', 'Cancelled')->count();

    $topDestinations = Booking::select('destination', Booking::raw('COUNT(*) as count'))
        ->groupBy('destination')
        ->orderByDesc('count')
        ->limit(5)
        ->get();
        
    $monthlyBookings = Booking::selectRaw('MONTH(start_date) as month, COUNT(*) as count')
        ->whereYear('start_date', $currentYear)
        ->groupByRaw('MONTH(start_date)')
        ->pluck('count', 'month');

    $monthlySales = Booking::where('status', 'Accepted')
        ->selectRaw('MONTH(start_date) as month, SUM(price) as total_sales')
        ->whereYear('start_date', $currentYear)
        ->groupByRaw('MONTH(start_date)')
        ->pluck('total_sales', 'month');

    $data = [
        'title' => 'MANIBELA NG BUHAY TRANSPORT COOPERATIVE',
        'date' => date('m/d/Y'),
        'totalMonthlyBookings' => $totalMonthlyBookings,
        'totalYearlyBookings' => $totalYearlyBookings,
        'totalRevenue' => $totalRevenue,
        'cancelledRevenue' => $cancelledRevenue,
        'cancelledCount' => $cancelledCount,
        'topDestinations' => $topDestinations,
        'monthlyBookings' => $monthlyBookings,
        'monthlySales' => $monthlySales,
    ];

    // $pdf = Pdf::loadView('admin.PDFFolder.printBooking', $data);
    // // return $pdf->download('invoice.pdf');
    // return view('admin.booking.booking', $data);
    return view('admin.PDFFolder.printBooking', $data);
    // return $pdf->stream(); 
    }

public function generateallmemberPDF()
    {
        $currentMonth = now()->month;
        $currentYear = now()->year;


        $totalMembers = Member::count();
        $activeMembers = Member::where('member_status', 'active')->count();
        $inactiveMembers = Member::where('member_status', 'inactive')->count();
        $membersByType = Member::select('type')
        ->selectRaw('count(*) as members_count') 
        ->groupBy('type') 
        ->get();
        
        $tripsPerDriver = Driver::withCount('schedule')->get();

        $totalVehicles = Vehicle::count();

        $totalPaidDues = Payment::where('status', 'paid')
    ->join('dues', 'payments.dues_id', '=', 'dues.id')
    ->whereYear('payments.created_at', $currentYear)
    ->select(
        DB::raw('MONTH(payments.created_at) as month'),
        DB::raw('COUNT(DISTINCT payments.member_id) as members_paid'),
        DB::raw('SUM(dues.amount) as total_paid')
    )
    ->groupBy(DB::raw('MONTH(payments.created_at)'))
    ->get();

$allMonths = collect([
    'January', 'February', 'March',
    'April', 'May', 'June',
    'July', 'August', 'September',
    'October', 'November', 'December',
]);

$tableData = $allMonths->mapWithKeys(function ($monthName) {
    return [$monthName => ['members_paid' => 0, 'total_paid' => 0.00]];
});

foreach ($totalPaidDues as $item) {
    $monthName = \Carbon\Carbon::create()->month($item->month)->format('F');
    $tableData[$monthName] = [
        'members_paid' => $item->members_paid,
        'total_paid' => $item->total_paid,
    ];
}

$overallTotalMembers = $tableData->sum('members_paid');
$overallTotalAmount = $tableData->sum('total_paid');
    
   

$membersWithPaidDues = DB::table('payments')
    ->join('members', 'payments.member_id', '=', 'members.id')
    ->where('payments.status', 'paid')
    ->whereYear('payments.created_at', $currentYear)
    ->select('payments.member_id', 'members.name', 'members.last_name', DB::raw('count(DISTINCT payments.dues_id) as paid_dues_count'))
    ->groupBy('payments.member_id', 'members.name', 'members.last_name')
    ->get();

$membersWithPaidDues = $membersWithPaidDues->map(function ($item) {
    return [
        'member_name' => $item->name . ' ' . $item->last_name, 
        'paid_dues_count' => $item->paid_dues_count
    ];
});

        // Total dues
        // $totalDuesAmount = Dues::sum('amount');
        // $paidMonthlyDues = Payment::whereMonth('last_payment', $currentMonth)
        //     ->where('status', 'Paid')
        //     ->count();

        // // Total collected dues
        // $totalCollectedDues = Payment::where('status', 'paid')
        //     ->sum('dues.amount');

        // $totalDuesAmount = Dues::sum('amount');

    // $monthlyPaidDues = Payment::selectRaw('MONTH(last_payment) as month, YEAR(last_payment) as year, COUNT(*) as paid_count')
    // ->where('status', 'paid')
    // ->whereYear('last_payment', $currentYear)  // Optionally filter by the current year
    // ->groupBy('month', 'year')
    // ->get();

    // // Now, for each month, multiply the paid count by 600
    // $monthlyTotalDues = $monthlyPaidDues->map(function ($payment) {
    //     $payment->total_due_amount = $payment->paid_count * 600;
    //     return $payment;
    // });

    // // Output the result for testing
    // dd($monthlyTotalDues);

    //     // 2. Count the number of members who have paid in the current month
    //     $paidMonthlyDues = Payment::whereMonth('created_at', $currentMonth)
    //         ->whereYear('created_at', $currentYear)
    //         ->where('status', 'paid')
    //         ->distinct('member_id') // Ensure unique member_id
    //         ->count();

    //     // 3. Count the total collected dues (sum of dues amount for paid status)
    //     $totalCollectedDues = Payment::where('status', 'paid')
    //         ->whereMonth('created_at', $currentMonth)
    //         ->whereYear('created_at', $currentYear)
    //         ->join('dues', 'payments.dues_id', '=', 'dues.id') // Join with dues table
    //         ->sum('dues.amount'); // Sum the amount from dues table

    //     // Output for testing
    //     dd($monthlyTotalDues, $paidMonthlyDues, $totalCollectedDues);
        

        $totalBookingsAssigned = Schedule::count();

        $currentMonth = now()->month;
$currentYear = now()->year;

$members = Member::with(['vehicle', 'payment', 'driver.schedule', 'owner'])
    ->get()
    ->map(function ($member) use ($currentMonth, $currentYear) {
        $paidPayments = $member->payment->where('status', 'paid')->count();

        $currentMonthPayment = $member->payment->filter(function ($payment) use ($currentMonth, $currentYear) {
            return $payment->status === 'paid' &&
                   $payment->created_at->month === $currentMonth &&
                   $payment->created_at->year === $currentYear;
        })->isNotEmpty();

        $member->paid_payments_count = $paidPayments;
        $member->current_month_status = $currentMonthPayment ? 'Paid' : 'Unpaid';

        return $member;
    });

    $topDrivers = Driver::with(['member', 'schedule' => function ($query) {
        $query->whereIn('driver_status', ['Scheduled', 'Accepted', 'Cancelled']);
    }])
        ->get()
        ->sortByDesc(function ($driver) {
            return $driver->schedule->count();
        })
        ->take(5);

    $topPaid = Payment::with('member')
        ->whereHas('member', function ($query) {
            $query->where('type', 'driver'); 
        }) 
        ->whereYear('created_at', $currentYear)  
        ->where('status', 'Paid') 
        ->selectRaw('member_id, COUNT(*) as paid_count') 
        ->groupBy('member_id') 
        ->orderByDesc('paid_count') 
        ->take(5) 
        ->get();

        $data = [
            'title' => 'MANIBELA NG BUHAY TRANSPORT COOPERATIVE',
            'date' => date('m/d/Y'),
            'members' => $members,
            'totalMembers' => $totalMembers,
            'activeMembers' => $activeMembers,
            'inactiveMembers' => $inactiveMembers,
            'membersByType' => $membersByType,
            'tripsPerDriver' => $tripsPerDriver,
            'totalVehicles' => $totalVehicles,
            // 'amountPerPaidDues' => $amountPerPaidDues,
            // 'totalDuesAmount' => $totalDuesAmount,
            // 'paidMonthlyDues' => $paidMonthlyDues,
            // 'totalCollectedDues' => $totalCollectedDues,
            'totalBookingsAssigned' => $totalBookingsAssigned,
            'currentYear' => $currentYear,
            // 'finalResults' => $finalResults,
            'tableData' => $tableData, // Add this line
            'overallTotalMembers' => $overallTotalMembers, // Add this line
            'overallTotalAmount' => $overallTotalAmount,
            'topDrivers' => $topDrivers,
            'topPaid' => $topPaid,
        ];

//         $pdf = Pdf::loadView('admin.PDFFolder.allmemberPDF', $data);
//         return $pdf->download('all_member_report.pdf');

$currentYear = now()->year;

    // Fetch members with related data
    // $members = Member::with(['vehicle', 'driver.schedule', 'payment'])
    //     ->get()
    //     ->map(function ($member) use ($currentYear) {
    //         $totalSchedules = $member->driver->first()
    //             ? $member->driver->first()->schedule()->whereYear('created_at', $currentYear)->count()
    //             : 'Not Applicable';

    //         $acceptedSchedules = $member->driver->first()
    //             ? $member->driver->first()->schedule()->where('driver_status', 'Accepted')->count()
    //             : 'Not Applicable';

    //         $notAcceptedSchedules = $member->driver->first()
    //             ? $member->driver->first()->schedule()->where('driver_status', 'Cancelled')->count()
    //             : 'Not Applicable';

    //         $totalVehicles = $member->vehicle->count();
    //         $vehiclePlateNumbers = $totalVehicles > 0
    //             ? $member->vehicle->pluck('plate_number')->implode(', ')
    //             : 'Not Applicable';

    //         $totalPaidDues = $member->payment
    //             ->filter(function ($payment) use ($currentYear) {
    //                 return $payment->status === 'paid' && $payment->created_at->year == $currentYear;
    //             })
    //             ->sum('amount');

    //         $currentDueStatus = $totalPaidDues > 0 ? 'Paid' : 'Unpaid';

    //         return [
    //             'name' => $member->name . ' ' . $member->last_name,
    //             'type' => $member->type,
    //             'member_status' => $member->member_status,
    //             'total_schedules' => $totalSchedules,
    //             'accepted_schedules' => $acceptedSchedules,
    //             'not_accepted_schedules' => $notAcceptedSchedules,
    //             'total_vehicles' => $totalVehicles,
    //             'vehicle_plate_numbers' => $vehiclePlateNumbers,
    //             'total_paid_dues' => $totalPaidDues,
    //             'current_due_status' => $currentDueStatus,
    //         ];
    //     });

    //     $totalMembers = Member::count();
    //             $activeMembers = Member::where('member_status', 'active')->count();
    //             $inactiveMembers = Member::where('member_status', 'inactive')->count();
    //             $membersByType = Member::select('type')
    //             ->selectRaw('count(*) as members_count') // Count members per type
    //             ->groupBy('type') // Group by member type
    //             ->get();

    //     $tripsPerDriver = Driver::withCount('schedule')->get();
    //     $totalVehicles = Vehicle::count();

    // // Data for the PDF
    // $data = [
    //     'title' => 'MANIBELA NG BUHAY TRANSPORT COOPERATIVE',
    //     'date' => now()->format('m/d/Y'),
    //     'members' => $members,
    //     'totalMembers' => Member::count(),
    //     'activeMembers' => Member::where('member_status', 'active')->count(),
    //     'inactiveMembers' => Member::where('member_status', 'inactive')->count(),
    //     'membersByType' => $membersByType,
    //     'tripsPerDriver' => $tripsPerDriver,
    //     'totalVehicles' => $totalVehicles,
    // ];

    $pdf = Pdf::loadView('admin.PDFFolder.allmemberPDF', $data);
    return $pdf->download('all_member_report.pdf');

    }

    public function printallMember()
    {
        $currentMonth = now()->month;
        $currentYear = now()->year;

        $totalMembers = Member::count();
        $activeMembers = Member::where('member_status', 'active')->count();
        $inactiveMembers = Member::where('member_status', 'inactive')->count();
        $membersByType = Member::select('type')
        ->selectRaw('count(*) as members_count') 
        ->groupBy('type') 
        ->get();
        
        $tripsPerDriver = Driver::withCount('schedule')->get();

       
        $totalVehicles = Vehicle::count();

        $totalPaidDues = Payment::where('status', 'paid')
    ->join('dues', 'payments.dues_id', '=', 'dues.id')
    ->whereYear('payments.created_at', $currentYear)
    ->select(
        DB::raw('MONTH(payments.created_at) as month'),
        DB::raw('COUNT(DISTINCT payments.member_id) as members_paid'),
        DB::raw('SUM(dues.amount) as total_paid')
    )
    ->groupBy(DB::raw('MONTH(payments.created_at)'))
    ->get();

$allMonths = collect([
    'January', 'February', 'March',
    'April', 'May', 'June',
    'July', 'August', 'September',
    'October', 'November', 'December',
]);


$tableData = $allMonths->mapWithKeys(function ($monthName) {
    return [$monthName => ['members_paid' => 0, 'total_paid' => 0.00]];
});

foreach ($totalPaidDues as $item) {
    $monthName = \Carbon\Carbon::create()->month($item->month)->format('F');
    $tableData[$monthName] = [
        'members_paid' => $item->members_paid,
        'total_paid' => $item->total_paid,
    ];
}

$overallTotalMembers = $tableData->sum('members_paid');
$overallTotalAmount = $tableData->sum('total_paid');
    
   
    

$membersWithPaidDues = DB::table('payments')
    ->join('members', 'payments.member_id', '=', 'members.id')
    ->where('payments.status', 'paid')
    ->whereYear('payments.created_at', $currentYear)
    ->select('payments.member_id', 'members.name', 'members.last_name', DB::raw('count(DISTINCT payments.dues_id) as paid_dues_count'))
    ->groupBy('payments.member_id', 'members.name', 'members.last_name')
    ->get();

$membersWithPaidDues = $membersWithPaidDues->map(function ($item) {
    return [
        'member_name' => $item->name . ' ' . $item->last_name, 
        'paid_dues_count' => $item->paid_dues_count
    ];
});

        // Total dues
        // $totalDuesAmount = Dues::sum('amount');
        // $paidMonthlyDues = Payment::whereMonth('last_payment', $currentMonth)
        //     ->where('status', 'Paid')
        //     ->count();

        // // Total collected dues
        // $totalCollectedDues = Payment::where('status', 'paid')
        //     ->sum('dues.amount');

        // $totalDuesAmount = Dues::sum('amount');

    // $monthlyPaidDues = Payment::selectRaw('MONTH(last_payment) as month, YEAR(last_payment) as year, COUNT(*) as paid_count')
    // ->where('status', 'paid')
    // ->whereYear('last_payment', $currentYear)  // Optionally filter by the current year
    // ->groupBy('month', 'year')
    // ->get();

    // // Now, for each month, multiply the paid count by 600
    // $monthlyTotalDues = $monthlyPaidDues->map(function ($payment) {
    //     $payment->total_due_amount = $payment->paid_count * 600;
    //     return $payment;
    // });

    // // Output the result for testing
    // dd($monthlyTotalDues);

    //     // 2. Count the number of members who have paid in the current month
    //     $paidMonthlyDues = Payment::whereMonth('created_at', $currentMonth)
    //         ->whereYear('created_at', $currentYear)
    //         ->where('status', 'paid')
    //         ->distinct('member_id') // Ensure unique member_id
    //         ->count();

    //     // 3. Count the total collected dues (sum of dues amount for paid status)
    //     $totalCollectedDues = Payment::where('status', 'paid')
    //         ->whereMonth('created_at', $currentMonth)
    //         ->whereYear('created_at', $currentYear)
    //         ->join('dues', 'payments.dues_id', '=', 'dues.id') // Join with dues table
    //         ->sum('dues.amount'); // Sum the amount from dues table

    //     // Output for testing
    //     dd($monthlyTotalDues, $paidMonthlyDues, $totalCollectedDues);
        

        // Number of bookings assigned
        $totalBookingsAssigned = Schedule::count();

        
        $currentMonth = now()->month;
$currentYear = now()->year;

$members = Member::with(['vehicle', 'payment', 'driver.schedule', 'owner'])
    ->get()
    ->map(function ($member) use ($currentMonth, $currentYear) {
        
        $paidPayments = $member->payment->where('status', 'paid')->count();

        $currentMonthPayment = $member->payment->filter(function ($payment) use ($currentMonth, $currentYear) {
            return $payment->status === 'paid' &&
                   $payment->created_at->month === $currentMonth &&
                   $payment->created_at->year === $currentYear;
        })->isNotEmpty();

        $member->paid_payments_count = $paidPayments;
        $member->current_month_status = $currentMonthPayment ? 'Paid' : 'Unpaid';

        return $member;
    });

    $topDrivers = Driver::with(['member', 'schedule' => function ($query) {
        $query->whereIn('driver_status', ['scheduled', 'accepted', 'cancelled']);
    }])
        ->get()
        ->sortByDesc(function ($driver) {
            return $driver->schedule->count();
        })
        ->take(5);

    $topPaid = Payment::with('member')
        ->whereHas('member', function ($query) {
            $query->where('type', 'driver'); 
        }) 
        ->whereYear('created_at', $currentYear) 
        ->where('status', 'Paid') 
        ->selectRaw('member_id, COUNT(*) as paid_count') 
        ->groupBy('member_id') 
        ->orderByDesc('paid_count') 
        ->take(5) 
        ->get();

        $data = [
            'title' => 'MANIBELA NG BUHAY TRANSPORT COOPERATIVE',
            'date' => date('m/d/Y'),
            'members' => $members,
            'totalMembers' => $totalMembers,
            'activeMembers' => $activeMembers,
            'inactiveMembers' => $inactiveMembers,
            'membersByType' => $membersByType,
            'tripsPerDriver' => $tripsPerDriver,
            'totalVehicles' => $totalVehicles,
            // 'amountPerPaidDues' => $amountPerPaidDues,
            // 'totalDuesAmount' => $totalDuesAmount,
            // 'paidMonthlyDues' => $paidMonthlyDues,
            // 'totalCollectedDues' => $totalCollectedDues,
            'totalBookingsAssigned' => $totalBookingsAssigned,
            'currentYear' => $currentYear,
            // 'finalResults' => $finalResults,
            'tableData' => $tableData, // Add this line
            'overallTotalMembers' => $overallTotalMembers, // Add this line
            'overallTotalAmount' => $overallTotalAmount,
            'topDrivers' => $topDrivers,
            'topPaid' => $topPaid,
        ];

//         $pdf = Pdf::loadView('admin.PDFFolder.allmemberPDF', $data);
//         return $pdf->download('all_member_report.pdf');

$currentYear = now()->year;

    // Fetch members with related data
    // $members = Member::with(['vehicle', 'driver.schedule', 'payment'])
    //     ->get()
    //     ->map(function ($member) use ($currentYear) {
    //         $totalSchedules = $member->driver->first()
    //             ? $member->driver->first()->schedule()->whereYear('created_at', $currentYear)->count()
    //             : 'Not Applicable';

    //         $acceptedSchedules = $member->driver->first()
    //             ? $member->driver->first()->schedule()->where('driver_status', 'Accepted')->count()
    //             : 'Not Applicable';

    //         $notAcceptedSchedules = $member->driver->first()
    //             ? $member->driver->first()->schedule()->where('driver_status', 'Cancelled')->count()
    //             : 'Not Applicable';

    //         $totalVehicles = $member->vehicle->count();
    //         $vehiclePlateNumbers = $totalVehicles > 0
    //             ? $member->vehicle->pluck('plate_number')->implode(', ')
    //             : 'Not Applicable';

    //         $totalPaidDues = $member->payment
    //             ->filter(function ($payment) use ($currentYear) {
    //                 return $payment->status === 'paid' && $payment->created_at->year == $currentYear;
    //             })
    //             ->sum('amount');

    //         $currentDueStatus = $totalPaidDues > 0 ? 'Paid' : 'Unpaid';

    //         return [
    //             'name' => $member->name . ' ' . $member->last_name,
    //             'type' => $member->type,
    //             'member_status' => $member->member_status,
    //             'total_schedules' => $totalSchedules,
    //             'accepted_schedules' => $acceptedSchedules,
    //             'not_accepted_schedules' => $notAcceptedSchedules,
    //             'total_vehicles' => $totalVehicles,
    //             'vehicle_plate_numbers' => $vehiclePlateNumbers,
    //             'total_paid_dues' => $totalPaidDues,
    //             'current_due_status' => $currentDueStatus,
    //         ];
    //     });

    //     $totalMembers = Member::count();
    //             $activeMembers = Member::where('member_status', 'active')->count();
    //             $inactiveMembers = Member::where('member_status', 'inactive')->count();
    //             $membersByType = Member::select('type')
    //             ->selectRaw('count(*) as members_count') // Count members per type
    //             ->groupBy('type') // Group by member type
    //             ->get();

    //     $tripsPerDriver = Driver::withCount('schedule')->get();
    //     $totalVehicles = Vehicle::count();

    // // Data for the PDF
    // $data = [
    //     'title' => 'MANIBELA NG BUHAY TRANSPORT COOPERATIVE',
    //     'date' => now()->format('m/d/Y'),
    //     'members' => $members,
    //     'totalMembers' => Member::count(),
    //     'activeMembers' => Member::where('member_status', 'active')->count(),
    //     'inactiveMembers' => Member::where('member_status', 'inactive')->count(),
    //     'membersByType' => $membersByType,
    //     'tripsPerDriver' => $tripsPerDriver,
    //     'totalVehicles' => $totalVehicles,
    // ];

        return view('admin.PDFFolder.printallMember', $data);
    }
}
