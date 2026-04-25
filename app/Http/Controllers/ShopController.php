<?php

namespace App\Http\Controllers;

use App\Models\Brand;
use App\Models\Category;
use App\Models\Size;
use App\Models\Color;
use App\Models\Product;
use App\Models\ShopBanner;
use Illuminate\Http\Request;
use App\Models\Review;
class ShopController extends Controller
{
    public function index(Request $request)
    {
        // Rename pagination size variable to avoid conflict
        $pageSize = $request->query("size") ? $request->query('size') : 12;

        $o_column = "";
        $o_order = "";
        $order = $request->query('order') ? $request->query('order') : -1;

        // Get all filter parameters
        $f_brands = $request->query('brands', '');
        $f_categories = $request->query('categories', '');
        $f_sizes = $request->query('sizes', '');
        $f_colors = $request->query('colors', '');

        $min_price = $request->query('min') ? $request->query('min') : 1;
        $max_price = $request->query('max') ? $request->query('max') : 500;

        // Determine sorting
        switch ($order) {
            case 1:
                $o_column = 'created_at';
                $o_order = 'DESC';
                break;
            case 2:
                $o_column = 'created_at';
                $o_order = 'ASC';
                break;
            case 3:
                $o_column = 'sale_price';
                $o_order = 'ASC';
                break;
            case 4:
                $o_column = 'sale_price';
                $o_order = 'DESC';
                break;
            default:
                $o_column = 'id';
                $o_order = 'DESC';
        }

        // Get all filter data
        $brands = Brand::orderBy('name', 'asc')->get();
        $categories = Category::orderBy('name', 'asc')->get();

        // Get sizes for filter (rename collection to avoid conflict)
        $sizeOptions = Size::where('status', 1)->orderBy('order', 'asc')->get();

        // Get colors for filter
      $colors = Color::where('status', 1)
                      ->withCount(['products' => function($query) {
                          $query->where('status', 1);
                      }])
                      ->orderBy('order', 'asc')
                      ->get();
        // Get active shop slides from new table
         $shopBanners = ShopBanner::where('is_active', true)
        ->orderBy('sort_order', 'asc')
        ->orderBy('created_at', 'desc')
        ->get();

        // Start product query
        $products = Product::where('status', 1);

        // Apply brand filter
        if ($f_brands) {
            $products->where(function($query) use ($f_brands) {
                $query->whereIn('brand_id', explode(',', $f_brands))
                      ->orWhereRaw("'" . $f_brands . "'=''");
            });
        }

        // Apply category filter
        if ($f_categories) {
            $products->where(function($query) use ($f_categories) {
                $query->whereIn('category_id', explode(',', $f_categories))
                      ->orWhereRaw("'" . $f_categories . "'=''");
            });
        }

        // Apply size filter
        if ($f_sizes) {
            $sizeIds = explode(',', $f_sizes);
            $products->whereHas('sizes', function($query) use ($sizeIds) {
                $query->whereIn('sizes.id', $sizeIds);
            });
        }

        // Apply color filter
        if ($f_colors) {
            $colorIds = explode(',', $f_colors);
            $products->whereHas('colors', function($query) use ($colorIds) {
                $query->whereIn('colors.id', $colorIds);
            });
        }

        // Apply price filter
        $products->where(function($query) use ($min_price, $max_price) {
            $query->whereBetween('regular_price', [$min_price, $max_price])
                  ->orWhereBetween('sale_price', [$min_price, $max_price]);
        });

        // Apply sorting and pagination
        $products = $products->orderBy($o_column, $o_order)->paginate($pageSize);

        return view("shop", compact(
            'products',
            'pageSize',
            'order',
            'brands',
            'f_brands',
            'categories',
            'f_categories',
            'sizeOptions',
            'f_sizes',
            'colors',
            'f_colors',
            'min_price',
            'max_price',
            'shopBanners'

        ));
    }

    public function product_details($product_slug)
    {
        $product = Product::where('slug', $product_slug)->where('status', 1)->firstOrFail();
        $rproducts = Product::where('slug', '<>', $product->slug)->where('status', 1)->inRandomOrder()->take(8)->get();

        $reviews = $product->reviews()
        ->where('approved', true)
        ->orderBy('created_at', 'desc')
        ->get();

        return view('details', compact('product', 'rproducts','reviews'));
    }
    public function reviews()
{
    return $this->hasMany(Review::class)->orderBy('created_at', 'desc');
}

public function checkVariant(Request $request, $productId)
{
    try {
        $product = Product::findOrFail($productId);

        $sizeId = $request->size_id;
        $colorId = $request->color_id;

        // Find the variant
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
                    'is_active' => $variant->is_active
                ]
            ]);
        }

        // If no variant found, check if we can use the default product
        if (!$sizeId && !$colorId) {
            return response()->json([
                'success' => true,
                'variant' => [
                    'id' => null,
                    'sku' => $product->SKU,
                    'price' => $product->regular_price,
                    'sale_price' => $product->sale_price,
                    'quantity' => $product->quantity,
                    'size_id' => null,
                    'color_id' => null,
                    'is_active' => true
                ]
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => 'Variante non disponible'
        ]);

    } catch (\Exception $e) {
        return response()->json([
            'success' => false,
            'message' => 'Erreur lors de la vérification de la variante'
        ], 500);
    }
}
}
