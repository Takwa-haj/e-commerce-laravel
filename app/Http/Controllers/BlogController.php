<?php

namespace App\Http\Controllers;

use App\Models\Blog;
use App\Models\Category;
use App\Models\Tag;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class BlogController extends Controller
{
    public function index(Request $request)
    {
        $query = Blog::with('user')
                    ->where('is_published', true)
                    ->orderBy('published_at', 'desc');

        // Apply search filter
        if ($request->has('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('excerpt', 'like', "%{$search}%")
                  ->orWhere('content', 'like', "%{$search}%");
            });
        }

        // Apply category filter
        if ($request->has('category')) {
            $query->whereHas('categories', function($q) use ($request) {
                $q->where('slug', $request->category);
            });
        }

        // Apply tag filter
        if ($request->has('tag')) {
            $query->whereHas('tags', function($q) use ($request) {
                $q->where('slug', $request->tag);
            });
        }

        $featuredPosts = Blog::where('is_published', true)
                            ->where('is_featured', true)
                            ->orderBy('published_at', 'desc')
                            ->take(3)
                            ->get();

        $recentPosts = Blog::where('is_published', true)
                          ->orderBy('published_at', 'desc')
                          ->take(5)
                          ->get();

        $posts = $query->paginate(9);

        return view('blog.index', compact('posts', 'featuredPosts', 'recentPosts'));
    }

    public function show($slug)
{
    $post = Blog::where('slug', $slug)
                ->where('is_published', true)
                ->with('user')
                ->firstOrFail();

    // Increment views
    $post->incrementViews();

    // Get related posts
    $relatedPosts = Blog::where('is_published', true)
                       ->where('id', '!=', $post->id)
                       ->orderBy('published_at', 'desc')
                       ->take(4)
                       ->get();

    // Get next and previous posts
    $nextPost = Blog::where('is_published', true)
                   ->where('published_at', '>', $post->published_at)
                   ->orderBy('published_at', 'asc')
                   ->first();

    $prevPost = Blog::where('is_published', true)
                   ->where('published_at', '<', $post->published_at)
                   ->orderBy('published_at', 'desc')
                   ->first();

    // Get recent posts for sidebar
    $recentPosts = Blog::where('is_published', true)
                      ->where('id', '!=', $post->id)
                      ->orderBy('published_at', 'desc')
                      ->take(5)
                      ->get();

    // Get post count for categories widget
    $postCount = Blog::where('is_published', true)->count();

    return view('blog.show', compact(
        'post',
        'relatedPosts',
        'nextPost',
        'prevPost',
        'recentPosts',
        'postCount'
    ));
}

    public function search(Request $request)
    {
        $request->validate([
            'query' => 'required|string|min:3'
        ]);

        $query = $request->query;
        $posts = Blog::where('is_published', true)
                    ->where(function($q) use ($query) {
                        $q->where('title', 'like', "%{$query}%")
                          ->orWhere('excerpt', 'like', "%{$query}%")
                          ->orWhere('content', 'like', "%{$query}%");
                    })
                    ->orderBy('published_at', 'desc')
                    ->paginate(9);

        return view('blog.search', compact('posts', 'query'));
    }

    /**
     * Set meta tags for SEO
     */
    private function setMetaTags($post)
    {
        $metaTitle = $post->meta_title ?: $post->title;
        $metaDescription = $post->meta_description ?: Str::limit(strip_tags($post->excerpt), 160);
        $metaKeywords = $post->meta_keywords ?: 'bijoux, blog, mode, accessoires';

        // Share these variables with the view
        view()->share('metaTitle', $metaTitle);
        view()->share('metaDescription', $metaDescription);
        view()->share('metaKeywords', $metaKeywords);
        view()->share('metaImage', $post->featured_image_url);
        view()->share('canonicalUrl', route('blog.show', $post->slug));
    }
}
