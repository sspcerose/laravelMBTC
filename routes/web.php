<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\MemberProfileController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\MBTCController;
use Illuminate\Support\Facades\Route;
use App\Http\Middleware\EnsureEmailIsVerified;
use App\Http\Controllers\MemberAuth\PasswordController;

// use Illuminate\Foundation\Auth\EmailVerificationRequest;
// use App\Http\Controllers\Auth\EmailVerificationNotificationController;

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


//////////////////////USER/////////////////////////////
Route::get('/dashboard', [MBTCController::class, 'bookingform'])->middleware(['auth:web', 'verified'])->name('dashboard');
Route::get('/', [MBTCController::class, 'bookingform'])->name('bookingdashboard');



Route::middleware(['auth:web'])->group(function () {

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






//////////////////////ADMIN/////////////////////////////

Route::get('/admin/dashboard', [MBTCController::class, 'dashboard'])
    ->middleware(['auth:admin', 'verified'])
    ->name('admin.dashboard');

Route::middleware(['auth:admin'])->group(function () {
    Route::get('/member', function () {
        return view('member.welcome');
    })->name('member.welcome');

    Route::get('/admin/adminwelcome', function () {
        return view('admin.adminwelcome');
    });
    // member page
    Route::get('/admin/member/member', [MBTCController::class, 'viewmember'])->name('admin.member.member');
    Route::post('/admin/member/member/{id}/archive', [MBTCController::class, 'archivemember'])->name('archivemember');
    Route::get('/admin/member/archivemember', [MBTCController::class, 'viewarchivemember']);
    Route::post('/admin/member/archivemember/{id}', [MBTCController::class, 'unarchivemember']);

    // tariff page
    Route::get('admin/tariff/tariff', [MBTCController::class, 'viewtariff'])->name('admin.tariff.tariff');
    Route::get('admin/tariff/addtariff', [MBTCController::class, 'addtariffform']);
    Route::post('admin/tariff/addtariff', [MBTCController::class, 'addtariff'])->name('admin.tariff.addtariff');
    Route::get('admin/tariff/archivetariff', [MBTCController::class, 'viewarchivetariff']);
    Route::post('admin/tariff/{id}/archive', [MBTCController::class, 'archiveTariff'])->name('archiveTariff');
    Route::post('admin/tariff/archivetariff/{id}', [MBTCController::class, 'unarchiveTariff']);
    Route::get('admin/tariff/updatetariff/{id}', [MBTCController::class, 'updateform']);
    Route::post('admin/tariff/updatetariff/{id}', [MBTCController::class, 'updatetariff']);

    //vehicle page
    Route::get('admin/vehicle/vehicle', [MBTCController::class, 'viewVehicle'])->name('admin.vehicle.vehicle');
    Route::get('admin/vehicle/addvehicle', [MBTCController::class, 'addvehicleform']);
    Route::post('admin/vehicle/addvehicle', [MBTCController::class, 'addvehicle'])->name('admin.vehicle.addvehicle');;
    Route::get('admin/vehicle/updatevehicle/{id}', [MBTCController::class, 'updatevehicleform']);
    Route::post('admin/vehicle/updatevehicle/{id}', [MBTCController::class, 'updateVehicle']);
    Route::get('admin/vehicle/archivevehicle', [MBTCController::class, 'viewarchivevehicle']);
    Route::post('admin/vehicle/{id}/archive', [MBTCController::class, 'archiveVehicle'])->name('archiveVehicle');
    Route::post('admin/vehicle/archivevehicle/{id}', [MBTCController::class, 'unarchiveVehicle']);

    //booking page
    Route::get('admin/booking/booking', [MBTCController::class, 'adminbookingpage'])->name('admin.booking.booking');
    Route::post('/booking/{bookingId}/accept', [MBTCController::class, 'acceptBooking'])->name('booking.accept');
    Route::post('/booking/{bookingId}/reject', [MBTCController::class, 'rejectBooking'])->name('booking.reject');

    //monthlydues page
    Route::get('admin/monthlydues/monthlydues', [MBTCController::class, 'viewMonthlyDues'])->name('admin.monthlydues.monthlydues');
    Route::get('admin/monthlydues/viewallmonthlydues', [MBTCController::class, 'viewAllMonthlyDues'])->name('admin.monthlydues.viewallmonthlydues');
    Route::post('admin/monthlydues/monthlydues/{id}', [MBTCController::class, 'confirmPayment']);

    //schedule page
    Route::get('admin/schedule/schedule', [MBTCController::class, 'viewSchedule'])->name('admin.schedule.schedule');
    Route::post('admin/schedule/schedule', [MBTCCOntroller::class, 'assignDriver']);
    Route::post('admin/schedule/optionschedule', [MBTCCOntroller::class, 'optionAssignDriver']);
    Route::post('admin/schedule/optionschedule1', [MBTCCOntroller::class, 'optionAssignDriver1']);

    // customer page
    Route::get('/admin/customer/customer', [MBTCController::class, 'viewCustomer'])->name('admin.customer.customer');

    // delete img
    // Route::get('admin/booking/booking', [MBTCCOntroller::class, 'deleteOldImages']);
    
});

require __DIR__.'/adminauth.php';

//////////////////////MEMBER/////////////////////////////
// Route::get('/member/dashboard', [MBTCController::class, 'memberdashboard'])->middleware(['member', 'ensureEmailIsVerified:member, member.verification.notice'])->name('member.dashboard');

// Route::middleware(['member', 'ensureEmailIsVerified:member'])->group(function () {
//     Route::get('/member/welcome', function () {
//         return view('member.welcome');
//     });
//     Route::post('/schedule/{scheduleId}/accept', [MBTCController::class, 'acceptSchedule'])->name('schedule.accept');
//     Route::post('/schedule/{scheduleId}/cancel', [MBTCController::class, 'cancelSchedule'])->name('schedule.cancel');
//     Route::post('/schedule/{scheduleId}/optionaccept', [MBTCController::class, 'optionSchedule'])->name('optionschedule.accept');
//     Route::get('member/profile', [MemberProfileController::class, 'edit'])->name('member.profile.edit');
//     Route::patch('member/profile', [MemberProfileController::class, 'update'])->name('member.profile.update');
//     Route::delete('member/profile', [MemberProfileController::class, 'destroy'])->name('member.profile.destroy');
//     Route::get('member/membermonthlydues', [MBTCController::class, 'memberMonthlyDues'])->name('member.membermonthlydues');
//     Route::post('member/membermonthlydues/{id}', [MBTCController::class, 'sendPaymentUpdate'])->name('monthlydue.update');

//      //dagdag from here 
//      //to here
    
// });

Route::get('/member/dashboard', [MBTCController::class, 'memberdashboard'])
    ->middleware(['member', 'ensureEmailIsVerified:member,member.verification.notice'])
    ->name('member.dashboard');

Route::middleware(['member', 'ensureEmailIsVerified:member,member.verification.notice'])->group(function () {
    Route::get('/member/welcome', function () {
        return view('member.welcome');
    });
    
    Route::post('/schedule/{scheduleId}/accept', [MBTCController::class, 'acceptSchedule'])
        ->name('member.schedule.accept');
    Route::post('/schedule/{scheduleId}/cancel', [MBTCController::class, 'cancelSchedule'])
        ->name('member.schedule.cancel');
    Route::post('/schedule/{scheduleId}/optionaccept', [MBTCController::class, 'optionSchedule'])
        ->name('member.optionschedule.accept');

    Route::get('/member/profile', [MemberProfileController::class, 'edit'])
        ->name('member.profile.edit');
    Route::patch('/member/profile', [MemberProfileController::class, 'update'])
        ->name('member.profile.update');
    Route::delete('/member/profile', [MemberProfileController::class, 'destroy'])
        ->name('member.profile.destroy');
        
    Route::get('/member/membermonthlydues', [MBTCController::class, 'memberMonthlyDues'])
        ->name('member.membermonthlydues');
    Route::post('/member/membermonthlydues/{id}', [MBTCController::class, 'sendPaymentUpdate'])
        ->name('member.monthlydue.update');
});

Route::get('member/profile/changepassword1', [MemberProfileController::class, 'showChangePasswordForm'])->name('member.profile.changepassword1');
Route::get('member/change-password', [PasswordController::class, 'showChangePasswordForm'])->name('change.password1');
Route::put('member/change-password', [PasswordController::class, 'updatePassword'])->name('password.update1');

require __DIR__.'/memberauth.php';

//////FOR TESTING///////

Route::get('/ForTesting/userHome', function () {
    return view('Fortesting.userHome');
})->name('ForTesting.userHome');

Route::get('/ForTesting/userLogin', function () {
    return view('ForTesting.userLogin');
});

Route::get('/ForTesting/userSignUp', function () {
    return view('ForTesting.userSignUp');
});

Route::get('/ForTesting/adminLogin', function () {
    return view('FOrTesting.adminLogin');
});

Route::get('/ForTesting/memberLogin', function () {
    return view('ForTesting.memberLogin');
});
Route::get('/ForTesting/memberHome', function () {
    return view('ForTesting.memberHome');
});
Route::get('/ForTesting/UserBooking', function () {
    return view('ForTesting.UserBooking');
});

Route::get('/ForTesting/UserBookings', function () {
    return view('ForTesting.UserBookings');
});

Route::get('/ForTesting/UserProfile', function () {
    return view('ForTesting.UserProfile');
});

Route::get('/ForTesting/MemberDues', function () {
    return view('ForTesting.MemberDues');
});

Route::get('/ForTesting/AdminBooking', function () {
    return view('ForTesting.AdminBooking');
})->name('ForTesting.AdminBooking');

Route::get('/ForTesting/AdminHome', function () {
    return view('ForTesting.AdminHome');
})->name('ForTesting.AdminHome');

Route::get('/ForTesting/DriverSchedule', function () {
    return view('ForTesting.DriverSchedule');
})->name('ForTesting.DriverSchedule');

Route::get('/ForTesting/AdminMembers', function () {
    return view('ForTesting.AdminMembers');
})->name('ForTesting.AdminMembers');

Route::get('/ForTesting/AddMember', function () {
    return view('ForTesting.AddMember');
})->name('ForTesting.AddMember');

Route::get('/ForTesting/ArchiveMember', function () {
    return view('ForTesting.ArchiveMember');
})->name('ForTesting.ArchiveMember');

Route::get('/ForTesting/AdminMonthlyDue', function () {
    return view('ForTesting.AdminMonthlyDue');
})->name('ForTesting.AdminMonthlyDue');

Route::get('/ForTesting/AdminTariff', function () {
    return view('ForTesting.AdminTariff');
})->name('ForTesting.AdminTariff');

Route::get('/ForTesting/AddTariff', function () {
    return view('ForTesting.AddTariff');
})->name('ForTesting.AddTariff');

Route::get('/ForTesting/AdminVehicle', function () {
    return view('ForTesting.AdminVehicle');
})->name('ForTesting.AdminVehicle');

Route::get('/ForTesting/AddVehicle', function () {
    return view('ForTesting.AddVehicle');
})->name('ForTesting.AddVehicle');

Route::get('/ForTesting/notif', function () {
    return view('ForTesting.notif');
});


///////////


//member



// might be useful 
// Route::get('/member/dashboard', function () {
//     return view('member.dashboard');
// })->middleware(['auth:member', 'verified'])->name('member.dashboard');

// Route::get('/admin/member/member', function () {
//     return view('admin.member.member');
// })->middleware(['auth:admin', 'verified'])->name('admin.member.member');

// Route::middleware('adminauth')->group(function () {
//     Route::get('admin/profile', [AdminController::class, 'edit'])->name('profile.edit');
//     Route::patch('admin/profile', [AdminController::class, 'update'])->name('profile.update');
//     Route::delete('admin/profile', [AdminController::class, 'destroy'])->name('profile.destroy');
// });


