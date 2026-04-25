<?php

namespace App\Services;

use App\Models\Order;
use App\Models\Contact;
use App\Models\Review;
use App\Models\RefundRequest;
use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Schema;
use App\Models\Slide;
use App\Models\ShopBanner;
use App\Models\CategoryBanner;
class NotificationService
{
    /**
     * Get total unread notifications count for header badge
     */
    public static function getUnreadCount()
    {
        $unreadOrders = self::getUnreadOrderCount();
        $unreadContacts = self::getUnreadContactCount();
        $pendingReviews = self::getPendingReviewCount();
        $pendingRefunds = self::getPendingRefundCount();

        return $unreadOrders + $unreadContacts + $pendingReviews + $pendingRefunds;
    }

    /**
     * Get unread orders count (status = 'ordered')
     */
    public static function getUnreadOrderCount()
    {
        try {
            return Order::where('status', 'ordered')->count();
        } catch (\Exception $e) {
            return 0;
        }
    }

    /**
     * Get unread contacts count
     */
    public static function getUnreadContactCount()
    {
        try {
            // Check if 'read' column exists
            if (Schema::hasColumn('contacts', 'read')) {
                return Contact::where('read', false)->count();
            }

            // If no read column, return 0 to avoid counting all contacts
            return 0;
        } catch (\Exception $e) {
            return 0;
        }
    }

    /**
     * Get pending reviews count
     */
    public static function getPendingReviewCount()
    {
        try {
            if (class_exists(Review::class)) {
                return Review::where(function($query) {
                    $query->where('status', 'pending')
                          ->orWhere(function($q) {
                              $q->whereNull('status')->where('approved', false);
                          });
                })->count();
            }
            return 0;
        } catch (\Exception $e) {
            return 0;
        }
    }

    /**
     * Get pending refunds count
     */
    public static function getPendingRefundCount()
    {
        try {
            if (class_exists(RefundRequest::class) && Schema::hasTable('refund_requests')) {
                return RefundRequest::where('status', 'pending')->count();
            }
            return 0;
        } catch (\Exception $e) {
            return 0;
        }
    }

    /**
     * Get all notification counts in one array
     */
    public static function getAllCounts()
    {
        return [
            'total' => self::getUnreadCount(),
            'orders' => self::getUnreadOrderCount(),
            'contacts' => self::getUnreadContactCount(),
            'reviews' => self::getPendingReviewCount(),
            'refunds' => self::getPendingRefundCount(),
        ];
    }

    /**
     * Get admin notifications for display
     */
    public static function getAdminNotifications($limit = 10)
    {
        $notifications = [];

        // Get recent orders
        try {
            $recentOrders = Order::with('user')
                ->where('status', 'ordered')
                ->orderBy('created_at', 'desc')
                ->limit(3)
                ->get();

            foreach ($recentOrders as $order) {
                $notifications[] = [
                    'id' => 'order_' . $order->id,
                    'type' => 'order',
                    'title' => 'Nouvelle commande',
                    'message' => 'Commande #' . $order->id . ' de ' . ($order->user->name ?? 'Client'),
                    'time' => $order->created_at->diffForHumans(),
                    'link' => route('admin.order.details', $order->id),
                    'read' => false,
                    'timestamp' => $order->created_at->timestamp
                ];
            }
        } catch (\Exception $e) {
            // Ignore error, continue with other notifications
        }

        // Get recent contacts
        try {
            if (Schema::hasColumn('contacts', 'read')) {
                $recentContacts = Contact::where('read', false)
                    ->orderBy('created_at', 'desc')
                    ->limit(2)
                    ->get();

                foreach ($recentContacts as $contact) {
                    $notifications[] = [
                        'id' => 'contact_' . $contact->id,
                        'type' => 'contact',
                        'title' => 'Nouveau message',
                        'message' => 'Message de ' . $contact->name . ': ' . Str::limit($contact->subject ?? $contact->comment, 50),
                        'time' => $contact->created_at->diffForHumans(),
                        'link' => route('admin.contacts'),
                        'read' => false,
                        'timestamp' => $contact->created_at->timestamp
                    ];
                }
            }
        } catch (\Exception $e) {
            // Ignore error, continue with other notifications
        }

        // Get pending reviews
        try {
            if (class_exists(Review::class)) {
                $pendingReviews = Review::with('product', 'user')
                    ->where(function($query) {
                        $query->where('status', 'pending')
                              ->orWhere(function($q) {
                                  $q->whereNull('status')->where('approved', false);
                              });
                    })
                    ->orderBy('created_at', 'desc')
                    ->limit(3)
                    ->get();

                foreach ($pendingReviews as $review) {
                    $productName = $review->product ? Str::limit($review->product->name, 30) : 'Produit';
                    $notifications[] = [
                        'id' => 'review_' . $review->id,
                        'type' => 'review',
                        'title' => 'Nouvel avis',
                        'message' => 'Avis sur "' . $productName . '" par ' . $review->user->name,
                        'time' => $review->created_at->diffForHumans(),
                        'link' => route('admin.reviews'),
                        'read' => false,
                        'timestamp' => $review->created_at->timestamp
                    ];
                }
            }
        } catch (\Exception $e) {
            // Ignore error, continue with other notifications
        }

        // Get pending refunds
        try {
            if (class_exists(RefundRequest::class) && Schema::hasTable('refund_requests')) {
                $pendingRefunds = RefundRequest::with(['order', 'user'])
                    ->where('status', 'pending')
                    ->orderBy('created_at', 'desc')
                    ->limit(2)
                    ->get();

                foreach ($pendingRefunds as $refund) {
                    $notifications[] = [
                        'id' => 'refund_' . $refund->id,
                        'type' => 'refund',
                        'title' => 'Nouvelle demande de remboursement',
                        'message' => 'Demande #' . $refund->id . ' pour commande #' . $refund->order_id,
                        'time' => $refund->created_at->diffForHumans(),
                        'link' => route('admin.refunds'),
                        'read' => false,
                        'timestamp' => $refund->created_at->timestamp
                    ];
                }
            }
        } catch (\Exception $e) {
            // Ignore error, continue with other notifications
        }

        // Sort by timestamp and limit
        usort($notifications, function($a, $b) {
            return $b['timestamp'] <=> $a['timestamp'];
        });

        return array_slice($notifications, 0, $limit);
    }

    /**
     * Mark notification as read
     */
    public static function markAsRead($notificationId)
    {
        try {
            $parts = explode('_', $notificationId);
            if (count($parts) !== 2) {
                return false;
            }

            $type = $parts[0];
            $id = $parts[1];

            switch ($type) {
                case 'contact':
                    $contact = Contact::find($id);
                    if ($contact && Schema::hasColumn('contacts', 'read')) {
                        $contact->read = true;
                        $contact->save();
                        return true;
                    }
                    break;

                case 'order':
                    // Orders don't have a read flag, mark in session
                    session()->put('read_order_' . $id, true);
                    return true;

                case 'review':
                    session()->put('read_review_' . $id, true);
                    return true;

                case 'refund':
                    session()->put('read_refund_' . $id, true);
                    return true;
            }
        } catch (\Exception $e) {
            return false;
        }

        return false;
    }

    /**
     * Mark all notifications as read
     */
    public static function markAllAsRead()
    {
        try {
            // Mark all contacts as read
            if (Schema::hasColumn('contacts', 'read')) {
                Contact::where('read', false)->update(['read' => true]);
            }

            // Clear all read flags from session
            $keys = session()->keys();
            if ($keys) {
                foreach ($keys as $key) {
                    if (str_starts_with($key, 'read_')) {
                        session()->forget($key);
                    }
                }
            }

            return true;
        } catch (\Exception $e) {
            return false;
        }
    }

    /**
     * Check if notification is read (for display)
     */
    public static function isRead($notificationId)
    {
        try {
            return session()->has('read_' . str_replace('_', '_', $notificationId));
        } catch (\Exception $e) {
            return false;
        }
    }

    /**
     * Get formatted notifications with read status
     */
    public static function getFormattedNotifications($limit = 10)
    {
        $notifications = self::getAdminNotifications($limit);

        // Apply read status
        foreach ($notifications as &$notification) {
            $notification['read'] = self::isRead($notification['id']);
        }

        return $notifications;
    }

// In App\Services\NotificationService.php
public static function getPendingSlidesCount()
{
    try {
        $homeSlides = Slide::where('type', 'home')->where('is_active', false)->count();
        $ShopBanners = ShopBanner::where('is_active', false)->count();
        $shopBanners = ShopBanner::where('is_active', false)->count();
        $categoryBanners = CategoryBanner::where('is_active', false)->count();

        return $homeSlides + $ShopBanners + $shopBanners + $categoryBanners;
    } catch (\Exception $e) {
        return 0;
    }
}

}
