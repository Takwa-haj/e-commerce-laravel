<?php

namespace App\Http\Controllers;
use Intervention\Image\Laravel\Facades\Image;

use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\Review;
use App\Models\OrderItem;
use App\Models\Transaction;
use App\Models\Address;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;

use App\Rules\StrongPassword;
use App\Models\Contact;
class UserController extends Controller
{
    public function index(){
       $user = Auth::user();
        $recentOrders = Order::where('user_id', $user->id)
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();

        return view('user.index', compact('user', 'recentOrders'));    }

    public function orders(){
        $orders = Order::where('user_id',Auth::user()->id)->orderBy('created_at','desc')->paginate(10);
        return view('user.orders',compact('orders'));

    }
    public function order_details($order_id){
        $order = Order::where('user_id',Auth::user()->id)->where('id',$order_id)->first();
        if($order){
           $orderItems = OrderItem::where('order_id',$order->id)->orderBy('id')->paginate(12);
           $transaction = Transaction::where('order_id',$order_id)->first();
           return view('user.order-details',compact('order','orderItems','transaction'));

        }else{
            return redirect('')->route('login');
        }
    }





public function order_cancel(Request $request){
    $order = Order::find($request->order_id);
    $order->status = "canceled";
    $order->cancelled_date = Carbon::now();
    $order->save();
    return back()->with("status","order has been cancelled");
}



    public function account_details()
    {


        $user = Auth::user();
        return view('user.account-details', compact('user'));
    }

    // Update account_details_update method
public function account_details_update(Request $request)
{
    $user = Auth::user();

    $validator = Validator::make($request->all(), [
        'name' => 'required|string|max:255',
        'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
        'current_password' => 'nullable|required_with:new_password',
        'new_password' => ['nullable', 'min:8', 'confirmed', new StrongPassword],
        'avatar' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048', // 2MB max
    ], [
        'new_password' => 'Le mot de passe doit contenir au moins 8 caractères, une majuscule, une minuscule, un chiffre et un caractère spécial.',
        'avatar.max' => 'L\'image ne doit pas dépasser 2MB.',
        'avatar.mimes' => 'Seuls les formats JPEG, PNG, JPG, GIF et WEBP sont acceptés.',
    ]);

    if ($validator->fails()) {
        return redirect()->back()
            ->withErrors($validator)
            ->withInput();
    }

    // Update basic information
    $user->name = $request->name;
    $user->email = $request->email;

    // Handle avatar upload
    if ($request->hasFile('avatar')) {
        $this->uploadAvatar($user, $request->file('avatar'));
    }

    // Update password if provided
    if ($request->filled('current_password') && $request->filled('new_password')) {
        if (Hash::check($request->current_password, $user->password)) {
            $user->password = Hash::make($request->new_password);

            // Log password change
            activity('security')
                ->performedOn($user)
                ->causedBy($user)
                ->log('password_changed');

            // Send notification
        } else {
            return redirect()->back()
                ->with('error', 'Le mot de passe actuel est incorrect.')
                ->withInput();
        }
    }
    if ($request->has('remove_avatar') && $user->avatar) {
    $uploadPath = public_path('uploads/avatars');

    if (file_exists($uploadPath . '/' . $user->avatar)) {
        unlink($uploadPath . '/' . $user->avatar);
    }

    $user->avatar = null;

    // Log avatar removal
    activity()
        ->performedOn($user)
        ->causedBy($user)
        ->log('avatar_removed');
}

    $user->save();

    // Log profile update
    activity()
        ->performedOn($user)
        ->causedBy($user)
        ->log('profile_updated');

    return redirect()->route('user.account.details')
        ->with('success', 'Vos informations ont été mises à jour avec succès!');
}

//avatar
protected function uploadAvatar($user, $avatarFile)
{
    // Create directory if it doesn't exist
    $uploadPath = public_path('uploads/avatars');
    if (!file_exists($uploadPath)) {
        mkdir($uploadPath, 0755, true);
    }

    // Generate unique filename
    $filename = 'avatar_' . $user->id . '_' . time() . '.' . $avatarFile->getClientOriginalExtension();

    // Delete old avatar if exists
    if ($user->avatar && file_exists($uploadPath . '/' . $user->avatar)) {
        unlink($uploadPath . '/' . $user->avatar);
    }

    // Resize and save image
    $image = Image::read($avatarFile->getRealPath());


    // Resize to max 500x500 while maintaining aspect ratio
     $image->scale(width: 500, height: 500);


    // Save the image
    $image->save($uploadPath . '/' . $filename, 80); // 80% quality

    // Update user avatar
    $user->avatar = $filename;

    // Remove Google avatar if user uploads their own
    $user->provider = null;
    $user->google_id = null;

    // Log avatar change
    activity()
        ->performedOn($user)
        ->causedBy($user)
        ->log('avatar_changed');
}
    public function addresses()
    {
        $addresses = Address::where('user_id', Auth::id())->get();
        return view('user.addresses', compact('addresses'));
    }

    public function address_store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'address_line_1' => 'required|string|max:255',
            'address_line_2' => 'nullable|string|max:255',
            'city' => 'required|string|max:100',
            'state' => 'required|string|max:100',
            'postal_code' => 'required|string|max:20',
            'country' => 'required|string|max:100',
            'is_default' => 'boolean',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $address = new Address();
        $address->user_id = Auth::id();
        $address->first_name = $request->first_name;
        $address->last_name = $request->last_name;
        $address->address_line_1 = $request->address_line_1;
        $address->address_line_2 = $request->address_line_2;
        $address->city = $request->city;
        $address->state = $request->state;
        $address->postal_code = $request->postal_code;
        $address->country = $request->country;
        $address->is_default = $request->has('is_default');

        // If this is set as default, unset other defaults
        if ($address->is_default) {
            Address::where('user_id', Auth::id())
                ->where('is_default', true)
                ->update(['is_default' => false]);
        }

        $address->save();

        return redirect()->route('user.addresses')
            ->with('success', 'Address added successfully!');
    }

    public function address_update(Request $request, $id)
    {
        $address = Address::where('user_id', Auth::id())
            ->where('id', $id)
            ->firstOrFail();

        $validator = Validator::make($request->all(), [
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'address_line_1' => 'required|string|max:255',
            'address_line_2' => 'nullable|string|max:255',
            'city' => 'required|string|max:100',
            'state' => 'required|string|max:100',
            'postal_code' => 'required|string|max:20',
            'country' => 'required|string|max:100',
            'is_default' => 'boolean',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $address->first_name = $request->first_name;
        $address->last_name = $request->last_name;
        $address->address_line_1 = $request->address_line_1;
        $address->address_line_2 = $request->address_line_2;
        $address->city = $request->city;
        $address->state = $request->state;
        $address->postal_code = $request->postal_code;
        $address->country = $request->country;

        $isDefault = $request->has('is_default');

        // If this is set as default, unset other defaults
        if ($isDefault && !$address->is_default) {
            Address::where('user_id', Auth::id())
                ->where('is_default', true)
                ->update(['is_default' => false]);
        }

        $address->is_default = $isDefault;
        $address->save();

        return redirect()->route('user.addresses')
            ->with('success', 'Address updated successfully!');
    }

    public function address_delete($id)
    {
        $address = Address::where('user_id', Auth::id())
            ->where('id', $id)
            ->firstOrFail();

        $address->delete();

        return redirect()->route('user.addresses')
            ->with('success', 'Address deleted successfully!');
    }
    public function notifications()
{
    // Mark all as read when viewing notifications page
    auth()->user->id->unreadNotifications->markAsRead();

    $notifications = auth()->user->id->notifications()->paginate(10);
    return view('user.notifications', compact('notifications'));
}


public function reviews()
{
    $reviews = Auth::user()->reviews()
        ->with('product.category')
        ->orderBy('created_at', 'desc')
        ->paginate(10);

    $averageRating = Auth::user()->reviews()->avg('rating') ?? 0;
    $approvedCount = Auth::user()->reviews()->where('approved', true)->count();
    $pendingCount = Auth::user()->reviews()->where('approved', false)->count();

    return view('user.reviews', compact(
        'reviews',
        'averageRating',
        'approvedCount',
        'pendingCount'
    ));
}

// Add this method for deleting user reviews
public function deleteReview($id)
{
    $review = Review::findOrFail($id);

    // Ensure the review belongs to the authenticated user
    if ($review->user_id !== Auth::id()) {
        return redirect()->back()
            ->with('error', 'Vous ne pouvez pas supprimer cet avis.');
    }

    $review->delete();

    return redirect()->route('user.reviews')
        ->with('success', 'Avis supprimé avec succès.');
}


public function security()
{
    $user = auth();
    $sessions = DB::table('sessions')
        ->where('user_id', $user->id)
        ->orderBy('last_activity', 'desc')
        ->get();

    return view('user.security', compact('user', 'sessions'));
}

public function logoutSession($id)
{
    DB::table('sessions')->where('id', $id)->delete();

    return back()->with('status', 'Session terminée avec succès.');
}

public function logoutAllSessions()
{
    $user = auth();

    DB::table('sessions')
        ->where('user_id', $user->id)
        ->where('id', '!=', session()->getId())
        ->delete();

    return back()->with('status', 'Toutes les autres sessions ont été terminées.');
}
public function messages()
{
    $user = Auth::user();
    $messages = Contact::where('user_id', $user->id)
                       ->orderBy('created_at', 'desc')
                       ->paginate(10);

    return view('user.messages', compact('messages'));
}
 /**
     * Display single message details
     */
    public function messageDetails($id)
    {
        $user = Auth::user();
        $message = Contact::where('user_id', $user->id)
                         ->where('id', $id)
                         ->firstOrFail();

        return view('user.message-details', compact('user', 'message'));
    }
}



