<?php

namespace App\Http\Controllers\MemberAuth;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;
// dagdag
use Illuminate\Support\Facades\Auth; 

class PasswordController extends Controller
{
    /**
     * Update the user's password.
     */
    public function update(Request $request): RedirectResponse
    {
        $validated = $request->validateWithBag('updatePassword', [
            'current_password' => ['required', 'current_password'],
            'password' => ['required', Password::defaults(), 'confirmed'],
        ]);

        $request->user()->update([
            'password' => Hash::make($validated['password']),
            'pass' => 'changed',
        ]);

        // dagdag
        if (!session('change_password_required')) {
            return back()->with('status', 'password-updated'); 
       }else{
        // session()->flash('status', 'password-updated');
    
        Auth::guard('member')->logout();
        return redirect()->route('member.auth.login');
       }
    //    to here

    //    original position
        // return back()->with('status', 'password-updated');
    }

    //dagdag from here 
    public function showChangePasswordForm()
    {
        if (!session('change_password_required')) {
            return redirect()->route('member.dashboard'); 
        }
    
        return view('member.profile.edit');
    }
    
    public function updatePassword(Request $request)
    {
        $user = Auth::guard('member')->user();
    
        // Validate and update the password
        $request->validate([
            'password' => 'required|string|min:8|confirmed',
        ]);
    
        $user->update([
            'password' => bcrypt($request->password),
            'pass' => 'changed',
        ]);
    
        session()->forget('change_password_required');
    
        
        return redirect()->route('member.dashboard')->with('status', 'Password updated successfully!');
    }
    // to here
}
