<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class ChangePasswordController extends Controller
{
    /**
     * Show the confirm password view.
     */
    public function show(Request $request)
    {
        $token = $request->route('token');
        return view('auth.change-password', compact('token'));
    }

    /**
     * Confirm the user's password.
     */

    public function update(Request $request)
    {
        // Validate the input
        // dd($request->all());
        $request->validate([
            'current_password' => ['required'],
            'new_password' => ['required', 'min:8', 'confirmed'],
        ]);

        // Check if the current password is correct
        if (!Hash::check($request->current_password, Auth::user()->password)) {
            throw ValidationException::withMessages([
                'current_password' => __('The provided password does not match your current password.'),
            ]);
        }

        // Update the user's password
        $user = Auth::user();
        $user->password = Hash::make($request->new_password);
        $user->save();

        // Redirect with a success message
        return redirect()->route('dashboard')->with('status', __('Password changed successfully.'));
    }
}
