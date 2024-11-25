<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
// use App\Http\Middleware\EnsureEmailIsVerified;
use illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\URL;

class EnsureEmailIsVerified
{

    public static function redirectTo($route)
    {
        return static::class.':'.$route;
    }


    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, $guard = null, $redirectToRoute = null): Response
    {
        // dd(auth()->guard('member')->user() instanceof MustVerifyEmail);
        if (! $request->user($guard) ||
        ($request->user($guard) instanceof MustVerifyEmail &&
        ! $request->user($guard)->hasVerifiedEmail())) {
        return $request->expectsJson()
                ? abort(403, 'Your email address is not verified.')
                : Redirect::guest(URL::route($redirectToRoute ?: 'verification.notice'));
    }

    return $next($request);
    }
}
