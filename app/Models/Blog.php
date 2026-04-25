<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use Egulias\EmailValidator\Parser\Comment;

class Blog extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'slug',
        'excerpt',
        'content',
        'featured_image',
        'meta_title',
        'meta_description',
        'meta_keywords',
        'views',
        'likes',
        'shares',
        'is_featured',
        'is_published',
        'published_at',
        'user_id'
    ];

    protected $casts = [
        'is_featured' => 'boolean',
        'is_published' => 'boolean',
        'published_at' => 'datetime',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($blog) {
            if (empty($blog->slug)) {
                $blog->slug = Str::slug($blog->title);
            }
            if (empty($blog->published_at) && $blog->is_published) {
                $blog->published_at = now();
            }
        });

        static::updating(function ($blog) {
            if ($blog->isDirty('title') && empty($blog->slug)) {
                $blog->slug = Str::slug($blog->title);
            }
        });
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Categories relationship (if you have blog categories)
    public function categories()
    {
        return $this->belongsToMany(Category::class, 'blog_categories');
    }

    // Tags relationship - commented out until you create Tag model
    // public function tags()
    // {
    //     return $this->belongsToMany(Tag::class, 'blog_tags');
    // }

    // Comments relationship (if you have comments)
    public function comments()
    {
        return $this->hasMany(Comment::class);
    }

    /**
     * Get the featured image URL
     */
    public function getFeaturedImageUrlAttribute()
    {
        if (!$this->featured_image) {
            return asset('images/default-blog.jpg');
        }

        // Check if file exists in uploads/blogs directory
        if (file_exists(public_path('uploads/blogs/' . $this->featured_image))) {
            return asset('uploads/blogs/' . $this->featured_image);
        }

        // If not found in uploads, check if it's a URL or try default
        if (filter_var($this->featured_image, FILTER_VALIDATE_URL)) {
            return $this->featured_image;
        }

        return asset('images/default-blog.jpg');
    }

    /**
     * Get the thumbnail image URL
     */
    public function getThumbnailUrlAttribute()
    {
        if (!$this->featured_image) {
            return asset('images/default-blog-thumb.jpg');
        }

        // Check if thumbnail exists
        $thumbnailPath = public_path('uploads/blogs/thumbnails/' . $this->featured_image);

        if (file_exists($thumbnailPath)) {
            return asset('uploads/blogs/thumbnails/' . $this->featured_image);
        }

        // If no thumbnail, return the full image
        return $this->featured_image_url;
    }

    /**
     * Calculate reading time
     */
    public function getReadingTimeAttribute()
    {
        $wordCount = str_word_count(strip_tags($this->content));
        $minutes = ceil($wordCount / 200); // Average reading speed: 200 words per minute
        return $minutes . ' min';
    }

    /**
     * Format published date for display
     */
    public function getPublishedAtFormattedAttribute()
    {
        if (!$this->published_at) {
            return $this->created_at->format('d/m/Y');
        }

        return $this->published_at->format('d/m/Y');
    }

    /**
     * Format published date for datetime input
     */
    public function getPublishedAtForInputAttribute()
    {
        if (!$this->published_at) {
            return now()->format('Y-m-d\TH:i');
        }

        return $this->published_at->format('Y-m-d\TH:i');
    }

    /**
     * Get short excerpt (for listings)
     */
    public function getShortExcerptAttribute()
    {
        return Str::limit($this->excerpt, 100);
    }

    /**
     * Get short title (for listings)
     */
    public function getShortTitleAttribute()
    {
        return Str::limit($this->title, 50);
    }

    /**
     * Scope for published blogs
     */
    public function scopePublished($query)
    {
        return $query->where('is_published', true);
    }

    /**
     * Scope for featured blogs
     */
    public function scopeFeatured($query)
    {
        return $query->where('is_featured', true)
                     ->where('is_published', true);
    }

    /**
     * Scope for recent blogs
     */
    public function scopeRecent($query, $limit = 5)
    {
        return $query->published()
                     ->orderBy('published_at', 'desc')
                     ->limit($limit);
    }

    /**
     * Increment views count
     */
    public function incrementViews()
    {
        $this->increment('views');
        return $this;
    }

    /**
     * Increment likes count
     */
    public function incrementLikes()
    {
        $this->increment('likes');
        return $this;
    }

    /**
     * Increment shares count
     */
    public function incrementShares()
    {
        $this->increment('shares');
        return $this;
    }

    /**
     * Get related posts (by same categories or tags)
     */
    public function getRelatedPosts($limit = 4)
    {
        return Blog::where('id', '!=', $this->id)
                   ->published()
                   ->orderBy('published_at', 'desc')
                   ->limit($limit)
                   ->get();
    }
}
