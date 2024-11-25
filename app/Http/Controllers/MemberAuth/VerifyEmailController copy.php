<?php

namespace App\Http\Controllers\MemberAuth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Auth\Events\Verified;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Http\RedirectResponse;

class VerifyEmailController extends Controller
{
    /**
     * Mark the authenticated user's email address as verified.
     */
    public function __invoke(EmailVerificationRequest $request): RedirectResponse
    {
        if ($request->user('member')->hasVerifiedEmail()) {
            return redirect('member')->intended(RouteServiceProvider::MEMBER_DASHBOARD.'?verified=1');
        }

        if ($request->user('member')->markEmailAsVerified()) {
            event(new Verified($request->user()));
        }

        return redirect()->intended(RouteServiceProvider::MEMBER_DASHBOARD.'?verified=1');
    }
}
