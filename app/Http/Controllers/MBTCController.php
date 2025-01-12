<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Carbon\Carbon; 
use App\Notifications\reservationAccept;
use App\Notifications\reservationDeclined;
use App\Notifications\newSchedule;
use App\Notifications\reservationDriver;
use App\Notifications\reservationDriverNotification;
use App\Notifications\declinedDriverSchedule;
use App\Notifications\optionalSchedule;
use App\Notifications\newMonthlyDue;

use Illuminate\Http\Request;
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



class MBTCController extends Controller
{

///////////////User Booking///////////////////////////////////////////////
    public function bookingform(Request $request)
    {
        $qrcode = qrcode::first();
        $activetariffs = Tariff::where('status', 'active')->get();
    
        if (!$request->isMethod('post')) {
            return view('dashboard', compact('qrcode', 'activetariffs'));
        }
    
        $selectedTariff = Tariff::find($request->id);
    
        if (!$selectedTariff) {
            return redirect()->back()->with('error', 'Invalid tariff selected.');
        }
    
        $start_date = new \DateTime($request->start_date);
        $end_date = new \DateTime($request->end_date);
        $interval = $start_date->diff($end_date);
        $days = $interval->days + 1;
    
        $rate = $selectedTariff->rate;
        $succeeding = $selectedTariff->succeeding;
    
        $total_price = ($days > 1) ? $rate + ($succeeding * ($days - 1)) : $rate;

        $remaining = $total_price - $request->paymentAmount;


        $bookReceipt = null;
        if ($request->hasFile('receipt')) {
            $receipt = $request->file('receipt');
            $bookReceipt = time() . '.' . $receipt->getClientOriginalExtension();
            $receipt->move(public_path('img'), $bookReceipt);
        }
    
        $validated = $request->validate([
            'passenger'   => 'required|integer|min:1',
            'location'    => 'required|string',
            'start_date'  => 'required|date',
            'end_date'    => 'required|date|after_or_equal:start_date',
        ], [
            'passenger.required' => 'Passenger is required',
            'location.required' => 'Location is required',
            'start_date.required' => 'Start date is required',
            'end_date.required' => 'End date is required',
        ]);

        Booking::create([
            'customer_id' => auth()->id(),
            'tariff_id' => $selectedTariff->id,
            'passenger' => $request->passenger,
            'time' => $request->time,
            'location' => $request->location,
            'destination' => $selectedTariff->destination,
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
            'status' => 'active',
            'price' => $total_price,
            'remaining' => $remaining,
            'receipt' => $bookReceipt, 
        ]);
    
        return redirect()->back()->with('success', 'Booking has been submitted successfully.');
    }

    public function userbookingpage(Request $request) {
        $userId = Auth::id();
        $bookings = Booking::with(['schedule.driver.member'])
                    ->where('customer_id', $userId)
                    ->get();
        
        return view('bookingpage', compact('bookings'));
    }
    
    // public function cancelbooking(Request $request, $id) {
    //     $schedule = Booking::find($id);
        
    //     if ($schedule) {
    //         $schedule->status = 'cancelled';
    //         $schedule->save();
    //     }
        
    //     return redirect()->back();
    // }
    
    public function cancelBooking(Request $request, $id)
{
    $booking = Booking::find($id);

    if ($booking) {

        $booking->status = 'cancelled';
        $booking->save();

        $schedule = Schedule::where('book_id', $booking->id)->latest()->first();

        if ($schedule) {
    
            if ($schedule->driver_status !== 'cancelled') {
                $schedule->update([
                    'driver_status' => 'active',  
                    'cust_status' => 'inactive'  
                ]);
            }
        }
        
    }

    return redirect()->back()->with('success', 'Booking has been cancelled and schedule updated successfully.');
}

    

/////Calculated price 
public function calculatePrice(Request $request) {
    $request->validate([
        'id' => 'required|exists:tariffs,id',
        'start_date' => 'required|date',
        'end_date' => 'required|date|after_or_equal:start_date',
    ]);

    $tariff = Tariff::find($request->id);

    if (!$tariff) {
        return response()->json(['error' => 'Tariff not found'], 404);
    }

    $selectedTariff = Tariff::find($request->id);
    
        if (!$selectedTariff) {
            return redirect()->back()->with('error', 'Invalid tariff selected.');
        }
    
        $start_date = new \DateTime($request->start_date);
        $end_date = new \DateTime($request->end_date);
        $interval = $start_date->diff($end_date);
        $days = $interval->days + 1;
    
        $rate = $selectedTariff->rate;
        $succeeding = $selectedTariff->succeeding;
        // dd($succeeding);
    
        $calculated_succeeding = ($days > 1) ? $succeeding * ($days - 1) : 0;
        $price = ($days > 1) ? $rate + ($succeeding * ($days - 1)) : $rate;

        return response()->json([
            'price' => $price,
            'rate' => $rate,
            'succeeding' => $calculated_succeeding,
        ]);
}


///////////////ADMIN/////////////////////////////////////////////////////////
///////////////Admin dashboard///////////////////////////////////////////////
public function dashboard() 
{
    $totalBookings = Booking::count();
    $totalUsers = User::count();
    $totalMembers = Member::count();

    $currentYear = now()->year; 
    $monthlyBookings = Booking::selectRaw('YEAR(created_at) as year, MONTH(created_at) as month, COUNT(*) as count')
        ->whereYear('created_at', $currentYear)
        ->where('status', 'accepted')
        ->groupBy('year', 'month')
        ->orderBy('month')
        ->pluck('count', 'month')
        ->toArray();

    $bookingData = array_fill(1, 12, 0);
    foreach ($monthlyBookings as $month => $count) {
        $bookingData[$month] = $count;
    }


    $currentYear = now()->year; 
    $topDestinations = Booking::select('tariff_id')
        ->selectRaw('COUNT(*) as count')
        ->whereYear('created_at', $currentYear) 
        ->groupBy('tariff_id')
        ->orderByDesc('count')
        ->limit(5)
        ->get();

    $tariffIds = $topDestinations->pluck('tariff_id')->toArray();
    $tariffs = Tariff::whereIn('id', $tariffIds)
        ->pluck('destination', 'id')
        ->toArray();

    $destinationNames = [];
    $destinationCounts = [];
    foreach ($topDestinations as $destination) {
        $destinationNames[] = $tariffs[$destination->tariff_id] ?? 'Unknown';
        $destinationCounts[] = $destination->count;
    }

    $currentYear = now()->year;
    $monthlySales = Booking::selectRaw('YEAR(start_date) as year, MONTH(start_date) as month, SUM(price) as total_sales')
        ->where('status', 'accepted') 
        ->whereYear('start_date', $currentYear)
        ->groupBy('year', 'month')
        ->orderBy('month')
        ->pluck('total_sales', 'month')
        ->toArray();

    $salesData = array_fill(1, 12, 0);
    foreach ($monthlySales as $month => $totalSales) {
        $salesData[$month] = $totalSales;
    }

    $currentMonth = now()->month;
    $paidMembersCount = Payment::whereMonth('created_at', $currentMonth)
                                ->where('status', 'paid')
                                ->distinct('member_id')
                                ->count('member_id');

    $activeMembersCount = Member::where('member_status', 'active')->count();

    $currentYear = now()->year;
    $lastYear = $currentYear - 1;

    // Count bookings per month for the current year with 'accepted' status
    // $monthlyBookingsCurrentYear = Booking::selectRaw('YEAR(created_at) as year, MONTH(created_at) as month, COUNT(*) as count')
    //     ->whereYear('created_at', $currentYear)
    //     ->where('status', 'accepted')
    //     ->groupBy('year', 'month')
    //     ->orderBy('month')
    //     ->pluck('count', 'month')
    //     ->toArray();

    $monthlyBookingsLastYear = Booking::selectRaw('YEAR(created_at) as year, MONTH(created_at) as month, COUNT(*) as count')
        ->whereYear('created_at', $lastYear)
        ->where('status', 'accepted')
        ->groupBy('year', 'month')
        ->orderBy('month')
        ->pluck('count', 'month')
        ->toArray();
    
    $monthlyBookingsCurrentYear = Booking::selectRaw('YEAR(created_at) as year, MONTH(created_at) as month, COUNT(*) as count')
            ->whereYear('created_at', $currentYear)
            ->where('status', 'accepted')
            ->groupBy('year', 'month')
            ->orderBy('month')
            ->pluck('count', 'month')
            ->toArray();
    
    $lastBookingData = array_fill(1, 12, 0);
        foreach ($monthlyBookingsLastYear as $month => $count) {
            $lastBookingData[$month] = $count;
        }
    
    // $bookingDataCurrentYear = array_fill(1, 12, 0);
    // $bookingDataLastYear = array_fill(1, 12, 0);

    //     $chartData = [
    //         'currentYear' => $bookingDataCurrentYear,
    //         'lastYear' => $bookingDataLastYear,
    //     ];
    $today = now();

    $scheduleCount = Schedule::whereHas('booking', function ($query) use ($today) {
        $query->whereDate('start_date', '=', $today);
    })->count();

    return view('admin.dashboard', compact(
        'totalBookings', 'totalUsers', 'totalMembers', 'bookingData', 'destinationNames', 'destinationCounts', 'salesData',
        'paidMembersCount', 'activeMembersCount', 'currentMonth', 'monthlyBookingsCurrentYear', 'monthlyBookingsLastYear', 'lastBookingData', 'scheduleCount'
    ));
}

///////////////Customer///////////////////////////////////////////////
public function viewCustomer(Request $request)
{
    // Step 1: Get all users
    $users = User::all();

    // Step 2: Count the bookings for each user
    $bookingCounts = Booking::select('customer_id')
        ->selectRaw('COUNT(*) as total_bookings')
        ->groupBy('customer_id')
        ->pluck('total_bookings', 'customer_id'); // [user_id => total_bookings]

    // Step 3: Get the latest booking for each user
    $latestBookings = Booking::select('customer_id', 'start_date', 'end_date')
        ->orderBy('start_date', 'desc')
        ->get()
        ->unique('customer_id') // Keep only the latest for each user_id
        ->keyBy('customer_id'); // [user_id => latest_booking]

    return view('admin.customer.customer', compact('users', 'bookingCounts', 'latestBookings'));
}


///////////////Member///////////////////////////////////////////////
    public function viewmember(Request $request)
    {
        $search = $request->memberSearch;

        if ($search) {
            $viewmembers = Member::where('member_status', 'active')
            ->where(function ($query) use ($search) {
                $query->where('name', 'LIKE', '%' . $search . '%')
                    ->orWhere('last_name', 'LIKE', '%' . $search . '%')
                    ->orWhere('tin', 'LIKE', '%' . $search . '%');
            })
            ->get();
        } else {
            $viewmembers = Member::where('member_status', 'active')->get();
            // $viewtariffs = Tariff::all();
        }

        return view('admin.member.member', compact('viewmembers'));
    }

    // public function viewSpecificMember($id)
    // {
    //     //
    //     $viewSpecific = Member::with('payment.dues', 'vehicle')->findOrFail($id);
    
    
    //     $dues = Dues::with(['payment' => function ($query) use ($id) {
    //         $query->where('member_id', $id);
    //     }])
    //     ->get()
    //     ->map(function ($dues) {
    //         $payment = $dues->payment->first(); 
    //         return [
    //             'month' => \Carbon\Carbon::parse($dues->date)->format('F Y'), 
    //             'amount' => $dues->amount,
    //             'status' => $payment->status ?? 'Unpaid',
    //             'last_payment' => $payment->last_payment ?? 'N/A', 
    //         ];
    //     });

    //     // $member = Member::with(['driver.schedule.booking' => function($query) use ($id) {
    //     //     $query->whereHas('schedule', function($scheduleQuery) use ($id) {
    //     //         $scheduleQuery->where('driver_id', $id);
    //     //     });
    //     // }])->findOrFail($id);
    
    //     // // Extract schedules and related booking information
    //     // $schedules = $member->driver->flatMap(function ($driver) {
    //     //     return $driver->schedule->map(function ($schedule) {
    //     //         return [
    //     //             'schedule_id' => $schedule->id,
    //     //             'booking_id' => $schedule->booking->id ?? 'N/A',
    //     //             'customer_name' => $schedule->booking->user->name ?? 'N/A',
    //     //             'driver_name' => $schedule->driver->member->name ?? 'N/A',
    //     //             'driver_status' => $schedule->driver_status ?? 'N/A',
    //     //             'schedule_date' => $schedule->created_at->format('F d, Y') ?? 'N/A', // Added schedule date
    //     //             'cust_status' => $schedule->cust_status ?? 'N/A', // Added cust_status
    //     //         ];
    //     //     });
    //     // });

    //     $currentMonth = \Carbon\Carbon::now()->month;
    //     $currentYear = \Carbon\Carbon::now()->year;
    
    //     // $member = Member::with(['driver.schedule.booking' => function($query) use ($id, $currentMonth, $currentYear) {
    //     //     $query->whereHas('schedule', function($scheduleQuery) use ($id, $currentMonth, $currentYear) {
    //     //         $scheduleQuery->where('driver_id', $id)
    //     //                       ->whereMonth('created_at', $currentMonth)
    //     //                       ->whereYear('created_at', $currentYear);
    //     //     })->get();
           
    //     // }])->findOrFail($id);
    
    //     // // Extract schedules and related booking information
    //     // $schedules = $member->driver->flatMap(function ($driver) {
    //     //     return $driver->schedule->map(function ($schedule) {
    //     //         return [
    //     //             'schedule_id' => $schedule->id,
    //     //             'booking_id' => $schedule->booking->id ?? 'N/A',
    //     //             'customer_name' => $schedule->booking->user->name ?? 'N/A',
    //     //             'driver_name' => $schedule->driver->member->name ?? 'N/A',
    //     //             'driver_status' => $schedule->driver_status ?? 'N/A',
    //     //             'schedule_date' => $schedule->booking->start_date ?? 'N/A',
    //     //             'cust_status' => $schedule->cust_status ?? 'N/A', 
    //     //         ];
                
    //     //     });
            
    //     // });

    //     $driver = $viewSpecific->driver()->first();  // Or you can use ->find($driverId) if you know the specific driver's id

    //     // Now you can safely access the driver's id
    //     if ($driver) {
    //         $scheduless = Schedule::where('driver_id', $driver->id)
    //             ->whereHas('booking', function ($query) use ($currentMonth, $currentYear) {
    //                 $query->whereMonth('start_date', $currentMonth)
    //                       ->whereYear('start_date', $currentYear)
    //                       ->orWhereMonth('end_date', $currentMonth)
    //                       ->whereYear('end_date', $currentYear);
    //             })
    //             ->with('booking')  // Eager load the booking for each schedule
    //             ->get();
    //         // dd($scheduless->toArray());
            
        
    //         // Extract the relevant data
    //         $schedules = $scheduless->map(function ($schedule) {
    //             return [
    //                 'schedule_id' => $schedule->id,
    //                 'booking_id' => $schedule->booking->id ?? 'N/A',
    //                 'customer_name' => $schedule->booking->user->name ?? 'N/A',
    //                 'driver_name' => $schedule->driver->member->name ?? 'N/A',
    //                 'driver_status' => $schedule->driver_status ?? 'N/A',
    //                 'schedule_date' => $schedule->booking->start_date ?? 'N/A',
    //                 'cust_status' => $schedule->cust_status ?? 'N/A',
    //             ];
    //         }); }
        
    //         // Return or use $scheduleDetails as needed
    //     } else {
    //         return "Not A Driver ";
    //     }
    
    //     // Count the schedules
    //     $scheduleCount = $schedules->count();
    //     $vehicles = $viewSpecific->vehicle ? $viewSpecific->vehicle : null;
       
    //     return view('admin.member.viewmember', compact('viewSpecific', 'dues', 'schedules', 'scheduleCount', 'vehicles'));
    // }

    public function viewSpecificMember($id)
    {
        // Retrieve specific member with relationships
        $viewSpecific = Member::with('payment.dues', 'vehicle')->findOrFail($id);
        
        $currentMonth = \Carbon\Carbon::now()->month;
        $currentYear = \Carbon\Carbon::now()->year;
        // Map dues data
        $currentMonthDues = Dues::with(['payment' => function ($query) use ($id) {
            $query->where('member_id', $id);
        }])
        ->whereMonth('date', $currentMonth)
        ->whereYear('date', $currentYear)
        ->get()
        ->map(function ($dues) {
            $payment = $dues->payment->first();
            return [
                'id' => $dues->payment->first()->id ?? 'N/A',
                'month' => \Carbon\Carbon::parse($dues->date)->format('F Y'),
                'amount' => $dues->amount,
                'status' => $payment->status ?? 'Unpaid',
                'last_payment' => $payment->last_payment ?? 'N/A',
            ];
        });
    
        // All dues for the current year
        $allDues = Dues::with(['payment' => function ($query) use ($id) {
            $query->where('member_id', $id);
        }])
        ->whereYear('date', $currentYear)
        ->get()
        ->map(function ($dues) {
            $payment = $dues->payment->first();
            return [
                'id' => $dues->payment->first()->id ?? 'N/A',
                'month' => \Carbon\Carbon::parse($dues->date)->format('F Y'),
                'amount' => $dues->amount,
                'status' => $payment->status ?? 'unpaid',
                'last_payment' => $payment->last_payment ?? 'N/A',
            ];
        });
       
    
$schedules = collect();
$driver = $viewSpecific->driver()->first();
if ($driver) {
    $vehicles = $viewSpecific->vehicle;

    // Debug: Check if vehicles exist
    
    $driverSchedules = Schedule::where('driver_id', $driver->id)
        ->whereHas('booking', function ($query) use ($currentMonth, $currentYear) {
            $query->whereMonth('start_date', $currentMonth)
                  ->whereYear('start_date', $currentYear)
                  ->orWhereMonth('end_date', $currentMonth)
                  ->whereYear('end_date', $currentYear);
        })
        ->with(['booking','vehicle'])
        ->get()
        ->map(function ($schedule) {
            return [
                'schedule_id' => $schedule->id,
                'booking_id' => $schedule->booking->id ?? 'N/A',
                // 'member_type' => $schedule->member->type ?? 'N/A',
                'customer_name' => $schedule->booking->user->name ?? 'N/A',
                'location' => $schedule->booking->location ?? 'N/A',
                'destination' => $schedule->booking->destination ?? 'N/A',
                'time' => $schedule->booking->time ?? 'N/A',
                'driver_name' => $schedule->driver->member->name ?? 'N/A',
                'vehicle_name' => $schedule->vehicle->plate_num ?? 'N/A',
                'driver_status' => $schedule->driver_status ?? 'N/A',
                'start_date' => $schedule->booking->start_date ?? 'N/A',
                'end_date' => $schedule->booking->end_date ?? 'N/A',
                'cust_status' => $schedule->cust_status ?? 'N/A',
            ];
        });

    $schedules = $schedules->merge($driverSchedules); // Merge with existing schedules
} else {
    // Handle owner's vehicle schedules
    $vehicles = $viewSpecific->vehicle;

    // Debug: Check if vehicles exist
    if ($vehicles->isEmpty()) {
        dd('No vehicles for this owner');
    } 

    // Check if the viewSpecific type is 'owner'
    if ($viewSpecific->type === 'Owner' && $vehicles) {
        // Get the owner schedules
        $ownerSchedules = Schedule::whereIn('vehicle_id', $vehicles->pluck('id'))
            ->whereHas('booking', function ($query) use ($currentMonth, $currentYear) {
                $query->whereMonth('start_date', $currentMonth)
                      ->whereYear('start_date', $currentYear)
                      ->orWhereMonth('end_date', $currentMonth)
                      ->whereYear('end_date', $currentYear);
            })
            ->with(['booking', 'vehicle.member'])
            ->get()

        // Debug: Dump the owner schedules to inspect the query result
        // dd($ownerSchedules); // This will stop the script here and show the $ownerSchedules collection

        // Map owner schedules
        ->map(function ($schedule) {
            return [
                'schedule_id' => $schedule->id,
                'booking_id' => $schedule->booking->id ?? 'N/A',
                'member_type' => $schedule->member->type ?? 'N/A',
                'customer_name' => $schedule->booking->user->name ?? 'N/A',
                'vehicle_name' => $schedule->vehicle->plate_num ?? 'N/A',
                'driver_name' => $schedule->driver->member->name ?? 'N/A',
                'driver_status' => $schedule->driver_status ?? 'N/A',
                'start_date' => $schedule->booking->start_date ?? 'N/A',
                'end_date' => $schedule->booking->end_date ?? 'N/A',
                'cust_status' => $schedule->cust_status ?? 'N/A',
            ];
        });

        // Merge with existing schedules if needed
        $schedules = $schedules->merge($ownerSchedules); // Merge with existing schedules (if any)
    } else {
        // Debug: Ensure correct type or missing vehicles
        dd('Not an owner or no vehicles found');
    }
}


        
        // Count the schedules
        $scheduleCount = $schedules->count();
    
        return view('admin.member.viewmember', compact('viewSpecific', 'schedules', 'scheduleCount', 'vehicles', 'currentMonthDues', 'allDues'));
    }
    
    


/////Archive Member Page 
    public function viewarchivemember(Request $request)
    {
        $search = $request->membersearch;

        if ($search) {
            $viewmembers = Member::where('member_status', 'inactive')
            ->where(function ($query) use ($search) {
                $query->where('name', 'LIKE', '%' . $search . '%')
                ->orWhere('last_name', 'LIKE', '%' . $search . '%')
                ->orWhere('tin', 'LIKE', '%' . $search . '%');
            })
            ->get();
        } else {
            $viewmembers = Member::where('member_status', 'inactive')->get();
        }

        return view('admin.member.archivemember', compact('viewmembers'));
    }

/////Archive a member
public function archivemember(Request $request, $id) {
    $member = Member::find($id);
    
    if ($member) {
        $member->member_status = 'inactive';
        $member->save();
    }
    
    return redirect()->back();
}

/////Unarchive a member
    public function unarchivemember(Request $request, $id)
        {
            
            $search = $request->membersearch;
        
            if ($search) {
                $viewmembers = Member::where('member_status', 'inactive')
                ->where(function ($query) use ($search) {
                    $query->where('name', 'LIKE', '%' . $search . '%')
                        ->orWhere('last_name', 'LIKE', '%' . $search . '%')
                        ->orWhere('tin', 'LIKE', '%' . $search . '%');
                })
                ->get();
            } else {
                $member = Member::find($id);
                if ($member) {
                    $member->member_status = 'active';
                    $member->save();
                }
                
                return redirect()->back();
            }
        
            return view('admin.member.archivemember', compact('viewmembers'));
        }



///////////////Tariff///////////////////////////////////////////////
    public function viewtariff(Request $request)
    {
        $search = $request->tariffSearch;
    
        if ($search) {
            $viewtariffs = Tariff::where('status', 'active')
            ->where(function ($query) use ($search) {
                $query->where('destination', 'LIKE', '%' . $search . '%')
                    ->orWhere('rate', 'LIKE', '%' . $search . '%')
                    ->orWhere('succeeding', 'LIKE', '%' . $search . '%');
            })
            ->get();
        } else {
            $viewtariffs = Tariff::where('status', 'active')->get();
        }
    
        return view('admin.tariff.tariff', compact('viewtariffs'));
    }

/////Add Tariff
public function addtariff(Request $request)
{
    $request->validate([
        'destination' => 'required|unique:tariffs,destination',
        'rate' => 'required|numeric',
        'succeeding' => 'required|numeric',
    ], [
        'destination.unique' => 'Destination is already exists'
    ]);

    Tariff::create([
        'destination' => $request->destination,
        'rate' => $request->rate,
        'succeeding' => $request->succeeding,
        'status' => $request->status
    ]);

    return redirect()->route('admin.tariff.tariff')->with('success', 'Successfully Added the Tariff!');
}


    public function addtariffform(){
        return view('admin.tariff.addtariff');
    }

/////Archive Tariff Page
    public function viewarchivetariff(Request $request)
    {
        $search = $request->tariffSearch;
    
        if ($search) {
            $viewtariffs = Tariff::where('status', 'inactive')
            ->where(function ($query) use ($search) {
                $query->where('destination', 'LIKE', '%' . $search . '%')
                      ->orWhere('rate', 'LIKE', '%' . $search . '%')
                      ->orWhere('succeeding', 'LIKE', '%' . $search . '%');
            })
            ->get();
        } else {
            $viewtariffs = Tariff::where('status', 'inactive')->get();
        }
    
        return view('admin.tariff.archivetariff', compact('viewtariffs'));
    }

/////Archive a tariff
    public function archiveTariff(Request $request, $Tariff_ID) {
        $tariff = Tariff::find($Tariff_ID);
        
        if ($tariff) {
            $tariff->status = 'inactive';
            $tariff->save();
        }
        
        return redirect()->back();
    }

/////Unarchive a tariff
    public function unarchiveTariff(Request $request, $Tariff_ID)
        {
            
            $search = $request->tariffSearch;
        
            if ($search) {
                $viewtariffs = Tariff::where('status', 'inactive')
                ->where(function ($query) use ($search) {
                    $query->where('destination', 'LIKE', '%' . $search . '%')
                        ->orWhere('rate', 'LIKE', '%' . $search . '%')
                        ->orWhere('succeeding', 'LIKE', '%' . $search . '%');
                })
                ->get();
            } else {
                $tariff = Tariff::find($Tariff_ID);
                if ($tariff) {
                    $tariff->status = 'active';
                    $tariff->save();
                }
                
                return redirect()->back();
            }
        
            return view('admin.tariff.archivetariff', compact('viewtariffs'));
        }
    
    public function updateform($id){
        
        $viewtariffs = Tariff::find($id);

        return view('admin.tariff.updatetariff', compact('viewtariffs'));
    }

/////Update a tariff
    public function updatetariff(Request $request, $id) {
        $updatetariff = Tariff::find($id);
    
        if ($updatetariff) {
            $request->validate([
                'destination' => 'required|unique:tariffs,destination,' . $id,
                'rate' => 'required|numeric',
                'succeeding' => 'required|numeric',
            ], [
                'destination.unique' => 'Destination is already exist'
            ]);
            $updatetariff->update([
                'destination' => $request->destination,
                'rate' => $request->rate,
                'succeeding' => $request->succeeding
            ]);

        }
        
        return redirect()->route('admin.tariff.tariff');
    }


///////////////Vehicle///////////////////////////////////////////////
    public function viewVehicle(Request $request)
    {
        $search = $request->vehicleSearch;
    
        if ($search) {
            $viewVehicles = Vehicle::where('status', 'active')
            ->where(function ($query) use ($search) {
                $query->where('type', 'LIKE', '%' . $search . '%')
                    ->orWhere('plate_num', 'LIKE', '%' . $search . '%')
                    ->orWhere('capacity', 'LIKE', '%' . $search . '%');
            })
            ->get();
        } else {
            $viewVehicles = Vehicle::where('status', 'active')->get();

        }
    
        return view('admin.vehicle.vehicle', compact('viewVehicles'));
    }

    public function addvehicleform(){

        $activeMembers = Member::where('member_status', 'active')->get();
            return view('admin/vehicle/addvehicle', compact('activeMembers'));

        // return view('admin.vehicle.addvehicle');
    }
    
/////Unarchive a vehicle
    public function addvehicle(Request $request)
    {
        $request->validate([
            'type' => ['required', 'string', 'max:255'],
            'plate_num' => ['required', 'string', 'max:255', 'unique:vehicles,plate_num'],
            'capacity' => ['required', 'integer', 'max:20'], 
            'status' => ['required', 'string', 'max:255'],
        ], [
            'plate_num.unique' => 'The plate number must be unique.', 
            'capacity.max' => 'The capacity cannot exceed 20.', 
        ]);
        $selectedMember = Member::find($request->id);
    
        if (!$selectedMember) {
            return redirect()->back()->with('error', 'Invalid member selected.');
        }
    
            Vehicle::create([
            'member_id' => $selectedMember->id,
            'type' => $request->type,
            'plate_num' => $request->plate_num,
            'capacity' => $request->capacity,
            'status' => $request->status 
        ]);

        $ownerExists = Owner::where('member_id', $selectedMember->id)->exists();
    
        if (!$ownerExists) {
            Owner::create([
                'member_id' => $selectedMember->id,
                'member_type' => 'Owner'
            ]);
        }
    
    
        return redirect()->route('admin.vehicle.vehicle')->with('success', 'Successfully Added the Vehicle!');
        
    }


    public function updatevehicleform($id) {
        $activeMembers = Member::where('member_status', 'active')->get();
        $viewVehicles = Vehicle::find($id);
        return view('admin.vehicle.updatevehicle', compact('viewVehicles', 'activeMembers'));
    }

/////Update a vehicle
    public function updateVehicle(Request $request, $id) {
        $updateVehicle = Vehicle::find($id);

        $request->validate([
            'type' => ['required', 'string', 'max:255'],
            'plate_num' => ['required', 'string', 'max:255', 'unique:vehicles,plate_num,' . $id], 
            'capacity' => ['required', 'integer', 'max:20'], 
            'status' => ['required', 'string', 'max:255'],
            'member_id' => ['required', 'exists:members,id'], 
        ], [
            'plate_num.unique' => 'The plate number must be unique.', 
            'capacity.max' => 'The capacity cannot exceed 20.', 
        ]);
    
        if ($updateVehicle) {
            $updateVehicle->update([
                'member_id' => $request->member_id,
                'type' => $request->type,
                'plate_num' => $request->plate_num,
                'capacity' => $request->capacity,
                'status' => $request->status
            ]);
        }

        $selectedMember = Member::find($request->member_id);
        if ($selectedMember) {
            $ownerExists = Owner::where('member_id', $selectedMember->id)->exists();
            if (!$ownerExists) {
                Owner::create([
                'member_id' => $selectedMember->id,
                'member_type' => 'Owner'
            ]);
            }
        }
    
        return redirect()->route('admin.vehicle.vehicle');
    }
    
/////Archive Vehicle Page
    public function viewarchivevehicle(Request $request)
    {
        $search = $request->vehicleSearch;
    
        if ($search) {
            $viewVehicles = Vehicle::where('status', 'inactive')
            ->where(function ($query) use ($search) {
                $query->where('type', 'LIKE', '%' . $search . '%')
                    ->orWhere('plate_num', 'LIKE', '%' . $search . '%')
                    ->orWhere('capacity', 'LIKE', '%' . $search . '%');
            })
            ->get();
        } else {
            $viewVehicles = Vehicle::where('status', 'inactive')->get();
        }
    
        return view('admin.vehicle.archivevehicle', compact('viewVehicles'));
    }

/////Archive a Vehicle
    public function archiveVehicle(Request $request, $id) {
        $vehicle = Vehicle::find($id);
        
        if ($vehicle) {
            $vehicle->status = 'inactive';
            $vehicle->save();
        }
        
        return redirect()->back();
    }

/////Unarchive a vehicle
    public function unarchiveVehicle(Request $request, $id)
        {
            
            $search = $request->vehicleSearch;
        
            if ($search) {
                $viewVehicles = Vehicle::where('status', 'inactive')
                ->where(function ($query) use ($search) {
                    $query->where('type', 'LIKE', '%' . $search . '%')
                        ->orWhere('plate_num', 'LIKE', '%' . $search . '%')
                        ->orWhere('capacity', 'LIKE', '%' . $search . '%');
                })
                ->get();
            } else {
                $vehicle = Vehicle::find($id);
                if ($vehicle) {
                    $vehicle->status = 'active';
                    $vehicle->save();
                }
                
                return redirect()->back();
            }
        
            return view('admin.vehicle.archivevehicle', compact('viewVehicles'));
        }




///////////////Admin Booking///////////////////////////////////////////////
        public function adminbookingpage(Request $request){

            $search = $request->bookingSearch;
        
            if ($search) {
                $viewBookings = Booking::all()
                    ->whereHas('user', function ($query) use ($search) {
                        $query->where('name', 'LIKE', '%' . $search . '%')
                            ->orWhere('last_name', 'LIKE', '%' . $search . '%');
                    })
                    ->orWhere('passenger', 'LIKE', '%' . $search . '%')
                    ->orWhere('location', 'LIKE', '%' . $search . '%')
                    ->orWhere('destination', 'LIKE', '%' . $search . '%')
                    ->get();
            } else {
                $viewBookings  = Booking::all();
            }

            $viewactiveBookings  = Booking::where('status', 'active')->get();
            
            $activeBookingCount = Booking::where('status', 'active')->count();

            $directory = public_path('img'); // Path to your images folder
            // $files = File::files($directory);

            // foreach ($files as $file) {
            //     File::delete($file); // Delete each file
            // }
            $oldBookings = Booking::where('end_date', '<=', Carbon::now()->subMonth())
                ->get();

            foreach ($oldBookings as $booking) {
                // Construct the full path to the image file
                $imagePath = $directory . '/' . $booking->receipt;

                // Check if the file exists before deleting
                if (File::exists($imagePath)) {
                    File::delete($imagePath);
                }
            }

            // return response()->json(['message' => 'All images deleted successfully.']);
            return view('admin.booking.booking', compact('viewBookings', 'activeBookingCount', 'viewactiveBookings'));

        }

    public function viewSpecificBooking($id){
        $viewSpecific = Booking::find($id);

        $members = Member::where('type', 'Driver')
        ->where('member_status', 'active')
        ->with(['driver.schedule', 'payment.dues']) // Ensure dues are eager-loaded
        ->get();

    $drivers = $members->filter(function ($member) {
        if ($member->driver) {
            foreach ($member->driver as $driver) {

                $scheduledCount = $driver->schedule()
                    ->where('driver_status', 'scheduled')
                    ->where('cust_status', '!=', 'inactive') 
                    ->count();

                $acceptedCount = $driver->schedule()
                    ->where('driver_status', 'accepted')
                    ->where('cust_status', '!=', 'inactive') 
                    ->count();

                $cancelledCount = $driver->schedule()
                    ->where('driver_status', 'cancelled')
                    ->count();

                $count = $scheduledCount + $acceptedCount + $cancelledCount;

                $paidCount = $member->payment()->where('status', 'paid')->count();

                if ($paidCount > $count) {
                    return true; 
                }
            }
        }
        return false;
    });

    // $drivers = $drivers->sortBy(function ($member) {
    //     return $member->driver->first() ? $member->driver->first()->id : null; 
    // });

    // Sort payments within each driver by dues' month
    foreach ($drivers as $driver) {
        foreach ($driver->payment as $payment) {
            // dump($payment->dues->date); // Check the date for each payment
            if ($payment->dues && $payment->dues->date) {
                $month = Carbon::parse($payment->dues->date)->format('F');
                // dump($month); // Display the month for debugging
            }
        }
    }

    $viewBookings = Booking::where('id', $id)
        ->with(['schedule' => function ($query) {
            $query->orderBy('created_at', 'desc'); 
        }, 'schedule.driver.member', 'user'])
        ->get();

        return view('admin.booking.view', compact('viewSpecific', 'drivers', 'viewBookings'));
    }


///////////////Admin Monthly Dues///////////////////////////////////////////////
        public function viewAllMonthlyDues(Request $request)
        {
                $payments = Payment::with('member', 'dues')
                    ->whereHas('dues')
                    ->get();
                $paymentss = Payment::with(['member', 'dues'])
                    ->where('status', 'update') 
                    ->whereHas('dues') 
                    ->orderBy('updated_at', 'desc')
                    ->get();

                $paymentnotifcount =  $paymentss->count();
        
                return view('admin.monthlydues.viewallmonthlydues', compact('payments', 'paymentss', 'paymentnotifcount'));
        }

        public function viewMonthlyDues(Request $request)
        {
            $search = $request->monthlyduesSearch;
            
            if ($search) {
                $payments = Member::where('member_status', 'active')
                    ->where(function ($query) use ($search) {
                        $query->where('name', 'LIKE', '%' . $search . '%')
                            ->orWhere('last_name', 'LIKE', '%' . $search . '%');
                    })
                    ->get();
            } else {
                $currentMonth = now()->startOfMonth()->format('Y-m-d');
                $amount = 600.00;

                $dues = Dues::firstOrCreate(
                    ['date' => $currentMonth],
                    ['amount' => $amount]
                );

                $members = Member::all();

                foreach ($members as $member) {
                    $existingPayment = Payment::where('member_id', $member->id)
                        ->where('dues_id', $dues->id)
                        ->exists();

                    $lastPayment = Payment::where('member_id', $member->id)
                        ->orderBy('last_payment', 'desc')
                        ->first();

                    if (!$existingPayment) {
                        Payment::create([
                            'member_id' => $member->id,
                            'dues_id' => $dues->id,
                            'last_payment' => $lastPayment ? $lastPayment->last_payment : null,
                            'status' => 'unpaid'
                        ]);
                        $members = Member::all(); // Retrieve all members
                        // Check if members exist
                        if ($members->isNotEmpty()) {
                            foreach ($members as $member) {
                                $member->notify(new newMonthlyDue());
                            }
                        }
                    }

                    if ($lastPayment && now()->diffInMonths($lastPayment->last_payment) >= 12) {
                        $member->update(['member_status' => 'inactive']);
                    }
                }

                
        
                $payments = Payment::with('member', 'dues')
                    ->whereHas('dues', function ($query) use ($currentMonth) {
                        $query->where('date', $currentMonth);
                    })
                    ->get();
                
                $paymentss = Payment::with(['member', 'dues'])
                    ->where('status', 'update') 
                    ->whereHas('dues') 
                    ->orderBy('updated_at', 'desc')
                    ->get();
                $paymentnotifcount =  $paymentss->count();
        
                return view('admin.monthlydues.monthlydues', compact('payments', 'paymentss', 'paymentnotifcount'));
            }
        }
        
        
/////Confirm Payment
    public function confirmPayment(Request $request) {
        $payment = Payment::findOrFail($request->payment_id);
        $payment->status = 'paid';
        $payment->last_payment = now();
        $payment->save();

        return redirect()->back();
    }

////Send payment update
    public function sendPaymentUpdate(Request $request, $id)
{

    $payment = Payment::findOrFail($id);
    $payment->status = 'update';
    $payment->save();

    return redirect()->back();
}


///////////////Admin Schedule///////////////////////////////////////////////

public function viewSchedule(Request $request) {

    // $vehiclesExist = Vehicle::exists();
    
    // $members = Member::where('type', 'Driver')
    //     ->where('member_status', 'active')
    //     ->with(['driver.schedule', 'payment']) 
    //     ->get();
    $members = Member::where('type', 'Driver')
        ->where('member_status', 'active')
        ->with(['driver.schedule', 'payment.dues']) // Ensure dues are eager-loaded
        ->get();

    $drivers = $members->filter(function ($member) {
        if ($member->driver) {
            foreach ($member->driver as $driver) {

                $scheduledCount = $driver->schedule()
                    ->where('driver_status', 'scheduled')
                    ->where('cust_status', '!=', 'inactive') 
                    ->count();

                $acceptedCount = $driver->schedule()
                    ->where('driver_status', 'accepted')
                    ->where('cust_status', '!=', 'inactive') 
                    ->count();

                $cancelledCount = $driver->schedule()
                    ->where('driver_status', 'cancelled')
                    ->count();

                $count = $scheduledCount + $acceptedCount + $cancelledCount;

                $paidCount = $member->payment()->where('status', 'paid')->count();

                if ($paidCount > $count) {
                    return true; 
                }
            }
        }
        return false;
    });

    // $drivers = $drivers->sortBy(function ($member) {
    //     return $member->driver->first() ? $member->driver->first()->id : null; 
    // });

    // Sort payments within each driver by dues' month
    foreach ($drivers as $driver) {
        foreach ($driver->payment as $payment) {
            // dump($payment->dues->date); // Check the date for each payment
            if ($payment->dues && $payment->dues->date) {
                $month = Carbon::parse($payment->dues->date)->format('F');
                // dump($month); // Display the month for debugging
            }
        }
    }

    $viewBookings = Booking::where('status', 'accepted')
        ->with(['schedule' => function ($query) {
            $query->orderBy('created_at', 'desc'); 
        }, 'schedule.driver.member', 'user'])
        ->get();

    return view('admin.schedule.schedule', compact('drivers', 'viewBookings'));
}

// public function viewSchedule(Request $request) {

//     // Check if vehicles exist
//     $vehiclesExist = Vehicle::exists();
    
//     // Fetch members (drivers) and eager load their related data
//     $members = Member::where('type', 'Driver')
//         ->where('member_status', 'active')
//         ->with(['driver.schedule', 'payment.dues']) // Ensure dues are eager-loaded
//         ->get();

//     // Filter drivers based on payment and schedule criteria
//     $drivers = $members->filter(function ($member) {
//         if ($member->driver) {
//             foreach ($member->driver as $driver) {

//                 $scheduledCount = $driver->schedule()
//                     ->where('driver_status', 'scheduled')
//                     ->where('cust_status', '!=', 'inactive') 
//                     ->count();

//                 $acceptedCount = $driver->schedule()
//                     ->where('driver_status', 'accepted')
//                     ->where('cust_status', '!=', 'inactive') 
//                     ->count();

//                 $cancelledCount = $driver->schedule()
//                     ->where('driver_status', 'cancelled')
//                     ->count();

//                 $count = $scheduledCount + $acceptedCount + $cancelledCount;

//                 // Count the number of paid dues for each driver
//                 $paidCount = $member->payment()->where('status', 'paid')->count();

//                 // Only include drivers who have more paid dues than scheduled counts
//                 if ($paidCount > $count) {
//                     return true; 
//                 }
//             }
//         }
//         return false;
//     });

//   // Now process the drivers, sorting them and removing the first payment month if it conflicts with their schedule
//   $sortedDrivers = $drivers->map(function ($driver) {
//     // Filter and sort payments by dues date
//     $paidPayments = $driver->payment->filter(function ($payment) {
//         return $payment->status === 'paid' && $payment->dues && $payment->dues->date;
//     })->sortBy(function ($payment) {
//         return Carbon::parse($payment->dues->date);
//     });

//     // Get all months the driver has paid for
//     $paidMonths = $paidPayments->map(function ($payment) {
//         return Carbon::parse($payment->dues->date)->format('F');
//     });

//     // Get all months for which this driver has already scheduled
//     $scheduledMonths = $driver->schedule()->where('driver_status', 'scheduled')
//         ->where('cust_status', '!=', 'inactive')
//         ->get()->map(function ($schedule) {
//             return Carbon::parse($schedule->start_date)->format('F');
//         });

//     // Remove months from the list of paid months that are already scheduled
//     $availableMonths = $paidMonths->diff($scheduledMonths);

//     // If there are available months, return them
//     if ($availableMonths->isNotEmpty()) {
//         // Select the first available month
//         $nextMonth = $availableMonths->first();

//         // Get the payment associated with this month
//         $nextPayment = $paidPayments->firstWhere(function ($payment) use ($nextMonth) {
//             return Carbon::parse($payment->dues->date)->format('F') === $nextMonth;
//         });

//         return [
//             'driver_id' => $driver->id,
//             'driver' => $driver,
//             'dues_date' => $nextPayment->dues->date,
//             'payment_month' => $nextMonth,
//         ];
//     }

//     return null;
// })->filter()->sortBy(function ($entry) {
//     return Carbon::parse($entry['dues_date']); // Sorting by the earliest available dues date
// });
//     // Now $drivers is sorted by their earliest payment date

//     // Fetch accepted bookings
//     $viewBookings = Booking::where('status', 'accepted')
//         ->with(['schedule' => function ($query) {
//             $query->orderBy('created_at', 'desc'); 
//         }, 'schedule.driver.member', 'user'])
//         ->get();

//     // Pass the sorted drivers and bookings to the view
//     return view('Admin.schedule.schedule', compact('drivers', 'viewBookings', 'vehiclesExist', 'sortedDrivers'));
// }



/////Assign a Driver
//     public function assignDriver(Request $request)
//     {
//         $bookingId = $request->input('booking_id');
//         $driverId = $request->input('driver_id');

//         $booking = Booking::find($bookingId);

//         if (!$booking) {
//             return redirect()->back()->with('error', 'Booking not found.');
//         }
    

//         $driver = Driver::find($driverId);

//         if (!$driver) {
//             return redirect()->back()->with('error', 'Driver not found.');
//         }

//         $member = $driver->member;

//         $paidCount = $member->payment()->where('status', 'paid')->count();
//         $acceptedCount = $driver->schedule()->where('driver_status', 'accepted')->count();

//         if ($paidCount > $acceptedCount) {

//             $vehicle = Vehicle::where('member_id', $member->id)
//                 ->whereHas('member.payment', function ($query) {
//                     $query->where('status', 'paid');
//                 })
//                 ->whereNotIn('id', function ($query) {
//                     $query->select('vehicle_id')->from('schedules')->where('driver_status', 'accepted');
//                 })
//                 ->first();

//             if (!$vehicle) {
//                 $vehicle = Vehicle::whereHas('member', function ($query) {
//                     $query->where('type', 'Owner')->whereHas('payment', function ($query) {
//                         $query->where('status', 'paid');
//                     });
//                 })
//                 ->whereNotIn('id', function ($query) {
//                     $query->select('vehicle_id')->from('schedules')->where('driver_status', 'accepted');
//                 })
//                 ->first();
//             }

//             if ($vehicle) {
//                 $existingSchedule = Schedule::where('book_id', $bookingId)
//                     ->where('driver_id', $driverId)
//                     ->first();

//                 if (!$existingSchedule) {
//                     Schedule::create([
//                         'book_id' => $booking->id,
//                         'driver_id' => $driver->id,
//                         'vehicle_id' => $vehicle->id,
//                         'cust_status' => 'active',
//                         'driver_status' => 'scheduled'
//                     ]);

//                     return redirect()->back()->with('success', 'Driver and vehicle assigned successfully.');
//                 } else {
//                     return redirect()->back()->with('error', 'This driver is already assigned to this booking.');
//                 }
//             } else {
//                 return redirect()->back()->with('error', 'No available vehicles with valid payment status.');
//             }
//         } else {
//             return redirect()->back()->with('error', 'Driver cannot be assigned due to insufficient paid dues.');
//         }
// }

public function assignDriver(Request $request)
{
    $bookingId = $request->input('booking_id');
    $driverId = $request->input('driver_id');

    // Find the booking by ID
    $booking = Booking::find($bookingId);

    if (!$booking) {
        return redirect()->back()->with('error', 'Booking not found.');
    }

    // Find the driver by ID
    $driver = Driver::find($driverId);

    if (!$driver) {
        return redirect()->back()->with('error', 'Driver not found.');
    }

    // Get the member associated with the driver
    $member = $driver->member;

    // Check if the driver has sufficient paid dues
    $paidCount = $member->payment()->where('status', 'paid')->count();
    $acceptedCount = $driver->schedule()->where('driver_status', 'accepted')->count();

    // If the driver has sufficient paid dues
    if ($paidCount > $acceptedCount) {

       // Check if the member exists in both Driver and Owner tables
    $isOwner = Owner::where('member_id', $member->id)->exists();

    if ($isOwner) {
        // Member exists in both Driver and Owner tables
        $vehicle = Vehicle::where('member_id', $member->id)
        ->whereHas('member.payment', function ($query) {
            $query->where('status', 'paid');
        })
        ->whereNotIn('id', function ($query) {
            $query->select('vehicle_id')
                ->from('schedules')
                ->where(function ($query) {
                    $query->where('driver_status', 'accepted')
                          ->orWhere('driver_status', 'scheduled');
                });
        })
        ->first();    
    } else {
        $vehicle = Vehicle::whereHas('member', function ($query) {
            $query->where('type', 'Owner')->whereHas('payment', function ($query) {
                $query->where('status', 'paid');
            });
        }) 
        ->whereNotIn('id', function ($query) {
            $query->select('vehicle_id')
                ->from('schedules')
                ->where(function ($query) {
                    $query->where('driver_status', 'accepted')
                          ->orWhere('driver_status', 'scheduled');
                });
        })
        ->first(); 
        // dd($vehicle);
    }

    if (!$vehicle) {
        return redirect()->back()->with('error', 'No available vehicle for this driver.');
    }

        if ($vehicle) {
            // Get the start and end date of the new booking
            $newStartDate = Carbon::parse($booking->start_date);
            $newEndDate = Carbon::parse($booking->end_date);

            // Check for any existing schedules for this driver that conflict with the new booking's dates
            $conflictingSchedules = Schedule::where('driver_id', $driver->id)
                ->where('driver_status', 'accepted') // Only check accepted schedules
                ->whereHas('booking', function ($query) use ($newStartDate, $newEndDate) {
                    // Find conflicting schedules based on start and end dates
                    $query->whereBetween('start_date', [$newStartDate, $newEndDate])
                        ->orWhereBetween('end_date', [$newStartDate, $newEndDate])
                        ->orWhere(function ($query) use ($newStartDate, $newEndDate) {
                            $query->where('start_date', '<=', $newStartDate)
                                ->where('end_date', '>=', $newEndDate);
                        });
                })
                ->get();

            if ($conflictingSchedules->isNotEmpty()) {
                // If there are conflicting schedules, create the new schedule with 'conflict' status
                foreach ($conflictingSchedules as $schedule) {
                    Schedule::create([
                        'book_id' => $booking->id,
                        'driver_id' => $driver->id,
                        'vehicle_id' => $vehicle->id, // Assign vehicle if found, else null
                        'cust_status' => 'active',
                        'driver_status' => 'conflict'  // Mark as conflict due to conflict
                    ]);
                }
            } else {
                // If there are no conflicts, create the new schedule with 'scheduled' status
                Schedule::create([
                    'book_id' => $booking->id,
                    'driver_id' => $driver->id,
                    'vehicle_id' => $vehicle->id,  // Assign vehicle if found, else null
                    'cust_status' => 'active',
                    'driver_status' => 'scheduled'  // Mark as scheduled
                ]);

                $member = $driver->member;

                if ($member) {
                    // Notify the customer
                    $member->notify(new newSchedule());
                }
                
            }

            return redirect()->back()->with('success', 'Driver and vehicle assigned successfully.');
        } else {
            return redirect()->back()->with('error', 'No available vehicle found for the driver.');
        }
    } else {
        return redirect()->back()->with('error', 'Driver cannot be assigned due to insufficient paid dues.');
    }
}


// Option Assign
public function optionAssignDriver(Request $request)
{
    $bookingId = $request->input('booking_id');
    $booking = Booking::find($bookingId);

    if (!$booking) {
        return redirect()->back()->with('error', 'Booking not found.');
    }

    $drivers = Driver::whereHas('member', function ($query) {
        $query->where('member_status', 'active');
    })->get();

    if ($drivers->isEmpty()) {
        return redirect()->back()->with('error', 'No active drivers available for scheduling.');
    }

    foreach ($drivers as $driver) {
        // if my vehicle yung driver then kunin yung first
        $vehicle = Vehicle::whereNotIn('id', function ($query) {
            $query->select('vehicle_id')
                  ->from('schedules')
                  ->whereIn('driver_status', ['accepted', 'scheduled']);
        })
        ->first();

        if (!$vehicle) {
            return redirect()->back()->with('error', 'No available vehicles for scheduling.');
        }
        
        $existingSchedule = Schedule::where('book_id', $bookingId)
            ->where('driver_id', $driver->id)
            ->first();

        if (!$existingSchedule) {
            Schedule::create([
                'book_id' => $booking->id,
                'driver_id' => $driver->id,
                'vehicle_id' => $vehicle->id,
                'cust_status' => 'active',
                'driver_status' => 'optionscheduled'
            ]);

            $member = $driver->member;
            // dd($member);
            if($member){
                $member->notify(new optionalSchedule());
            }
            
        }
    }
    

    return redirect()->back()->with('success', 'All active drivers have been assigned to the booking with status "optionscheduled".');
}





///////////////Member///////////////////////////////////////////////
public function memberdashboard()
{
    if (Auth::guard('member')->user()->pass == 'new') {
        // Logout the user
        // Auth::guard('member')->logout();

        // Redirect to change password page
        return redirect()->route('member.profile.changepassword1');
        
    }
    else{
    $member_id = Auth::guard('member')->user()->id;
    $memberType = Member::find($member_id);
    $driver = Driver::where('member_id', $member_id)->first();

    $schedules = Schedule::with(['booking', 'booking.user', 'driver.member', 'booking.tariff'])
        ->where('cust_status', 'active')
        ->where(function ($query) {
            $query->whereNull('driver_status')
                ->orWhere('driver_status', '!=', 'optionscheduled');
        })
        ->where(function ($query) use ($member_id) {
            $query->whereHas('driver.member', function ($q) use ($member_id) {
                $q->where('member_id', $member_id);
            });
        })
        ->get();

        $scheduless = Schedule::with(['booking', 'booking.user', 'driver.member', 'booking.tariff'])
        ->where('cust_status', 'active')
        ->where('driver_status', 'optionscheduled') 
        ->where(function ($query) use ($member_id) {
            $query->whereHas('driver.member', function ($q) use ($member_id) {
                $q->where('member_id', $member_id);
            });
        })
        ->get();
        // dd($scheduless);

        $schednotifcount = $scheduless->count();

    return view('member.dashboard', compact('schedules', 'memberType', 'scheduless', 'schednotifcount'));
}
}

// public function memberdashboard()
// {
//     if (Auth::guard('member')->user()->pass == 'new') {
//         // Logout the user
//         // Auth::guard('member')->logout();

//         // Redirect to change password page
//         return redirect()->route('member.profile.changepassword1');
        
//     }
//     else{
//     $member_id = Auth::guard('member')->user()->id;
//     $memberType = Member::find($member_id);
//     $driver = Driver::where('member_id', $member_id)->first();

//     $schedules = Schedule::with(['booking', 'booking.user', 'driver.member', 'vehicle.member', 'booking.tariff'])
//         ->where('cust_status', 'active')
//         ->where(function ($query) {
//             $query->whereNull('driver_status')
//                 ->orWhere('driver_status', '!=', 'optionscheduled');
//         })
//         ->where(function ($query) use ($member_id) {
//             $query->whereHas('driver.member', function ($q) use ($member_id) {
//                 $q->where('member_id', $member_id);
//             })
//             ->orWhereHas('vehicle.member', function ($q) use ($member_id) {
//                 $q->where('member_id', $member_id);
//             });
//         })
//         ->get();

//         $scheduless = Schedule::with(['booking', 'booking.user', 'driver.member', 'booking.tariff'])
//         ->where('cust_status', 'active')
//         ->where('driver_status', 'optionscheduled') 
//         ->where(function ($query) use ($member_id) {
//             $query->whereHas('driver.member', function ($q) use ($member_id) {
//                 $q->where('member_id', $member_id);
//             });
//         })
//         ->get();
//         // dd($scheduless);

//         $schednotifcount = $scheduless->count();

//     return view('member.dashboard', compact('schedules', 'memberType', 'scheduless', 'schednotifcount'));
// }
// }


//////Acceptbooking
public function acceptBooking(Request $request, $bookingId)
    {
        $schedule = Booking::find($bookingId);
        $schedule->status = 'accepted';
        $schedule->save();

        $customer = $schedule->user;

        if ($customer) {
            // Notify the customer
            $message = 'Your booking has been accepted.';
            $customer->notify(new reservationAccept($message));
        }
        return redirect()->back();
    }

//////Declinebooking
public function rejectBooking(Request $request, $bookingId)
    {
        $schedule = Booking::find($bookingId);
        $schedule->status = 'rejected';
        $schedule->save();
        $customer = $schedule->user;

        if ($customer) {
            // Notify the customer
            $customer->notify(new reservationDeclined());
        }

        return redirect()->back();
    }



/////accept schedule
    public function acceptSchedule(Request $request, $scheduleId)
    {
        $schedule = Schedule::find($scheduleId);
        $schedule->driver_status = 'accepted';
        $schedule->save();

        $customer = $schedule->booking ? $schedule->booking->user : null;
        // dd($customer);
        if ($customer) {
            // Notify the customer
            $customer->notify(new reservationDriver());

            $admin = Admin::where('email', 'mbtransportcooperative@gmail.com')->first();
            // dd($admin);
            if ($admin) {
                $admin->notify(new reservationDriverNotification());
            }
        }

        return redirect()->route('member.dashboard')->with('success', 'Schedule accepted successfully.');
    }

/////cancel schedule
    public function cancelSchedule(Request $request, $scheduleId)
    {
        $schedule = Schedule::find($scheduleId);
        $schedule->driver_status = 'cancelled';
        $schedule->save();

        $admin = Admin::where('email', 'mbtransportcooperative@gmail.com')->first();
            // dd($admin);
            if ($admin) {
                $admin->notify(new declinedDriverSchedule());
            }

        return redirect()->route('member.dashboard')->with('success', 'Schedule cancelled successfully.');
    }

/////accept schedule
public function optionSchedule(Request $request, $scheduleId)
{
    $schedule = Schedule::find($scheduleId);

    if (!$schedule) {
        return redirect()->route('member.dashboard')->with('error', 'Schedule not found.');
    }
    $driverId = $schedule->driver_id;
    $bookId = $schedule->book_id; 

    $schedule->driver_status = 'accepted';
    $schedule->save();

    Schedule::where('book_id', $bookId) 
        ->where('driver_status', 'optionscheduled')
        ->where('driver_id', '!=', $driverId)
        ->where('id', '!=', $schedule->id)
        ->delete();

    return redirect()->route('member.dashboard')->with('success', 'Schedule accepted successfully, and conflicting schedules have been removed.');
}



/////monthly dues
    public function memberMonthlyDues() {
        $member_id = Auth::guard('member')->user()->id;
        $memberType = Member::find($member_id);
        if (Auth::guard('member')->user()->pass == 'new') {
            // Auth::guard('member')->logout();
    
            return redirect()->route('member.profile.changepassword1');
            
        }
        $member_id = Auth::guard('member')->user()->id;
    
        $membermonthlydues = Payment::with(['member', 'dues'])
            ->where('member_id', $member_id)
            ->get();
    
        return view('member.membermonthlydues', compact('membermonthlydues', 'memberType'));
    }
    

// public function deleteOldImages()
//     {
//         // $directory = public_path('img'); // Path to your images folder
//         // $files = File::files($directory);

//         // $now = Carbon::now();

//         // foreach ($files as $file) {
//         //     $lastModified = Carbon::createFromTimestamp(File::lastModified($file));
//         //     $diffInMonths = $now->diffInMonths($lastModified);

//         //     if ($diffInMonths >= 1) {
//         //         File::delete($file);
//         //     }
//         // }
//         $directory = public_path('img'); // Path to your images folder
//         $files = File::files($directory);

//         foreach ($files as $file) {
//             File::delete($file); // Delete each file
//         }

//         return response()->json(['message' => 'All images deleted successfully.']);

//         // return response()->json(['message' => 'Old images deleted successfully.']);
//     }
}
