<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\MemberProfileController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\MBTCController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

//////////////////////USER/////////////////////////////
Route::get('/dashboard', [MBTCController::class, 'bookingform'])->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::get('/booking-form', [MBTCController::class, 'showBookingForm']);
    Route::post('bookingform', [MBTCController::class, 'bookingform']);
    Route::post('/calculate-price', [MBTCController::class, 'calculatePrice']);
    Route::get('userbookingpage', [MBTCController::class, 'userbookingpage'])->name('booking');
    Route::post('cancelbooking/{id}', [MBTCController::class, 'cancelbooking'])->name('cancelbooking');
});

require __DIR__.'/auth.php';


// Route::middleware('adminauth')->group(function () {
//     Route::get('admin/profile', [AdminController::class, 'edit'])->name('profile.edit');
//     Route::patch('admin/profile', [AdminController::class, 'update'])->name('profile.update');
//     Route::delete('admin/profile', [AdminController::class, 'destroy'])->name('profile.destroy');
// });


//////for testing

Route::get('/user/userHome', function () {
    return view('user.userHome');
})->name('user.userHome');

Route::get('/user/userLogin', function () {
    return view('user.userLogin');
});

Route::get('/user/userSignUp', function () {
    return view('user.userSignUp');
});

Route::get('/user/adminLogin', function () {
    return view('user.adminLogin');
});

Route::get('/user/memberLogin', function () {
    return view('user.memberLogin');
});

///////////



Route::get('/member/welcome', function () {
    return view('member.welcome');
})->name('member.welcome');


//////////////////////ADMIN/////////////////////////////
Route::get('/admin/adminwelcome', function () {
    return view('admin.adminwelcome');
});

Route::get('/admin/dashboard', function () {
    return view('admin.dashboard');
})->middleware(['auth:admin', 'verified'])->name('admin.dashboard');

Route::middleware(['auth:admin'])->group(function () {
    // member
    Route::get('/admin/member/member', [MBTCController::class, 'viewmember'])->name('admin.member.member');
    Route::post('/admin/member/member/{id}/archive', [MBTCController::class, 'archivemember'])->name('archivemember');
    Route::get('/admin/member/archivemember', [MBTCController::class, 'viewarchivemember']);
    Route::post('/admin/member/archivemember/{id}', [MBTCController::class, 'unarchivemember']);

    // tariff
    Route::get('admin/tariff/tariff', [MBTCController::class, 'viewtariff'])->name('admin.tariff.tariff');
    Route::get('admin/tariff/addtariff', [MBTCController::class, 'addtariffform']);
    Route::post('admin/tariff/addtariff', [MBTCController::class, 'addtariff']);
    Route::get('admin/tariff/archivetariff', [MBTCController::class, 'viewarchivetariff']);
    Route::post('admin/tariff/{id}/archive', [MBTCController::class, 'archiveTariff'])->name('archiveTariff');
    Route::post('admin/tariff/archivetariff/{id}', [MBTCController::class, 'unarchiveTariff']);
    Route::get('admin/tariff/updatetariff/{id}', [MBTCController::class, 'updateform']);
    Route::post('admin/tariff/updatetariff/{id}', [MBTCController::class, 'updatetariff']);

    //vehicle
    Route::get('admin/vehicle/vehicle', [MBTCController::class, 'viewVehicle'])->name('admin.vehicle.vehicle');
    Route::get('admin/vehicle/addvehicle', [MBTCController::class, 'addvehicleform']);
    Route::post('admin/vehicle/addvehicle', [MBTCController::class, 'addvehicle']);
    Route::get('admin/vehicle/updatevehicle/{id}', [MBTCController::class, 'updatevehicleform']);
    Route::post('admin/vehicle/updatevehicle/{id}', [MBTCController::class, 'updateVehicle']);
    Route::get('admin/vehicle/archivevehicle', [MBTCController::class, 'viewarchivevehicle']);
    Route::post('admin/vehicle/{id}/archive', [MBTCController::class, 'archiveVehicle'])->name('archiveVehicle');
    Route::post('admin/vehicle/archivevehicle/{id}', [MBTCController::class, 'unarchiveVehicle']);

    //booking 
    Route::get('admin/booking/booking', [MBTCController::class, 'adminbookingpage'])->name('admin.booking.booking');

    //monthlydues
    Route::get('admin/monthlydues/monthlydues', [MBTCController::class, 'viewMonthlyDues'])->name('admin.monthlydues.monthlydues');
    Route::post('admin/monthlydues/monthlydues/{id}', [MBTCController::class, 'confirmPayment']);

    //schedule

    Route::get('admin/schedule/schedule', [MBTCController::class, 'viewSchedule'])->name('admin.schedule.schedule');
    Route::post('admin/schedule/schedule', [MBTCCOntroller::class, 'assignDriver']);
    
});

// Route::get('/admin/member/member', function () {
//     return view('admin.member.member');
// })->middleware(['auth:admin', 'verified'])->name('admin.member.member');

require __DIR__.'/adminauth.php';



//member
Route::get('/member/welcome', function () {
    return view('member.welcome');
});

// Route::get('/member/dashboard', function () {
//     return view('member.dashboard');
// })->middleware(['auth:member', 'verified'])->name('member.dashboard');

//////////////////////MEMBER/////////////////////////////
Route::get('/member/dashboard', [MBTCController::class, 'memberdashboard'])->middleware(['auth:member', 'verified'])->name('member.dashboard');

Route::middleware(['auth:member'])->group(function () {
    Route::post('/schedule/{scheduleId}/accept', [MBTCController::class, 'acceptSchedule'])->name('schedule.accept');
    Route::post('/schedule/{scheduleId}/cancel', [MBTCController::class, 'cancelSchedule'])->name('schedule.cancel');
    Route::get('member/profile', [MemberProfileController::class, 'edit'])->name('member.profile.edit');
    Route::patch('member/profile', [MemberProfileController::class, 'update'])->name('member.profile.update');
    Route::delete('member/profile', [MemberProfileController::class, 'destroy'])->name('member.profile.destroy');
    Route::get('member/membermonthlydues', [MBTCController::class, 'memberMonthlyDues'])->name('member.membermonthlydues');
});
require __DIR__.'/memberauth.php';

