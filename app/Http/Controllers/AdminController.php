<?php

namespace App\Http\Controllers;
use App\Models\Brand;
use App\Models\Category;
use App\Models\ColorPalette;
use App\Services\ColorService;
use Illuminate\Support\Facades\Cache;
use App\Models\Contact;
use App\Models\Coupon;
use App\Models\CategoryBanner;
use App\Models\Order;
use App\Services\BrevoService;
use Illuminate\Support\Facades\Mail;
use App\Models\User;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Models\Setting;
use App\Models\Review;
use App\Models\Size;
use App\Models\Color;
use App\Models\OrderItem;
use App\Models\Transaction;
use App\Models\ShopBanner;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;
use App\Models\ProductVariant;
use Intervention\Image\Laravel\Facades\Image;
use App\Models\Product;
use App\Models\Slide;
use App\Services\NotificationService;
use Illuminate\Support\Facades\Auth;
use App\Models\Blog;


class AdminController extends Controller
{
public function index()
{
    // Get recent orders for table
    $orders = Order::with('user')
        ->orderBy("created_at","desc")
        ->take(10)
        ->get();

    // Existing dashboard statistics
    $dashboardDatas = DB::select("SELECT
        SUM(total) as TotalAmount,
        SUM(IF(status='ordered', total, 0)) As TotalOrderedAmount,
        SUM(IF(status='confirmed', total, 0)) As TotalConfirmedAmount,
        SUM(IF(status='delivered', total, 0)) As TotalDeliveredAmount,
        SUM(IF(status='canceled', total, 0)) As TotalCancelledAmount,
        COUNT(*) As Total,
        SUM(IF(status='ordered', 1, 0)) As TotalOrdered,
        SUM(IF(status='confirmed', 1, 0)) As TotalConfirmed,
        SUM(IF(status='delivered', 1, 0)) As TotalDelivered,
        SUM(IF(status='canceled', 1, 0)) As TotalCancelled
    FROM orders");

    // Monthly data for charts - FIXED: Make sure we get data for ALL months
    $monthlyDatas = DB::table('month_names as M')
        ->select(
            'M.id as MonthNo',
            'M.name as MonthName',
            DB::raw('IFNULL(SUM(D.total), 0) as TotalAmount'),
            DB::raw('IFNULL(SUM(CASE WHEN D.status = "ordered" THEN D.total ELSE 0 END), 0) as TotalOrderedAmount'),
            DB::raw('IFNULL(SUM(CASE WHEN D.status = "delivered" THEN D.total ELSE 0 END), 0) as TotalDeliveredAmount'),
            DB::raw('IFNULL(SUM(CASE WHEN D.status = "canceled" THEN D.total ELSE 0 END), 0) as TotalCanceledAmount')
        )
        ->leftJoin('orders as D', function($join) {
            $join->on(DB::raw('MONTH(D.created_at)'), '=', 'M.id')
                 ->whereYear('D.created_at', date('Y'));
        })
        ->groupBy('M.id', 'M.name')
        ->orderBy('M.id')
        ->get();

    // Format data for charts - ENSURE THESE ARE ALWAYS ARRAYS
    $AmountM = $monthlyDatas->pluck('TotalAmount')->toArray();
    $OrderedAmountM = $monthlyDatas->pluck('TotalOrderedAmount')->toArray();
    $DeliveredAmountM = $monthlyDatas->pluck('TotalDeliveredAmount')->toArray();
    $CanceledAmountM = $monthlyDatas->pluck('TotalCanceledAmount')->toArray();

    // --- ENHANCED STATISTICS ---

    // 1. Total Revenue (only from delivered orders)
    $totalRevenue = Order::where('status', 'delivered')->sum('total') ?: 0;

    // 2. Total Orders Count
    $totalOrders = Order::count() ?: 0;

    // 3. Average Order Value
    $averageOrderValue = $totalOrders > 0 ? round($totalRevenue / $totalOrders, 2) : 0;

    // 4. New Customers this month
    $newCustomers = User::where('utype', 'USR')
        ->whereMonth('created_at', now()->month)
        ->whereYear('created_at', now()->year)
        ->count() ?: 0;

    // 5. Today's statistics
    $todayRevenue = Order::whereDate('created_at', today())
        ->where('status', 'delivered')
        ->sum('total') ?: 0;

    $todayNewCustomers = User::where('utype', 'USR')
        ->whereDate('created_at', today())
        ->count() ?: 0;

    // 6. Revenue data for line chart (last 12 months) - FIXED: Always return array of 12 values
    $revenueData = [];
    for ($i = 1; $i <= 12; $i++) {
        $revenueData[] = Order::whereMonth('created_at', $i)
            ->whereYear('created_at', now()->year)
            ->where('status', 'delivered')
            ->sum('total') ?: 0;
    }

    // 7. Order status distribution for donut chart - FIXED: Always return array of 4 values
    $orderStatusData = [
        Order::where('status', 'delivered')->count() ?: 0,
        Order::where('status', 'confirmed')->count() ?: 0,
        Order::where('status', 'ordered')->count() ?: 0,
        Order::where('status', 'canceled')->count() ?: 0,
    ];

    // 8. Top selling products
    $topProducts = Product::select(
            'products.id',
            'products.name',
            'products.image',
            'products.SKU'
        )
        ->selectRaw('COALESCE(SUM(order_items.quantity), 0) as total_sold')
        ->selectRaw('COALESCE(SUM(order_items.price * order_items.quantity), 0) as total_revenue')
        ->leftJoin('order_items', 'products.id', '=', 'order_items.product_id')
        ->leftJoin('orders', function($join) {
            $join->on('order_items.order_id', '=', 'orders.id')
                 ->where('orders.status', 'delivered');
        })
        ->groupBy('products.id', 'products.name', 'products.image', 'products.SKU')
        ->orderBy('total_sold', 'desc')
        ->limit(5)
        ->get();

    // 9. Recent activities
    $recentActivities = $this->getRecentActivities();

    // 10. Conversion rate
    $conversionRate = $this->calculateConversionRate();

    // 11. Average rating
    $avgRating = Review::where('status', 'approved')->avg('rating') ?? 0;
    $avgRating = round($avgRating, 1);

    // 12. Return/Cancellation rate
    $returnRate = $this->calculateReturnRate();

    // 13. Pending actions
    $pendingOrders = Order::whereIn('status', ['ordered', 'confirmed'])->count() ?: 0;
    $pendingReviews = Review::where('status', 'pending')->count() ?: 0;
    $unreadMessages = Contact::where('read', false)->count() ?: 0;

    // 14. Top categories - FIXED: Always return collection with data
    $topCategories = Category::select('id', 'name', 'image')
        ->withCount('products')
        ->orderBy('products_count', 'desc')
        ->limit(5)
        ->get();

    // Add color to categories for charts
    $categoryColors = ['#2377FC', '#8b5cf6', '#ec4899', '#f59e0b', '#10b981'];
    foreach ($topCategories as $index => $category) {
        $category->color = $categoryColors[$index % count($categoryColors)] ?? '#2377FC';
    }

    // 15. Inventory alerts
    $lowStockProducts = Product::where('quantity', '<', 10)
        ->where('stock_status', '!=', 'outofstock')
        ->count() ?: 0;

    // 16. NEW VARIABLES FOR ADDITIONAL GRAPHS
    $totalProducts = Product::count() ?: 0;
    $inStockProducts = Product::where('stock_status', 'instock')->count() ?: 0;
    $outOfStockProducts = Product::where('stock_status', 'outofstock')->count() ?: 0;

    // 17. Customer loyalty metrics
    $repeatCustomers = User::has('orders', '>=', 2)->count() ?: 0;
    $repeatRate = $newCustomers > 0 ? round(($repeatCustomers / $newCustomers) * 100, 1) : 0;
    $satisfactionRate = round($avgRating * 20); // Convert 4.2/5 to 84%

    // 18. Year comparison data
    $lastYearRevenueData = [];
    for ($i = 1; $i <= 12; $i++) {
        $lastYearRevenueData[] = Order::whereMonth('created_at', $i)
            ->whereYear('created_at', now()->year - 1)
            ->where('status', 'delivered')
            ->sum('total') ?: 0;
    }

    return view('admin.index', compact(
        'orders',
        'dashboardDatas',
        'AmountM',
        'OrderedAmountM',
        'DeliveredAmountM',
        'CanceledAmountM',
        'totalRevenue',
        'totalOrders',
        'averageOrderValue',
        'newCustomers',
        'todayRevenue',
        'todayNewCustomers',
        'revenueData',
        'lastYearRevenueData',
        'orderStatusData',
        'topProducts',
        'recentActivities',
        'conversionRate',
        'avgRating',
        'returnRate',
        'pendingOrders',
        'pendingReviews',
        'unreadMessages',
        'topCategories',
        'lowStockProducts',
        'totalProducts',
        'inStockProducts',
        'outOfStockProducts',
        'repeatCustomers',
        'repeatRate',
        'satisfactionRate',

    ));
}



private function getRecentActivities()
{
    $activities = [];

    try {
        // Get last 5 orders
        $recentOrders = Order::with('user')
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();

        foreach ($recentOrders as $order) {
            $activities[] = [
                'icon' => 'fas fa-shopping-cart',
                'color' => 'primary',
                'title' => 'New Order #' . $order->id,
                'description' => $order->name . ' placed a new order',
                'time' => $order->created_at->diffForHumans(),
                'type' => 'order'
            ];
        }

        // Get last 3 new customers
        $newCustomers = User::where('utype', 'USR')
            ->orderBy('created_at', 'desc')
            ->take(3)
            ->get();

        foreach ($newCustomers as $customer) {
            $activities[] = [
                'icon' => 'fas fa-user-plus',
                'color' => 'success',
                'title' => 'New Customer',
                'description' => $customer->name . ' registered',
                'time' => $customer->created_at->diffForHumans(),
                'type' => 'customer'
            ];
        }

        // Sort by time and limit to 4
        usort($activities, function($a, $b) {
            // For demonstration, use timestamp comparison
            return strtotime($b['time']) - strtotime($a['time']);
        });

        return array_slice($activities, 0, 4);

    } catch (\Exception $e) {
        // Return default activities if there's an error
        return [
            [
                'icon' => 'fas fa-shopping-cart',
                'color' => 'primary',
                'title' => 'System Active',
                'description' => 'Dashboard is loading statistics',
                'time' => 'Just now',
                'type' => 'system'
            ]
        ];
    }
}

private function calculateConversionRate()
{
    try {
        // Simplified calculation
        $uniqueOrderUsers = Order::whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->distinct('user_id')
            ->count('user_id');

        // Simulate visitors (10x customers for demonstration)
        $totalVisitors = max($uniqueOrderUsers * 10, 100);

        if ($totalVisitors > 0) {
            return round(($uniqueOrderUsers / $totalVisitors) * 100, 2);
        }

        return 2.5;
    } catch (\Exception $e) {
        return 2.5;
    }
}

private function calculateReturnRate()
{
    try {
        $totalDelivered = Order::where('status', 'delivered')->count();
        $totalCancelled = Order::where('status', 'canceled')->count();

        if ($totalDelivered > 0) {
            return round(($totalCancelled / $totalDelivered) * 100, 2);
        }

        return 0;
    } catch (\Exception $e) {
        return 0;
    }
}



// Then add these methods to AdminController:
public function getDashboardStats()
{
    return response()->json([
        'totalRevenue' => Order::where('status', 'delivered')->sum('total'),
        'totalOrders' => Order::count(),
        'todayRevenue' => Order::whereDate('created_at', today())
            ->where('status', 'delivered')
            ->sum('total'),
        'todayOrders' => Order::whereDate('created_at', today())->count(),
        'pendingOrders' => Order::whereIn('status', ['ordered', 'confirmed'])->count(),
        'newCustomers' => User::where('utype', 'USR')
            ->whereDate('created_at', today())
            ->count(),
        'unreadMessages' => Contact::where('read', false)->count(),
        'lowStockProducts' => Product::where('quantity', '<', 10)
            ->where('stock_status', '!=', 'outofstock')
            ->count(),
    ]);
}

public function getChartData()
{
    $currentYear = now()->year;

    // Monthly revenue data
    $revenueData = [];
    for ($i = 1; $i <= 12; $i++) {
        $revenueData[] = Order::whereMonth('created_at', $i)
            ->whereYear('created_at', $currentYear)
            ->where('status', 'delivered')
            ->sum('total') ?: 0;
    }

    // Order status counts
    $orderStatus = [
        Order::where('status', 'delivered')->count() ?: 0,
        Order::where('status', 'confirmed')->count() ?: 0,
        Order::where('status', 'ordered')->count() ?: 0,
        Order::where('status', 'canceled')->count() ?: 0,
    ];

    // Monthly data for bar chart
    $monthlyDatas = DB::table('month_names as M')
        ->select(
            'M.id as MonthNo',
            'M.name as MonthName',
            DB::raw('IFNULL(SUM(D.total), 0) as TotalAmount'),
            DB::raw('IFNULL(SUM(CASE WHEN D.status = "ordered" THEN D.total ELSE 0 END), 0) as TotalOrderedAmount'),
            DB::raw('IFNULL(SUM(CASE WHEN D.status = "delivered" THEN D.total ELSE 0 END), 0) as TotalDeliveredAmount'),
            DB::raw('IFNULL(SUM(CASE WHEN D.status = "canceled" THEN D.total ELSE 0 END), 0) as TotalCanceledAmount')
        )
        ->leftJoin('orders as D', function($join) use ($currentYear) {
            $join->on(DB::raw('MONTH(D.created_at)'), '=', 'M.id')
                 ->whereYear('D.created_at', $currentYear);
        })
        ->groupBy('M.id', 'M.name')
        ->orderBy('M.id')
        ->get();

    // Last year data for comparison
    $lastYearData = [];
    for ($i = 1; $i <= 12; $i++) {
        $lastYearData[] = Order::whereMonth('created_at', $i)
            ->whereYear('created_at', $currentYear - 1)
            ->where('status', 'delivered')
            ->sum('total') ?: 0;
    }

    return response()->json([
        'revenue' => $revenueData,
        'orderStatus' => $orderStatus,
        'barData' => [
            'total' => $monthlyDatas->pluck('TotalAmount')->toArray(),
            'ordered' => $monthlyDatas->pluck('TotalOrderedAmount')->toArray(),
            'delivered' => $monthlyDatas->pluck('TotalDeliveredAmount')->toArray(),
            'canceled' => $monthlyDatas->pluck('TotalCanceledAmount')->toArray(),
        ],
        'comparison' => [
            'current' => $revenueData,
            'previous' => $lastYearData
        ]
    ]);
}

    public function brands(Request $request)
{
    $query = $request->input('query');

    $brands = Brand::withCount(['products' => function($q) {
                    $q->where('status', 1);
                }]);

    if ($query) {
        $brands->where('name', 'LIKE', "%{$query}%")
               ->orWhere('slug', 'LIKE', "%{$query}%");
    }

    $brands = $brands->orderBy('name', 'asc')->paginate(10);

    return view('admin.brands', compact('brands'));
}

    public function add_Brand(){
        return view('admin.brand-add');
    }

    public function brand_store(Request $request){
        $request->validate([
            'name' => 'required|unique:brands,name',
            'slug' => 'required|unique:brands,slug',
            'image' => 'mimes:png,jpg,jpeg|max:2048'
        ]);

        $brand = new Brand();
        $brand->name = $request->name;
        $brand->slug = Str::slug($request->name);

        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $file_extension = $image->extension();
            $file_name = Carbon::now()->timestamp.'.'.$file_extension;
            $this->GenerateBrandandThunbnailsImage($image, $file_name);
            $brand->image = $file_name;
        }

        $brand->save();
        return redirect()->route('admin.brands')->with('status','Brand has been added successfully');
    }

    public function brand_edit($id){
        $brand = Brand::find($id);
        return view('admin.brand-edit', compact('brand'));
    }

    public function brand_update(Request $request){
        $request->validate([
            'name' => 'required',
            'slug' => 'required|unique:brands,slug,' . $request->id,
            'image' => 'mimes:png,jpg,jpeg|max:2048'
        ]);

        $brand = Brand::find($request->id);
        $brand->name = $request->name;
        $brand->slug = Str::slug($request->name);

        if ($request->hasFile('image')){
            if(File::exists(public_path('uploads/brands').'/'.$brand->image)){
                File::delete(public_path('uploads/brands').'/'.$brand->image);
            }

            $image = $request->file('image');
            $file_extension = $image->extension();
            $file_name = Carbon::now()->timestamp.'.'.$file_extension;
            $this->GenerateBrandandThunbnailsImage($image, $file_name);
            $brand->image = $file_name;
        }

        $brand->save();
        return redirect()->route('admin.brands')->with('status','Brand has been updated successfully');
    }

    public function GenerateBrandandThunbnailsImage($image, $imageName)
    {
        $destinationPath = public_path('uploads/brands');
        if (!File::exists($destinationPath)) {
            File::makeDirectory($destinationPath, 0755, true);
        }

        $img = Image::read($image->path());
        $img->cover(124, 124, 'center');
        $img->save($destinationPath .'/'. $imageName);
    }

    public function brand_delete($id)
    {
        $brand = Brand::find($id);
        if(File::exists(public_path('uploads/brands').'/'.$brand->image)) {
            File::delete(public_path('uploads/brands').'/'.$brand->image);
        }
        $brand->delete();
        return redirect()->route('admin.brands')->with('status','Brand has been deleted successfully');
    }

   public function categories(Request $request)
{
    $query = $request->input('query');

    $categories = Category::withCount(['products' => function($q) {
                    $q->where('status', 1);
                }]);

    if ($query) {
        $categories->where('name', 'LIKE', "%{$query}%")
                   ->orWhere('slug', 'LIKE', "%{$query}%");
    }

    $categories = $categories->orderBy('name', 'asc')->paginate(10);

    return view('admin.categories', compact('categories'));
}

    public function category_add(){
        return view('admin.category-add');
    }

    public function category_store(Request $request){
        $request->validate([
            'name' => 'required|unique:categories,name',
            'slug' => 'required|unique:categories,slug',
            'image' => 'mimes:png,jpg,jpeg|max:2048'
        ]);

        $category = new Category();
        $category->name = $request->name;
        $category->slug = Str::slug($request->name);

        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $file_extension = $image->extension();
            $file_name = Carbon::now()->timestamp.'.'.$file_extension;
            $this->GenerateCategoryandThunbnailsImage($image, $file_name);
            $category->image = $file_name;
        }

        $category->save();
        return redirect()->route('admin.categories')->with('status','Category has been added successfully');
    }

    public function GenerateCategoryandThunbnailsImage($image, $imageName)
    {
        $destinationPath = public_path('uploads/categories');
        if (!File::exists($destinationPath)) {
            File::makeDirectory($destinationPath, 0755, true);
        }

        $img = Image::read($image->path());
        $img->cover(124, 124, 'center');
        $img->save($destinationPath .'/'. $imageName);
    }

    public function category_edit($id){
        $category = Category::find($id);
        return view('admin.category-edit', compact('category'));
    }

    public function category_update(Request $request){
        $request->validate([
            'name' => 'required',
            'slug' => 'required|unique:categories,slug,' . $request->id,
            'image' => 'mimes:png,jpg,jpeg|max:2048'
        ]);

        $category = Category::find($request->id);
        $category->name = $request->name;
        $category->slug = Str::slug($request->name);

        if ($request->hasFile('image')){
            if(File::exists(public_path('uploads/categories').'/'.$category->image)){
                File::delete(public_path('uploads/categories').'/'.$category->image);
            }

            $image = $request->file('image');
            $file_extension = $image->extension();
            $file_name = Carbon::now()->timestamp.'.'.$file_extension;
            $this->GenerateCategoryandThunbnailsImage($image, $file_name);
            $category->image = $file_name;
        }

        $category->save();
        return redirect()->route('admin.categories')->with('status','Category has been updated successfully');
    }

    public function category_delete($id)
    {
        $category = Category::find($id);
        if(File::exists(public_path('uploads/categories').'/'.$category->image)) {
            File::delete(public_path('uploads/categories').'/'.$category->image);
        }
        $category->delete();
        return redirect()->route('admin.categories')->with('status','Category has been deleted successfully');
    }

    public function products(Request $request)
{
    $query = $request->input('query');

    $products = Product::with(['category', 'brand']);

    if ($query) {
        $products->where('name', 'LIKE', "%{$query}%")
                 ->orWhere('SKU', 'LIKE', "%{$query}%")
                 ->orWhere('slug', 'LIKE', "%{$query}%")
                 ->orWhereHas('category', function($q) use ($query) {
                     $q->where('name', 'LIKE', "%{$query}%");
                 })
                 ->orWhereHas('brand', function($q) use ($query) {
                     $q->where('name', 'LIKE', "%{$query}%");
                 });
    }

    $products = $products->orderBy('created_at', 'desc')->paginate(10);

    return view('admin.products', compact('products'));
}

    public function product_add(){
        $categories = Category::select('id','name')->orderBy('name')->get();
        $sizes = Size::where('status', 1)->orderBy('order', 'asc')->get();
        $colors = Color::where('status', 1)->orderBy('order', 'asc')->get();
        $brands = Brand::select('id','name')->orderBy('name')->get();
        return view('admin.product-add', compact('categories','brands','sizes','colors'));
    }
//voir produit
/**
 * Product store method with variant handling
 */
public function product_store(Request $request)
{
    $request->validate([
        'name' => 'required',
        'slug' => 'required|unique:products,slug',
        'short_description' => 'required',
        'description' => 'required',
        'regular_price' => 'required|numeric',
        'sale_price' => 'nullable|numeric|min:0|lt:regular_price',
        'SKU' => 'required',
        'stock_status' => 'required',
        'featured' => 'required|boolean',
        'quantity' => 'required|integer',
        'image' => 'required|mimes:png,jpg,jpeg|max:2048',
        'images.*' => 'mimes:png,jpg,jpeg|max:2048',
        'category_id' => 'required|exists:categories,id',
        'sizes' => 'nullable|array',
        'colors' => 'nullable|array',
        'brand_id' => 'required|exists:brands,id',
        'variants' => 'nullable|array',
        'variants.*.size_id' => 'nullable|exists:sizes,id',
        'variants.*.color_id' => 'nullable|exists:colors,id',
        'variants.*.quantity' => 'required|integer|min:0',
        'variants.*.sku' => 'nullable|string|max:100',
        'variants.*.price' => 'nullable|numeric|min:0',
        'variants.*.sale_price' => 'nullable|numeric|min:0',
    ]);

    $product = new Product();
    $product->name = $request->name;
    $product->slug = Str::slug($request->name);
    $product->short_description = $request->short_description;
    $product->description = $request->description;
    $product->regular_price = $request->regular_price;
    $product->sale_price = $request->filled('sale_price') ? $request->sale_price : null;
    $product->SKU = $request->SKU;
    $product->featured = $request->featured;
    $product->quantity = $request->quantity;
    $product->category_id = $request->category_id;
    $product->brand_id = $request->brand_id;

    $current_timestamp = Carbon::now()->timestamp;

    // Main image
    if ($request->hasFile('image')) {
        $image = $request->file('image');
        $imageName = $current_timestamp . '.' . $image->extension();
        $this->GenerateProductThumbnailsImage($image, $imageName);
        $product->image = $imageName;
    }

    // Gallery images
    $gallery_arr = [];
    if ($request->hasFile("images")) {
        $allowedFileExtensions = ['jpg','png','jpeg'];
        $files = $request->file('images');
        $counter = 1;

        foreach ($files as $file) {
            $gExtension = $file->getClientOriginalExtension();
            if (in_array($gExtension, $allowedFileExtensions)) {
                $gFileName = $current_timestamp . "." . $counter . "." . $gExtension;
                $this->GenerateProductThumbnailsImage($file, $gFileName);
                $gallery_arr[] = $gFileName;
                $counter++;
            }
        }

        if (!empty($gallery_arr)) {
            $product->images = implode(",", $gallery_arr);
        }
    }

    $product->save();

    // Attach sizes and colors
    if ($request->has('sizes') && is_array($request->sizes)) {
        $product->sizes()->attach($request->sizes);
    }

    if ($request->has('colors') && is_array($request->colors)) {
        $product->colors()->attach($request->colors);
    }

    // Create variants if provided
    if ($request->has('variants') && is_array($request->variants)) {
        foreach ($request->variants as $variantData) {
            if (!empty($variantData['size_id']) || !empty($variantData['color_id'])) {
                $variant = new ProductVariant();
                $variant->product_id = $product->id;
                $variant->size_id = $variantData['size_id'] ?? null;
                $variant->color_id = $variantData['color_id'] ?? null;
                $variant->sku = $variantData['sku'] ?? $this->generateVariantSKU($product->SKU, $variantData);
                $variant->price = $variantData['price'] ?? $product->regular_price;
                $variant->sale_price = $variantData['sale_price'] ?? $product->sale_price;
                $variant->quantity = $variantData['quantity'];
                $variant->is_active = true;
                $variant->save();
            }
        }
    }

    return redirect()->route("admin.products")->with("status","Product has been added successfully");
}

/**
 * Generate variant SKU
 */
private function generateVariantSKU($baseSKU, $variantData)
{
    $sku = $baseSKU;

    if (!empty($variantData['size_id'])) {
        $size = Size::find($variantData['size_id']);
        if ($size) {
            $sku .= '-' . $size->code;
        }
    }

    if (!empty($variantData['color_id'])) {
        $color = Color::find($variantData['color_id']);
        if ($color) {
            $sku .= '-' . $color->code;
        }
    }

    return $sku;
}
//image thubmnails de produit
    public function GenerateProductThumbnailsImage($image, $imageName)
    {
        $destinationPathThumbnail = public_path('uploads/products/thumbnails');
        $destinationPath = public_path('uploads/products');

        // Create directories if they don't exist
        if (!File::exists($destinationPath)) {
            File::makeDirectory($destinationPath, 0755, true);
        }
        if (!File::exists($destinationPathThumbnail)) {
            File::makeDirectory($destinationPathThumbnail, 0755, true);
        }

        // Original image
        $img = Image::read($image->path());
        $img->save($destinationPath . '/' . $imageName);

        // Thumbnail
        $imgThumb = Image::read($image->path());
        $imgThumb->cover(540, 689, 'center');
        $imgThumb->save($destinationPathThumbnail . '/' . $imageName);
    }



public function product_edit($id){
    $product = Product::find($id);

    // Check if variants method exists before trying to load
    if (method_exists($product, 'variants')) {
        $product->load(['sizes', 'colors', 'variants']);
    } else {
        $product->load(['sizes', 'colors']);
    }

    $categories = Category::select('id','name')->orderBy('name')->get();
    $brands = Brand::select('id','name')->orderBy('name')->get();
    $sizes = Size::where('status', 1)->orderBy('order', 'asc')->get();
    $colors = Color::where('status', 1)->orderBy('order', 'asc')->get();
    $selectedSizes = $product->sizes->pluck('id')->toArray();
    $selectedColors = $product->colors->pluck('id')->toArray();

    return view('admin.product-edit', compact(
        'product',
        'categories',
        'brands',
        'sizes',
        'colors',
        'selectedSizes',
        'selectedColors'
    ));
}
//update product details

public function product_update(Request $request)
{
    $request->validate([
        'name' => 'required',
        'slug' => 'required|unique:products,slug,'.$request->id,
        'short_description' => 'required',
        'description' => 'required',
        'regular_price' => 'required|numeric',
        'sale_price' => 'nullable|numeric|min:0|lt:regular_price',
        'SKU' => 'required',
        'stock_status' => 'required',
        'featured' => 'required|boolean',
        'quantity' => 'required|integer',
        'image' => 'mimes:png,jpg,jpeg|max:2048',
        'images.*' => 'mimes:png,jpg,jpeg|max:2048',
        'category_id' => 'required|exists:categories,id',
        'sizes' => 'nullable|array',
        'colors' => 'nullable|array',
        'brand_id' => 'required|exists:brands,id',
        'variants' => 'nullable|array',
        'variants.*.size_id' => 'nullable|exists:sizes,id',
        'variants.*.color_id' => 'nullable|exists:colors,id',
        'variants.*.quantity' => 'required|integer|min:0',
        'variants.*.sku' => 'nullable|string|max:100',
        'variants.*.price' => 'nullable|numeric|min:0',
        'variants.*.sale_price' => 'nullable|numeric|min:0',
    ]);

    $product = Product::find($request->id);
    $product->name = $request->name;
    $product->slug = Str::slug($request->name);
    $product->short_description = $request->short_description;
    $product->description = $request->description;
    $product->regular_price = $request->regular_price;
    $product->sale_price = $request->filled('sale_price') ? $request->sale_price : null;
    $product->SKU = $request->SKU;
    $product->featured = $request->featured;
    $product->quantity = $request->quantity;
    $product->category_id = $request->category_id;
    $product->brand_id = $request->brand_id;

    $current_timestamp = Carbon::now()->timestamp;

    if ($request->hasFile('image')) {
        if(File::exists(public_path('uploads/products'. '/'.$product->image))) {
            File::delete(public_path('uploads/products'.'/'. $product->image));
        }
        if(File::exists(public_path('uploads/products/thumbnails'.'/'. $product->image))) {
            File::delete(public_path('uploads/products/thumbnails'.'/'. $product->image));
        }

        $image = $request->file('image');
        $imageName = $current_timestamp . '.' . $image->extension();
        $this->GenerateProductThumbnailsImage($image, $imageName);
        $product->image = $imageName;
    }

    // Gallery images
    $gallery_arr = [];
    if ($request->hasFile("images")) {
        foreach (explode(",", $product->images) as $ofile) {
            if(File::exists(public_path('uploads/products'. '/'.$ofile))) {
                File::delete(public_path('uploads/products'.'/'. $ofile));
            }
            if(File::exists(public_path('uploads/products/thumbnails'.'/'. $ofile))) {
                File::delete(public_path('uploads/products/thumbnails'.'/'. $ofile));
            }
        }

        $allowedFileExtensions = ['jpg','png','jpeg'];
        $files = $request->file('images');
        $counter = 1;

        foreach ($files as $file) {
            $gExtension = $file->getClientOriginalExtension();
            if (in_array($gExtension, $allowedFileExtensions)) {
                $gFileName = $current_timestamp . "." . $counter . "." . $gExtension;
                $this->GenerateProductThumbnailsImage($file, $gFileName);
                $gallery_arr[] = $gFileName;
                $counter++;
            }
        }

        if (!empty($gallery_arr)) {
            $product->images = implode(",", $gallery_arr);
        }
    }

    // Update sizes
    if ($request->has('sizes')) {
        $product->sizes()->sync($request->sizes);
    } else {
        $product->sizes()->detach();
    }

    // Update colors
    if ($request->has('colors')) {
        $product->colors()->sync($request->colors);
    } else {
        $product->colors()->detach();
    }

    $product->save();

    // Update variants
    if ($request->has('variants') && is_array($request->variants)) {
        // First, delete all existing variants
        $product->variants()->delete();

        // Create new variants
        foreach ($request->variants as $variantData) {
            if (!empty($variantData['size_id']) || !empty($variantData['color_id'])) {
                $variant = new ProductVariant();
                $variant->product_id = $product->id;
                $variant->size_id = $variantData['size_id'] ?? null;
                $variant->color_id = $variantData['color_id'] ?? null;
                $variant->sku = $variantData['sku'] ?? $this->generateVariantSKU($product->SKU, $variantData);
                $variant->price = $variantData['price'] ?? $product->regular_price;
                $variant->sale_price = $variantData['sale_price'] ?? $product->sale_price;
                $variant->quantity = $variantData['quantity'] ?? 0;
                $variant->is_active = true;
                $variant->save();
            }
        }
    }

    // Update product stock status based on variants
    $product->updateStockStatus();

    return redirect()->route('admin.products')->with('status','Product updated successfully');
}






//delete product
    public function product_delete ($id)
    {
        $product = Product::find($id);
        if(File::exists(path: public_path('uploads/products'.'/'.$product ->image)))
                {
                File::delete(public_path('uploads/products'.'/'. $product ->image));
                }
             if(File::exists(path: public_path('uploads/products/thumbnails'.'/'. $product ->image)))
                {
                File::delete(public_path('uploads/products/thumbnails'.'/'. $product ->image));

                }
        foreach (explode(",", $product->images) as $ofile)
            {
            if(File::exists(path: public_path('uploads/products'. '/'.$ofile)))
                {
                File::delete(public_path('uploads/products'.'/'. $ofile));
                }
             if(File::exists(path: public_path('uploads/products/thumbnails'.'/'. $ofile)))
                {
                File::delete(public_path('uploads/products/thumbnails'.'/'. $ofile));

                }
            }
        $product->delete();
        return redirect()->route('admin.products')->with('status',value: 'Product deleted successfully');
    }


//all the coupns function start


public function coupon_add(){
    return view('admin.coupon-add');

}

public function coupon_store(Request $request){
    $request ->validate([
        'code'=> 'required',
        'type'=> 'required',
        'value'=> 'required|numeric',
        'cart_value'=> 'required|numeric',
        'expiry_date'=> 'required|date'
    ]);
    $coupon = new Coupon();
    $coupon ->code = $request ->code;
    $coupon ->type = $request ->type;
    $coupon ->value = $request ->value;
    $coupon ->cart_value = $request ->cart_value;
    $coupon ->expiry_date = $request ->expiry_date;
    $coupon ->save();
    return redirect()->route('admin.coupons')->with('status',value:'coupon has been added sucssefully');
}

public function coupon_edit($id){

    $coupon = Coupon::find($id);
    return view('admin.coupon-edit',compact('coupon'));

}
public function coupon_update(Request $request){
     $request ->validate([
        'code'=> 'required',
        'type'=> 'required',
        'value'=> 'required|numeric',
        'cart_value'=> 'required|numeric',
        'expiry_date'=> 'required|date'
        ]);
    $coupon = Coupon::find($request->id);
    $coupon ->code = $request ->code;
    $coupon ->type = $request ->type;
    $coupon ->value = $request ->value;
    $coupon ->cart_value = $request ->cart_value;
    $coupon ->expiry_date = $request ->expiry_date;
    $coupon ->save();
    return redirect()->route('admin.coupons')->with('status',value:'coupon has been updated sucssefully');

}
public function coupon_delete($id){
    $coupon = Coupon::find($id);
    $coupon ->delete();
    return redirect()->route('admin.coupons')->with('status',value:'couponhas been deleted successfully');
}
//all the coupns function end


//orders functions



public function order_details($order_id)
{
    $order = Order::findOrFail($order_id);
    $orderItems = OrderItem::where('order_id', $order_id)
        ->with(['product' => function($query) {
            $query->select('id', 'name', 'image', 'SKU', 'category_id', 'brand_id', 'slug');
        }, 'product.category', 'product.brand'])
        ->paginate(12);

    $transaction = Transaction::where('order_id', $order_id)->first();

    return view('admin.order-details', compact('order', 'orderItems', 'transaction'));
}


//update order status

public function update_order_status(Request $request){
    $order = Order::find($request->order_id);
    $oldStatus = $order->status;
    $newStatus = $request->order_status;

    $order->status = $newStatus;

    // Set appropriate dates
    if($newStatus == 'confirmed'){
        $order->confirmed_date = Carbon::now();
    }
    elseif($newStatus == 'delivered'){
        $order->delivered_date = Carbon::now();
    }
    elseif($newStatus == 'canceled'){
        $order->cancelled_date = Carbon::now();

        // RESTORE STOCK WHEN ORDER IS CANCELLED
        $this->restoreStockAfterCancellation($order);
    }

    $order->save();

    // Handle transaction status for delivered orders
    if($newStatus == 'delivered'){
        $transaction = Transaction::where('order_id', $request->order_id)->first();
        if($transaction) {
            $transaction->status = 'approved';
            $transaction->save();
        }
    }

    return back()->with('status', 'Statut modifié avec succès!!');
}


/**
 * Restore stock after order cancellation
 */
private function restoreStockAfterCancellation($order)
{
    foreach($order->orderItems as $orderItem) {
        if ($orderItem->variant_id) {
            $variant = ProductVariant::find($orderItem->variant_id);
            if ($variant) {
                $variant->incrementStock($orderItem->quantity);
                $orderItem->stock_updated = false;
                $orderItem->save();
            }
        } else {
            $product = $orderItem->product;
            if ($product) {
                $product->increment('quantity', $orderItem->quantity);
                $product->updateStockStatus();
                $orderItem->stock_updated = false;
                $orderItem->save();
            }
        }
    }
}

// Add this method too
private function updateProductStockStatus($product)
{
    // Check if product has variants
    if (method_exists($product, 'variants') && $product->variants()->exists()) {
        $hasStock = $product->variants()->where('quantity', '>', 0)->exists();
        $product->stock_status = $hasStock ? 'instock' : 'outofstock';
    } else {
        $product->stock_status = $product->quantity > 0 ? 'instock' : 'outofstock';
    }

    $product->save();
}
//orders end

//fonction de sliders start
// In the slides method:
public function slides()
{
    $slides = Slide::where('type', 'home')->orderBy('order', 'asc')->orderBy('created_at', 'desc')->paginate(12);
    return view('admin.slides', compact('slides'));
}

public function slide_add(){
    return view('admin.slide-add');
}

public function slide_store(Request $request)
{
    $request->validate([
        'tagline' => 'required|string|max:255',
        'title' => 'required|string|max:255',
        'subtitle' => 'required|string|max:255',
        'description' => 'nullable|string',
        'link' => 'required|url',
        'button_text' => 'nullable|string|max:50',
        'price' => 'nullable|numeric|min:0',
        'price_text' => 'nullable|string|max:100',
        'image' => 'required|image|mimes:jpeg,png,jpg,gif|max:5120', // 5MB
        'featured_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:5120',
        'background_color' => 'nullable|string|max:20',
        'text_position' => 'nullable|in:left,center,right',
        'order' => 'nullable|integer',
        'is_active' => 'required|boolean'
    ]);

    $slide = new Slide();
    $slide->tagline = $request->tagline;
    $slide->title = $request->title;
    $slide->subtitle = $request->subtitle;
    $slide->description = $request->description;
    $slide->link = $request->link;
    $slide->button_text = $request->button_text ?? 'Explorer la collection';
    $slide->price = $request->price;
    $slide->price_text = $request->price_text;
    $slide->background_color = $request->background_color ?? '#0a0a0a';
    $slide->text_position = $request->text_position ?? 'left';
    $slide->order = $request->order ?? 0;
    $slide->is_active = $request->is_active;
    $slide->type = 'home';

    // Handle main image upload
    if ($request->hasFile('image')) {
        $image = $request->file('image');
        $file_extension = $image->extension();
        $file_name = Carbon::now()->timestamp . '_main.' . $file_extension;
        $this->GenerateSlideImage($image, $file_name);
        $slide->image = $file_name;
    }

    // Handle featured image upload
    if ($request->hasFile('featured_image')) {
        $featuredImage = $request->file('featured_image');
        $featured_extension = $featuredImage->extension();
        $featured_name = Carbon::now()->timestamp . '_featured.' . $featured_extension;
        $this->GenerateFeaturedImage($featuredImage, $featured_name);
        $slide->featured_image = $featured_name;
    }

    $slide->save();

    return redirect()->route('admin.slides')->with('status', 'Slide has been added successfully');
}


// Add these new image processing methods:
public function GenerateSlideImage($image, $imageName)
{
    $destinationPath = public_path('uploads/slides');
    if (!File::exists($destinationPath)) {
        File::makeDirectory($destinationPath, 0755, true);
    }

    // Simply move the file without any processing
    $image->move($destinationPath, $imageName);
}

public function GenerateFeaturedImage($image, $imageName)
{
    $destinationPath = public_path('uploads/slides/featured');
    if (!File::exists($destinationPath)) {
        File::makeDirectory($destinationPath, 0755, true);
    }

    $img = Image::read($image->path());
    // Resize featured image (600x600 for product display)
    $img->cover(800, 800, 'center');
    $img->save($destinationPath . '/' . $imageName);
}



 public function GenerateslideThunbnailsImage($image, $imageName)
    {
        $destinationPath = public_path('uploads/slides');
        if (!File::exists($destinationPath)) {
            File::makeDirectory($destinationPath, 0755, true);
        }
        $img = Image::read($image->path());
        $img->cover(400, 690, "top");
        $img->save($destinationPath.'/'.$imageName);
        //$img->resize(400, 690,function($constraint){
        //    $constraint->aspectRatio();
      //  })->save($destinationPath.'/'.$imageName);


    }

    public function slide_edit($id){
        $slide = Slide::find($id);
        return view('admin.slide-edit', compact('slide'));
    }

public function slide_update(Request $request)
{
    $request->validate([
        'id' => 'required|exists:slides,id',
        'tagline' => 'required|string|max:255',
        'title' => 'required|string|max:255',
        'subtitle' => 'required|string|max:255',
        'description' => 'nullable|string',
        'link' => 'required|url',
        'button_text' => 'nullable|string|max:50',
        'price' => 'nullable|numeric|min:0',
        'price_text' => 'nullable|string|max:100',
        'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:5120',
        'featured_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:5120',
        'background_color' => 'nullable|string|max:20',
        'text_position' => 'nullable|in:left,center,right',
        'order' => 'nullable|integer',
        'is_active' => 'required|boolean'
    ]);

    $slide = Slide::find($request->id);
    $slide->tagline = $request->tagline;
    $slide->title = $request->title;
    $slide->subtitle = $request->subtitle;
    $slide->description = $request->description;
    $slide->link = $request->link;
    $slide->button_text = $request->button_text ?? 'Explorer la collection';
    $slide->price = $request->price;
    $slide->price_text = $request->price_text;
    $slide->background_color = $request->background_color ?? '#0a0a0a';
    $slide->text_position = $request->text_position ?? 'left';
    $slide->order = $request->order ?? 0;
    $slide->is_active = $request->is_active;

    // Handle main image update
    if ($request->hasFile('image')) {
        // Delete old image
        if (File::exists(public_path('uploads/slides') . '/' . $slide->image)) {
            File::delete(public_path('uploads/slides') . '/' . $slide->image);
        }

        $image = $request->file('image');
        $file_extension = $image->extension();
        $file_name = Carbon::now()->timestamp . '_main.' . $file_extension;
        $this->GenerateSlideImage($image, $file_name);
        $slide->image = $file_name;
    }

    // Handle featured image update
    if ($request->hasFile('featured_image')) {
        // Delete old featured image if exists
        if ($slide->featured_image && File::exists(public_path('uploads/slides/featured') . '/' . $slide->featured_image)) {
            File::delete(public_path('uploads/slides/featured') . '/' . $slide->featured_image);
        }

        $featuredImage = $request->file('featured_image');
        $featured_extension = $featuredImage->extension();
        $featured_name = Carbon::now()->timestamp . '_featured.' . $featured_extension;
        $this->GenerateFeaturedImage($featuredImage, $featured_name);
        $slide->featured_image = $featured_name;
    }

    // Handle removal of featured image
    if ($request->has('remove_featured_image') && $slide->featured_image) {
        if (File::exists(public_path('uploads/slides/featured') . '/' . $slide->featured_image)) {
            File::delete(public_path('uploads/slides/featured') . '/' . $slide->featured_image);
        }
        $slide->featured_image = null;
    }

    $slide->save();

    return redirect()->route('admin.slides')->with('status', 'Slide has been updated successfully');
}



public function slide_delete($id)
{
    $slide = Slide::find($id);

    // Delete main image
    if (File::exists(public_path('uploads/slides') . '/' . $slide->image)) {
        File::delete(public_path('uploads/slides') . '/' . $slide->image);
    }

    // Delete featured image if exists
    if ($slide->featured_image && File::exists(public_path('uploads/slides/featured') . '/' . $slide->featured_image)) {
        File::delete(public_path('uploads/slides/featured') . '/' . $slide->featured_image);
    }

    $slide->delete();

    return redirect()->route('admin.slides')->with('status', 'Slide has been deleted successfully');
}
/**
 * Unified slides management page
 */

public function slides_manage(Request $request)
{
    $type = $request->get('type', 'all');
    $search = $request->get('search');

    // Get all slide types with type identifiers
    $slides = collect();

    // Home Slides
    if ($type == 'all' || $type == 'home_slides') {
        $homeSlides = Slide::where('type', 'home')
            ->orderBy('order')
            ->orderBy('created_at', 'desc')
            ->get()
            ->map(function($item) {
                $item->item_type = 'home_slide';
                $item->type_label = 'Slide Accueil';
                $item->type_icon = 'fas fa-home';
                $item->type_color = 'primary';
                $item->edit_route = route('admin.slide.edit', ['id' => $item->id]);
                $item->delete_route = route('admin.slide.delete', ['id' => $item->id]);
                $item->image_path = 'uploads/slides/' . $item->image;
                $item->display_title = $item->title;
                $item->display_subtitle = $item->subtitle;
                $item->display_status = $item->is_active;
                $item->order_value = $item->order ?? 0;
                return $item;
            });
        $slides = $slides->concat($homeSlides);
    }

    // Shop Banners
    if ($type == 'all' || $type == 'shop_banners') {
        $shopBanners = ShopBanner::orderBy('sort_order')
            ->orderBy('created_at', 'desc')
            ->get()
            ->map(function($item) {
                $item->item_type = 'shop_banner';
                $item->type_label = 'Bannière Boutique';
                $item->type_icon = 'fas fa-tag';
                $item->type_color = 'warning';
                $item->edit_route = route('admin.shop-banner.edit', ['id' => $item->id]);
                $item->delete_route = route('admin.shop-banner.delete', ['id' => $item->id]);
                $item->image_path = 'uploads/shop-banners/' . $item->image;
                $item->display_title = $item->title;
                $item->display_subtitle = $item->subtitle;
                $item->display_status = $item->is_active;
                $item->order_value = $item->sort_order ?? 0;
                return $item;
            });
        $slides = $slides->concat($shopBanners);
    }

    // Category Banners
    if ($type == 'all' || $type == 'category_banners') {
        $categoryBanners = CategoryBanner::orderBy('sort_order')
            ->orderBy('created_at', 'desc')
            ->get()
            ->map(function($item) {
                $item->item_type = 'category_banner';
                $item->type_label = 'Bannière Catégorie';
                $item->type_icon = 'fas fa-folder';
                $item->type_color = 'info';
                $item->edit_route = route('admin.category-banner.edit', ['id' => $item->id]);
                $item->delete_route = route('admin.category-banner.delete', ['id' => $item->id]);
                $item->image_path = 'uploads/category-banners/' . $item->image;
                $item->display_title = $item->title;
                $item->display_subtitle = $item->subtitle;
                $item->display_status = $item->is_active;
                $item->order_value = $item->sort_order ?? 0;
                return $item;
            });
        $slides = $slides->concat($categoryBanners);
    }

    // Apply search filter
    if ($search) {
        $slides = $slides->filter(function($item) use ($search) {
            return stripos($item->display_title, $search) !== false ||
                   ($item->display_subtitle && stripos($item->display_subtitle, $search) !== false) ||
                   stripos($item->type_label, $search) !== false;
        });
    }

    // Sort by created_at descending
    $slides = $slides->sortByDesc('created_at');

    // Pagination (manual pagination for collection)
    $perPage = 15;
    $currentPage = $request->get('page', 1);
    $pagedData = $slides->slice(($currentPage - 1) * $perPage, $perPage)->values();
    $slides = new \Illuminate\Pagination\LengthAwarePaginator(
        $pagedData,
        $slides->count(),
        $perPage,
        $currentPage,
        ['path' => $request->url(), 'query' => $request->query()]
    );

    // Get counts for statistics
    $stats = [
        'total' => $slides->total(),
        'home_slides' => Slide::where('type', 'home')->count(),
        'shop_banners' => ShopBanner::count(),
        'category_banners' => CategoryBanner::count(),
        'active' => $slides->filter(function($item) {
            return isset($item->display_status) && $item->display_status;
        })->count(),
    ];

    return view('admin.slides-manage', compact('slides', 'stats', 'type', 'search'));
}

/**
 * Quick status toggle for unified slides
 */

public function slides_toggle_status(Request $request)
{
    $request->validate([
        'type' => 'required|in:home_slide,shop_banner,category_banner',
        'id' => 'required|integer',
        'status' => 'required|boolean'
    ]);

    try {
        switch($request->type) {
            case 'home_slide':
                $item = Slide::findOrFail($request->id);
                $item->is_active = $request->status;
                $item->save();
                break;
            case 'shop_banner':
                $item = ShopBanner::findOrFail($request->id);
                $item->is_active = $request->status;
                $item->save();
                break;
            case 'category_banner':
                $item = CategoryBanner::findOrFail($request->id);
                $item->is_active = $request->status;
                $item->save();
                break;
            default:
                return response()->json(['success' => false, 'message' => 'Type invalide'], 400);
        }

        return response()->json([
            'success' => true,
            'message' => 'Statut mis à jour avec succès'
        ]);

    } catch (\Exception $e) {
        return response()->json([
            'success' => false,
            'message' => 'Erreur: ' . $e->getMessage()
        ], 500);
    }
}

/**
 * Quick delete for unified slides
 */
public function slides_quick_delete(Request $request)
{
    $request->validate([
        'type' => 'required|in:home_slide,shop_slide,shop_banner,category_banner',
        'id' => 'required|integer'
    ]);

    try {
        switch($request->type) {
            case 'home_slide':
                $item = Slide::findOrFail($request->id);
                // Delete images
                if (File::exists(public_path('uploads/slides') . '/' . $item->image)) {
                    File::delete(public_path('uploads/slides') . '/' . $item->image);
                }
                if ($item->featured_image && File::exists(public_path('uploads/slides/featured') . '/' . $item->featured_image)) {
                    File::delete(public_path('uploads/slides/featured') . '/' . $item->featured_image);
                }
                $item->delete();
                break;
            case 'shop_slide':
                $item = \App\Models\ShopSlide::findOrFail($request->id);
                if (File::exists(public_path('uploads/shop-slides') . '/' . $item->image)) {
                    File::delete(public_path('uploads/shop-slides') . '/' . $item->image);
                }
                $item->delete();
                break;
            case 'shop_banner':
                $item = ShopBanner::findOrFail($request->id);
                if (File::exists(public_path('uploads/shop-banners') . '/' . $item->image)) {
                    File::delete(public_path('uploads/shop-banners') . '/' . $item->image);
                }
                $item->delete();
                break;
            case 'category_banner':
                $item = CategoryBanner::findOrFail($request->id);
                if (File::exists(public_path('uploads/category-banners') . '/' . $item->image)) {
                    File::delete(public_path('uploads/category-banners') . '/' . $item->image);
                }
                $item->delete();
                break;
            default:
                return response()->json(['success' => false, 'message' => 'Type invalide'], 400);
        }

        return response()->json([
            'success' => true,
            'message' => 'Élément supprimé avec succès'
        ]);

    } catch (\Exception $e) {
        return response()->json([
            'success' => false,
            'message' => 'Erreur: ' . $e->getMessage()
        ], 500);
    }
}
//contactf for user

public function showContact()
{
    $settings = Setting::first() ?? new Setting();
    return view('contact', compact('settings'));
}



////contact us start


public function contact_mark_read($id)
{
    $contact = Contact::find($id);
    if($contact) {
        $contact->read = true;
        $contact->save();
    }
    return redirect()->route('admin.contacts');
}


public function contact_delete ($id){
    $contact = Contact::find($id);
    $contact->delete();
    return redirect()->route('admin.contacts')->with('status','contact message deleted suuccessfully!!!');
}
//contact end
public function rechercher(Request $request){
    $query = $request->input('query');
    $results = Product::where('name','LIKE', "%{$query}%")->get()->take(8);
    return response()->json($results);

}







//users



    /**
     * Delete a user
     */
    public function user_delete($id)
    {
        // Prevent admin from deleting themselves
        if (Auth::check() && Auth::id() == $id) {
        return redirect()->route('admin.users')->with('error', 'You cannot delete your own account!');
    }

        $user = User::findOrFail($id);

        // Optional: Check if user has orders before deleting
        $orderCount = Order::where('user_id', $id)->count();
        if ($orderCount > 0) {
            return redirect()->route('admin.users')->with('error', 'Cannot delete user with existing orders!');
        }

        $user->delete();

        return redirect()->route('admin.users')->with('status', 'User has been deleted successfully');
    }


    public function user_update_role(Request $request, $id)
    {
        $request->validate([
            'utype' => 'required|in:ADM,USR'
        ]);

        $user = User::findOrFail($id);
        $user->utype = $request->utype;
        $user->save();

        return redirect()->route('admin.users')->with('status', 'User role updated successfully');
    }

    //View user details
    // Update the user_view method in AdminController.php
public function user_view($id)
{
    $user = User::findOrFail($id);

    // Load orders with pagination
    $orders = Order::where('user_id', $id)
                   ->withCount('orderItems')
                   ->orderBy('created_at', 'desc')
                   ->paginate(10);

    // Load reviews
    $reviews = Review::where('user_id', $id)
                     ->with('product')
                     ->orderBy('created_at', 'desc')
                     ->get();

    // Calculate total spent
    $total_spent = Order::where('user_id', $id)
                        ->where('status', '!=', 'canceled')
                        ->sum('total');

    // Add counts to user object
    $user->orders_count = $orders->total();
    $reviews_count = $reviews->count();

    return view('admin.user-view', compact('user', 'orders', 'reviews', 'total_spent', 'reviews_count'));
}

// Add this method to AdminController.php if it doesn't exist
public function user_edit($id)
{
    $user = User::findOrFail($id);
    return view('admin.user-edit', compact('user'));
}
public function user_update(Request $request)
{
    $request->validate([
        'id' => 'required|exists:users,id',
        'name' => 'required|string|max:255',
        'email' => 'required|email|unique:users,email,' . $request->id,
        'numero' => 'nullable|string|max:20',
        'password' => 'nullable|min:8|confirmed',
        'utype' => 'required|in:USR,ADM'
    ]);

    $user = User::findOrFail($request->id);
    $user->name = $request->name;
    $user->email = $request->email;
    $user->numero = $request->numero;
    $user->utype = $request->utype;

    if ($request->filled('password')) {
        $user->password = Hash::make($request->password);
    }

    $user->save();

    return redirect()->route('admin.users')->with('status', 'User updated successfully');
}


 public function settings(Request $request)
{
    // Get user from request
    $user = $request->user();
    if (!$user) {
        return redirect()->route('login');
    }

    $settings = Setting::first();

        if (!$settings) {
            $settings = new Setting();
        }

    return view('admin.settings', compact('settings', 'user'));
}


      //Update general settings

    public function settings_update(Request $request)
{
    $request->validate([
        'site_name' => 'required|string|max:255',
        'site_email' => 'nullable|email|max:255',
        'site_phone' => 'required|string|max:20',
        'site_address' => 'nullable|string|max:500',
        'currency' => 'required|string|max:10',
        'currency_symbol' => 'required|string|max:5',
        'tax_rate' => 'nullable|numeric|min:0|max:100',
        'shipping_cost' => 'nullable|numeric|min:0',
        'footer_text' => 'nullable|string|max:1000',
        'logo' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        'favicon' => 'nullable|image|mimes:jpeg,png,jpg,gif,ico|max:1024',

    ]);

    $settings = Setting::first();

    if (!$settings) {
        $settings = new Setting();
    }

    // Update text fields
    $settings->fill($request->only([
        'site_name', 'site_email', 'site_phone', 'site_address',
        'currency', 'currency_symbol', 'tax_rate', 'shipping_cost',
        'footer_text'
    ]));

    // Handle logo upload
    if ($request->hasFile('logo')) {
        $logo = $request->file('logo');
        $logoName = time() . '_logo.' . $logo->getClientOriginalExtension();

        // Create directory if it doesn't exist
        if (!file_exists(public_path('uploads/settings'))) {
            mkdir(public_path('uploads/settings'), 0755, true);
        }

        // Delete old logo if exists
        if ($settings->logo && file_exists(public_path('uploads/settings/' . $settings->logo))) {
            unlink(public_path('uploads/settings/' . $settings->logo));
        }

        $logo->move(public_path('uploads/settings'), $logoName);
        $settings->logo = $logoName;
    }

    // Handle favicon upload
    if ($request->hasFile('favicon')) {
        $favicon = $request->file('favicon');
        $faviconName = time() . '_favicon.' . $favicon->getClientOriginalExtension();

        // Delete old favicon if exists
        if ($settings->favicon && file_exists(public_path('uploads/settings/' . $settings->favicon))) {
            unlink(public_path('uploads/settings/' . $settings->favicon));
        }

        $favicon->move(public_path('uploads/settings'), $faviconName);
        $settings->favicon = $faviconName;
    }

    // Handle removal of files
    if ($request->input('remove_logo') == '1' && $settings->logo) {
        if (file_exists(public_path('uploads/settings/' . $settings->logo))) {
            unlink(public_path('uploads/settings/' . $settings->logo));
        }
        $settings->logo = null;
    }

    if ($request->input('remove_favicon') == '1' && $settings->favicon) {
        if (file_exists(public_path('uploads/settings/' . $settings->favicon))) {
            unlink(public_path('uploads/settings/' . $settings->favicon));
        }
        $settings->favicon = null;
    }

     $settings->save();

    // Clear all caches to reflect changes immediately
    Artisan::call('view:clear');
    Artisan::call('cache:clear');

    // Clear the specific settings cache
    cache()->forget('site_settings');

    return redirect()->route('admin.settings')->with('status', 'Settings updated successfully!');
}



     //Change admin password

    public function change_password(Request $request)
    {
        $request->validate([
            'current_password' => 'required',
            'new_password' => 'required|min:8|confirmed',
        ]);

         $user = $request->user();

        // Check current password
        if (!Hash::check($request->current_password, $user->password)) {
            return redirect()->route('admin.settings')->with('error', 'Current password is incorrect!');
        }

        // Update password
        $user->password = Hash::make($request->new_password);
        $user->save();

        return redirect()->route('admin.settings')->with('status', 'Password changed successfully!');
    }




// Add to the change_password method or create a new method for profile update
public function update_profile(Request $request)
{
    $request->validate([
        'name' => 'required|string|max:255',
        'avatar' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
    ]);

    $user = $request->user();
    $user->name = $request->name;

    // Handle avatar upload
    if ($request->hasFile('avatar')) {
        // Create directory if it doesn't exist
        if (!file_exists(public_path('uploads/avatars'))) {
            mkdir(public_path('uploads/avatars'), 0755, true);
        }

        // Delete old avatar if exists
        if ($user->avatar && file_exists(public_path('uploads/avatars/' . $user->avatar))) {
            unlink(public_path('uploads/avatars/' . $user->avatar));
        }

        $avatar = $request->file('avatar');
        $avatarName = time() . '_' . $user->id . '.' . $avatar->getClientOriginalExtension();
        $avatar->move(public_path('uploads/avatars'), $avatarName);
        $user->avatar = $avatarName;
    }

    $user->save();

    return redirect()->route('admin.settings')->with('status', 'Profile updated successfully!');
}





//notifications
public function getNotificationCounts()
{
    return response()->json([
        'newOrders' => NotificationService::getUnreadOrderCount(),
        'newMessages' => NotificationService::getUnreadContactCount()
    ]);
}

// Category Banner Functions Start
public function category_banners()
{
    $banners = CategoryBanner::orderBy('sort_order', 'asc')
                            ->orderBy('created_at', 'desc')
                            ->paginate(10);
    return view('admin.category-banners', compact('banners'));
}

public function category_banner_add()
{
    return view('admin.category-banner-add');
}

public function category_banner_store(Request $request)
{
    $request->validate([
        'title' => 'required|string|max:255',
        'subtitle' => 'nullable|string|max:255',
        'description' => 'nullable|string',
        'image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
        'alt_text' => 'nullable|string|max:255',
        'price_from' => 'nullable|numeric|min:0',
        'price_text' => 'nullable|string|max:100',
        'button_text' => 'required|string|max:50',
        'button_link' => 'required|url',
        'position' => 'required|in:left,right',
        'is_active' => 'required|boolean',
        'sort_order' => 'nullable|integer'
    ]);

    $banner = new CategoryBanner();
    $banner->fill($request->except('image'));

    // Handle image upload
    if ($request->hasFile('image')) {
        $image = $request->file('image');
        $file_extension = $image->extension();
        $file_name = Carbon::now()->timestamp . '.' . $file_extension;
        $this->GenerateCategoryBannerImage($image, $file_name);
        $banner->image = $file_name;
    }

    $banner->save();

    return redirect()->route('admin.category-banners')
                    ->with('status', 'Category banner has been added successfully');
}

public function GenerateCategoryBannerImage($image, $imageName)
{
    $destinationPath = public_path('uploads/category-banners');
    if (!File::exists($destinationPath)) {
        File::makeDirectory($destinationPath, 0755, true);
    }

    $img = Image::read($image->path());
    // Resize to match your template dimensions (690x665)
    $img->cover(690, 665, 'center');
    $img->save($destinationPath . '/' . $imageName);
}

public function category_banner_edit($id)
{
    $banner = CategoryBanner::findOrFail($id);
    return view('admin.category-banner-edit', compact('banner'));
}

public function category_banner_update(Request $request)
{
    $request->validate([
        'id' => 'required|exists:category_banners,id',
        'title' => 'required|string|max:255',
        'subtitle' => 'nullable|string|max:255',
        'description' => 'nullable|string',
        'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        'alt_text' => 'nullable|string|max:255',
        'price_from' => 'nullable|numeric|min:0',
        'price_text' => 'nullable|string|max:100',
        'button_text' => 'required|string|max:50',
        'button_link' => 'required|url',
        'position' => 'required|in:left,right',
        'is_active' => 'required|boolean',
        'sort_order' => 'nullable|integer'
    ]);

    $banner = CategoryBanner::findOrFail($request->id);
    $banner->fill($request->except('image'));

    // Handle image update
    if ($request->hasFile('image')) {
        // Delete old image
        if (File::exists(public_path('uploads/category-banners') . '/' . $banner->image)) {
            File::delete(public_path('uploads/category-banners') . '/' . $banner->image);
        }

        $image = $request->file('image');
        $file_extension = $image->extension();
        $file_name = Carbon::now()->timestamp . '.' . $file_extension;
        $this->GenerateCategoryBannerImage($image, $file_name);
        $banner->image = $file_name;
    }

    $banner->save();

    return redirect()->route('admin.category-banners')
                    ->with('status', 'Category banner has been updated successfully');
}

public function category_banner_delete($id)
{
    $banner = CategoryBanner::findOrFail($id);

    // Delete image file
    if (File::exists(public_path('uploads/category-banners') . '/' . $banner->image)) {
        File::delete(public_path('uploads/category-banners') . '/' . $banner->image);
    }

    $banner->delete();

    return redirect()->route('admin.category-banners')
                    ->with('status', 'Category banner has been deleted successfully');
}
// Category Banner Functions End

// Review
// In AdminController.php
public function reviews(Request $request)
{
    $query = Review::with(['product' => function($query) {
                    $query->withTrashed();
                }, 'user'])
                ->orderBy('created_at', 'desc');

    // Apply search filter
    if ($request->has('search') && $request->search) {
        $search = $request->search;
        $query->where(function($q) use ($search) {
            $q->where('comment', 'like', "%{$search}%")
              ->orWhereHas('product', function($q) use ($search) {
                  $q->withTrashed()->where('name', 'like', "%{$search}%");
              })
              ->orWhereHas('user', function($q) use ($search) {
                  $q->where('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%");
              });
        });
    }

    // Apply status filter
    $status = $request->query('status', 'all');
    if ($status === 'pending') {
        $query->where('status', 'pending');
    } elseif ($status === 'approved') {
        $query->where('status', 'approved');
    } elseif ($status === 'declined') {
        $query->where('status', 'declined');
    }

    // Get counts for badges
    $totalReviews = Review::count();
    $pendingReviews = Review::where('status', 'pending')->count();
    $approvedReviews = Review::where('status', 'approved')->count();
    $declinedReviews = Review::where('status', 'declined')->count();

    // Paginate results
    $reviews = $query->paginate(15);

    return view('admin.reviews', compact(
        'reviews',
        'totalReviews',
        'pendingReviews',
        'approvedReviews',
        'declinedReviews'
    ));
}

public function approveReview($id)
{
    $review = Review::findOrFail($id);
    $review->status = 'approved';
    $review->approved_at = now();
    $review->declined_at = null;
    $review->save();

    return redirect()->route('admin.reviews')
        ->with('success', 'L\'avis a été approuvé avec succès.');
}

public function declineReview($id)
{
    $review = Review::findOrFail($id);
    $review->status = 'declined';
    $review->declined_at = now();
    $review->approved_at = null;
    $review->save();

    return redirect()->route('admin.reviews')
        ->with('success', 'L\'avis a été refusé avec succès.');
}

public function restoreReview($id)
{
    $review = Review::findOrFail($id);
    $review->status = 'pending';
    $review->approved_at = null;
    $review->declined_at = null;
    $review->save();

    return redirect()->route('admin.reviews')
        ->with('success', 'L\'avis a été restauré à l\'état en attente.');
}

public function unapproveReview($id)
{
    $review = Review::findOrFail($id);
    $review->status = 'pending';
    $review->approved_at = null;
    $review->save();

    return redirect()->route('admin.reviews')
        ->with('success', 'L\'approbation a été retirée. L\'avis est maintenant en attente.');
}

public function deleteReview($id)
{
    $review = Review::findOrFail($id);
    $review->delete();

    return redirect()->route('admin.reviews')
        ->with('success', 'L\'avis a été supprimé définitivement.');
}
public function reviewDetails($id)
{
    $review = Review::with(['product', 'user'])->findOrFail($id);

    return response()->json([
        'success' => true,
        'review' => [
            'id' => $review->id,
            'rating' => $review->rating,
            'comment' => $review->comment,
            'approved' => $review->approved,
            'created_at_formatted' => $review->created_at->format('d M, Y h:i A'),
            'product' => [
                'id' => $review->product->id,
                'name' => $review->product->name,
                'slug' => $review->product->slug,
                'image_url' => asset('uploads/products/thumbnails/' . $review->product->image),
            ],
            'product_link' => route('shop.product.details', ['product_slug' => $review->product->slug]),
            'user' => [
                'id' => $review->user->id,
                'name' => $review->user->name,
                'email' => $review->user->email,
            ]
        ]
    ]);
}




//shop slides






// Shop Banner Functions Start
public function shop_banners()
{
    $banners = ShopBanner::orderBy('sort_order', 'asc')
                        ->orderBy('created_at', 'desc')
                        ->paginate(10);
    return view('admin.shop-banners', compact('banners'));
}

public function shop_banner_add()
{
    return view('admin.shop-banner-add');
}

public function shop_banner_store(Request $request)
{
    $request->validate([
        'title' => 'required|string|max:255',
        'subtitle' => 'nullable|string|max:255',
        'tagline' => 'nullable|string|max:255',
        'description' => 'nullable|string',
        'image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
        'alt_text' => 'nullable|string|max:255',
        'button_text' => 'required|string|max:50',
        'button_link' => 'required|url',
        'background_color' => 'nullable|string|max:20',
        'is_active' => 'required|boolean',
        'sort_order' => 'nullable|integer'
    ]);

    $banner = new ShopBanner();
    $banner->fill($request->except('image'));

    // Handle image upload
    if ($request->hasFile('image')) {
        $image = $request->file('image');
        $file_extension = $image->extension();
        $file_name = Carbon::now()->timestamp . '.' . $file_extension;
        $this->GenerateShopBannerImage($image, $file_name);
        $banner->image = $file_name;
    }

    $banner->save();

    return redirect()->route('admin.shop-banners')
                    ->with('status', 'Shop banner has been added successfully');
}

public function GenerateShopBannerImage($image, $imageName)
{
    $destinationPath = public_path('uploads/shop-banners');
    if (!File::exists($destinationPath)) {
        File::makeDirectory($destinationPath, 0755, true);
    }

    $img = Image::read($image->path());
    // Resize to match your template dimensions (630x450)
    $img->cover(630, 450, 'center');
    $img->save($destinationPath . '/' . $imageName);
}

public function shop_banner_edit($id)
{
    $banner = ShopBanner::findOrFail($id);
    return view('admin.shop-banner-edit', compact('banner'));
}

public function shop_banner_update(Request $request)
{
    $request->validate([
        'id' => 'required|exists:shop_banners,id',
        'title' => 'required|string|max:255',
        'subtitle' => 'nullable|string|max:255',
        'tagline' => 'nullable|string|max:255',
        'description' => 'nullable|string',
        'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        'alt_text' => 'nullable|string|max:255',
        'button_text' => 'required|string|max:50',
        'button_link' => 'required|url',
        'background_color' => 'nullable|string|max:20',
        'is_active' => 'required|boolean',
        'sort_order' => 'nullable|integer'
    ]);

    $banner = ShopBanner::findOrFail($request->id);
    $banner->fill($request->except('image'));

    // Handle image update
    if ($request->hasFile('image')) {
        // Delete old image
        if (File::exists(public_path('uploads/shop-banners') . '/' . $banner->image)) {
            File::delete(public_path('uploads/shop-banners') . '/' . $banner->image);
        }

        $image = $request->file('image');
        $file_extension = $image->extension();
        $file_name = Carbon::now()->timestamp . '.' . $file_extension;
        $this->GenerateShopBannerImage($image, $file_name);
        $banner->image = $file_name;
    }

    $banner->save();

    return redirect()->route('admin.shop-banners')
                    ->with('status', 'Shop banner has been updated successfully');
}

public function shop_banner_delete($id)
{
    $banner = ShopBanner::findOrFail($id);

    // Delete image file
    if (File::exists(public_path('uploads/shop-banners') . '/' . $banner->image)) {
        File::delete(public_path('uploads/shop-banners') . '/' . $banner->image);
    }

    $banner->delete();

    return redirect()->route('admin.shop-banners')
                    ->with('status', 'Shop banner has been deleted successfully');
}
// Shop Banner Functions End


// Search for brands
// Add these search methods to your AdminController.php class

// Search for brands
public function search_brands(Request $request)
{
    $query = $request->input('query');

    $brands = Brand::withCount(['products' => function($query) {
                    $query->where('status', 1);
                }])
                ->where('name', 'LIKE', "%{$query}%")
                ->orWhere('slug', 'LIKE', "%{$query}%")
                ->orderBy('name', 'asc')
                ->paginate(10);

    return view('admin.brands', compact('brands'));
}

// Search for categories
public function search_categories(Request $request)
{
    $query = $request->input('query');

    $categories = Category::withCount(['products' => function($query) {
                    $query->where('status', 1);
                }])
                ->where('name', 'LIKE', "%{$query}%")
                ->orWhere('slug', 'LIKE', "%{$query}%")
                ->orderBy('name', 'asc')
                ->paginate(10);

    return view('admin.categories', compact('categories'));
}

// Search for products
public function search_products(Request $request)
{
    $query = $request->input('query');

    $products = Product::with(['category', 'brand'])
                ->where('name', 'LIKE', "%{$query}%")
                ->orWhere('SKU', 'LIKE', "%{$query}%")
                ->orWhere('slug', 'LIKE', "%{$query}%")
                ->orWhereHas('category', function($q) use ($query) {
                    $q->where('name', 'LIKE', "%{$query}%");
                })
                ->orWhereHas('brand', function($q) use ($query) {
                    $q->where('name', 'LIKE', "%{$query}%");
                })
                ->orderBy('created_at', 'desc')
                ->paginate(10);

    return view('admin.products', compact('products'));
}

// Search for coupons
public function search_coupons(Request $request)
{
    $query = $request->input('query');

    $coupons = Coupon::where('code', 'LIKE', "%{$query}%")
                ->orWhere('type', 'LIKE', "%{$query}%")
                ->orderBy('expiry_date', 'desc')
                ->paginate(12);

    return view('admin.coupons', compact('coupons'));
}

// Search for orders
public function search_orders(Request $request)
{
    $query = $request->input('query');

    $orders = Order::where('id', 'LIKE', "%{$query}%")
                ->orWhere('name', 'LIKE', "%{$query}%")
                ->orWhere('email', 'LIKE', "%{$query}%")
                ->orWhere('phone', 'LIKE', "%{$query}%")
                ->orWhere('status', 'LIKE', "%{$query}%")
                ->orderBy('created_at', 'desc')
                ->paginate(12);

    return view('admin.orders', compact('orders'));
}

// Search for contacts
public function search_contacts(Request $request)
{
    $query = $request->input('query');

    $contacts = Contact::where('name', 'LIKE', "%{$query}%")
                ->orWhere('email', 'LIKE', "%{$query}%")
                ->orWhere('phone', 'LIKE', "%{$query}%")
                ->orWhere('comment', 'LIKE', "%{$query}%")
                ->orderBy('created_at', 'desc')
                ->paginate(10);

    return view('admin.contacts', compact('contacts'));
}

// Search for users
public function search_users(Request $request)
{
    $query = $request->input('query');

    $users = User::where('name', 'LIKE', "%{$query}%")
                ->orWhere('email', 'LIKE', "%{$query}%")
                ->orderBy('created_at', 'desc')
                ->paginate(15);

    return view('admin.users', compact('users'));
}

// Search for colors
public function search_colors(Request $request)
{
    $query = $request->input('query');

    $colors = Color::withCount('products')
                ->where('name', 'LIKE', "%{$query}%")
                ->orWhere('code', 'LIKE', "%{$query}%")
                ->orWhere('hex_code', 'LIKE', "%{$query}%")
                ->orderBy('name', 'asc')
                ->paginate(10);

    return view('admin.colors', compact('colors'));
}

// Search for sizes
public function search_sizes(Request $request)
{
    $query = $request->input('query');

    $sizes = Size::withCount('products')
                ->where('name', 'LIKE', "%{$query}%")
                ->orWhere('code', 'LIKE', "%{$query}%")
                ->orWhere('description', 'LIKE', "%{$query}%")
                ->orderBy('order', 'asc')
                ->paginate(10);

    return view('admin.sizes', compact('sizes'));
}

// ORDERS - Update existing method
// ORDERS - Update with user relationship search
public function orders(Request $request)
{
    $query = $request->input('query');

    $orders = Order::with(['user', 'orderItems'])
                ->withCount('orderItems')
                ->orderBy('created_at', 'desc');

    if ($query) {
        $orders->where(function($q) use ($query) {
            $q->where('id', 'LIKE', "%{$query}%")
              ->orWhere('name', 'LIKE', "%{$query}%")
              ->orWhere('phone', 'LIKE', "%{$query}%")
              ->orWhere('status', 'LIKE', "%{$query}%")
              ->orWhereHas('user', function($userQuery) use ($query) {
                  $userQuery->where('email', 'LIKE', "%{$query}%")
                           ->orWhere('name', 'LIKE', "%{$query}%");
              });
        });
    }

    $orders = $orders->paginate(12);

    return view('admin.orders', compact('orders'));
}

// COUPONS - Update existing method
public function coupons(Request $request)
{
    $query = $request->input('query');

    $coupons = Coupon::orderBy('expiry_date', 'desc');

    if ($query) {
        $coupons->where(function($q) use ($query) {
            $q->where('code', 'LIKE', "%{$query}%")
              ->orWhere('type', 'LIKE', "%{$query}%");
        });
    }

    $coupons = $coupons->paginate(12);

    return view('admin.coupons', compact('coupons'));
}

// CONTACTS - Update existing method
public function contacts(Request $request)
{
    $query = $request->input('query');

    $contacts = Contact::orderBy('created_at', 'desc');

    if ($query) {
        $contacts->where(function($q) use ($query) {
            $q->where('name', 'LIKE', "%{$query}%")
              ->orWhere('email', 'LIKE', "%{$query}%")
              ->orWhere('phone', 'LIKE', "%{$query}%")
              ->orWhere('comment', 'LIKE', "%{$query}%");
        });
    }

    $contacts = $contacts->paginate(10);

    return view('admin.contacts', compact('contacts'));
}

/**
 * Show contact message details
 */
public function contact_details($id)
{
    $contact = Contact::with('user')->findOrFail($id);

    // Mark as read when viewed
    if (!$contact->read) {
        $contact->markAsRead();
    }

    return view('admin.contact-details', compact('contact'));
}

/**
 * Reply to contact message (optional)
 */
public function contact_reply(Request $request, $id)
{
    $request->validate([
        'reply' => 'required|string'
    ]);

    $contact = Contact::findOrFail($id);

    // Save reply to database
    $contact->admin_reply = $request->reply;
    $contact->replied_at = now();
    $contact->read = true;
    $contact->save();

    // Format HTML email
    $html = "
    <!DOCTYPE html>
    <html>
    <head>
        <meta charset='UTF-8'>
        <title>Réponse à votre message</title>
        <style>
            body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; }
            .container { max-width: 600px; margin: 0 auto; padding: 20px; }
            .header { background: linear-gradient(135deg, #667eea, #764ba2); color: white; padding: 20px; text-align: center; }
            .content { padding: 30px; background: #f9f9f9; }
            .footer { text-align: center; padding: 20px; font-size: 12px; color: #666; }
            .message { background: var(--surface); padding: 20px; border-radius: 8px; margin: 20px 0; }
        </style>
    </head>
    <body>
        <div class='container'>
            <div class='header'>
                <h2>Réponse à votre message</h2>
            </div>
            <div class='content'>
                <p>Bonjour " . htmlspecialchars($contact->name) . ",</p>

                <p>Voici la réponse de notre équipe à votre message :</p>

                <div class='message'>
                    " . nl2br(htmlspecialchars($request->reply)) . "
                </div>

                <p>Si vous avez d'autres questions, n'hésitez pas à nous recontacter.</p>

                <p>Cordialement,<br>L'équipe " . htmlspecialchars(config('app.name')) . "</p>
            </div>
            <div class='footer'>
                <p>Cet email a été envoyé automatiquement. Merci de ne pas y répondre.</p>
            </div>
        </div>
    </body>
    </html>
    ";

    Mail::html($html, function ($message) use ($contact) {
        $message->to($contact->email, $contact->name)
               ->subject('Réponse à votre message - ' . config('app.name'));
    });

    return redirect()->route('admin.contact.details', $id)
                     ->with('status', 'Réponse envoyée avec succès!');
}
// USERS - Update existing method
public function users(Request $request)
{
    $query = $request->input('query');

    $users = User::orderBy('created_at', 'desc');

    if ($query) {
        $users->where(function($q) use ($query) {
            $q->where('name', 'LIKE', "%{$query}%")
              ->orWhere('email', 'LIKE', "%{$query}%")
              ->orWhere('numero', 'LIKE', "%{$query}%");
        });
    }

    $users = $users->paginate(15);

    return view('admin.users', compact('users'));
}

// If you have a colors method, update it. If not, add this:
public function colors(Request $request)
{
    $query = $request->input('query');

    $colors = Color::withCount('products')
                ->where('status', 1)
                ->orderBy('order', 'asc');

    if ($query) {
        $colors->where(function($q) use ($query) {
            $q->where('name', 'LIKE', "%{$query}%")
              ->orWhere('code', 'LIKE', "%{$query}%")
              ->orWhere('hex_code', 'LIKE', "%{$query}%");
        });
    }

    $colors = $colors->paginate(10);

    return view('admin.colors', compact('colors'));
}

// If you have a sizes method, update it. If not, add this:


public function sizes(Request $request)
{
    $query = $request->input('query');

    $sizes = Size::withCount('products')
                ->where('status', 1)
                ->orderBy('order', 'asc');

    if ($query) {
        $sizes->where(function($q) use ($query) {
            $q->where('name', 'LIKE', "%{$query}%")
              ->orWhere('code', 'LIKE', "%{$query}%")
              ->orWhere('description', 'LIKE', "%{$query}%");
        });
    }

    $sizes = $sizes->paginate(10);

    return view('admin.sizes', compact('sizes'));
}
/**
 * Batch update order status
 */


/**
 * Get French text for status
 */
private function getStatusText($status)
{
    $statuses = [
        'ordered' => 'commandée',
        'confirmed' => 'confirmée',
        'delivered' => 'livrée',
        'canceled' => 'annulée'
    ];

    return $statuses[$status] ?? $status;
}



// Add to AdminController class



public function batchUpdateOrderStatus(Request $request)
{
    $request->validate([
        'order_ids' => 'required|array',
        'order_ids.*' => 'exists:orders,id',
        'order_status' => 'required|in:ordered,confirmed,delivered,canceled'
    ]);

    $status = $request->order_status;
    $orderIds = $request->order_ids;

    // Update status for each order
    foreach ($orderIds as $orderId) {
        $order = Order::find($orderId);

        if ($order) {
            $order->update([
                'status' => $status,
                'updated_at' => now()
            ]);

            // Update specific date fields based on status
            switch ($status) {
                case 'confirmed':
                    $order->update(['confirmed_date' => now()]);
                    break;
                case 'delivered':
                    $order->update(['delivered_date' => now()]);
                    break;
                case 'canceled':
                    $order->update(['cancelled_date' => now()]);
                    break;
            }

            // Send notification email to customer
            if ($order->user) {
                // Add email notification logic here
            }
        }
    }

    return redirect()->back()->with('status',
        count($orderIds) . ' commande(s) mise(s) à jour avec succès.'
    );
}


//blog de site
// Blog Functions Start

public function blogs(Request $request)
{
    $query = $request->input('query');

    $blogs = Blog::with('user')
                ->orderBy('created_at', 'desc');

    if ($query) {
        $blogs->where(function($q) use ($query) {
            $q->where('title', 'LIKE', "%{$query}%")
              ->orWhere('excerpt', 'LIKE', "%{$query}%")
              ->orWhereHas('user', function($userQuery) use ($query) {
                  $userQuery->where('name', 'LIKE', "%{$query}%");
              });
        });
    }

    $blogs = $blogs->paginate(12);

    // Calculate statistics
    $totalArticles = Blog::count();
    $publishedArticles = Blog::where('is_published', true)->count();
    $draftArticles = Blog::where('is_published', false)->count();
    $featuredArticles = Blog::where('is_featured', true)->count();

    $totalViews = Blog::sum('views');
    $totalLikes = Blog::sum('likes');
    $totalShares = Blog::sum('shares');
    $totalEngagement = $totalLikes + $totalShares;

    $thisMonthArticles = Blog::whereMonth('created_at', now()->month)
                             ->whereYear('created_at', now()->year)
                             ->count();

    $avgViewsPerArticle = $totalArticles > 0 ? round($totalViews / $totalArticles) : 0;

    return view('admin.blogs', compact(
        'blogs',
        'totalArticles',
        'publishedArticles',
        'draftArticles',
        'featuredArticles',
        'totalViews',
        'totalEngagement',
        'thisMonthArticles',
        'avgViewsPerArticle'
    ));
}

public function blog_add()
{
    return view('admin.blog-add');
}

public function blog_store(Request $request)
{
    $request->validate([
        'title' => 'required|string|max:255',
        'slug' => 'nullable|string|max:255|unique:blogs,slug',
        'excerpt' => 'required|string|max:500',
        'content' => 'required|string',
        'featured_image' => 'required|image|mimes:jpeg,png,jpg,gif,webp|max:5120',
        'meta_title' => 'nullable|string|max:255',
        'meta_description' => 'nullable|string|max:500',
        'meta_keywords' => 'nullable|string|max:500',
        'is_featured' => 'nullable|boolean',
        'is_published' => 'nullable|boolean',
        'published_at' => 'nullable|date'
    ]);

    try {
        $blog = new Blog();
        $blog->title = $request->title;
        $blog->slug = $request->slug ?: Str::slug($request->title);
        $blog->excerpt = $request->excerpt;
        $blog->content = $request->content; // FIXED: Use $request->content, not just $request
        $blog->meta_title = $request->meta_title;
        $blog->meta_description = $request->meta_description;
        $blog->meta_keywords = $request->meta_keywords;
        $blog->is_featured = $request->boolean('is_featured');
        $blog->is_published = $request->boolean('is_published');
        $blog->published_at = $request->published_at ?: now();
        $blog->user_id = auth()->id(); // FIXED: Add the user_id from authenticated user

        // Handle featured image upload
        if ($request->hasFile('featured_image')) {
            $image = $request->file('featured_image');
            $file_extension = $image->extension();
            $file_name = Carbon::now()->timestamp . '.' . $file_extension;
            $this->GenerateBlogImages($image, $file_name);
            $blog->featured_image = $file_name;
        }

        $blog->save();

        return redirect()->route('admin.blogs')
                        ->with('status', 'Article de blog créé avec succès');

    } catch (\Exception $e) {
        return redirect()->back()
                        ->withInput()
                        ->with('error', 'Erreur lors de la création: ' . $e->getMessage());
    }
}



public function GenerateBlogImages($image, $imageName)
{
    $destinationPath = public_path('uploads/blogs');
    $thumbnailPath = public_path('uploads/blogs/thumbnails');

    // Create directories if they don't exist
    if (!File::exists($destinationPath)) {
        File::makeDirectory($destinationPath, 0755, true);
    }
    if (!File::exists($thumbnailPath)) {
        File::makeDirectory($thumbnailPath, 0755, true);
    }

    // Save original image
    $img = Image::read($image->path());
    $img->save($destinationPath . '/' . $imageName);

    // Create thumbnail (800x400)
    $imgThumb = Image::read($image->path());
    $imgThumb->cover(800, 400, 'center');
    $imgThumb->save($thumbnailPath . '/' . $imageName);
}

public function blog_edit($id)
{
    $blog = Blog::findOrFail($id);
    return view('admin.blog-edit', compact('blog'));
}

public function blog_update(Request $request)
{
    $request->validate([
        'id' => 'required|exists:blogs,id',
        'title' => 'required|string|max:255',
        'slug' => 'required|string|max:255|unique:blogs,slug,' . $request->id,
        'excerpt' => 'required|string|max:500',
        'content' => 'required|string',
        'featured_image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:5120',
        'meta_title' => 'nullable|string|max:255',
        'meta_description' => 'nullable|string|max:500',
        'meta_keywords' => 'nullable|string|max:500',
        'is_featured' => 'nullable|boolean',
        'is_published' => 'nullable|boolean',
        'published_at' => 'nullable|date'
    ]);

    $blog = Blog::findOrFail($request->id);
    $blog->title = $request->title;
    $blog->slug = $request->slug;
    $blog->excerpt = $request->excerpt;
    $blog->content = $request;
    $blog->meta_title = $request->meta_title;
    $blog->meta_description = $request->meta_description;
    $blog->meta_keywords = $request->meta_keywords;
    $blog->is_featured = $request->boolean('is_featured');
    $blog->is_published = $request->boolean('is_published');
    $blog->published_at = $request->published_at ?: now();

    // Handle featured image update
    if ($request->hasFile('featured_image')) {
        // Delete old images
        if (File::exists(public_path('uploads/blogs') . '/' . $blog->featured_image)) {
            File::delete(public_path('uploads/blogs') . '/' . $blog->featured_image);
        }
        if (File::exists(public_path('uploads/blogs/thumbnails') . '/' . $blog->featured_image)) {
            File::delete(public_path('uploads/blogs/thumbnails') . '/' . $blog->featured_image);
        }

        $image = $request->file('featured_image');
        $file_extension = $image->extension();
        $file_name = Carbon::now()->timestamp . '.' . $file_extension;
        $this->GenerateBlogImages($image, $file_name);
        $blog->featured_image = $file_name;
    }

    $blog->save();

    return redirect()->route('admin.blogs')
                    ->with('status', 'Article de blog mis à jour avec succès');
}

public function blog_delete($id)
{
    $blog = Blog::findOrFail($id);

    // Delete associated images
    if ($blog->featured_image) {
        if (File::exists(public_path('uploads/blogs') . '/' . $blog->featured_image)) {
            File::delete(public_path('uploads/blogs') . '/' . $blog->featured_image);
        }
        if (File::exists(public_path('uploads/blogs/thumbnails') . '/' . $blog->featured_image)) {
            File::delete(public_path('uploads/blogs/thumbnails') . '/' . $blog->featured_image);
        }
    }

    $blog->delete();

    return redirect()->route('admin.blogs')
                    ->with('status', 'Article de blog supprimé avec succès');
}

public function blog_toggle_featured($id)
{
    $blog = Blog::findOrFail($id);
    $blog->is_featured = !$blog->is_featured;
    $blog->save();

    $message = $blog->is_featured
        ? 'Article mis en vedette'
        : 'Article retiré des vedettes';

    return redirect()->route('admin.blogs')
                    ->with('status', $message);
}

public function blog_toggle_published($id)
{
    $blog = Blog::findOrFail($id);
    $blog->is_published = !$blog->is_published;
    $blog->published_at = $blog->is_published ? now() : null;
    $blog->save();

    $message = $blog->is_published
        ? 'Article publié'
        : 'Article dépublifié';

    return redirect()->route('admin.blogs')
                    ->with('status', $message);
}
/**
 * Handle blog image upload for Summernote editor
 */
public function blog_upload_image(Request $request)
{
    $request->validate([
        'image' => 'required|image|mimes:jpeg,png,jpg,gif,webp|max:2048'
    ]);

    try {
        $image = $request->file('image');
        $fileName = time() . '_' . uniqid() . '.' . $image->getClientOriginalExtension();

        // Create directory if it doesn't exist
        $uploadPath = public_path('uploads/blog-content');
        if (!File::exists($uploadPath)) {
            File::makeDirectory($uploadPath, 0755, true);
        }

        // Save the image
        $image->move($uploadPath, $fileName);

        return response()->json([
            'success' => true,
            'url' => asset('uploads/blog-content/' . $fileName)
        ]);

    } catch (\Exception $e) {
        return response()->json([
            'success' => false,
            'message' => 'Erreur lors du téléchargement de l\'image: ' . $e->getMessage()
        ]);
    }
}
// Blog Functions End
// Add after other methods in AdminController.php

/**
 * Show email composer interface
 */
public function composeEmail()
{
    // Get recent orders for quick selection
    $recentOrders = Order::orderBy('created_at', 'desc')->limit(20)->get();
    $users = User::orderBy('name')->limit(50)->get();

    return view('admin.emails.compose', compact('recentOrders', 'users'));
}

/**
 * Send email to single recipient
 */

public function sendEmail(Request $request)
{
    $request->validate([
        'recipient_type' => 'required|in:customer,user,manual',
        'recipient_email' => 'required_if:recipient_type,manual|email',
        'recipient_id' => 'required_if:recipient_type,customer,user|exists:users,id',
        'order_id' => 'nullable|exists:orders,id',
        'subject' => 'required|string|max:255',
        'message' => 'required|string',
        'template' => 'nullable|in:brevo_template,custom',
        'brevo_template_id' => 'nullable|integer',
        'attach_invoice' => 'nullable|boolean',
        'cc' => 'nullable|string',
        'bcc' => 'nullable|string'
    ]);

    try {
        $recipientEmail = '';
        $recipientName = '';

        // Determine recipient
        if ($request->recipient_type === 'manual') {
            $recipientEmail = $request->recipient_email;
            $recipientName = 'Client';
        } else {
            $user = User::find($request->recipient_id);
            $recipientEmail = $user->email;
            $recipientName = $user->name;
        }

        // Get order if specified
        $order = null;
        if ($request->order_id) {
            $order = Order::with('orderItems.product')->find($request->order_id);
        }

        // Prepare email content
        $content = view('emails.admin-custom', [
            'content' => $request->message,
            'recipientName' => $recipientName,
            'subject' => $request->subject,
            'order' => $order,
            'admin' => Auth::user()
        ])->render();

        // Option 1: Use Brevo API directly (better tracking)
        if ($request->template === 'brevo_template' && $request->brevo_template_id) {
            $brevoService = new BrevoService();

            $params = [
                'NAME' => $recipientName,
                'ORDER_ID' => $order ? $order->id : null,
                'ORDER_TOTAL' => $order ? number_format($order->total, 2) . '€' : null,
                'CUSTOM_CONTENT' => $request->message
            ];

            $result = $brevoService->sendTemplateEmail(
                ['email' => $recipientEmail, 'name' => $recipientName],
                $request->brevo_template_id,
                $params
            );

        } else {
            // Option 2: Use Laravel Mail with Brevo SMTP
            Mail::send([], [], function ($message) use ($recipientEmail, $recipientName, $request, $content, $order) {
                $message->to($recipientEmail, $recipientName)
                       ->subject($request->subject)
                       ->setBody($content, 'text/html');

                if ($request->cc) {
                    $emails = array_map('trim', explode(',', $request->cc));
                    $message->cc($emails);
                }

                if ($request->bcc) {
                    $emails = array_map('trim', explode(',', $request->bcc));
                    $message->bcc($emails);
                }

                // Attach invoice if requested
                if ($request->attach_invoice && $order) {
                    $pdf = PDF::loadView('pdf.invoice', ['order' => $order]);
                    $message->attachData($pdf->output(), 'invoice-' . $order->id . '.pdf', [
                        'mime' => 'application/pdf',
                    ]);
                }
            });

            $result = ['success' => true];
        }

        if ($result['success']) {
            // Log the email
            DB::table('email_logs')->insert([
                'recipient_email' => $recipientEmail,
                'recipient_name' => $recipientName,
                'subject' => $request->subject,
                'content' => $request->message,
                'sent_by' => Auth::id(),
                'order_id' => $request->order_id,
                'brevo_template_id' => $request->brevo_template_id,
                'created_at' => now(),
                'updated_at' => now()
            ]);

            return redirect()->back()->with('success', 'Email envoyé avec succès!');
        } else {
            return redirect()->back()->with('error', 'Erreur Brevo: ' . $result['error']);
        }

    } catch (\Exception $e) {
        return redirect()->back()->with('error', 'Erreur lors de l\'envoi: ' . $e->getMessage());
    }
}


//  settings_update method

// Add these methods inside the AdminController class

/**
 * Display all color palettes
 */
public function color_palettes(Request $request)
{
    $query = $request->input('query');

    $palettes = ColorPalette::orderBy('created_at', 'desc');

    if ($query) {
        $palettes->where('name', 'LIKE', "%{$query}%")
                 ->orWhere('type', 'LIKE', "%{$query}%");
    }

    $palettes = $palettes->paginate(10);

    return view('admin.color-palettes.index', compact('palettes'));
}

/**
 * Show add palette form
 */
public function color_palette_add()
{
    $colors = ColorPalette::getDefaultColors();
    return view('admin.color-palettes.add', compact('colors'));
}

/**
 * Store new palette
 */
public function color_palette_store(Request $request)
{
    $validated = $request->validate([
        'name' => 'required|string|max:255',
        'type' => 'required|in:default,custom,seasonal',
        'is_active' => 'boolean',
        'colors' => 'required|array',
        'colors.*' => 'required|string|max:50'
    ]);

    if (isset($validated['is_active']) && $validated['is_active']) {
        ColorPalette::query()->update(['is_active' => false]);
    }

    $palette = ColorPalette::create($validated);

    if ($palette->is_active) {
        app(ColorService::class)->generateCSSVariables($palette);
    }

    return redirect()
        ->route('admin.color-palettes')
        ->with('status', 'Palette de couleurs créée avec succès!');
}

/**
 * Show edit palette form
 */
/**
 * Show edit palette form
 */
public function color_palette_edit($id)
{
    $palette = ColorPalette::findOrFail($id);
    return view('admin.color-palettes.add', compact('palette'));
}

/**
 * Update palette
 */
public function color_palette_update(Request $request)
{
    $request->validate([
        'id' => 'required|exists:color_palettes,id',
        'name' => 'required|string|max:255',
        'type' => 'required|in:default,custom,seasonal',
        'is_active' => 'boolean',
        'colors' => 'required|array',
        'colors.*' => 'required|string|max:50'
    ]);

    $palette = ColorPalette::findOrFail($request->id);

    if ($request->boolean('is_active')) {
        ColorPalette::query()->update(['is_active' => false]);
    }

    $palette->update([
        'name' => $request->name,
        'type' => $request->type,
        'is_active' => $request->boolean('is_active'),
        'colors' => $request->colors
    ]);

    if ($palette->is_active) {
        app(ColorService::class)->generateCSSVariables($palette);
    }

    return redirect()
        ->route('admin.color-palettes')
        ->with('status', 'Palette de couleurs mise à jour avec succès!');
}

/**
 * Activate a palette
 */
public function color_palette_activate($id)
{
    app(ColorService::class)->activate($id);

    return redirect()
        ->route('admin.color-palettes')
        ->with('status', 'Palette de couleurs activée avec succès!');
}

/**
 * Delete a palette
 */
public function color_palette_delete($id)
{
    $palette = ColorPalette::findOrFail($id);

    if ($palette->is_active) {
        // Don't delete active palette, just deactivate
        $palette->update(['is_active' => false]);
        Cache::forget('active_color_palette');
    }

    $palette->delete();

    return redirect()
        ->route('admin.color-palettes')
        ->with('status', 'Palette de couleurs supprimée avec succès!');
}
}
