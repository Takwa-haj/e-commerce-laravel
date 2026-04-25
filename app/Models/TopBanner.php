<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TopBanner extends Model
{
    use HasFactory;

    protected $fillable = [
        'message',
        'free_shipping_threshold',
        'is_active',
        'facebook_url',
        'instagram_url',
        'twitter_url',
        'pinterest_url',
        'youtube_url',
        'tiktok_url',
        'background_color',
        'text_color',
        'sort_order'
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'free_shipping_threshold' => 'decimal:2'
    ];

    /**
     * Get the active top banner
     */
    public static function getActive()
    {
        return self::where('is_active', true)
                   ->orderBy('sort_order')
                   ->first() ?? new self();
    }

    /**
     * Format the message with threshold if available
     */
    public function getFormattedMessage()
    {
        if ($this->free_shipping_threshold && $this->free_shipping_threshold > 0) {
            return str_replace(
                '{threshold}',
                number_format($this->free_shipping_threshold, 2) ,
                $this->message
            );
        }
        return $this->message;
    }

    /**
     * Get all active social links
     */
    public function getActiveSocialLinks()
    {
        $links = [];

        if ($this->facebook_url) $links['facebook'] = $this->facebook_url;
        if ($this->instagram_url) $links['instagram'] = $this->instagram_url;
        if ($this->twitter_url) $links['twitter'] = $this->twitter_url;
        if ($this->pinterest_url) $links['pinterest'] = $this->pinterest_url;
        if ($this->youtube_url) $links['youtube'] = $this->youtube_url;
        if ($this->tiktok_url) $links['tiktok'] = $this->tiktok_url;

        return $links;
    }
}
