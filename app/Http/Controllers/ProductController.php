<?php
// app/Http/Controllers/ProductController.php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    /**
     * Check variant availability
     */
    public function checkVariant(Request $request, $id)
    {
        $product = Product::findOrFail($id);

        $sizeId = $request->input('size_id');
        $colorId = $request->input('color_id');

        // Find variant
        $variant = $product->variants()
            ->when($sizeId, function($query) use ($sizeId) {
                return $query->where('size_id', $sizeId);
            })
            ->when($colorId, function($query) use ($colorId) {
                return $query->where('color_id', $colorId);
            })
            ->first();

        if ($variant) {
            return response()->json([
                'success' => true,
                'variant' => [
                    'id' => $variant->id,
                    'sku' => $variant->sku,
                    'price' => $variant->price,
                    'sale_price' => $variant->sale_price,
                    'quantity' => $variant->quantity,
                    'size_id' => $variant->size_id,
                    'color_id' => $variant->color_id,
                    'stock_status' => $variant->stock_status,
                    'stock_status_label' => $variant->stock_status_label,
                    'stock_status_color' => $variant->stock_status_color
                ]
            ]);
        }

        // Check if it's a combination that should exist but doesn't
        $sizeExists = $sizeId ? $product->sizes()->where('sizes.id', $sizeId)->exists() : true;
        $colorExists = $colorId ? $product->colors()->where('colors.id', $colorId)->exists() : true;

        if ($sizeExists && $colorExists) {
            // This combination is theoretically available but no variant created
            // Return product defaults
            return response()->json([
                'success' => true,
                'variant' => [
                    'id' => null,
                    'sku' => $product->SKU,
                    'price' => $product->regular_price,
                    'sale_price' => $product->sale_price,
                    'quantity' => 0,
                    'size_id' => $sizeId,
                    'color_id' => $colorId,
                    'stock_status' => 'outofstock',
                    'stock_status_label' => 'Rupture',
                    'stock_status_color' => 'danger'
                ]
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => 'Variant not found'
        ]);
    }
}
