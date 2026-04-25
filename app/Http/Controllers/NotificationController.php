<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\NotificationService;

class NotificationController extends Controller
{
    /**
     * Load notifications via AJAX
     */
    public function loadNotifications()
    {
        $notifications =NotificationService::getAdminNotifications(5);

        $html = view('admin.partials.notifications-list', compact('notifications'))->render();

        return response()->json([
            'html' => $html,
            'count' => NotificationService::getUnreadCount()
        ]);
    }

    /**
     * Mark single notification as read
     */
    public function markAsRead(Request $request)
    {
        $validated = $request->validate([
            'notification_id' => 'required|integer|exists:notifications,id'
        ]);

        $success = NotificationService::markAllAsRead();

    return response()->json([
        'success' => $success,
        'count' => NotificationService::getUnreadCount()
        ]);
    }

    /**
     * Mark all notifications as read
     */
    public function markAllAsRead(Request $request)
    {
        $success = NotificationService::markAllAsRead();

        return response()->json([
            'success' => $success,
            'count' => NotificationService::getUnreadCount()
        ]);
    }

    /**
     * View all notifications page
     */
    public function allNotifications()
    {
        $notifications = \App\Models\Notification::where('notifiable_type', \App\Models\User::class)
            ->where('notifiable_id', auth()->id())
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        return view('admin.notifications-all', compact('notifications'));
    }
}
