<?php

namespace App\Services;

use App\Models\ColorPalette;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\File;

class ColorService
{
    public function activate(int $paletteId): ColorPalette
    {
        ColorPalette::query()->update(['is_active' => false]);

        $palette = ColorPalette::findOrFail($paletteId);
        $palette->update(['is_active' => true]);

        Cache::forget('active_color_palette');
        $this->generateCSSVariables($palette);

        return $palette;
    }

    public function generateCSSVariables(?ColorPalette $palette = null): void
    {
        if (!$palette) {
            $palette = $this->getActivePalette();
        }

        if (!$palette) {
            return;
        }

        $css = ":root {\n";
        foreach ($palette->colors as $key => $color) {
            $css .= "    --{$key}: {$color};\n";
        }
        $css .= "}\n";

        $path = public_path('css/dynamic-colors.css');

        if (!File::exists(dirname($path))) {
            File::makeDirectory(dirname($path), 0755, true);
        }

        File::put($path, $css);

        // Backup in storage
        $backupPath = storage_path('app/color-palettes/active-palette.css');
        if (!File::exists(dirname($backupPath))) {
            File::makeDirectory(dirname($backupPath), 0755, true);
        }
        File::put($backupPath, $css);
    }

    public function getActivePalette(): ?ColorPalette
    {
        return Cache::remember('active_color_palette', 3600, function () {
            $palette = ColorPalette::where('is_active', true)->first();

            if (!$palette) {
                $palette = $this->createDefaultPalette();
            }

            if (!File::exists(public_path('css/dynamic-colors.css'))) {
                $this->generateCSSVariables($palette);
            }

            return $palette;
        });
    }

    public function createDefaultPalette(): ColorPalette
    {
        $exists = ColorPalette::where('type', 'default')->first();

        if ($exists) {
            $exists->update(['is_active' => true]);
            return $exists;
        }

        return ColorPalette::create([
            'name' => 'Default Gold Theme',
            'type' => 'default',
            'is_active' => true,
            'colors' => ColorPalette::getDefaultColors()
        ]);
    }
}
