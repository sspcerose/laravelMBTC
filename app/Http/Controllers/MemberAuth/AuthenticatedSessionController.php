<?php

namespace App\Http\Controllers\MemberAuth;

use App\Http\Controllers\Controller;
use App\Http\Requests\MemberAuth\LoginRequest;
use App\Providers\RouteServiceProvider;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     */
    public function create(): View
    {
        return view('member.auth.login');
    }

    /**
     * Handle an incoming authentication request.
     */
    public function store(LoginRequest $request): RedirectResponse
{
    $request->authenticate();
    // dd($request);

    $request->session()->regenerate();

    $user = Auth::guard('member')->user();

    if (!$user->hasVerifiedEmail()) {
        
        return redirect()->route('member.verification.notice');
    }

    if ($user->type === 'Owner') {
        return redirect()->route('member.membermonthlydues');
    }

    return redirect()->intended(RouteServiceProvider::MEMBER_DASHBOARD);
}

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('member')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('member/login');
    }
}
