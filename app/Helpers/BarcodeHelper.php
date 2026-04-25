<?php

namespace App\Helpers;

class BarcodeHelper
{
    public static function generateBarcodeHTML($number, $height = 60)
    {
        $html = '<div style="text-align: center; margin: 20px 0;">';
        $html .= '<div style="display: inline-block; background: white; padding: 15px; border: 1px solid #ddd; border-radius: 5px;">';

        // Convert number to binary representation for bars
        $binary = '';
        for ($i = 0; $i < strlen($number); $i++) {
            $binary .= str_pad(decbin(ord($number[$i])), 8, '0', STR_PAD_LEFT);
        }

        // Generate bars
        $html .= '<div style="display: flex; align-items: center; justify-content: center; height: ' . $height . 'px;">';
        for ($i = 0; $i < strlen($binary); $i++) {
            $width = $binary[$i] == '1' ? 3 : 1;
            $color = $binary[$i] == '1' ? '#000' : '#fff';
            $html .= '<div style="width: ' . $width . 'px; height: 100%; background: ' . $color . '; margin: 0 1px;"></div>';
        }
        $html .= '</div>';

        // Number below
        $html .= '<div style="font-family: monospace; font-size: 16px; letter-spacing: 5px; margin-top: 10px; font-weight: bold;">';
        $html .= $number;
        $html .= '</div>';

        $html .= '</div></div>';

        return $html;
    }
}
