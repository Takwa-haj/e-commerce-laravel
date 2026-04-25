<?php
// app/Services/BarcodeService.php

namespace App\Services;

use Picqer\Barcode\BarcodeGeneratorHTML;
use Picqer\Barcode\BarcodeGeneratorPNG;
use Picqer\Barcode\BarcodeGeneratorSVG;
use Picqer\Barcode\BarcodeGeneratorJPG;

class BarcodeService
{
    protected $htmlGenerator;
    protected $pngGenerator;

    public function __construct()
    {
        $this->htmlGenerator = new BarcodeGeneratorHTML();
        $this->pngGenerator = new BarcodeGeneratorPNG();
    }

    /**
     * Generate HTML barcode for invoices
     */
    public function generateInvoiceBarcode($orderId, $height = 50)
    {
        $code = 'INV-' . str_pad($orderId, 8, '0', STR_PAD_LEFT);

        try {
            return $this->htmlGenerator->getBarcode($code, $this->htmlGenerator::TYPE_CODE_128, 2, $height);
        } catch (\Exception $e) {
            return $this->getFallbackBarcode($code, $height);
        }
    }

    /**
     * Generate HTML barcode for shipping labels
     */
    public function generateShippingBarcode($orderId, $height = 50)
    {
        $code = 'SHIP-' . str_pad($orderId, 8, '0', STR_PAD_LEFT);

        try {
            return $this->htmlGenerator->getBarcode($code, $this->htmlGenerator::TYPE_CODE_128, 2, $height);
        } catch (\Exception $e) {
            return $this->getFallbackBarcode($code, $height);
        }
    }

    /**
     * Generate HTML barcode for packing slips
     */
    public function generatePackingBarcode($orderId, $height = 50)
    {
        $code = 'PCK-' . str_pad($orderId, 8, '0', STR_PAD_LEFT);

        try {
            return $this->htmlGenerator->getBarcode($code, $this->htmlGenerator::TYPE_CODE_128, 2, $height);
        } catch (\Exception $e) {
            return $this->getFallbackBarcode($code, $height);
        }
    }

    /**
     * Generate HTML barcode for delivery notes
     */
    public function generateDeliveryBarcode($orderId, $height = 50)
    {
        $code = 'DEL-' . str_pad($orderId, 8, '0', STR_PAD_LEFT);

        try {
            return $this->htmlGenerator->getBarcode($code, $this->htmlGenerator::TYPE_CODE_128, 2, $height);
        } catch (\Exception $e) {
            return $this->getFallbackBarcode($code, $height);
        }
    }

    /**
     * Generate PNG barcode as base64
     */
    public function generatePNGBarcode($code, $height = 50)
    {
        try {
            $barcode = $this->pngGenerator->getBarcode($code, $this->pngGenerator::TYPE_CODE_128, 2, $height);
            return 'data:image/png;base64,' . base64_encode($barcode);
        } catch (\Exception $e) {
            return null;
        }
    }

    /**
     * Generate EAN-13 barcode
     */
    public function generateEAN13Barcode($orderId, $height = 50)
    {
        // EAN-13 needs exactly 12 digits + check digit
        $code = str_pad($orderId, 12, '0', STR_PAD_LEFT);

        try {
            return $this->htmlGenerator->getBarcode($code, $this->htmlGenerator::TYPE_EAN_13, 2, $height);
        } catch (\Exception $e) {
            return $this->getFallbackBarcode($code, $height);
        }
    }

    /**
     * Generate all barcodes for an order
     */
    public function getAllBarcodes($orderId)
    {
        $paddedId = str_pad($orderId, 8, '0', STR_PAD_LEFT);

        return [
            'invoice' => [
                'code' => 'INV-' . $paddedId,
                'html' => $this->generateInvoiceBarcode($orderId, 40),
                'png' => $this->generatePNGBarcode('INV-' . $paddedId, 40)
            ],
            'shipping' => [
                'code' => 'SHIP-' . $paddedId,
                'html' => $this->generateShippingBarcode($orderId, 40),
                'png' => $this->generatePNGBarcode('SHIP-' . $paddedId, 40)
            ],
            'packing' => [
                'code' => 'PCK-' . $paddedId,
                'html' => $this->generatePackingBarcode($orderId, 40),
                'png' => $this->generatePNGBarcode('PCK-' . $paddedId, 40)
            ],
            'delivery' => [
                'code' => 'DEL-' . $paddedId,
                'html' => $this->generateDeliveryBarcode($orderId, 40),
                'png' => $this->generatePNGBarcode('DEL-' . $paddedId, 40)
            ],
            'ean13' => [
                'code' => $paddedId,
                'html' => $this->generateEAN13Barcode($orderId, 40)
            ]
        ];
    }

    /**
     * Fallback method using font-based barcode
     */
    protected function getFallbackBarcode($code, $height = 50)
    {
        $fontSize = $height * 0.8;

        return '<div style="text-align: center; font-family: \'Libre Barcode 128\', cursive; font-size: ' . $fontSize . 'px; color: #000; line-height: 1.2;">*' . $code . '*</div>' .
               '<div style="text-align: center; font-family: monospace; font-size: 10px; color: #666; margin-top: 5px;">' . $code . '</div>';
    }
}
