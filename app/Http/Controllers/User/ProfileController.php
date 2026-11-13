<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Notification;
use App\Services\AccountDeletionService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;
use Illuminate\Support\Facades\Auth;

class ProfileController extends Controller
{
    public function destroy(
        Request $request,
        AccountDeletionService $accounts
    ): RedirectResponse {
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();
        $accounts->delete($user);

        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }

    /**
     * Display the profile edit form.
     *
     * @return \Illuminate\View\View
     */
    public function edit()
    {
        // Retrieve the authenticated user
        $user = Auth::user();

        // Return the profile edit view with user data
        return view('user.profile.edit', compact('user'));
    }

    /**
     * Update the user's profile information, including password and passcode.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request)
{
    // Retrieve the authenticated user
    $user = Auth::user();
    // Validate the incoming request data
    $validatedData = $request->validate([
        'passcode' => ['required', 'string', 'size:6'],
        'current_password' => ['required', 'string'],
        'password' => [
            'required',
            'string',
            'confirmed', // Ensures password_confirmation matches
            Password::min(8)
                ->mixedCase()
                ->letters()
                ->numbers()
                ->symbols(),
        ],
    ]);
    // Verify that the current password matches
    if (!Hash::check($validatedData['current_password'], $user->password)) {
        return back()->withErrors(['current_password' => 'The current password you entered is incorrect.']);
    }
    // Update the user's passcode and password
    $user->passcode = Hash::make($validatedData['passcode']);
    $user->password = Hash::make($validatedData['password']);
    $user->save();

    // Create a notification for password change
    Notification::create([
        'user_id' => $user->id,
        'type' => 'password_reset',
        'title' => 'Password Changed',
        'message' => 'Your account password was successfully updated.',
        'is_read' => false,
        'extra_data' => json_encode([
            'action' => 'password_change',
            'timestamp' => now()->toDateTimeString()
        ])
    ]);

    // Redirect back with a success message
    return back()->with('success', 'Your security settings have been updated successfully.');
}
}
