<?php

namespace App\Helpers;

use App\Models\Notification;

class NotificationHelper
{
    public static function getNotificationLink($notification)
    {
        $data = $notification->data ?? [];

        switch ($notification->type) {
            case 'new-order':
                return route('admin.order.details', ['order_id' => $data['order_id'] ?? 0]);

            case 'new-user':
                return route('admin.users');

            case 'order-status':
                return route('admin.order.details', ['order_id' => $data['order_id'] ?? 0]);

            default:
                return '#';
        }
    }

    public static function createNotification($userId, $type, $title, $message, $data = [])
    {
        return Notification::create([
            'user_id' => $userId,
            'type' => $type,
            'title' => $title,
            'message' => $message,
            'data' => $data,
            'is_read' => false
        ]);
    }

    public static function notifyNewOrder($order, $userId)
    {
        $notification = self::createNotification(
            $userId,
            'new-order',
            'Nouvelle commande',
            "Une nouvelle commande #{$order->id} a été passée par {$order->name}",
            ['order_id' => $order->id]
        );

        // Broadcast via Pusher
        broadcast(new \App\Events\NewOrderNotification($notification));

        return $notification;
    }

    public static function notifyNewUser($user, $adminId)
    {
        $notification = self::createNotification(
            $adminId,
            'new-user',
            'Nouvel utilisateur',
            "Un nouvel utilisateur s'est inscrit: {$user->name}",
            ['user_id' => $user->id]
        );

        broadcast(new \App\Events\NewUserNotification($notification));

        return $notification;
    }

    public static function notifyOrderStatus($order, $adminId, $oldStatus, $newStatus)
    {
        $notification = self::createNotification(
            $adminId,
            'order-status',
            'Statut de commande mis à jour',
            "La commande #{$order->id} est passée de {$oldStatus} à {$newStatus}",
            ['order_id' => $order->id]
        );

        broadcast(new \App\Events\OrderStatusNotification($notification));

        return $notification;
    }
}
