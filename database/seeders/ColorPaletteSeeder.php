<?php

namespace Database\Seeders;

use App\Models\ColorPalette;
use App\Services\ColorService;
use Illuminate\Database\Seeder;

class ColorPaletteSeeder extends Seeder
{
    public function run(): void
    {
        ColorPalette::query()->update(['is_active' => false]);

        $palettes = [
            $this->getClassicGoldElegance(),
            $this->getBeigeWarmNude(),
            $this->getHighFashionDiamond(),
            $this->getMinimalSoftPink(),
            $this->getTrendyInstagram(),
            $this->getSageGoldOrganic(),
            $this->getPearlOceanMist(),
            $this->getCaramelCreamLuxe(),
            $this->getMidnightAmethyst(),
            $this->getPureIvorySilver(),
            $this->getSoftBeigeMinimal(),
            $this->getSoftBlushMinimal(),
            $this->getNeutralSageMinimal(),
            $this->getCreamMonochrome(),
            $this->getPureWhiteWarmCream(),
        ];

        foreach ($palettes as $paletteData) {
            ColorPalette::create($paletteData);
        }

        $activePalette = ColorPalette::where('is_active', true)->first();
        if ($activePalette) {
            app(ColorService::class)->generateCSSVariables($activePalette);
        }

        $this->command->info('✅ 14 palettes avec fonds clairs créées avec succès!');
    }

    // ========== PALETTE 1: Classic Gold Elegance ==========
    private function getClassicGoldElegance(): array
    {
        return [
            'name' => '🌸 Classic Gold Elegance',
            'type' => 'custom',
            'is_active' => true,
            'colors' => [
                'primary' => '#C9A96E',
                'secondary' => '#2C2C2C',
                'accent' => '#E8D5B7',
                'background' => '#FAFAFA',      // Light
                'surface' => '#FFFFFF',          // Light
                'bg_main' => '#FAFAFA',          // Light
                'bg_soft' => '#F5F0EB',          // Light warm
                'bg_light' => '#FFFFFF',         // Light
                'text_primary' => '#1A1A1A',
                'text_secondary' => '#8B7355',
                'text_main' => '#2C2C2C',
                'border' => '#E8D5B7',
                'border_soft' => '#F0E6D8',
                'divider' => '#E8D5B7',
                'success' => '#2D5016',
                'warning' => '#B8860B',
                'error' => '#8B0000',
                'info' => '#6B7B8D',
                'card_shadow' => 'rgba(201, 169, 110, 0.1)',
                'hover_overlay' => 'rgba(44, 44, 44, 0.3)',
                'gold_highlight' => '#C9A96E',
                'champagne_glow' => '#F5E6CC',
                'sale_badge' => '#C41E3A',
                'error_bg' => '#FEF2F2',
            ]
        ];
    }

    // ========== PALETTE 2: Beige Warm Nude ==========
    private function getBeigeWarmNude(): array
    {
        return [
            'name' => '🤍 Beige & Warm Nude Luxury',
            'type' => 'custom',
            'is_active' => false,
            'colors' => [
                'primary' => '#C4A882',
                'secondary' => '#4A3728',
                'accent' => '#D4C4B7',
                'background' => '#FDF8F4',      // Light warm
                'surface' => '#FFFFFF',          // Light
                'bg_main' => '#FDF8F4',          // Light warm
                'bg_soft' => '#F5EDE4',          // Light beige
                'bg_light' => '#FFFFFF',         // Light
                'text_primary' => '#3C2A1E',
                'text_secondary' => '#8B7355',
                'text_main' => '#4A3728',
                'border' => '#D4C4B7',
                'border_soft' => '#E8DCD0',
                'divider' => '#D4C4B7',
                'success' => '#5B8C5A',
                'warning' => '#D4943A',
                'error' => '#A94442',
                'info' => '#8B7D6B',
                'card_shadow' => 'rgba(196, 168, 130, 0.1)',
                'hover_overlay' => 'rgba(74, 55, 40, 0.3)',
                'gold_highlight' => '#C4A882',
                'champagne_glow' => '#F0E6D8',
                'sale_badge' => '#D4A574',
                'error_bg' => '#FEF2F2',
            ]
        ];
    }

    // ========== PALETTE 3: High Fashion Diamond ==========
    private function getHighFashionDiamond(): array
    {
        return [
            'name' => '💎 High Fashion Diamond',
            'type' => 'custom',
            'is_active' => false,
            'colors' => [
                'primary' => '#4A4A4A',
                'secondary' => '#E5E5E5',
                'accent' => '#C0C0C0',
                'background' => '#FAFAFA',      // Light
                'surface' => '#FFFFFF',          // Light
                'bg_main' => '#FAFAFA',          // Light
                'bg_soft' => '#F5F5F5',          // Light gray
                'bg_light' => '#FFFFFF',         // Light
                'text_primary' => '#1A1A1A',
                'text_secondary' => '#888888',
                'text_main' => '#2D2D2D',
                'border' => '#E0E0E0',
                'border_soft' => '#F0F0F0',
                'divider' => '#E0E0E0',
                'success' => '#2E7D32',
                'warning' => '#F5A623',
                'error' => '#C62828',
                'info' => '#666666',
                'card_shadow' => 'rgba(0, 0, 0, 0.06)',
                'hover_overlay' => 'rgba(0, 0, 0, 0.3)',
                'gold_highlight' => '#D4AF37',
                'champagne_glow' => '#F5F5DC',
                'sale_badge' => '#FF4444',
                'error_bg' => '#FEF2F2',
            ]
        ];
    }

    // ========== PALETTE 4: Minimal Soft Pink ==========
    private function getMinimalSoftPink(): array
    {
        return [
            'name' => '🌸 Minimal Soft Pink Luxury',
            'type' => 'custom',
            'is_active' => false,
            'colors' => [
                'primary' => '#D4A0A0',
                'secondary' => '#4A4A4A',
                'accent' => '#F0D5D5',
                'background' => '#FDF8F8',      // Light pink
                'surface' => '#FFFFFF',          // Light
                'bg_main' => '#FDF8F8',          // Light pink
                'bg_soft' => '#F5E6E6',          // Light pink
                'bg_light' => '#FFFFFF',         // Light
                'text_primary' => '#3C3C3C',
                'text_secondary' => '#9E7B7B',
                'text_main' => '#4A4A4A',
                'border' => '#F0D5D5',
                'border_soft' => '#F5E6E6',
                'divider' => '#F0D5D5',
                'success' => '#6B8E6B',
                'warning' => '#C9A9A9',
                'error' => '#B55A5A',
                'info' => '#A89B9B',
                'card_shadow' => 'rgba(212, 160, 160, 0.1)',
                'hover_overlay' => 'rgba(74, 74, 74, 0.3)',
                'gold_highlight' => '#D4A0A0',
                'champagne_glow' => '#F5E6E6',
                'sale_badge' => '#E8C8C8',
                'error_bg' => '#FEF2F2',
            ]
        ];
    }

    // ========== PALETTE 5: Trendy Instagram ==========
    private function getTrendyInstagram(): array
    {
        return [
            'name' => '🌼 Trendy Instagram Jewelry',
            'type' => 'custom',
            'is_active' => false,
            'colors' => [
                'primary' => '#E8B4B8',
                'secondary' => '#2D3436',
                'accent' => '#FFEAA7',
                'background' => '#FFF5F5',      // Light blush
                'surface' => '#FFFFFF',          // Light
                'bg_main' => '#FFF5F5',          // Light blush
                'bg_soft' => '#FFE4E1',          // Light pink
                'bg_light' => '#FFFFFF',         // Light
                'text_primary' => '#2D3436',
                'text_secondary' => '#636E72',
                'text_main' => '#2D3436',
                'border' => '#FFEAA7',
                'border_soft' => '#FFF0F0',
                'divider' => '#FFEAA7',
                'success' => '#00B894',
                'warning' => '#FDCB6E',
                'error' => '#FF7675',
                'info' => '#74B9FF',
                'card_shadow' => 'rgba(232, 180, 184, 0.15)',
                'hover_overlay' => 'rgba(45, 52, 54, 0.3)',
                'gold_highlight' => '#FFD700',
                'champagne_glow' => '#FFF8DC',
                'sale_badge' => '#FF6B6B',
                'error_bg' => '#FEF2F2',
            ]
        ];
    }

    // ========== PALETTE 6: Sage & Gold Organic ==========
    private function getSageGoldOrganic(): array
    {
        return [
            'name' => '🌿 Sage & Gold Organic',
            'type' => 'custom',
            'is_active' => false,
            'colors' => [
                'primary' => '#9CAF88',
                'secondary' => '#3A3A3A',
                'accent' => '#D4A853',
                'background' => '#FAFAF8',      // Light sage white
                'surface' => '#FFFFFF',          // Light
                'bg_main' => '#FAFAF8',          // Light sage white
                'bg_soft' => '#F2F4EF',          // Light sage
                'bg_light' => '#FFFFFF',         // Light
                'text_primary' => '#2C2C2C',
                'text_secondary' => '#7A8B6E',
                'text_main' => '#3A3A3A',
                'border' => '#D4D9CE',
                'border_soft' => '#E8ECE4',
                'divider' => '#D4D9CE',
                'success' => '#5B8C5A',
                'warning' => '#D4943A',
                'error' => '#C17F7F',
                'info' => '#7B9B8A',
                'card_shadow' => 'rgba(156, 175, 136, 0.1)',
                'hover_overlay' => 'rgba(58, 58, 58, 0.3)',
                'gold_highlight' => '#D4A853',
                'champagne_glow' => '#F5F0E8',
                'sale_badge' => '#C17F7F',
                'error_bg' => '#FEF2F2',
            ]
        ];
    }

    // ========== PALETTE 7: Pearl & Ocean Mist ==========
    private function getPearlOceanMist(): array
    {
        return [
            'name' => '🦪 Pearl & Ocean Mist',
            'type' => 'custom',
            'is_active' => false,
            'colors' => [
                'primary' => '#B8C9D4',
                'secondary' => '#2C3E50',
                'accent' => '#E8DED1',
                'background' => '#F7F9FB',      // Light blue white
                'surface' => '#FFFFFF',          // Light
                'bg_main' => '#F7F9FB',          // Light blue white
                'bg_soft' => '#EDF1F5',          // Light mist
                'bg_light' => '#FFFFFF',         // Light
                'text_primary' => '#1C2833',
                'text_secondary' => '#7B8FA1',
                'text_main' => '#2C3E50',
                'border' => '#D6DEE5',
                'border_soft' => '#E8EDF2',
                'divider' => '#D6DEE5',
                'success' => '#5D8B7A',
                'warning' => '#C9A96E',
                'error' => '#A87B7B',
                'info' => '#7B9EB3',
                'card_shadow' => 'rgba(184, 201, 212, 0.1)',
                'hover_overlay' => 'rgba(44, 62, 80, 0.3)',
                'gold_highlight' => '#C9A96E',
                'champagne_glow' => '#F0EDE8',
                'sale_badge' => '#A87B7B',
                'error_bg' => '#FEF2F2',
            ]
        ];
    }

    // ========== PALETTE 8: Caramel & Cream Luxe ==========
    private function getCaramelCreamLuxe(): array
    {
        return [
            'name' => '🍂 Caramel & Cream Luxe',
            'type' => 'custom',
            'is_active' => false,
            'colors' => [
                'primary' => '#B8956A',
                'secondary' => '#3D2B1F',
                'accent' => '#E8D5B7',
                'background' => '#FDF9F5',      // Light vanilla
                'surface' => '#FFFFFF',          // Light
                'bg_main' => '#FDF9F5',          // Light vanilla
                'bg_soft' => '#F5EDE4',          // Light cream
                'bg_light' => '#FFFFFF',         // Light
                'text_primary' => '#2C1810',
                'text_secondary' => '#8B7355',
                'text_main' => '#3D2B1F',
                'border' => '#E0D0C0',
                'border_soft' => '#F0E6D8',
                'divider' => '#E0D0C0',
                'success' => '#6B8B5A',
                'warning' => '#D4A853',
                'error' => '#B8675A',
                'info' => '#9B8B7B',
                'card_shadow' => 'rgba(184, 149, 106, 0.1)',
                'hover_overlay' => 'rgba(61, 43, 31, 0.3)',
                'gold_highlight' => '#D4A853',
                'champagne_glow' => '#F5ECD8',
                'sale_badge' => '#B8675A',
                'error_bg' => '#FEF2F2',
            ]
        ];
    }

    // ========== PALETTE 9: Midnight Amethyst (Light Background) ==========
    private function getMidnightAmethyst(): array
    {
        return [
            'name' => '🌙 Midnight Amethyst',
            'type' => 'custom',
            'is_active' => false,
            'colors' => [
                'primary' => '#9B8EC4',
                'secondary' => '#3D3D5C',
                'accent' => '#C4B5D0',
                'background' => '#F8F7FA',      // Light lavender
                'surface' => '#FFFFFF',          // Light
                'bg_main' => '#F8F7FA',          // Light lavender
                'bg_soft' => '#F0EDF5',          // Light lavender
                'bg_light' => '#FFFFFF',         // Light
                'text_primary' => '#2D2D4A',
                'text_secondary' => '#7B7B9E',
                'text_main' => '#3D3D5C',
                'border' => '#D8D4E5',
                'border_soft' => '#EBE8F2',
                'divider' => '#D8D4E5',
                'success' => '#6B8B7B',
                'warning' => '#C9A96E',
                'error' => '#B87B8B',
                'info' => '#8B9DC9',
                'card_shadow' => 'rgba(155, 142, 196, 0.1)',
                'hover_overlay' => 'rgba(61, 61, 92, 0.3)',
                'gold_highlight' => '#D4AF37',
                'champagne_glow' => '#F0EBF5',
                'sale_badge' => '#B87B8B',
                'error_bg' => '#FEF2F2',
            ]
        ];
    }

    // ========== PALETTE 10: Pure Ivory & Silver ==========
    private function getPureIvorySilver(): array
    {
        return [
            'name' => '🕊️ Pure Ivory & Silver',
            'type' => 'custom',
            'is_active' => false,
            'colors' => [
                'primary' => '#A8A8A8',
                'secondary' => '#3D3D3D',
                'accent' => '#D4D4D4',
                'background' => '#FAFAFA',      // Light
                'surface' => '#FFFFFF',          // Light
                'bg_main' => '#FAFAFA',          // Light
                'bg_soft' => '#F5F5F5',          // Light gray
                'bg_light' => '#FFFFFF',         // Light
                'text_primary' => '#2D2D2D',
                'text_secondary' => '#999999',
                'text_main' => '#3D3D3D',
                'border' => '#E0E0E0',
                'border_soft' => '#F0F0F0',
                'divider' => '#E0E0E0',
                'success' => '#7B9B7B',
                'warning' => '#C9A96E',
                'error' => '#B07B7B',
                'info' => '#9BA8B8',
                'card_shadow' => 'rgba(0, 0, 0, 0.05)',
                'hover_overlay' => 'rgba(61, 61, 61, 0.3)',
                'gold_highlight' => '#C0C0C0',
                'champagne_glow' => '#F5F5F0',
                'sale_badge' => '#B07B7B',
                'error_bg' => '#FEF2F2',
            ]
        ];
    }

    // ========== PALETTE 11: Soft Beige Minimal ==========
    private function getSoftBeigeMinimal(): array
    {
        return [
            'name' => '🤍 Soft Beige Minimal',
            'type' => 'custom',
            'is_active' => false,
            'colors' => [
                'primary' => '#E8DCCF',
                'secondary' => '#D8C3A5',
                'accent' => '#C6A77D',
                'background' => '#F7F3EE',      // Light beige
                'surface' => '#FFFFFF',          // Light
                'bg_main' => '#F7F3EE',          // Light beige
                'bg_soft' => '#F2EDE7',          // Light beige
                'bg_light' => '#FFFFFF',         // Light
                'text_primary' => '#3E3A37',
                'text_secondary' => '#7A6F66',
                'text_main' => '#3E3A37',
                'border' => '#E6DDD4',
                'border_soft' => '#F0EAE4',
                'divider' => '#E6DDD4',
                'success' => '#7B9B7B',
                'warning' => '#C9A96E',
                'error' => '#B07B7B',
                'info' => '#9BA8B8',
                'card_shadow' => 'rgba(62, 58, 55, 0.05)',
                'hover_overlay' => 'rgba(62, 58, 55, 0.3)',
                'gold_highlight' => '#C6A77D',
                'champagne_glow' => '#F5EFE7',
                'sale_badge' => '#C6A77D',
                'error_bg' => '#FEF2F2',
            ]
        ];
    }

    // ========== PALETTE 12: Beige + Soft Blush ==========
    private function getSoftBlushMinimal(): array
    {
        return [
            'name' => '🌸 Beige + Soft Blush',
            'type' => 'custom',
            'is_active' => false,
            'colors' => [
                'primary' => '#F4D7D3',
                'secondary' => '#E8B7B1',
                'accent' => '#D8A7A3',
                'background' => '#F9F4F2',      // Light blush
                'surface' => '#FFFFFF',          // Light
                'bg_main' => '#F9F4F2',          // Light blush
                'bg_soft' => '#F5ECEA',          // Light blush
                'bg_light' => '#FFFFFF',         // Light
                'text_primary' => '#4A3F3A',
                'text_secondary' => '#8A7A73',
                'text_main' => '#4A3F3A',
                'border' => '#EADBD6',
                'border_soft' => '#F2E8E4',
                'divider' => '#EADBD6',
                'success' => '#7B9B7B',
                'warning' => '#C9A96E',
                'error' => '#B07B7B',
                'info' => '#9BA8B8',
                'card_shadow' => 'rgba(74, 63, 58, 0.05)',
                'hover_overlay' => 'rgba(74, 63, 58, 0.3)',
                'gold_highlight' => '#D8A7A3',
                'champagne_glow' => '#F5EDEA',
                'sale_badge' => '#D8A7A3',
                'error_bg' => '#FEF2F2',
            ]
        ];
    }

    // ========== PALETTE 13: Neutral Sage Minimal ==========
    private function getNeutralSageMinimal(): array
    {
        return [
            'name' => '🌿 Neutral Sage Minimal',
            'type' => 'custom',
            'is_active' => false,
            'colors' => [
                'primary' => '#A8BBA3',
                'secondary' => '#DCE5DA',
                'accent' => '#7C9A7C',
                'background' => '#FAFAF8',      // Light sage white
                'surface' => '#FFFFFF',          // Light
                'bg_main' => '#FAFAF8',          // Light sage white
                'bg_soft' => '#F2F5F0',          // Light sage
                'bg_light' => '#FFFFFF',         // Light
                'text_primary' => '#2F3A2F',
                'text_secondary' => '#6F7F6F',
                'text_main' => '#2F3A2F',
                'border' => '#E5EDE5',
                'border_soft' => '#EFF4EF',
                'divider' => '#E5EDE5',
                'success' => '#5B8C5A',
                'warning' => '#C9A96E',
                'error' => '#B07B7B',
                'info' => '#7B9B8A',
                'card_shadow' => 'rgba(47, 58, 47, 0.05)',
                'hover_overlay' => 'rgba(47, 58, 47, 0.3)',
                'gold_highlight' => '#7C9A7C',
                'champagne_glow' => '#F0F4EC',
                'sale_badge' => '#7C9A7C',
                'error_bg' => '#FEF2F2',
            ]
        ];
    }

    // ========== PALETTE 14: Cream Monochrome ==========
    private function getCreamMonochrome(): array
    {
        return [
            'name' => '🤍 Cream Monochrome',
            'type' => 'custom',
            'is_active' => false,
            'colors' => [
                'primary' => '#F1E9DD',
                'secondary' => '#E8DCCB',
                'accent' => '#D4C2A8',
                'background' => '#FAF7F2',      // Light cream
                'surface' => '#FFFFFF',          // Light
                'bg_main' => '#FAF7F2',          // Light cream
                'bg_soft' => '#F5F0E8',          // Light cream
                'bg_light' => '#FFFFFF',         // Light
                'text_primary' => '#5A4634',
                'text_secondary' => '#9A8A7A',
                'text_main' => '#5A4634',
                'border' => '#EFE6D8',
                'border_soft' => '#F5EFE5',
                'divider' => '#EFE6D8',
                'success' => '#7B9B7B',
                'warning' => '#C9A96E',
                'error' => '#B07B7B',
                'info' => '#9BA8B8',
                'card_shadow' => 'rgba(90, 70, 52, 0.05)',
                'hover_overlay' => 'rgba(90, 70, 52, 0.3)',
                'gold_highlight' => '#D4C2A8',
                'champagne_glow' => '#F5EFE5',
                'sale_badge' => '#D4C2A8',
                'error_bg' => '#FEF2F2',
            ]
        ];
    }
    private function getPureWhiteWarmCream(): array
{
    return [
        'name' => '🤍 Pure White & Warm Cream',
        'type' => 'custom',
        'is_active' => false,
        'colors' => [
            'primary' => '#D4B896',
            'secondary' => '#C4A67A',
            'accent' => '#E8D5C0',
            'background' => '#FFFFFF',
            'surface' => '#FFFFFF',
            'bg_main' => '#FFFFFF',
            'bg_soft' => '#FAFAF8',
            'bg_light' => '#FFFFFF',
            'text_primary' => '#2C2C2C',
            'text_secondary' => '#8B8070',
            'text_main' => '#3D3D3D',
            'border' => '#E8E0D5',
            'border_soft' => '#F0EBE5',
            'divider' => '#E8E0D5',
            'success' => '#6B8B6B',
            'warning' => '#C9A96E',
            'error' => '#B07B7B',
            'info' => '#8B9BA8',
            'card_shadow' => 'rgba(180, 160, 140, 0.08)',
            'hover_overlay' => 'rgba(60, 50, 40, 0.3)',
            'gold_highlight' => '#D4B896',
            'champagne_glow' => '#F5ECE0',
            'sale_badge' => '#C4A67A',
            'error_bg' => '#FDF5F5',
        ]
    ];
}
}
