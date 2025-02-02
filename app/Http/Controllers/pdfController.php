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

public function generatePDF(Request $request)
{
     $filterStartDate = $request->input('startDate');
        $filterEndDate = $request->input('endDate');
    
        // Parse the filtered start and end dates
        $startDate = $filterStartDate ? Carbon::parse($filterStartDate) : null;
        $endDate = $filterEndDate ? Carbon::parse($filterEndDate) : null;
    
        // Adjust query filters based on provided dates
        $baseQuery = Booking::query();
        if ($startDate && $endDate) {
            $baseQuery->whereBetween('start_date', [$startDate, $endDate]);
        }
    
        // Individual Queries for Metrics
        $totalMonthlyBookings = (clone $baseQuery)
            ->whereMonth('start_date', $startDate?->month ?? now()->month)
            ->count();
    
        // total no. of Bookings
        $totalYearlyBookings = (clone $baseQuery)->count();
            // ->whereYear('start_date', $startDate?->year ?? now()->year)
            
    
        $totalRevenue = (clone $baseQuery)
            ->whereNotIn('status', ['cancelled', 'rejected'])
            ->sum('price');
    
        $cancelledRevenue = (clone $baseQuery)
            ->where('status', ['cancelled'])
            ->selectRaw('SUM(price - remaining) as revenue')
            ->value('revenue');

        $overallTotalRevenue = $totalRevenue + $cancelledRevenue;

    
        $cancelledCount = (clone $baseQuery)
            ->where('status', 'cancelled')
            ->count();

        $acceptedCount = (clone $baseQuery)
            ->where('status', 'accepted')
            ->count();

        $rejectedCount = (clone $baseQuery)
            ->where('status', 'rejected')
            ->count();

    
        // Top Destinations
        $topDestinations = (clone $baseQuery)
            ->select('destination', Booking::raw('COUNT(*) as count'))
            ->groupBy('destination')
            ->orderByDesc('count')
            ->limit(5)
            ->get();
    
        // Monthly Revenue
        $monthlyBookings = (clone $baseQuery)
            ->selectRaw('MONTH(start_date) as month, COUNT(*) as count')
            ->groupByRaw('MONTH(start_date)')
            ->pluck('count', 'month');
    
        $monthlySales = (clone $baseQuery)
            ->whereIn('status', ['accepted', 'active'])
            ->selectRaw('MONTH(start_date) as month, SUM(price) as total_sales')
            ->groupByRaw('MONTH(start_date)')
            ->pluck('total_sales', 'month');

        $topCustomers = (clone $baseQuery)
            ->with('user') // Load customer relationship
            ->select('customer_id', Booking::raw('COUNT(*) as booking_count'))
            ->groupBy('customer_id')
            ->orderByDesc('booking_count')
            ->limit(5)
            ->get()
            ->map(function ($booking) {
                return [
                    'name' => $booking->user->name ?? 'Unknown',
                    'last_name' => $booking->user->last_name ?? 'Unknown',
                    'booking_count' => $booking->booking_count,
                ];
            });

        $topStartDates = (clone $baseQuery)
            ->select('start_date', Booking::raw('COUNT(*) as booking_count'))
            ->groupBy('start_date')
            ->orderByDesc('booking_count')
            ->limit(5)
            ->get();
        

        $topPickUpLocations = (clone $baseQuery)
            ->select('location', Booking::raw('COUNT(*) as booking_count'))
            ->groupBy('location')
            ->orderByDesc('booking_count')
            ->limit(5)
            ->get();
       
            
    
        // Data for the PDF
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
            'overallTotalRevenue' => $overallTotalRevenue,
            'acceptedCount' => $acceptedCount,
            'rejectedCount' => $rejectedCount,
            'topCustomers' =>  $topCustomers,
            'topStartDates' =>  $topStartDates,
            'topPickUpLocations' => $topPickUpLocations,
            'filterStartDate' => $filterStartDate,
            'filterEndDate' => $filterEndDate, 
        ];
    
        // Generate PDF
        $pdf = Pdf::loadView('admin.PDFFolder.bookingPDF', $data);
        return $pdf->download('invoice.pdf');
    }
    
    public function printBooking(Request $request)
    {
        $filterStartDate = $request->input('startDate');
        $filterEndDate = $request->input('endDate');
    
        // Parse the filtered start and end dates
        $startDate = $filterStartDate ? Carbon::parse($filterStartDate) : null;
        $endDate = $filterEndDate ? Carbon::parse($filterEndDate) : null;
    
        // Adjust query filters based on provided dates
        $baseQuery = Booking::query();
        if ($startDate && $endDate) {
            $baseQuery->whereBetween('start_date', [$startDate, $endDate]);
        }
    
        // Individual Queries for Metrics
        $totalMonthlyBookings = (clone $baseQuery)
            ->whereMonth('start_date', $startDate?->month ?? now()->month)
            ->count();
    
        // total no. of Bookings
        $totalYearlyBookings = (clone $baseQuery)->count();
            // ->whereYear('start_date', $startDate?->year ?? now()->year)
            
    
        $totalRevenue = (clone $baseQuery)
            ->whereNotIn('status', ['cancelled', 'rejected'])
            ->sum('price');
    
        $cancelledRevenue = (clone $baseQuery)
            ->where('status', ['cancelled'])
            ->selectRaw('SUM(price - remaining) as revenue')
            ->value('revenue');

        $overallTotalRevenue = $totalRevenue + $cancelledRevenue;

    
        $cancelledCount = (clone $baseQuery)
            ->where('status', 'cancelled')
            ->count();

        $acceptedCount = (clone $baseQuery)
            ->where('status', 'accepted')
            ->count();

        $rejectedCount = (clone $baseQuery)
            ->where('status', 'rejected')
            ->count();

    
        // Top Destinations
        $topDestinations = (clone $baseQuery)
            ->select('destination', Booking::raw('COUNT(*) as count'))
            ->groupBy('destination')
            ->orderByDesc('count')
            ->limit(5)
            ->get();
    
        // Monthly Revenue
        $monthlyBookings = (clone $baseQuery)
            ->selectRaw('MONTH(start_date) as month, COUNT(*) as count')
            ->groupByRaw('MONTH(start_date)')
            ->pluck('count', 'month');
    
        $monthlySales = (clone $baseQuery)
            ->whereIn('status', ['accepted', 'active'])
            ->selectRaw('MONTH(start_date) as month, SUM(price) as total_sales')
            ->groupByRaw('MONTH(start_date)')
            ->pluck('total_sales', 'month');

        $topCustomers = (clone $baseQuery)
            ->with('user') // Load customer relationship
            ->select('customer_id', Booking::raw('COUNT(*) as booking_count'))
            ->groupBy('customer_id')
            ->orderByDesc('booking_count')
            ->limit(5)
            ->get()
            ->map(function ($booking) {
                return [
                    'name' => $booking->user->name ?? 'Unknown',
                    'last_name' => $booking->user->last_name ?? 'Unknown',
                    'booking_count' => $booking->booking_count,
                ];
            });

        $topStartDates = (clone $baseQuery)
            ->select('start_date', Booking::raw('COUNT(*) as booking_count'))
            ->groupBy('start_date')
            ->orderByDesc('booking_count')
            ->limit(5)
            ->get();
        

        $topPickUpLocations = (clone $baseQuery)
            ->select('location', Booking::raw('COUNT(*) as booking_count'))
            ->groupBy('location')
            ->orderByDesc('booking_count')
            ->limit(5)
            ->get();
       
            
    
        // Data for the PDF
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
            'overallTotalRevenue' => $overallTotalRevenue,
            'acceptedCount' => $acceptedCount,
            'rejectedCount' => $rejectedCount,
            'topCustomers' =>  $topCustomers,
            'topStartDates' =>  $topStartDates,
            'topPickUpLocations' => $topPickUpLocations,
            'filterStartDate' => $filterStartDate,
            'filterEndDate' => $filterEndDate, 
        ];

    // $pdf = Pdf::loadView('admin.PDFFolder.printBooking', $data);
    // // return $pdf->download('invoice.pdf');
    // return view('admin.booking.booking', $data);
    return view('admin.PDFFolder.printBooking', $data);
    // return $pdf->stream(); 
    }


// //////////////////////////////////////////////////////////////////////////////////////////////////
///////////////////////////////////////////////////////////////////////////////////////////////////

public function generateallmemberPDF(Request $request)
    {
        $filterStartDate = $request->input('startDate');
        $filterEndDate = $request->input('endDate');
        
        
        // Parse dates
        $startDate = $filterStartDate ? Carbon::parse($filterStartDate) : null;
        $endDate = $filterEndDate ? Carbon::parse($filterEndDate)->endOfDay() : null;
        
        $currentMonth = $startDate?->month ?? now()->month;
        $endDateMonth = $endDate?->month ?? now()->month;
        $currentYear = $startDate?->year ?? now()->year;
        $endcurrentYear = $endDate?->year ?? now()->year;
        
        // Total Members
        $baseQuery = Member::query();
        if ($startDate && $endDate) {
            $baseQuery->whereBetween('created_at', [$startDate, $endDate]);
        }

        $totalMembers = Member::count();
        
        // Active Members
        // $activeMembers = $baseQuery->clone()->where('member_status', 'active')->count();
        $allactiveMembers = Member::where('member_status', 'active')->count();
        
        // Inactive Members
        // $inactiveMembers = $baseQuery->clone()->where('member_status', 'inactive')->count();
        $allinactiveMembers = Member::where('member_status', 'inactive')->count();
        
        // Members by Type
        $membersByType = Member::select('type')
        ->selectRaw('COUNT(*) as total_count') // Total members count regardless of status
        ->selectRaw("SUM(CASE WHEN member_status = 'active' THEN 1 ELSE 0 END) as active_count") // Count active members
        ->groupBy('type')
        ->get();

        $allmembersByType = Member::select('type')
            ->selectRaw('count(*) as members_count')
            ->groupBy('type')
            ->get();
        
        // Trips Per Driver
        $tripsPerDriver = Driver::query();

        if ($startDate && $endDate) {
            $tripsPerDriver->whereHas('schedule.booking', function ($query) use ($startDate, $endDate) {
                $query->where(function ($subQuery) use ($startDate, $endDate) {
                    $subQuery->whereBetween('start_date', [$startDate, $endDate])
                            ->orWhereBetween('end_date', [$startDate, $endDate])
                            ->orWhere(function ($nestedQuery) use ($startDate, $endDate) {
                                $nestedQuery->where('start_date', '<=', $startDate)
                                            ->where('end_date', '>=', $endDate);
                            });
                });
            });
        }

        $tripsPerDriver = $tripsPerDriver->withCount('schedule')->get();

        
        // Total Vehicles   
        // $totalVehiclesQuery = Vehicle::query();
        // if ($startDate && $endDate) {
        //     $totalVehiclesQuery->whereBetween('created_at', [$startDate, $endDate]);
        // }
        $totalVehicles = Vehicle::count();
      

        
        
        // Total Paid Dues
        $totalPaidDuesQuery = Payment::query()
            ->where('status', 'paid')
            ->join('dues', 'payments.dues_id', '=', 'dues.id')
            ->whereBetween('payments.created_at', [
                Carbon::create($currentYear, $currentMonth, 1),
                Carbon::create($endcurrentYear, $endDateMonth, 1)->endOfMonth()
            ]);
    
        $totalPaidDues = $totalPaidDuesQuery
            ->select(
                DB::raw('MONTH(payments.created_at) as month'),
                DB::raw('COUNT(DISTINCT payments.member_id) as members_paid'),
                DB::raw('SUM(dues.amount) as total_paid')
            )
            ->groupBy(DB::raw('MONTH(payments.created_at)'))
            ->orderBy(DB::raw('MONTH(payments.created_at)'), 'desc') // Ordering by month in descending order
            ->get();
    
    // Generate table data dynamically based on actual months with data
    $tableData = $totalPaidDues->mapWithKeys(function ($item) {
        $monthName = Carbon::create()->month($item->month)->format('F');
        return [
            $monthName => [
                'members_paid' => $item->members_paid,
                'total_paid' => $item->total_paid,
            ],
        ];
    });
        
        $overallTotalMembers = $tableData->sum('members_paid');
        $overallTotalAmount = $tableData->sum('total_paid');
        
        // Members
        $members = Member::with(['vehicle', 'payment', 'driver.schedule', 'owner'])->get()
            ->map(function ($member) use ($currentMonth, $currentYear, $endDateMonth, $endcurrentYear) {
                // Filter payments based on startDate and endDate for paid and unpaid counts
                $paidPayments = $member->payment->filter(function ($payment) use ($currentMonth, $currentYear, $endDateMonth, $endcurrentYear) {
                    // Check if the payment is 'paid' and if its created_at month/year is between the current and end date range
                    return $payment->status === 'paid' &&
                           (
                               ($payment->created_at->year > $currentYear || 
                                ($payment->created_at->year === $currentYear && $payment->created_at->month >= $currentMonth)) &&
                               ($payment->created_at->year < $endcurrentYear || 
                                ($payment->created_at->year === $endcurrentYear && $payment->created_at->month <= $endDateMonth))
                           );
                })->count();
                
                $unpaidPayments = $member->payment->filter(function ($payment) use ($currentMonth, $currentYear, $endDateMonth, $endcurrentYear) {
                    // Check if the payment is 'unpaid' and if its created_at month/year is between the current and end date range
                    return $payment->status === 'unpaid' &&
                           (
                               ($payment->created_at->year > $currentYear || 
                                ($payment->created_at->year === $currentYear && $payment->created_at->month >= $currentMonth)) &&
                               ($payment->created_at->year < $endcurrentYear || 
                                ($payment->created_at->year === $endcurrentYear && $payment->created_at->month <= $endDateMonth))
                           );
                })->count();

                // Filter payments based on the specific end date month and year
                $currentMonthPayment = $member->payment->filter(function ($payment) use ($endDateMonth, $endcurrentYear) {
                    return $payment->status === 'paid' &&
                        $payment->created_at->month === $endDateMonth &&
                        $payment->created_at->year === $endcurrentYear;
                })->isNotEmpty();
        
                $member->paid_payments_count = $paidPayments;
                $member->unpaid_payments_count = $unpaidPayments;
                $member->current_month_status = $currentMonthPayment ? 'Paid' : 'Unpaid';
        
                return $member;
            });
        
        // Top Drivers
        $topDrivers = Driver::with(['member', 'schedule' => function ($query) use ($startDate, $endDate) {
            $query->whereHas('booking', function($query) use ($startDate, $endDate) {
                // Filter bookings based on start_date
                if ($startDate && $endDate) {
                    $query->whereBetween('start_date', [$startDate, $endDate]);
                }
            })
            ->whereIn('driver_status', ['scheduled', 'accepted', 'cancelled'])
            ->where('cust_status', '!=', 'inactive'); // Add the filter on schedule's cust_status
        }])
        ->get()
        ->sortByDesc(fn($driver) => $driver->schedule->count())
        ->take(5);
        
        // Top Paid Members
        $topPaid = Payment::with('member')
            ->whereHas('member', function ($query) {
                $query->where('type', 'driver');
            })
            ->whereBetween('created_at', [
                \Carbon\Carbon::createFromDate($currentYear, $currentMonth, 1)->startOfMonth(),
                \Carbon\Carbon::createFromDate($endcurrentYear, $endDateMonth, 1)->endOfMonth()
            ]) 
            ->where('status', 'paid')
            ->selectRaw('member_id, COUNT(*) as paid_count') 
            ->groupBy('member_id') // Group by member_id
            ->orderByDesc('paid_count') // Sort by paid count in descending order
            ->take(5) // Get the top 5
            ->get();
        
                
        // Total Bookings Assigned
        $totalBookingsAssigned = Schedule::query()
            ->whereHas('booking', function($query) use ($startDate, $endDate) {
                // Filter bookings based on start_date
                if ($startDate && $endDate) {
                    $query->whereBetween('start_date', [$startDate, $endDate]);
                }
            })
            ->count();
        
        // Final Data
        $data = [
            'title' => 'MANIBELA NG BUHAY TRANSPORT COOPERATIVE',
            'date' => date('m/d/Y'),
            'totalMembers' => $totalMembers,
            // 'activeMembers' => $activeMembers,
            // 'inactiveMembers' => $inactiveMembers,
            'membersByType' => $membersByType,
            'tripsPerDriver' => $tripsPerDriver,
            'totalVehicles' => $totalVehicles,
            'totalPaidDues' => $totalPaidDues,
            'tableData' => $tableData,
            'overallTotalMembers' => $overallTotalMembers,
            'overallTotalAmount' => $overallTotalAmount,
            'members' => $members,
            'topDrivers' => $topDrivers,
            'topPaid' => $topPaid,
            'totalBookingsAssigned' => $totalBookingsAssigned,
            'filterStartDate' => $filterStartDate,
            'filterEndDate' => $filterEndDate,
            'currentYear' => $currentYear,
            'allactiveMembers' => $allactiveMembers,
            'allinactiveMembers' => $allinactiveMembers,
            'startDate' => $startDate,
            'endDate' => $endDate,
        ];
        
    $pdf = Pdf::loadView('admin.PDFFolder.allmemberPDF', $data);
    return $pdf->download('all_member_report.pdf');

    }

    public function printallMember(Request $request)
    {
        $filterStartDate = $request->input('startDate');
        $filterEndDate = $request->input('endDate');
        
        
        // Parse dates
        $startDate = $filterStartDate ? Carbon::parse($filterStartDate) : null;
        $endDate = $filterEndDate ? Carbon::parse($filterEndDate)->endOfDay() : null;
        
        $currentMonth = $startDate?->month ?? now()->month;
        $endDateMonth = $endDate?->month ?? now()->month;
        $currentYear = $startDate?->year ?? now()->year;
        $endcurrentYear = $endDate?->year ?? now()->year;
        
        // Total Members
        $baseQuery = Member::query();
        if ($startDate && $endDate) {
            $baseQuery->whereBetween('created_at', [$startDate, $endDate]);
        }

        $totalMembers = Member::count();
        
        // Active Members
        // $activeMembers = $baseQuery->clone()->where('member_status', 'active')->count();
        $allactiveMembers = Member::where('member_status', 'active')->count();
        
        // Inactive Members
        // $inactiveMembers = $baseQuery->clone()->where('member_status', 'inactive')->count();
        $allinactiveMembers = Member::where('member_status', 'inactive')->count();
        
        // Members by Type
        $membersByType = Member::select('type')
        ->selectRaw('COUNT(*) as total_count') // Total members count regardless of status
        ->selectRaw("SUM(CASE WHEN member_status = 'active' THEN 1 ELSE 0 END) as active_count") // Count active members
        ->groupBy('type')
        ->get();

        $allmembersByType = Member::select('type')
            ->selectRaw('count(*) as members_count')
            ->groupBy('type')
            ->get();
        
        // Trips Per Driver
        $tripsPerDriver = Driver::query();

        if ($startDate && $endDate) {
            $tripsPerDriver->whereHas('schedule.booking', function ($query) use ($startDate, $endDate) {
                $query->where(function ($subQuery) use ($startDate, $endDate) {
                    $subQuery->whereBetween('start_date', [$startDate, $endDate])
                            ->orWhereBetween('end_date', [$startDate, $endDate])
                            ->orWhere(function ($nestedQuery) use ($startDate, $endDate) {
                                $nestedQuery->where('start_date', '<=', $startDate)
                                            ->where('end_date', '>=', $endDate);
                            });
                });
            });
        }

        $tripsPerDriver = $tripsPerDriver->withCount('schedule')->get();

        
        // Total Vehicles   
        // $totalVehiclesQuery = Vehicle::query();
        // if ($startDate && $endDate) {
        //     $totalVehiclesQuery->whereBetween('created_at', [$startDate, $endDate]);
        // }
        $totalVehicles = Vehicle::count();
      

        
        
        // Total Paid Dues
        $totalPaidDuesQuery = Payment::query()
            ->where('status', 'paid')
            ->join('dues', 'payments.dues_id', '=', 'dues.id')
            ->whereBetween('payments.created_at', [
                Carbon::create($currentYear, $currentMonth, 1),
                Carbon::create($endcurrentYear, $endDateMonth, 1)->endOfMonth()
            ]);
    
        $totalPaidDues = $totalPaidDuesQuery
            ->select(
                DB::raw('MONTH(payments.created_at) as month'),
                DB::raw('COUNT(DISTINCT payments.member_id) as members_paid'),
                DB::raw('SUM(dues.amount) as total_paid')
            )
            ->groupBy(DB::raw('MONTH(payments.created_at)'))
            ->orderBy(DB::raw('MONTH(payments.created_at)'), 'desc') // Ordering by month in descending order
            ->get();
    
    // Generate table data dynamically based on actual months with data
    $tableData = $totalPaidDues->mapWithKeys(function ($item) {
        $monthName = Carbon::create()->month($item->month)->format('F');
        return [
            $monthName => [
                'members_paid' => $item->members_paid,
                'total_paid' => $item->total_paid,
            ],
        ];
    });
        
        $overallTotalMembers = $tableData->sum('members_paid');
        $overallTotalAmount = $tableData->sum('total_paid');
        
        // Members
        $members = Member::with(['vehicle', 'payment', 'driver.schedule', 'owner'])->get()
            ->map(function ($member) use ($currentMonth, $currentYear, $endDateMonth, $endcurrentYear) {
                // Filter payments based on startDate and endDate for paid and unpaid counts
                $paidPayments = $member->payment->filter(function ($payment) use ($currentMonth, $currentYear, $endDateMonth, $endcurrentYear) {
                    // Check if the payment is 'paid' and if its created_at month/year is between the current and end date range
                    return $payment->status === 'paid' &&
                           (
                               ($payment->created_at->year > $currentYear || 
                                ($payment->created_at->year === $currentYear && $payment->created_at->month >= $currentMonth)) &&
                               ($payment->created_at->year < $endcurrentYear || 
                                ($payment->created_at->year === $endcurrentYear && $payment->created_at->month <= $endDateMonth))
                           );
                })->count();
                
                $unpaidPayments = $member->payment->filter(function ($payment) use ($currentMonth, $currentYear, $endDateMonth, $endcurrentYear) {
                    // Check if the payment is 'unpaid' and if its created_at month/year is between the current and end date range
                    return $payment->status === 'unpaid' &&
                           (
                               ($payment->created_at->year > $currentYear || 
                                ($payment->created_at->year === $currentYear && $payment->created_at->month >= $currentMonth)) &&
                               ($payment->created_at->year < $endcurrentYear || 
                                ($payment->created_at->year === $endcurrentYear && $payment->created_at->month <= $endDateMonth))
                           );
                })->count();

                // Filter payments based on the specific end date month and year
                $currentMonthPayment = $member->payment->filter(function ($payment) use ($endDateMonth, $endcurrentYear) {
                    return $payment->status === 'paid' &&
                        $payment->created_at->month === $endDateMonth &&
                        $payment->created_at->year === $endcurrentYear;
                })->isNotEmpty();
        
                $member->paid_payments_count = $paidPayments;
                $member->unpaid_payments_count = $unpaidPayments;
                $member->current_month_status = $currentMonthPayment ? 'Paid' : 'Unpaid';
        
                return $member;
            });
        
        // Top Drivers
        $topDrivers = Driver::with(['member', 'schedule' => function ($query) use ($startDate, $endDate) {
            $query->whereHas('booking', function($query) use ($startDate, $endDate) {
                // Filter bookings based on start_date
                if ($startDate && $endDate) {
                    $query->whereBetween('start_date', [$startDate, $endDate]);
                }
            })
            ->whereIn('driver_status', ['scheduled', 'accepted', 'cancelled'])
            ->where('cust_status', '!=', 'inactive'); // Add the filter on schedule's cust_status
        }])
        ->get()
        ->sortByDesc(fn($driver) => $driver->schedule->count())
        ->take(5);
        
        // Top Paid Members
        $topPaid = Payment::with('member')
            ->whereHas('member', function ($query) {
                $query->where('type', 'driver');
            })
            ->whereBetween('created_at', [
                \Carbon\Carbon::createFromDate($currentYear, $currentMonth, 1)->startOfMonth(),
                \Carbon\Carbon::createFromDate($endcurrentYear, $endDateMonth, 1)->endOfMonth()
            ]) 
            ->where('status', 'paid')
            ->selectRaw('member_id, COUNT(*) as paid_count') 
            ->groupBy('member_id') // Group by member_id
            ->orderByDesc('paid_count') // Sort by paid count in descending order
            ->take(5) // Get the top 5
            ->get();
        
                
        // Total Bookings Assigned
        $totalBookingsAssigned = Schedule::query()
            ->whereHas('booking', function($query) use ($startDate, $endDate) {
                // Filter bookings based on start_date
                if ($startDate && $endDate) {
                    $query->whereBetween('start_date', [$startDate, $endDate]);
                }
            })
            ->count();
        
        // Final Data
        $data = [
            'title' => 'MANIBELA NG BUHAY TRANSPORT COOPERATIVE',
            'date' => date('m/d/Y'),
            'totalMembers' => $totalMembers,
            // 'activeMembers' => $activeMembers,
            // 'inactiveMembers' => $inactiveMembers,
            'membersByType' => $membersByType,
            'tripsPerDriver' => $tripsPerDriver,
            'totalVehicles' => $totalVehicles,
            'totalPaidDues' => $totalPaidDues,
            'tableData' => $tableData,
            'overallTotalMembers' => $overallTotalMembers,
            'overallTotalAmount' => $overallTotalAmount,
            'members' => $members,
            'topDrivers' => $topDrivers,
            'topPaid' => $topPaid,
            'totalBookingsAssigned' => $totalBookingsAssigned,
            'filterStartDate' => $filterStartDate,
            'filterEndDate' => $filterEndDate,
            'currentYear' => $currentYear,
            'allactiveMembers' => $allactiveMembers,
            'allinactiveMembers' => $allinactiveMembers,
            'startDate' => $startDate,
            'endDate' => $endDate,
        ];

        return view('admin.PDFFolder.printallMember', $data);
    }
}
