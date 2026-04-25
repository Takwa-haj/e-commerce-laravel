<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Review;
use App\Models\Product;

class ReviewController extends Controller
{
    /**
     * Display a listing of the user's reviews.
     */
    public function index(Request $request)
    {
        $user = Auth::user();

        // Get search and filter parameters
        $search = $request->query('search');
        $status = $request->query('status', 'all');
        $sort = $request->query('sort', 'newest');

        // Start query
        $reviews = Review::where('user_id', $user->id)
            ->with(['product' => function($query) {
                $query->select('id', 'name', 'slug', 'image');
            }]);

        // Apply search filter
        if ($search) {
            $reviews->where(function($query) use ($search) {
                $query->where('comment', 'like', '%' . $search . '%')
                    ->orWhereHas('product', function($q) use ($search) {
                        $q->where('name', 'like', '%' . $search . '%');
                    });
            });
        }

        // Apply status filter
        if ($status !== 'all') {
            $reviews->where('approved', $status === 'approved');
        }

        // Apply sorting
        switch ($sort) {
            case 'oldest':
                $reviews->orderBy('created_at', 'asc');
                break;
            case 'rating_high':
                $reviews->orderBy('rating', 'desc');
                break;
            case 'rating_low':
                $reviews->orderBy('rating', 'asc');
                break;
            default: // newest
                $reviews->orderBy('created_at', 'desc');
        }

        $reviews = $reviews->paginate(15)->withQueryString();

        // Get counts for filter tabs
        $totalReviews = Review::where('user_id', $user->id)->count();
        $approvedReviews = Review::where('user_id', $user->id)->where('approved', true)->count();
        $pendingReviews = Review::where('user_id', $user->id)->where('approved', false)->count();

        return view('user.reviews.index', compact(
            'reviews',
            'search',
            'status',
            'sort',
            'totalReviews',
            'approvedReviews',
            'pendingReviews'
        ));
    }

    /**
     * Show the form for editing a review.
     */
    public function edit($id)
    {
        $review = Review::where('user_id', Auth::id())->findOrFail($id);

        return view('user.reviews.edit', compact('review'));
    }

    /**
     * Update the specified review.
     */
    public function update(Request $request, $id)
    {
        $review = Review::where('user_id', Auth::id())->findOrFail($id);

        $validated = $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'required|string|min:10|max:1000',
        ]);

        // If review was approved and is being edited, reset approval
        if ($review->approved) {
            $review->approved = false;
        }

        $review->update($validated);

        return redirect()->route('user.reviews.index')
            ->with('success', 'Votre avis a été mis à jour avec succès. Il sera réexaminé avant publication.');
    }

    /**
     * Remove the specified review.
     */
    public function destroy($id)
    {
        $review = Review::where('user_id', Auth::id())->findOrFail($id);
        $review->delete();

        return redirect()->route('user.reviews.index')
            ->with('success', 'Votre avis a été supprimé avec succès.');
    }

    /**
     * Get review stats for the user.
     */
    public function stats()
    {
        $user = Auth::user();

        $stats = [
            'total' => $user->reviews()->count(),
            'approved' => $user->reviews()->where('approved', true)->count(),
            'pending' => $user->reviews()->where('approved', false)->count(),
            'average_rating' => round($user->reviews()->avg('rating'), 1),
            'most_recent' => $user->reviews()->latest()->first(),
            'top_rated_product' => $user->reviews()
                ->with('product')
                ->orderBy('rating', 'desc')
                ->first(),
        ];

        // Get rating distribution
        $distribution = [];
        for ($i = 1; $i <= 5; $i++) {
            $distribution[$i] = $user->reviews()->where('rating', $i)->count();
        }
        $stats['distribution'] = $distribution;

        return response()->json($stats);
    }
}
