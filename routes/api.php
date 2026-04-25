
<?php

use App\Http\Controllers\HomeController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\SizeController;
use App\Http\Controllers\ColorController;
use App\Http\Controllers\ReviewController;
use App\Http\Controllers\StripeCheckoutController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\ShopController;
use App\Http\Controllers\StripeController;
use App\Http\Controllers\Auth0Controller;
use App\Http\Controllers\Admin\NotificationController;
use App\Http\Controllers\RefundController;
use App\Http\Controllers\WishlistController;
use App\Http\Middleware\AuthAdmin;
use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use App\Http\Controllers\ProductController;




Route::post('/product/{id}/variant-check', function(Request $request, $id) {
    $product = \App\Models\Product::findOrFail($id);

    $variant = $product->variants()
        ->where('size_id', $request->size_id)
        ->where('color_id', $request->color_id)
        ->first();

    if ($variant) {
        return response()->json([
            'success' => true,
            'variant' => [
                'id' => $variant->id,
                'quantity' => $variant->quantity,
                'price' => $variant->price,
                'sale_price' => $variant->sale_price,
                'sku' => $variant->sku
            ]
        ]);
    } else {
        // Check if variant combination exists (even with 0 quantity)
        $exists = $product->variants()
            ->where('size_id', $request->size_id)
            ->where('color_id', $request->color_id)
            ->exists();

        if ($exists) {
            return response()->json([
                'success' => true,
                'variant' => [
                    'id' => null,
                    'quantity' => 0,
                    'price' => $product->regular_price,
                    'sale_price' => $product->sale_price,
                    'sku' => 'N/A'
                ]
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => 'Variant not found'
        ]);
    }
})->middleware('web');

Route::post('/product/{id}/variant-check', [ProductController::class, 'checkVariant']);
