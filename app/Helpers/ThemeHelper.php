<?php
// app/Helpers/ThemeHelper.php

namespace App\Helpers;

use App\Models\Setting;
use Illuminate\Support\Facades\Cache;

class ThemeHelper
{
    public static function getThemeColors()
    {
        return Cache::remember('theme_colors', 3600, function () {
            $settings = Setting::first();
            $defaultColors = self::getDefaultColors();

            if (!$settings) {
                return $defaultColors;
            }

            if ($settings->theme_custom_mode && $settings->theme_colors) {
                $colors = json_decode($settings->theme_colors, true);
                return $colors ?: $defaultColors;
            }

            if ($settings->theme_palette) {
                $palettes = self::getPalettes();
                if (isset($palettes[$settings->theme_palette])) {
                    return $palettes[$settings->theme_palette]['colors'];
                }
            }

            return $defaultColors;
        });
    }

    public static function outputCSSVariables()
    {
        $colors = self::getThemeColors();
        $css = ":root {\n";
        foreach ($colors as $name => $color) {
            $css .= "    --" . str_replace('_', '-', $name) . ": {$color};\n";
        }
        $css .= "    --card-shadow: rgba(" . self::hexToRgb($colors['text_main'] ?? '#3E3A37') . ", 0.08);\n";
        $css .= "    --hover-overlay: rgba(" . self::hexToRgb($colors['primary'] ?? '#D8C3A5') . ", 0.35);\n";
        $css .= "    --divider: " . ($colors['border_soft'] ?? '#E6DDD4') . ";\n";
        $css .= "}";
        return $css;
    }

    private static function getDefaultColors()
    {
        return [
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
            'success' => '#A8C3A0',
            'error' => '#D88C8C',
            'warning' => '#E5D3B3',
            'info' => '#D9CFC6',
        ];
    }

    private static function getPalettes()
    {
        // Same palettes as in AdminController
        return [
            'soft-minimal' => [
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
                    'success' => '#A8C3A0',
                    'error' => '#D88C8C',
                    'warning' => '#E5D3B3',
                    'info' => '#D9CFC6',
                ]
            ],
            // Add other palettes...
        ];
    }

    private static function hexToRgb($hex)
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
}
