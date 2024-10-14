<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Auth;

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

        $bookReceipt = null;
        if ($request->hasFile('receipt')) {
            $receipt = $request->file('receipt');
            $bookReceipt = time() . '.' . $receipt->getClientOriginalExtension();
            $receipt->move(public_path('img'), $bookReceipt);
        }
    

        Booking::create([
            'customer_id' => auth()->id(),
            'tariff_id' => $selectedTariff->id,
            'passenger' => $request->passenger,
            'location' => $request->location,
            'destination' => $selectedTariff->destination,
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
            'status' => 'active',
            'price' => $total_price,
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

    

// calculated price 
public function calculatePrice(Request $request) {
    // Validate the incoming request
    $request->validate([
        'id' => 'required|exists:tariffs,id',
        'start_date' => 'required|date',
        'end_date' => 'required|date|after_or_equal:start_date',
    ]);

    // Retrieve the tariff based on the selected destination
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
    
        $price = ($days > 1) ? $rate + ($succeeding * ($days - 1)) : $rate;

    return response()->json(['price' => $price]);
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

        return view('Admin.member.member', compact('viewmembers'));
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

        return view('Admin.member.archivemember', compact('viewmembers'));
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
        
            return view('Admin.member.archivemember', compact('viewmembers'));
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
    
        return view('Admin.tariff.tariff', compact('viewtariffs'));
    }

/////Add Tariff
    public function addtariff(Request $request){

        Tariff::create ([
            'destination' => $request->destination,
            'rate' => $request->rate,
            'succeeding' => $request->succeeding,
            'status' => $request->status
        ]);

        return redirect()->route('admin.tariff.tariff')->with('success', 'Tariff added successfully.');

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
            $updatetariff->update([
                'destination' => $request->destination,
                'rate' => $request->rate,
                'succeeding' => $request->succeeding
            ]);

        }
        
        return redirect()->back();
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
    
        return view('Admin.vehicle.vehicle', compact('viewVehicles'));
    }

    public function addvehicleform(){

        $activeMembers = Member::where('member_status', 'active')->get();
            return view('admin/vehicle/addvehicle', compact('activeMembers'));

        // return view('admin.vehicle.addvehicle');
    }
    
/////Unarchive a vehicle
    public function addvehicle(Request $request)
    {
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
    
    
        return redirect()->route('admin.vehicle.vehicle')->with('success', 'Vehicle added successfully.');
    }


    public function updatevehicleform($id) {
        $activeMembers = Member::where('member_status', 'active')->get();
        $viewVehicles = Vehicle::find($id);
        return view('admin.vehicle.updatevehicle', compact('viewVehicles', 'activeMembers'));
    }

/////Update a vehicle
    public function updateVehicle(Request $request, $id) {
        $updateVehicle = Vehicle::find($id);
    
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
    
        return redirect()->back();
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
            
        
            return view('admin.booking.booking', compact('viewBookings'));

        }


///////////////Admin Monthly Dues///////////////////////////////////////////////
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
                $amount = 500.00;
        
                $existingDues = Dues::where('date', $currentMonth)->first();
        
                if (!$existingDues) {
                 
                    $newDues = Dues::create([
                        'date' => $currentMonth,
                        'amount' => $amount
                    ]);
                } else {
                    $newDues = $existingDues; 
                }
        
                if ($newDues && $newDues->wasRecentlyCreated) {
                  
                    $members = Member::where('member_status', 'active')->get();  
        
                    foreach ($members as $member) {
                     
                        $lastPayment = Payment::where('member_id', $member->id)
                                               ->orderBy('last_payment', 'desc')
                                               ->first();
        
                        Payment::create([
                            'member_id' => $member->id,
                            'dues_id' => $newDues->id,
                            'last_payment' => $lastPayment ? $lastPayment->last_payment : null,
                            'status' => 'unpaid'
                        ]);
                    }
                }
        
           
                $payments = Payment::with('member', 'dues')
                    ->whereHas('dues', function ($query) use ($currentMonth) {
                        $query->where('date', $currentMonth);
                    })
                    ->get();
        
                return view('admin.monthlydues.monthlydues', compact('payments'));
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


///////////////Admin Monthly Dues///////////////////////////////////////////////

public function viewSchedule(Request $request) {
    $members = Member::where('type', 'Driver')
        ->with(['driver.schedule', 'payment']) 
        ->get();


    $drivers = $members->filter(function ($member) {
        if ($member->driver) {
            foreach ($member->driver as $driver) {
             
                $scheduledCount = $driver->schedule()
                                    ->where('driver_status', 'scheduled')
                                    ->count();
                $acceptedCount = $driver->schedule()
                                  ->where('driver_status', 'accepted')
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

    $viewBookings = Booking::where('status', 'active')
        ->with(['schedule' => function ($query) {
            $query->orderBy('created_at', 'desc'); 
        }, 'schedule.driver.member', 'user']) 
        ->get();

    return view('Admin.schedule.schedule', compact('drivers', 'viewBookings'));
}


/////Schedule
    public function assignDriver(Request $request)
    {
        $bookingId = $request->input('booking_id');
        $driverId = $request->input('driver_id');

        $booking = Booking::find($bookingId);

        if (!$booking) {
            return redirect()->back()->with('error', 'Booking not found.');
        }
    

        $driver = Driver::find($driverId);

        if (!$driver) {
            return redirect()->back()->with('error', 'Driver not found.');
        }

        $member = $driver->member;

        $paidCount = $member->payment()->where('status', 'paid')->count();
        $acceptedCount = $driver->schedule()->where('driver_status', 'accepted')->count();

        if ($paidCount > $acceptedCount) {

            $vehicle = Vehicle::where('member_id', $member->id)
                ->whereHas('member.payment', function ($query) {
                    $query->where('status', 'paid');
                })
                ->whereNotIn('id', function ($query) {
                    $query->select('vehicle_id')->from('schedules')->where('driver_status', 'accepted');
                })
                ->first();

            if (!$vehicle) {
                $vehicle = Vehicle::whereHas('member', function ($query) {
                    $query->where('type', 'Owner')->whereHas('payment', function ($query) {
                        $query->where('status', 'paid');
                    });
                })
                ->whereNotIn('id', function ($query) {
                    $query->select('vehicle_id')->from('schedules')->where('driver_status', 'accepted');
                })
                ->first();
            }

            if ($vehicle) {
                $existingSchedule = Schedule::where('book_id', $bookingId)
                    ->where('driver_id', $driverId)
                    ->first();

                if (!$existingSchedule) {
                    Schedule::create([
                        'book_id' => $booking->id,
                        'driver_id' => $driver->id,
                        'vehicle_id' => $vehicle->id,
                        'cust_status' => 'active',
                        'driver_status' => 'scheduled'
                    ]);

                    return redirect()->back()->with('success', 'Driver and vehicle assigned successfully.');
                } else {
                    return redirect()->back()->with('error', 'This driver is already assigned to this booking.');
                }
            } else {
                return redirect()->back()->with('error', 'No available vehicles with valid payment status.');
            }
        } else {
            return redirect()->back()->with('error', 'Driver cannot be assigned due to insufficient paid dues.');
        }
}







///////////////Member///////////////////////////////////////////////
public function memberdashboard()
{
    $member_id = Auth::guard('member')->user()->id;

    $schedules = Schedule::with(['booking', 'booking.user', 'driver.member', 'booking.tariff'])
        ->where('cust_status', 'active')
        ->whereHas('driver.member', function ($query) use ($member_id) {
            $query->where('member_id', $member_id);
        })
        ->get();

    return view('member.dashboard', compact('schedules'));
}


    public function acceptSchedule(Request $request, $scheduleId)
    {
        $schedule = Schedule::find($scheduleId);
        $schedule->driver_status = 'accepted';
        $schedule->save();

        return redirect()->route('member.dashboard')->with('success', 'Schedule accepted successfully.');
    }

    
    public function cancelSchedule(Request $request, $scheduleId)
    {
        $schedule = Schedule::find($scheduleId);
        $schedule->driver_status = 'cancelled';
        $schedule->save();

        return redirect()->route('member.dashboard')->with('success', 'Schedule cancelled successfully.');
    }

    public function memberMonthlyDues() {
        $member_id = Auth::guard('member')->user()->id;
    
        $membermonthlydues = Payment::with(['member', 'dues'])
            ->where('member_id', $member_id)
            ->get();
    
        return view('member.membermonthlydues', compact('membermonthlydues'));
    }
    
}
