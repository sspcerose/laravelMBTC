<?php

namespace App\Http\Controllers\MemberAuth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class EmailVerificationNotificationController extends Controller
{
    /**
     * Send a new email verification notification.
     */
    public function store(Request $request): RedirectResponse
    {
        if ($request->user('member')->hasVerifiedEmail()) {

            if ($user->type === 'Owner') {
                return redirect()->route('member.membermonthlydues');
            }

            return redirect()->intended(RouteServiceProvider::MEMBER_DASHBOARD);
        }

        $request->user('member')->sendEmailVerificationNotification();

        return back()->with('status', 'verification-link-sent');
    }
}
