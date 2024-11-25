<?php

namespace App\Http\Controllers\MemberAuth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class EmailVerificationPromptController extends Controller
{
    /**
     * Display the email verification prompt.
     */
    // public function __invoke(Request $request): RedirectResponse|View
    // {
    //     return $request->user('member')->hasVerifiedEmail()
    //                 ? redirect()->intended(RouteServiceProvider::MEMBER_DASHBOARD)
    //                 : view('member.auth.verify-email');
    // }
    public function __invoke(Request $request): RedirectResponse|View
{
    $user = $request->user('member');

    if ($user->hasVerifiedEmail()) {
        if ($user->type === 'Owner') {
            return redirect()->route('member.membermonthlydues');
        }

        return redirect()->intended(RouteServiceProvider::MEMBER_DASHBOARD);
    }

    return view('member.auth.verify-email');
}
}
