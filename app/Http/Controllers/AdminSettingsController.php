<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use App\Models\Setting;
use Nette\Utils\Image;
use App\Models\User;

class AdminSettingsController extends Controller
{
    /**
     * Display the settings page
     */
    public function index()
    {
        $settings = Setting::first();
        $user = Auth::user();

        if (!$settings) {
            // Create default settings if none exist
            $settings = Setting::create([
                'site_name' => config('app.name'),
                'site_email' => 'admin@example.com',
                'currency' => 'USD',
                'currency_symbol' => '$',
                'tax_rate' => 0,
                'shipping_cost' => 0,
            ]);
        }

        return view('admin.settings', compact('settings', 'user'));
    }

    /**
     * Update general settings
     */
    public function update(Request $request)
    {
        // Validate the request
        $validator = Validator::make($request->all(), [
            'site_name' => 'required|string|max:255',
            'site_email' => 'nullable|email|max:255',
            'site_phone' => 'nullable|string|max:50',
            'site_address' => 'nullable|string|max:500',
            'currency' => 'required|string|max:10',
            'currency_symbol' => 'required|string|max:5',
            'tax_rate' => 'nullable|numeric|min:0|max:100',
            'shipping_cost' => 'nullable|numeric|min:0',
            'footer_text' => 'nullable|string|max:1000',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'favicon' => 'nullable|image|mimes:ico,png,jpg|max:1024',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        // Get current settings
        $settings = Setting::first();
        if (!$settings) {
            $settings = new Setting();
        }

        // Handle logo upload
        if ($request->hasFile('logo')) {
            // Delete old logo if exists
            if ($settings->logo && Storage::disk('public')->exists('settings/' . $settings->logo)) {
                Storage::disk('public')->delete('settings/' . $settings->logo);
            }

            // Upload new logo
            $logo = $request->file('logo');
            $logoName = time() . '_logo.' . $logo->getClientOriginalExtension();

            // Store with intervention image for optimization
            $logoPath = storage_path('app/public/settings');
            if (!file_exists($logoPath)) {
                mkdir($logoPath, 0777, true);
            }

            Image::make($logo)
                ->resize(200, null, function ($constraint) {
                    $constraint->aspectRatio();
                })
                ->save($logoPath . '/' . $logoName);

            $settings->logo = $logoName;
        }

        // Handle logo removal
        if ($request->input('remove_logo') == '1' && $settings->logo) {
            if (Storage::disk('public')->exists('settings/' . $settings->logo)) {
                Storage::disk('public')->delete('settings/' . $settings->logo);
            }
            $settings->logo = null;
        }

        // Handle favicon upload
        if ($request->hasFile('favicon')) {
            // Delete old favicon if exists
            if ($settings->favicon && Storage::disk('public')->exists('settings/' . $settings->favicon)) {
                Storage::disk('public')->delete('settings/' . $settings->favicon);
            }

            // Upload new favicon
            $favicon = $request->file('favicon');
            $faviconName = time() . '_favicon.' . $favicon->getClientOriginalExtension();

            // Store favicon
            $faviconPath = storage_path('app/public/settings');
            if (!file_exists($faviconPath)) {
                mkdir($faviconPath, 0777, true);
            }

            // For favicon, maintain small size
            Image::make($favicon)
                ->resize(64, 64)
                ->save($faviconPath . '/' . $faviconName);

            $settings->favicon = $faviconName;
        }

        // Handle favicon removal
        if ($request->input('remove_favicon') == '1' && $settings->favicon) {
            if (Storage::disk('public')->exists('settings/' . $settings->favicon)) {
                Storage::disk('public')->delete('settings/' . $settings->favicon);
            }
            $settings->favicon = null;
        }

        // Handle avatar upload for user
        if ($request->hasFile('avatar')) {
            $user = Auth::user();

            // Delete old avatar if exists
            if ($user->avatar && Storage::disk('public')->exists('avatars/' . $user->avatar)) {
                Storage::disk('public')->delete('avatars/' . $user->avatar);
            }

            // Upload new avatar
            $avatar = $request->file('avatar');
            $avatarName = time() . '_avatar.' . $avatar->getClientOriginalExtension();

            // Store avatar
            $avatarPath = storage_path('app/public/avatars');
            if (!file_exists($avatarPath)) {
                mkdir($avatarPath, 0777, true);
            }

            // Resize and save avatar
            Image::make($avatar)
                ->resize(300, 300, function ($constraint) {
                    $constraint->aspectRatio();
                })
                ->save($avatarPath . '/' . $avatarName);

            $user->avatar = $avatarName;
            $user->save();
        }

        // Update other settings
        $settings->site_name = $request->input('site_name');
        $settings->site_email = $request->input('site_email');
        $settings->site_phone = $request->input('site_phone');
        $settings->site_address = $request->input('site_address');
        $settings->currency = $request->input('currency');
        $settings->currency_symbol = $request->input('currency_symbol');
        $settings->tax_rate = $request->input('tax_rate') ?? 0;
        $settings->shipping_cost = $request->input('shipping_cost') ?? 0;
        $settings->footer_text = $request->input('footer_text');

        // Save settings
        $settings->save();

        return redirect()->route('admin.settings')
            ->with('status', 'Paramètres mis à jour avec succès!');
    }

    /**
     * Change password
     */
    public function changePassword(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'current_password' => 'required',
            'new_password' => 'required|min:8|confirmed',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $user = Auth::user();

        // Check current password
        if (!Hash::check($request->current_password, $user->password)) {
            return redirect()->back()
                ->with('error', 'Le mot de passe actuel est incorrect.');
        }

        // Update password
        $user->password = Hash::make($request->new_password);
        $user->save();

        return redirect()->back()
            ->with('status', 'Mot de passe mis à jour avec succès!');
    }

public function contact()
{
    $settings = Setting::first() ?? new Setting();
    return view('contact', compact('settings'));
}
public function showContact()
{
    $settings = Setting::first() ?? new Setting();
    return view('contact', compact('settings'));
}

}
