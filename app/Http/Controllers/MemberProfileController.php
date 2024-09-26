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
    public function edit(Request $request): View
    {
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

    /**
     * Delete the user's account.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user('member');

        Auth::gaurd('member')->logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/member/login');
    }
}
