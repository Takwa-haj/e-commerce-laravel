<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Laravel\Socialite\Facades\Socialite;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class SocialController extends Controller
{
    /**
     * Redirect the user to the Google authentication page.
     */
    public function googleLogin()
    {
        return Socialite::driver('google')->redirect();
    }

    /**
     * Obtain the user information from Google.
     */
    public function googleAuthentication()
    {
        try {
            $googleUser = Socialite::driver('google')->user();

            // Check if a user with this Google ID already exists
            $user = User::where('google_id', $googleUser->id)->first();

            if ($user) {
                // If user exists, log them in
                Auth::login($user);
                return redirect()->intended('/');
            } else {
                // Check if a user with this Email already exists (to merge accounts)
                $existingUser = User::where('email', $googleUser->email)->first();

                if ($existingUser) {
                    // Update the existing user with the Google ID
                    $existingUser->update([
                        'google_id' => $googleUser->id
                    ]);
                    Auth::login($existingUser);
                    return redirect()->intended('/');
                } else {
                    // Create a new user
                    $userData = User::create([
                        'name' => $googleUser->name,
                        'email' => $googleUser->email,
                        'google_id' => $googleUser->id,
                        'password' => Hash::make(Str::random(16)), // Random password since they use Google
                        'utype' => 'USR', // Default user type
                        'email_verified_at' => now(), // Automatically verify email from Google
                    ]);

                    Auth::login($userData);
                    return redirect()->intended('/');
                }
            }

        } catch (\Exception $e) {
            // Log the error or handle it as needed
            return redirect()->route('login')->with('error', 'Something went wrong with Google Login. Please try again.');
        }
    }
}
