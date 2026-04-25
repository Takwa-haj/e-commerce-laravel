<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Laravel\Socialite\Facades\Socialite;
use Illuminate\Support\Facades\Log;

class GoogleController extends Controller
{
    /**
     * Redirect to Google OAuth
     */
    public function redirectToGoogle()
    {
        try {
            return Socialite::driver('google')
                ->scopes(['email', 'profile'])
                ->with(['prompt' => 'select_account'])
                ->redirect();
        } catch (\Exception $e) {
            Log::error('Google OAuth Redirect Error: ' . $e->getMessage());
            return redirect()->route('login')
                ->with('error', 'Service Google temporairement indisponible.');
        }
    }

    /**
     * Handle Google callback with enhanced error handling
     */
    public function handleGoogleCallback(Request $request)
    {
        try {
            // Check for OAuth errors
            if ($request->has('error')) {
                Log::error('Google OAuth Error: ' . $request->error);
                return redirect()->route('login')
                    ->with('error', 'Erreur d\'authentification Google: ' . $request->error);
            }

            // Get Google user data
            $googleUser = Socialite::driver('google')->stateless()->user();

            if (!$googleUser) {
                throw new \Exception('No user data returned from Google');
            }

            // Debug logging
            Log::info('Google User Data:', [
                'id' => $googleUser->getId(),
                'email' => $googleUser->getEmail(),
                'name' => $googleUser->getName(),
                'avatar' => $googleUser->getAvatar(),
            ]);

            // Find user by Google ID first
            $user = User::where('google_id', $googleUser->getId())->first();

            // If not found by Google ID, try email
            if (!$user) {
                $user = User::where('email', $googleUser->getEmail())->first();

                // If user exists but doesn't have Google ID, update it
                if ($user && !$user->google_id) {
                    $user->update([
                        'google_id' => $googleUser->getId(),
                        'provider' => 'google',
                        'email_verified_at' => now(),
                    ]);

                    Log::info('Updated existing user with Google ID:', ['user_id' => $user->id]);
                }
            }

            // If user doesn't exist at all, create new user
            if (!$user) {
                $user = $this->createNewUserFromGoogle($googleUser);
                Log::info('Created new user from Google:', ['user_id' => $user->id]);
            }

            // Log the user in
            Auth::login($user, true);

            // Record login activity
            $user->recordLogin($request->ip());

            // Redirect to intended URL or dashboard
            return redirect()->intended(route('user.index'))
                ->with('success', 'Connexion réussie avec Google!');

        } catch (\Exception $e) {
            Log::error('Google OAuth Callback Error: ' . $e->getMessage());
            Log::error('Error Trace: ' . $e->getTraceAsString());

            return redirect()->route('login')
                ->with('error', 'Une erreur est survenue lors de la connexion avec Google. Veuillez réessayer ou utiliser votre email et mot de passe.');
        }
    }

    /**
     * Create new user from Google data
     */
    private function createNewUserFromGoogle($googleUser)
    {
        // Generate a random password for security
        $password = Hash::make(Str::random(24));

        // Extract first and last name from full name
        $nameParts = explode(' ', $googleUser->getName());
        $firstName = $nameParts[0] ?? '';
        $lastName = count($nameParts) > 1 ? end($nameParts) : '';

        $user = User::create([
            'name' => $googleUser->getName(),
            'email' => $googleUser->getEmail(),
            'google_id' => $googleUser->getId(),
            'provider' => 'google',
            'password' => $password,
            'email_verified_at' => now(), // Google emails are verified
            'utype' => 'USR', // Default user type
            'numero' => '', // Empty by default, can be filled later
        ]);

        // Optionally save avatar URL
        if ($googleUser->getAvatar()) {
            $user->avatar = $this->saveGoogleAvatar($googleUser->getAvatar(), $user->id);
            $user->save();
        }

        return $user;
    }

    /**
     * Save Google avatar to local storage
     */
    private function saveGoogleAvatar($avatarUrl, $userId)
    {
        try {
            $avatarContents = file_get_contents($avatarUrl);
            $avatarName = 'google_avatar_' . $userId . '_' . time() . '.jpg';
            $avatarPath = public_path('uploads/avatars/' . $avatarName);

            // Ensure directory exists
            if (!file_exists(public_path('uploads/avatars'))) {
                mkdir(public_path('uploads/avatars'), 0755, true);
            }

            file_put_contents($avatarPath, $avatarContents);

            return $avatarName;
        } catch (\Exception $e) {
            Log::error('Failed to save Google avatar: ' . $e->getMessage());
            return null;
        }
    }
}
