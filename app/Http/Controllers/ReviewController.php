<?php

namespace App\Http\Controllers;

use App\Models\Review;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ReviewController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'required|string|min:10|max:1000',
        ]);

        // Check if user is authenticated
        if (!Auth::check()) {
            return redirect()->route('login')
                ->with('error', 'Please login to submit a review.');
        }

        // Check if user already reviewed this product
        $existingReview = Review::where('user_id', Auth::id())
            ->where('product_id', $request->product_id)
            ->first();

        if ($existingReview) {
            return redirect()->back()
                ->with('error', 'You have already reviewed this product.')
                ->withInput();
        }

        $review = new Review();
        $review->product_id = $request->product_id;
        $review->user_id = Auth::id();
        $review->rating = $request->rating;
        $review->comment = $request->comment;
        $review->approved = true;


        $review->save();

        return redirect()->back()
            ->with('success', 'Merci pour votre avis !');    }

    // admin approval functionality
public function approve($id)
{
    if (Auth::user()->utype !== 'ADM') { // Changed from === to !==
        return redirect()->back()
            ->with('error', 'Unauthorized action.');
    }

    $review = Review::findOrFail($id);
    $review->approved = true;
    $review->save();

    return redirect()->back()
        ->with('success', 'Review approved successfully.');
}

    public function destroy($id)
    {
        $review = Review::findOrFail($id);

        // Check if user owns the review or is admin
        if (Auth::id() !== $review->user_id && Auth::user()->utype !== 'ADM') {
            return redirect()->back()
                ->with('error', 'Unauthorized action.');
        }

        $review->delete();

        return redirect()->back()
            ->with('success', 'Review deleted successfully.');
    }
}
