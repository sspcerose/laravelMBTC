<?php

namespace App\Http\Middleware;

use App\Providers\RouteServiceProvider;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class RedirectIfAuthenticated
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, string ...$guards): Response
    {
        $guards = empty($guards) ? [null] : $guards;

        foreach ($guards as $guard) {
            if ($guard=="admin" && Auth::guard($guard)->check()){
                return redirect(RouteServiceProvider::ADMIN_DASHBOARD);
            }
            if ($guard == "member" && Auth::guard($guard)->check()) {
                $user = Auth::guard($guard)->user();
        
                if ($user->member_type === 'Owner') {
                    return redirect()->route('member.membermonthlydues');
                }
        
                return redirect(RouteServiceProvider::MEMBER_DASHBOARD);
            }
            if (Auth::guard($guard)->check()) {
                return redirect(RouteServiceProvider::HOME);
            }
        }

        // foreach ($guards as $guard) {
        //     if (Auth::guard($guard)->check()) {
        //         if ($guard === 'admin') {
        //             return redirect(RouteServiceProvider::ADMIN_DASHBOARD);
        //         } elseif ($guard === 'member') {
        //             return redirect(RouteServiceProvider::MEMBER_DASHBOARD);
        //         } else {
        //             return redirect(RouteServiceProvider::HOME);
        //         }
        //     }
        // }
        

        return $next($request);
    }
}

