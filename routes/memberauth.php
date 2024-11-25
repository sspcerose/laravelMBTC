<?php

use App\Http\Controllers\MemberAuth\AuthenticatedSessionController;
use App\Http\Controllers\MemberAuth\ConfirmablePasswordController;
use App\Http\Controllers\MemberAuth\EmailVerificationNotificationController;
use App\Http\Controllers\MemberAuth\EmailVerificationPromptController;
use App\Http\Controllers\MemberAuth\NewPasswordController;
use App\Http\Controllers\MemberAuth\PasswordController;
use App\Http\Controllers\MemberAuth\PasswordResetLinkController;
use App\Http\Controllers\MemberAuth\RegisteredUserController;
use App\Http\Controllers\MemberAuth\VerifyEmailController;
use Illuminate\Support\Facades\Route;



Route::get('admin/member/register', [RegisteredUserController::class, 'create'])
       ->name('admin.member.auth.register');
Route::post('admin/member/register', [RegisteredUserController::class, 'store']);

Route::get('member/login', [AuthenticatedSessionController::class, 'create'])
        ->name('member.auth.login');

    Route::post('member/login', [AuthenticatedSessionController::class, 'store']);

Route::group([
    'middleware' => ['member'],
    'as' => 'member.',
    'prefix' => 'member',
], function(){
// Route::middleware('guest:web')->group(function () {
    

    Route::get('forgot-password', [PasswordResetLinkController::class, 'create'])
        ->name('password.request');

    Route::post('forgot-password', [PasswordResetLinkController::class, 'store'])
        ->name('password.email');

    Route::get('reset-password/{token}', [NewPasswordController::class, 'create'])
        ->name('password.reset');

    Route::post('reset-password', [NewPasswordController::class, 'store'])
        ->name('password.store');

    Route::get('verify-email', EmailVerificationPromptController::class)
        ->name('verification.notice');

    Route::get('verify-email/{id}/{hash}', VerifyEmailController::class)
        ->middleware(['signed', 'throttle:6,1'])
        ->name('verification.verify');

    Route::post('email/verification-notification', [EmailVerificationNotificationController::class, 'store'])
        ->middleware('throttle:6,1')
        ->name('verification.send');

    Route::post('logout', [AuthenticatedSessionController::class, 'destroy'])
        ->name('logout');
});

// Route::middleware('auth:web')->group(function () {
//     Route::get('verify-email', EmailVerificationPromptController::class)
//         ->name('verification.notice');

//     Route::get('verify-email/{id}/{hash}', VerifyEmailController::class)
//         ->middleware(['signed', 'throttle:6,1'])
//         ->name('verification.verify');

//     Route::post('email/verification-notification', [EmailVerificationNotificationController::class, 'store'])
//         ->middleware('throttle:6,1')
//         ->name('verification.send');

//     Route::get('confirm-password', [ConfirmablePasswordController::class, 'show'])
//         ->name('password.confirm');

//     Route::post('confirm-password', [ConfirmablePasswordController::class, 'store']);

//     Route::put('password', [PasswordController::class, 'update'])->name('password.update');

//     Route::post('logout', [AuthenticatedSessionController::class, 'destroy'])
//         ->name('logout');
// });

