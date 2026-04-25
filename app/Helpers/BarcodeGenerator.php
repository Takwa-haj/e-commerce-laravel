<?php

namespace App\Helpers;

class BarcodeGenerator
{
    public static function generateCode128($text)
    {
        // Code 128 character set (simplified)
        $code128 = [
            '0' => '11011001100', '1' => '11001101100', '2' => '11001100110',
            '3' => '10010011000', '4' => '10010001100', '5' => '10001001100',
            '6' => '10011001000', '7' => '10011000100', '8' => '10001100100',
            '9' => '11001001000', 'A' => '11001000100', 'B' => '11000100100',
        ];

        $barcode = '';
        $text = strtoupper($text);

        for ($i = 0; $i < strlen($text); $i++) {
            $char = $text[$i];
            if (isset($code128[$char])) {
                $barcode .= $code128[$char];
            }
        }

        return $barcode;
    }

    public static function generateBars($barcodePattern, $barWidth = 2, $barHeight = 50)
    {
        $bars = [];
        $pattern = str_split($barcodePattern);

        foreach ($pattern as $bit) {
            $bars[] = [
                'width' => $barWidth,
                'height' => $bit == '1' ? $barHeight : $barHeight * 0.7,
                'color' => $bit == '1' ? '#000000' : '#333333'
            ];
        }

        return $bars;
    }

    public static function generateSimpleBarcode($number, $totalWidth = 200, $maxHeight = 60)
    {
        $digits = str_split($number);
        $bars = [];
        $barWidth = floor($totalWidth / (count($digits) * 4));

        foreach ($digits as $digit) {
            $height = ($digit + 1) * ($maxHeight / 10);

            // Create 4 bars per digit (simulating binary)
            for ($i = 0; $i < 4; $i++) {
                $bars[] = [
                    'width' => $barWidth,
                    'height' => $height - ($i * 5),
                    'color' => '#000000'
                ];
            }
        }

        return $bars;
    }
}
