<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Auth\Events\Verified;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Http\RedirectResponse;

class EmailVerificationController extends Controller
{
    /**
     * Display the email verification notice.
     */
    public function notice()
    {
        $user = auth()->user();

        if ($user->hasVerifiedEmail()) {
            return redirect()->route('user.index');
        }

        // Resend verification email if it's been more than 5 minutes
        $lastSent = session('verification_email_sent_at');
        $canResend = !$lastSent || now()->diffInMinutes($lastSent) > 5;

        return view('auth.verify-email', [
            'canResend' => $canResend,
            'email' => $user->email,
        ]);
    }

    /**
     * Mark the authenticated user's email address as verified.
     */
    public function verify(EmailVerificationRequest $request): RedirectResponse
    {
        if ($request->user()->hasVerifiedEmail()) {
            return redirect()->route('user.index')->with('info', 'Votre email est déjà vérifié.');
        }

        if ($request->user()->markEmailAsVerified()) {
            event(new Verified($request->user()));

            // Log activity
            activity()
                ->performedOn($request->user())
                ->causedBy($request->user())
                ->log('email_verified');
        }

        return redirect()->route('user.index')->with('success', 'Félicitations ! Votre email a été vérifié avec succès.');
    }

    /**
     * Send a new email verification notification.
     */
    public function send()
    {
        $user = auth()->user();

        if ($user->hasVerifiedEmail()) {
            return redirect()->route('user.index')->with('info', 'Votre email est déjà vérifié.');
        }

        // Rate limiting: 1 email every 5 minutes
        $lastSent = session('verification_email_sent_at');
        if ($lastSent && now()->diffInMinutes($lastSent) < 5) {
            return back()->with('error', 'Veuillez patienter avant de renvoyer un nouvel email. (5 minutes minimum)');
        }

        $user->sendEmailVerificationNotification();

        session(['verification_email_sent_at' => now()]);

        // Log activity
        activity()
            ->performedOn($user)
            ->causedBy($user)
            ->log('verification_email_resent');

        return back()->with('status', 'Un nouveau lien de vérification a été envoyé à votre adresse email.');
    }
}
