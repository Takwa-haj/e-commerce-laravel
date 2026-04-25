<?php
// app/Http/Controllers/Admin/AdminBlogController.php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Blog;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth; // Add this

class AdminBlogController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('admin');
    }

    /**
     * Display a listing of blog posts
     */
    public function index(Request $request)
    {
        $query = Blog::with('user');

        // Search functionality
        if ($request->filled('query')) {
            $search = $request->input('query');
            $query->where(function($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('excerpt', 'like', "%{$search}%")
                  ->orWhere('content', 'like', "%{$search}%");
            });
        }

        $blogs = $query->orderBy('created_at', 'desc')->paginate(15);

        return view('admin.blogs.blogs', compact('blogs'));
    }

    /**
     * Show form to create new blog post
     */
    public function create()
    {
        return view('admin.blogs.blog-add');
    }

    /**
     * Store a newly created blog post - FIXED VERSION
     */
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|max:200',
            'slug' => 'nullable|unique:blogs,slug',
            'excerpt' => 'required|max:500',
            'content' => 'required',
            'featured_image' => 'required|image|mimes:jpeg,png,jpg,gif|max:5120',
            'is_published' => 'nullable',
            'is_featured' => 'nullable',
            'published_at' => 'nullable|date',
            'meta_title' => 'nullable|max:60',
            'meta_description' => 'nullable|max:160',
            'meta_keywords' => 'nullable',
        ]);

        try {
            $blog = new Blog();
            $blog->title = $request->title;
            $blog->slug = $request->slug ?: Str::slug($request->title);
            $blog->excerpt = $request->excerpt;
            $blog->content = $request->content; // FIXED: was $request, now $request->content
            $blog->user_id = Auth::user()->id; // FIXED: Added user_id from authenticated user
            $blog->is_featured = $request->has('is_featured') ? 1 : 0;
            $blog->is_published = $request->has('is_published') ? 1 : 0;
            $blog->published_at = $request->has('is_published') ? ($request->published_at ?: now()) : null;
            $blog->meta_title = $request->meta_title;
            $blog->meta_description = $request->meta_description;
            $blog->meta_keywords = $request->meta_keywords;
            $blog->views = 0;
            $blog->likes = 0;
            $blog->shares = 0;

            // Handle featured image upload
            if ($request->hasFile('featured_image')) {
                $image = $request->file('featured_image');
                $filename = time() . '_' . Str::slug($blog->title) . '.' . $image->getClientOriginalExtension();

                // Create upload directories if they don't exist
                if (!file_exists(public_path('uploads/blogs'))) {
                    mkdir(public_path('uploads/blogs'), 0755, true);
                }

                // Create thumbnail directory
                if (!file_exists(public_path('uploads/blogs/thumbnails'))) {
                    mkdir(public_path('uploads/blogs/thumbnails'), 0755, true);
                }

                // Save original image
                $image->move(public_path('uploads/blogs'), $filename);

                // Create a copy for thumbnail (you can add image manipulation here if needed)
                copy(
                    public_path('uploads/blogs/' . $filename),
                    public_path('uploads/blogs/thumbnails/' . $filename)
                );

                $blog->featured_image = $filename;
            }

            $blog->save();

            return redirect()->route('admin.blogs')
                           ->with('status', 'Article créé avec succès!');
        } catch (\Exception $e) {
            return back()->withInput()
                        ->with('error', 'Erreur lors de la création: ' . $e->getMessage());
        }
    }

    /**
     * Show form to edit blog post
     */
    public function edit($id)
    {
        $blog = Blog::findOrFail($id);
        return view('admin.blogs.blog-edit', compact('blog'));
    }

    /**
     * Update the specified blog post - FIXED VERSION
     */
    public function update(Request $request)
    {
        $request->validate([
            'id' => 'required|exists:blogs,id',
            'title' => 'required|max:200',
            'slug' => 'required|unique:blogs,slug,' . $request->id,
            'excerpt' => 'required|max:500',
            'content' => 'required',
            'featured_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:5120',
            'is_published' => 'nullable',
            'is_featured' => 'nullable',
            'published_at' => 'nullable|date',
            'meta_title' => 'nullable|max:60',
            'meta_description' => 'nullable|max:160',
            'meta_keywords' => 'nullable',
        ]);

        try {
            $blog = Blog::findOrFail($request->id);
            $blog->title = $request->title;
            $blog->slug = $request->slug;
            $blog->excerpt = $request->excerpt;
            $blog->content = $request->content; // FIXED: was $request, now $request->content
            $blog->is_featured = $request->has('is_featured') ? 1 : 0;
            $blog->is_published = $request->has('is_published') ? 1 : 0;
            $blog->published_at = $request->has('is_published') ? ($request->published_at ?: now()) : null;
            $blog->meta_title = $request->meta_title;
            $blog->meta_description = $request->meta_description;
            $blog->meta_keywords = $request->meta_keywords;

            // Handle featured image upload
            if ($request->hasFile('featured_image')) {
                // Delete old image
                if ($blog->featured_image && file_exists(public_path('uploads/blogs/' . $blog->featured_image))) {
                    unlink(public_path('uploads/blogs/' . $blog->featured_image));
                }
                if ($blog->featured_image && file_exists(public_path('uploads/blogs/thumbnails/' . $blog->featured_image))) {
                    unlink(public_path('uploads/blogs/thumbnails/' . $blog->featured_image));
                }

                $image = $request->file('featured_image');
                $filename = time() . '_' . Str::slug($blog->title) . '.' . $image->getClientOriginalExtension();

                // Save original image
                $image->move(public_path('uploads/blogs'), $filename);

                // Create thumbnail copy
                copy(
                    public_path('uploads/blogs/' . $filename),
                    public_path('uploads/blogs/thumbnails/' . $filename)
                );

                $blog->featured_image = $filename;
            }

            $blog->save();

            return redirect()->route('admin.blogs')
                           ->with('status', 'Article mis à jour avec succès!');
        } catch (\Exception $e) {
            return back()->withInput()
                        ->with('error', 'Erreur lors de la mise à jour: ' . $e->getMessage());
        }
    }

    /**
     * Toggle featured status
     */
    public function toggleFeatured($id)
    {
        $blog = Blog::findOrFail($id);
        $blog->is_featured = !$blog->is_featured;
        $blog->save();

        return back()->with('status', 'Statut vedette modifié avec succès!');
    }

    /**
     * Toggle published status
     */
    public function togglePublished($id)
    {
        $blog = Blog::findOrFail($id);
        $blog->is_published = !$blog->is_published;
        $blog->published_at = $blog->is_published ? now() : null;
        $blog->save();

        $status = $blog->is_published ? 'publié' : 'dépublié';
        return back()->with('status', "Article $status avec succès!");
    }

    /**
     * Delete the specified blog post
     */
    public function destroy($id)
    {
        try {
            $blog = Blog::findOrFail($id);

            // Delete featured image
            if ($blog->featured_image) {
                if (file_exists(public_path('uploads/blogs/' . $blog->featured_image))) {
                    unlink(public_path('uploads/blogs/' . $blog->featured_image));
                }
                if (file_exists(public_path('uploads/blogs/thumbnails/' . $blog->featured_image))) {
                    unlink(public_path('uploads/blogs/thumbnails/' . $blog->featured_image));
                }
            }

            $blog->delete();

            return back()->with('status', 'Article supprimé avec succès!');
        } catch (\Exception $e) {
            return back()->with('error', 'Erreur lors de la suppression: ' . $e->getMessage());
        }
    }

    /**
     * Upload image for summernote editor
     */
    public function uploadImage(Request $request)
    {
        $request->validate([
            'image' => 'required|image|mimes:jpeg,png,jpg,gif|max:5120'
        ]);

        try {
            $image = $request->file('image');
            $filename = time() . '_' . uniqid() . '.' . $image->getClientOriginalExtension();

            // Create upload directory if it doesn't exist
            $uploadPath = public_path('uploads/blogs/content');
            if (!file_exists($uploadPath)) {
                mkdir($uploadPath, 0755, true);
            }

            // Save image
            $image->move($uploadPath, $filename);

            return response()->json([
                'success' => true,
                'url' => asset('uploads/blogs/content/' . $filename)
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors du téléchargement: ' . $e->getMessage()
            ], 500);
        }
    }
}
