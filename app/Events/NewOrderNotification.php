<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class NewOrderNotification implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $notification;

    public function __construct($notification)
    {
        $this->notification = $notification;
    }

    public function broadcastOn()
    {
        return new Channel('admin-notifications');
    }

    public function broadcastAs()
    {
        return 'new-order';
    }

    public function broadcastWith()
    {
        return [
            'message' => $this->notification->message,
            'order_id' => $this->notification->data['order_id'] ?? null,
            'notification_id' => $this->notification->id
        ];
    }
}
