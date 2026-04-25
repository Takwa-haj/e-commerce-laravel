<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\NotificationService;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    public function loadNotifications()
    {
        $notifications = NotificationService::getAdminNotifications(5);
        $count = NotificationService::getUnreadCount();

        return response()->json([
            'html' => view('admin.partials.notifications-list', compact('notifications'))->render(),
            'count' => $count
        ]);
    }

    public function markAsRead(Request $request)
    {
        $validated = $request->validate([
            'notification_id' => 'required|exists:notifications,id'
        ]);

        $success = NotificationService::markAsRead($validated['notification_id']);

        return response()->json(['success' => $success]);
    }

    public function markAllAsRead(Request $request)
    {
        NotificationService::markAllAsRead();

        return response()->json(['success' => true]);
    }

    public function allNotifications()
    {
        $notifications = \App\Models\Notification::forAdmin()
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        return view('admin.notifications.all', compact('notifications'));
    }
}
