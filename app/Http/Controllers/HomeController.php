<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Slide;
use App\Models\Category;
use App\Models\Contact;
use App\Models\Product;
use App\Models\CategoryBanner;
use App\Models\Setting;
use Illuminate\Support\Facades\Auth;



class HomeController extends Controller
{

    // In HomeController.php, update the index method:

public function index()
{
    $slides = Slide::where('type', 'home')->where('is_active', true)->orderBy('order')->get();
    $categoryBanners = CategoryBanner::where('is_active', true)
        ->orderBy('sort_order', 'asc')
        ->orderBy('position', 'asc')
        ->get();
    $categories = Category::orderBy("name")->get();
    $sproducts = Product::whereNotNull('sale_price')->where('sale_price', '<>', '')->inRandomOrder()->get()->take(8);

    // Change this to paginate featured products
    $fproducts = Product::where('featured', 1)->paginate(8);

    $categoryBanners = CategoryBanner::where('is_active', true)
        ->orderBy('sort_order', 'asc')
        ->orderBy('position', 'asc')
        ->get();

    return view('index', compact('slides', 'categories', 'sproducts', 'fproducts', 'categoryBanners'));
}

// Add a new method for loading more products
public function loadMoreProducts(Request $request)
{
    $page = $request->get('page', 2); // Start from page 2 since page 1 is already loaded
    $perPage = 8;

    $products = Product::where('featured', 1)
        ->paginate($perPage, ['*'], 'page', $page);

    if ($request->ajax()) {
        return response()->json([
            'html' => view('partials.product-grid', ['products' => $products])->render(),
            'hasMore' => $products->hasMorePages()
        ]);
    }

    return redirect()->route('home');
}

     public function contact()
    {
        $settings = Setting::first() ?? new Setting();
        return view('contact', compact('settings'));
    }

public function contact_store(Request $request){
    $request->validate([
        'name'=> 'required|max:100',
        'email'=> 'required|email',
        'phone' => 'required|string|max:20',
        'comment' => 'required|string|max:500',
    ]);
     try {
            $contact =new  Contact();
            $contact->name = $request->name;
            $contact->email = $request->email;
            $contact->phone = $request->phone;
            $contact->comment = $request->comment;
            $contact->read = false;
 // Associate with user if logged in
            if (Auth::check()) {
                $contact->user_id = Auth::id();
            }

            // Store additional info
            $contact->ip_address = $request->ip();
            $contact->user_agent = $request->userAgent();

            $contact->save();
    return redirect()->back()->with('success','Votre message a été envoyé avec succès! Nous vous répondrons dans les plus brefs délais.');
 } catch (\Exception $e) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Une erreur est survenue. Veuillez réessayer plus tard.');

}}
public function search(Request $request){
    $query = $request->input('query');
    $results = Product::where('name','LIKE', "%{$query}%")->get()->take(8);
    return response()->json($results);

}




}

