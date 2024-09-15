<?php

namespace App\Http\Controllers\AdminAuth\MemberAuth;

use App\Http\Controllers\Controller;
use App\Models\Member;
use App\Models\Driver;
use App\Models\Owner;
use App\Providers\RouteServiceProvider;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): View
    {
        return view('admin.member.auth.register');
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'last_name' => ['required', 'string', 'max:255'],
            'tin' => ['required', 'string', 'max:255'],
            'mobile_num' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.Member::class],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'date_joined' => ['required', 'string', 'max:255'],
            'type' => ['required', 'string', 'max:255'],
            'member_status' => ['required', 'string', 'max:255'],
            
        ]);

        $user = Member::create([
            'name' => $request->name,
            'last_name' => $request->last_name,
            'tin' => $request->tin,
            'mobile_num' => $request->mobile_num,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'date_joined' => $request->date_joined,
            'type' => $request->type,
            'member_status' => $request->member_status,
        ]);

        if ($request->type === 'Owner') {
            Owner::create([
                'member_id' => $user->id,
                'member_type' => $request->type,
            ]);
        } else {
            Driver::create([
                'member_id' => $user->id,
                'member_type' => $request->type,
            ]);
        }

        event(new Registered($user));

        // Auth::guard('admin')->login($user);

        // return redirect(RouteServiceProvider::ADMIN_DASHBOARD);

        return redirect('admin/member');
    }
}
