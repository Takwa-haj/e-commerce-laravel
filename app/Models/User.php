<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\Activitylog\Traits\CausesActivity;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Support\Facades\Storage;
use Gloudemans\Shoppingcart\Cart;

class User extends Authenticatable implements HasMedia
{
    use HasFactory, Notifiable, InteractsWithMedia, CausesActivity, LogsActivity;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'auth0_id',
        'utype',
        'google_id',
        'provider',
        'email_verified_at',
        'two_factor_secret',
        'two_factor_recovery_codes',
        'two_factor_confirmed_at',
        'last_login_at',
        'last_login_ip',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
        'two_factor_secret',
        'two_factor_recovery_codes',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'last_login_at' => 'datetime',
            'two_factor_confirmed_at' => 'datetime',
        ];
    }

    /**
     * Configure activity logging
     */
    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly(['name', 'email', 'last_login_at'])
            ->logOnlyDirty()
            ->setDescriptionForEvent(fn(string $eventName) => "User {$eventName}");
    }

    /**
     * User addresses relationship - UNIFIED
     */
    public function addresses(): HasMany
    {
        return $this->hasMany(UnifiedAddress::class);
    }

    /**
     * Get default address
     */
    public function defaultAddress()
    {
        return $this->hasOne(UnifiedAddress::class)->where('is_default', true);
    }

    /**
     * Get default address attribute
     */
    public function getDefaultAddressAttribute()
    {
        return $this->defaultAddress()->first();
    }

    /**
     * Get shipping addresses
     */
    public function shippingAddresses()
    {
        return $this->addresses()->whereIn('address_type', ['shipping', 'both']);
    }

    /**
     * Get billing addresses
     */
    public function billingAddresses()
    {
        return $this->addresses()->whereIn('address_type', ['billing', 'both']);
    }

    /**
     * User orders relationship
     */
    public function orders()
    {
        return $this->hasMany(Order::class);
    }

    /**
     * User reviews relationship
     */
    public function reviews()
    {
        return $this->hasMany(Review::class);
    }

    /**
     * User wishlist relationship
     */
    public function wishlist()
    {
        return $this->belongsToMany(Product::class, 'wishlists')
            ->withTimestamps();
    }

    /**
     * User cart relationship
     */
    public function cart()
    {
        return $this->hasOne(Cart::class);
    }

    /**
     * Check if user is admin
     */
    public function isAdmin(): bool
    {
        return $this->utype === 'ADM';
    }

    /**
     * Check if user is regular user
     */
    public function isUser(): bool
    {
        return $this->utype === 'USR';
    }

    /**
     * Check if user has 2FA enabled
     */
    public function hasTwoFactorEnabled(): bool
    {
        return !is_null($this->two_factor_secret);
    }

    /**
     * Get user avatar URL with fallback
     */
    public function getAvatarUrlAttribute(): string
    {
        if ($this->hasMedia('avatar')) {
            return $this->getFirstMediaUrl('avatar', 'thumbnail');
        }

        if ($this->avatar && Storage::disk('public')->exists("avatars/{$this->avatar}")) {
            return Storage::url("avatars/{$this->avatar}");
        }

        $hash = md5(strtolower(trim($this->email)));
        return "https://www.gravatar.com/avatar/{$hash}?d=mp&s=200";
    }

    /**
     * Register media collections
     */
    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('avatar')
            ->singleFile()
            ->acceptsMimeTypes(['image/jpeg', 'image/png', 'image/webp'])
            ->registerMediaConversions(function () {
                $this->addMediaConversion('thumbnail')
                    ->width(150)
                    ->height(150)
                    ->sharpen(10);

                $this->addMediaConversion('small')
                    ->width(50)
                    ->height(50)
                    ->sharpen(5);
            });
    }

    /**
     * Record login activity
     */
    public function recordLogin(string $ipAddress): void
    {
        $this->update([
            'last_login_at' => now(),
            'last_login_ip' => $ipAddress,
        ]);

        activity('auth')
            ->performedOn($this)
            ->causedBy($this)
            ->withProperties(['ip' => $ipAddress])
            ->log('logged_in');
    }

    public function inTransitOrders()
    {
        return $this->hasMany(Order::class, 'user_id')
            ->where('status', 'confirmed')
            ->whereNotNull('tracking_number')
            ->whereNull('delivered_date');
    }

    public function getTrackableOrdersAttribute()
    {
        return Order::where(function($query) {
                $query->where('user_id', $this->id)
                      ->orWhere('email', $this->email);
            })
            ->where('status', '!=', 'canceled')
            ->orderBy('created_at', 'desc')
            ->get();
    }

    public function emailLogs()
    {
        return $this->hasMany(EmailLog::class, 'sent_by');
    }

    public function getEmailCountAttribute()
    {
        return $this->emailLogs()->count();
    }

    public function contacts()
    {
        return $this->hasMany(Contact::class);
    }
}
