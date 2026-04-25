<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use PragmaRX\Google2FA\Google2FA;
use BaconQrCode\Renderer\ImageRenderer;
use BaconQrCode\Renderer\Image\ImagickImageBackEnd;
use BaconQrCode\Renderer\RendererStyle\RendererStyle;
use BaconQrCode\Writer;

class TwoFactorController extends Controller
{
    protected $google2fa;

    public function __construct(Google2FA $google2fa)
    {
        $this->google2fa = $google2fa;
        $this->middleware(['auth']);
    }

    /**
     * Show 2FA setup page
     */
    public function index()
    {
        $user = Auth::user();

        if ($user->two_factor_secret) {
            return $this->showRecoveryCodes();
        }

        return $this->showSetupForm();
    }

    /**
     * Show 2FA setup form
     */
    protected function showSetupForm()
    {
        $user = Auth::user();

        // Generate secret key
        $secret = $this->google2fa->generateSecretKey();

        // Generate QR Code URL
        $qrCodeUrl = $this->google2fa->getQRCodeUrl(
            config('app.name'),
            $user->email,
            $secret
        );

        // Generate QR Code image
        $qrCode = $this->generateQrCode($qrCodeUrl);

        return view('auth.two-factor-setup', compact('secret', 'qrCode', 'qrCodeUrl'));
    }

    /**
     * Enable 2FA
     */
    public function enable(Request $request)
    {
        $request->validate([
            'secret' => 'required',
            'code' => 'required|digits:6',
        ]);

        $user = Auth::user();
        $secret = $request->secret;
        $code = $request->code;

        // Verify the code
        $valid = $this->google2fa->verifyKey($secret, $code);

        if (!$valid) {
            throw ValidationException::withMessages([
                'code' => 'Le code de vérification est incorrect.',
            ]);
        }

        // Enable 2FA
        $user->two_factor_secret = encrypt($secret);
        $user->two_factor_recovery_codes = encrypt(json_encode($this->generateRecoveryCodes()));
        $user->two_factor_confirmed_at = now();
        $user->save();

        // Log activity
        activity('security')
            ->performedOn($user)
            ->causedBy($user)
            ->log('two_factor_enabled');

        // Send notification
        $user->notify(new \App\Notifications\TwoFactorEnabledNotification());

        return redirect()->route('two-factor.index')
            ->with('success', 'L\'authentification à deux facteurs a été activée avec succès.');
    }

    /**
     * Disable 2FA
     */
    public function disable(Request $request)
    {
        $request->validate([
            'password' => 'required|current_password',
        ]);

        $user = Auth::user();

        $user->two_factor_secret = null;
        $user->two_factor_recovery_codes = null;
        $user->two_factor_confirmed_at = null;
        $user->save();

        // Log activity
        activity('security')
            ->performedOn($user)
            ->causedBy($user)
            ->log('two_factor_disabled');

        return redirect()->route('user.account.details')
            ->with('success', 'L\'authentification à deux facteurs a été désactivée.');
    }

    /**
     * Show recovery codes
     */
    protected function showRecoveryCodes()
    {
        $user = Auth::user();
        $recoveryCodes = json_decode(decrypt($user->two_factor_recovery_codes));

        return view('auth.two-factor-recovery', compact('recoveryCodes'));
    }

    /**
     * Generate new recovery codes
     */
    public function generateNewRecoveryCodes()
    {
        $user = Auth::user();

        $user->two_factor_recovery_codes = encrypt(json_encode($this->generateRecoveryCodes()));
        $user->save();

        // Log activity
        activity('security')
            ->performedOn($user)
            ->causedBy($user)
            ->log('recovery_codes_regenerated');

        return redirect()->route('two-factor.index')
            ->with('success', 'De nouveaux codes de récupération ont été générés.');
    }

    /**
     * Verify 2FA code (for login)
     */
    public function verify(Request $request)
    {
        $request->validate([
            'code' => 'required|digits:6',
        ]);

        $user = Auth::user();
        $secret = decrypt($user->two_factor_secret);
        $code = $request->code;

        $valid = $this->google2fa->verifyKey($secret, $code);

        if (!$valid) {
            throw ValidationException::withMessages([
                'code' => 'Le code de vérification est incorrect.',
            ]);
        }

        // Store verification in session
        session(['2fa_verified' => true]);
        session(['2fa_verified_at' => now()]);

        return redirect()->intended(route('user.index'));
    }

    /**
     * Show 2FA verification form (for login)
     */
    public function showVerificationForm()
    {
        return view('auth.two-factor-verify');
    }

    /**
     * Generate QR code
     */
    protected function generateQrCode($url)
    {
        try {
            $renderer = new ImageRenderer(
                new RendererStyle(300),
                new ImagickImageBackEnd()
            );

            $writer = new Writer($renderer);
            return base64_encode($writer->writeString($url));
        } catch (\Exception $e) {
            // Fallback if Imagick is not available
            return null;
        }
    }

    /**
     * Generate recovery codes
     */
    protected function generateRecoveryCodes()
    {
        $codes = [];
        for ($i = 0; $i < 8; $i++) {
            $codes[] = strtoupper(bin2hex(random_bytes(5)));
        }
        return $codes;
    }
}
