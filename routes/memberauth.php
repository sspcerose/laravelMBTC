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

Route::middleware('guest:member')->group(function () {
    Route::get('admin/member/register', [RegisteredUserController::class, 'create'])
                ->name('admin.member.auth.register');

    Route::post('admin/member/register', [RegisteredUserController::class, 'store']);

    Route::get('member/login', [AuthenticatedSessionController::class, 'create'])
                ->name('member.auth.login');
                
    Route::get('member', [AuthenticatedSessionController::class, 'create'])
                ->name('member.auth.login');

    Route::post('member/login', [AuthenticatedSessionController::class, 'store']);

    Route::get('member/forgot-password', [PasswordResetLinkController::class, 'create'])
                ->name('member.password.request');

    Route::post('member/forgot-password', [PasswordResetLinkController::class, 'store'])
                ->name('member.password.email');

    Route::get('member/reset-password/{token}', [NewPasswordController::class, 'create'])
                ->name('password.reset');

    Route::post('member/reset-password', [NewPasswordController::class, 'store'])
                ->name('password.store');
});

Route::middleware('auth:member')->group(function () {
    Route::get('member/verify-email', EmailVerificationPromptController::class)
                ->name('member.verification.notice');

    Route::get('member/verify-email/{id}/{hash}', VerifyEmailController::class)
                ->middleware(['signed', 'throttle:6,1'])
                ->name('member.verification.verify');

    Route::post('member/email/verification-notification', [EmailVerificationNotificationController::class, 'store'])
                ->middleware('throttle:6,1')
                ->name('member.verification.send');

    Route::get('member/confirm-password', [ConfirmablePasswordController::class, 'show'])
                ->name('password.confirm');
    
    //dagdag from here 
    Route::get('member/change-password', [PasswordController::class, 'showChangePasswordForm'])->name('change.password1');
    Route::post('member/change-password', [PasswordController::class, 'updatePassword'])->name('password.update1');
    //to here

    Route::post('member/confirm-password', [ConfirmablePasswordController::class, 'store']);

    Route::put('member/password', [PasswordController::class, 'update'])->name('password.update');

    Route::post('member/logout', [AuthenticatedSessionController::class, 'destroy'])
                ->name('member.logout');

});
