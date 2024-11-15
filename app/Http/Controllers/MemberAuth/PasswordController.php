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
        ]);

        // dagdag
        if (!session('change_password_required')) {
            return back()->with('status', 'password-updated'); // Or another default page
       }else{
        // session()->flash('status', 'password-updated');
    
        // Log out the user after setting the status message
        Auth::guard('member')->logout();
        
        // After logging out, redirect back to the previous page
        return redirect()->route('member.auth.login');
       }
    //    to here

    //    original position
        // return back()->with('status', 'password-updated');
    }

    //dagdag from here 
    public function showChangePasswordForm()
    {
        // Ensure the change password page can only be accessed when required
        if (!session('change_password_required')) {
            return redirect()->route('member.dashboard'); // Or another default page
        }
    
        return view('member.profile.edit'); // Show the change password form
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
    
        // Clear the session marker
        session()->forget('change_password_required');
    
        // Redirect to the dashboard or another secure page
        return redirect()->route('member.dashboard')->with('status', 'Password updated successfully!');
    }
    // to here
}
