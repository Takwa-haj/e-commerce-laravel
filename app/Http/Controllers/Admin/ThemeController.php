<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ThemeSetting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Cache;

class ThemeController extends Controller
{
    /**
     * All predefined color palettes
     */
    private function getPalettes()
    {
        return [
            'soft-minimal' => [
                'name' => 'Soft Minimal Luxury',
                'description' => 'Elegant, clean, and timeless. Perfect for any jewelry store.',
                'badge' => 'Best Seller',
                'colors' => [
                    'primary' => '#D8C3A5',
                    'primary_hover' => '#CBB394',
                    'secondary' => '#E8DCCF',
                    'bg_main' => '#F7F3EE',
                    'bg_soft' => '#EFE6DC',
                    'bg_light' => '#F2E9E4',
                    'surface' => '#FFFFFF',
                    'text_main' => '#3E3A37',
                    'text_secondary' => '#7A6F66',
                    'text_muted' => '#A89F98',
                    'border_soft' => '#E6DDD4',
                    'border_light' => '#EFE6DC',
                    'accent' => '#C6A77D',
                    'accent_glow' => '#E5D3B3',
                    'sale_badge' => '#E8B7B1',
                ],
            ],
            'blush-artisan' => [
                'name' => 'Feminine Blush Artisan',
                'description' => 'Soft, romantic, and handmade feel. Best for artisan jewelry.',
                'badge' => 'Popular',
                'colors' => [
                    'primary' => '#E8B7B1',
                    'primary_hover' => '#DFA39C',
                    'secondary' => '#F4D7D3',
                    'bg_main' => '#FDF8F5',
                    'bg_soft' => '#F9EAE7',
                    'bg_light' => '#FBEFEC',
                    'surface' => '#FFFFFF',
                    'text_main' => '#5A4A42',
                    'text_secondary' => '#8B7D76',
                    'text_muted' => '#B5A89F',
                    'border_soft' => '#F4D7D3',
                    'border_light' => '#F9EAE7',
                    'accent' => '#D4A297',
                    'accent_glow' => '#F2D3CE',
                    'sale_badge' => '#E8B7B1',
                ],
            ],
            'cream-monochrome' => [
                'name' => 'Cream Monochrome Luxury',
                'description' => 'Ultra minimal, high-end feel. Focus on products.',
                'badge' => 'Elegant',
                'colors' => [
                    'primary' => '#E8DCCB',
                    'primary_hover' => '#DBCFB8',
                    'secondary' => '#F1E9DD',
                    'bg_main' => '#FAF7F2',
                    'bg_soft' => '#F3EDE4',
                    'bg_light' => '#F5F0E8',
                    'surface' => '#FFFFFF',
                    'text_main' => '#5A4634',
                    'text_secondary' => '#8B7D6B',
                    'text_muted' => '#B5A894',
                    'border_soft' => '#E8DCCB',
                    'border_light' => '#F1E9DD',
                    'accent' => '#D4C4A8',
                    'accent_glow' => '#F0E6D6',
                    'sale_badge' => '#E0D0B8',
                ],
            ],
            'sage-natural' => [
                'name' => 'Natural Sage Organic',
                'description' => 'Trendy, eco-friendly, artisan feel. Strong brand identity.',
                'badge' => 'Trending',
                'colors' => [
                    'primary' => '#A8BBA3',
                    'primary_hover' => '#95A990',
                    'secondary' => '#E8DCCF',
                    'bg_main' => '#FAFAF8',
                    'bg_soft' => '#F0F4EF',
                    'bg_light' => '#F4F7F2',
                    'surface' => '#FFFFFF',
                    'text_main' => '#4A5A4A',
                    'text_secondary' => '#7A8A7A',
                    'text_muted' => '#AAB5AA',
                    'border_soft' => '#DCE5DA',
                    'border_light' => '#E8EDE6',
                    'accent' => '#C4D5C0',
                    'accent_glow' => '#E5EDE2',
                    'sale_badge' => '#B8C8B5',
                ],
            ],
            'modern-black' => [
                'name' => 'Modern Boutique Luxury',
                'description' => 'Bold, high-end, fashion-forward. Strong luxury presence.',
                'badge' => 'Premium',
                'colors' => [
                    'primary' => '#D4B996',
                    'primary_hover' => '#C9AD82',
                    'secondary' => '#2C2C2C',
                    'bg_main' => '#F7F3EE',
                    'bg_soft' => '#EFE6DC',
                    'bg_light' => '#F2E9E4',
                    'surface' => '#FFFFFF',
                    'text_main' => '#2C2C2C',
                    'text_secondary' => '#6B6363',
                    'text_muted' => '#9A9292',
                    'border_soft' => '#E6D3C7',
                    'border_light' => '#EFE6DC',
                    'accent' => '#C6A77D',
                    'accent_glow' => '#E5D3B3',
                    'sale_badge' => '#D4B996',
                ],
            ],
            'ocean-calm' => [
                'name' => 'Ocean Calm Elegance',
                'description' => 'Serene, sophisticated, cooling. Great for pearl/silver jewelry.',
                'badge' => 'Calm',
                'colors' => [
                    'primary' => '#B8C9D4',
                    'primary_hover' => '#A5B8C5',
                    'secondary' => '#D4E0E8',
                    'bg_main' => '#F5F7FA',
                    'bg_soft' => '#E8EDF2',
                    'bg_light' => '#EDF1F5',
                    'surface' => '#FFFFFF',
                    'text_main' => '#3A4550',
                    'text_secondary' => '#6B7885',
                    'text_muted' => '#A0ABB5',
                    'border_soft' => '#D4E0E8',
                    'border_light' => '#E8EDF2',
                    'accent' => '#C4D0D8',
                    'accent_glow' => '#E0E8EE',
                    'sale_badge' => '#B8C9D4',
                ],
            ],
            'warm-autumn' => [
                'name' => 'Warm Autumn Glow',
                'description' => 'Rich, warm, inviting. Perfect for gold jewelry.',
                'badge' => 'Warm',
                'colors' => [
                    'primary' => '#D4A574',
                    'primary_hover' => '#C9955F',
                    'secondary' => '#E8D5C4',
                    'bg_main' => '#FAF5F0',
                    'bg_soft' => '#F2E8E0',
                    'bg_light' => '#F5EDE5',
                    'surface' => '#FFFFFF',
                    'text_main' => '#5A4030',
                    'text_secondary' => '#8B7060',
                    'text_muted' => '#B5A090',
                    'border_soft' => '#E8D5C4',
                    'border_light' => '#F2E8E0',
                    'accent' => '#D4B896',
                    'accent_glow' => '#F0E0D0',
                    'sale_badge' => '#E0C8B0',
                ],
            ],
        ];
    }

    /**
     * Show theme settings page
     */
    public function index()
    {
        $palettes = $this->getPalettes();
        $activeTheme = ThemeSetting::where('is_active', true)->first();

        if (!$activeTheme) {
            $activeTheme = ThemeSetting::create([
                'name' => 'Default Theme',
                'palette_key' => 'soft-minimal',
                'is_custom' => false,
                'colors' => json_encode($palettes['soft-minimal']['colors']),
                'is_active' => true,
            ]);
        }

        $currentColors = $activeTheme->colors ? json_decode($activeTheme->colors, true) : $palettes['soft-minimal']['colors'];

        return view('admin.theme-settings', compact('palettes', 'activeTheme', 'currentColors'));
    }

    /**
     * Apply a theme palette
     */
    public function apply(Request $request)
    {
        $request->validate([
            'palette_key' => 'required|string',
            'is_custom' => 'nullable|boolean',
            'custom_colors' => 'nullable|array',
        ]);

        $palettes = $this->getPalettes();

        // Deactivate all themes
        ThemeSetting::query()->update(['is_active' => false]);

        $paletteKey = $request->palette_key;
        $isCustom = $request->boolean('is_custom');

        if ($isCustom && $request->has('custom_colors')) {
            $colors = $request->custom_colors;
            $name = 'Custom Theme';
        } else {
            $selectedPalette = $palettes[$paletteKey] ?? $palettes['soft-minimal'];
            $colors = $selectedPalette['colors'];
            $name = $selectedPalette['name'];
        }

        // Create new active theme
        $theme = ThemeSetting::create([
            'name' => $name,
            'palette_key' => $paletteKey,
            'is_custom' => $isCustom,
            'colors' => json_encode($colors),
            'is_active' => true,
        ]);

        // Clear all caches
        Cache::forget('active_theme');
        Cache::forget('theme_css');
        Artisan::call('view:clear');
        Artisan::call('cache:clear');

        return response()->json([
            'success' => true,
            'message' => 'Theme applied successfully! Refresh the page to see changes.',
            'theme' => $theme,
            'css_variables' => $this->generateCSSVariables($colors),
        ]);
    }

    /**
     * Preview a theme without saving
     */
    public function preview(Request $request)
    {
        $paletteKey = $request->input('palette', 'soft-minimal');
        $palettes = $this->getPalettes();

        $palette = $palettes[$paletteKey] ?? $palettes['soft-minimal'];

        return response()->json([
            'success' => true,
            'palette' => $paletteKey,
            'name' => $palette['name'],
            'colors' => $palette['colors'],
            'css_variables' => $this->generateCSSVariables($palette['colors']),
        ]);
    }

    /**
     * Generate CSS variables string
     */
    private function generateCSSVariables($colors)
    {
        $cssVars = [];
        foreach ($colors as $key => $value) {
            $cssVars[] = "--{$key}: {$value};";
        }

        // Add computed variables
        $textMain = $colors['text_main'] ?? '#3E3A37';
        $primary = $colors['primary'] ?? '#D8C3A5';

        $rgbText = $this->hexToRgb($textMain);
        $rgbPrimary = $this->hexToRgb($primary);

        $cssVars[] = "--card-shadow: rgba({$rgbText}, 0.08);";
        $cssVars[] = "--hover-overlay: rgba({$rgbPrimary}, 0.35);";
        $cssVars[] = "--divider: " . ($colors['border_soft'] ?? '#E6DDD4') . ";";

        return implode("\n", $cssVars);
    }

    /**
     * Convert hex to RGB string
     */
    private function hexToRgb($hex)
    {
        $hex = ltrim($hex, '#');
        if (strlen($hex) == 3) {
            $hex = $hex[0].$hex[0].$hex[1].$hex[1].$hex[2].$hex[2];
        }
        $r = hexdec(substr($hex, 0, 2));
        $g = hexdec(substr($hex, 2, 2));
        $b = hexdec(substr($hex, 4, 2));
        return "$r, $g, $b";
    }

    /**
     * Reset to default theme
     */
    public function reset()
    {
        ThemeSetting::query()->update(['is_active' => false]);

        $palettes = $this->getPalettes();
        $defaultPalette = $palettes['soft-minimal'];

        $theme = ThemeSetting::create([
            'name' => $defaultPalette['name'],
            'palette_key' => 'soft-minimal',
            'is_custom' => false,
            'colors' => json_encode($defaultPalette['colors']),
            'is_active' => true,
        ]);

        Cache::forget('active_theme');
        Cache::forget('theme_css');
        Artisan::call('view:clear');
        Artisan::call('cache:clear');

        return response()->json([
            'success' => true,
            'message' => 'Theme reset to default!',
            'theme' => $theme,
        ]);
    }
}
