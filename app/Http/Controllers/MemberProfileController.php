<?php

namespace App\Http\Controllers;

use App\Http\Requests\MemberUpdateRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;
use App\Models\Member;


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
    // dd(Auth::guard('member')->user());
    if (Auth::guard('member')->user()->pass == 'new') {
        return Redirect::route('member.profile.changepassword1');
    }

    // If the user has updated their password, show the profile edit page
    // return view('member.profile.edit', [
    //     'user' => $request->user('member'),
    // ]);

        $member_id = Auth::guard('member')->user()->id;
        $memberType = Member::find($member_id);
        return view('member.profile.edit', [
            'user' => Auth::guard('member')->user(),
            'memberType' => $memberType,
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
        if (!session('change_password_required')) {
             return redirect()->route('member.dashboard'); 
        }
    
        return view('member.profile.changepassword1');
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
