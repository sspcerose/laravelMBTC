<?php

namespace App\Http\Controllers;

use App\Http\Requests\MemberUpdateRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;


class MemberProfileController extends Controller
{
    /**
     * Display the user's profile form.
     */
    // public function edit(Request $request): View
    // {
    //     return view('member.profile.edit', [
    //         'user' => $request->user('member'),
    //     ]);
    // }

    public function edit(Request $request)
{
    // Check if the user's updated_at is equal to email_verified_at
    if (Auth::guard('member')->user()->pass == 'new') {
        // Redirect to the change password page if they haven't changed their password
        return Redirect::route('member.profile.changepassword1');
    }

    // If the user has updated their password, show the profile edit page
    return view('member.profile.edit', [
        'user' => $request->user('member'),
    ]);
}

    /**
     * Update the user's profile information.
     */
    public function update(MemberUpdateRequest $request): RedirectResponse
    {
        $request->user('member')->fill($request->validated());

        if ($request->user('member')->isDirty('email')) {
            $request->user('member')->email_verified_at = null;
        }

        $request->user('member')->save();

        return Redirect::route('member.profile.edit')->with('status', 'profile-updated');
    }

     //dagdag from here 
    public function showChangePasswordForm()
    {
         // Ensure the change password page can only be accessed when required
        if (!session('change_password_required')) {
             return redirect()->route('member.dashboard'); // Or another default page
        }
    
        return view('member.profile.changepassword1'); // Show the change password form
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

    /**
     * Delete the user's account.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user('member');

        Auth::guard('member')->logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/member/login');
    }
}
