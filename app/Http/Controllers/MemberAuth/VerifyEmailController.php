<?php

namespace App\Http\Controllers\MemberAuth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Auth\Events\Verified;
// use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Http\RedirectResponse;
use App\Http\Requests\MemberAuth\EmailVerificationRequest;

class VerifyEmailController extends Controller
{
    /**
     * Mark the authenticated user's email address as verified.
     */
    // public function __invoke(EmailVerificationRequest $request): RedirectResponse
    // {
    //     if ($request->user('member')->hasVerifiedEmail()) {
    //         return redirect()->intended(RouteServiceProvider::MEMBER_DASHBOARD.'?verified=1');
    //     }

    //     if ($request->user('member')->markEmailAsVerified()) {
    //         event(new Verified($request->user('member')));
    //     }

    //     return redirect()->intended(RouteServiceProvider::MEMBER_DASHBOARD.'?verified=1');
    // }

    public function __invoke(EmailVerificationRequest $request): RedirectResponse
{
    $user = $request->user('member');

    if ($user->hasVerifiedEmail()) {
        if ($user->type === 'Owner') {
            return redirect()->route('member.membermonthlydues');
        }

        return redirect()->intended(RouteServiceProvider::MEMBER_DASHBOARD.'?verified=1');
    }

    if ($user->markEmailAsVerified()) {
        event(new Verified($user));

        if ($user->type === 'Owner') {
            return redirect()->route('member.membermonthlydues');
        }
    }
    return redirect()->intended(RouteServiceProvider::MEMBER_DASHBOARD . '?verified=1');
}
}
