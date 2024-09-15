<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Booking;
use App\Models\Member;
use App\Models\qrcode;
use App\Models\Tariff;
use App\Models\Vehicle;
use App\Models\Owner;
use App\Models\Dues;
use App\Models\Payment;


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
                $viewBookings = Booking::where('status', 'active')
                    ->whereHas('user', function ($query) use ($search) {
                        $query->where('name', 'LIKE', '%' . $search . '%')
                              ->orWhere('last_name', 'LIKE', '%' . $search . '%');
                    })
                    ->orWhere('passenger', 'LIKE', '%' . $search . '%')
                    ->orWhere('location', 'LIKE', '%' . $search . '%')
                    ->orWhere('destination', 'LIKE', '%' . $search . '%')
                    ->get();
            } else {
                $viewBookings  = Booking::where('status', 'active')->get();
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

        

}
