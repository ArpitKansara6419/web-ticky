<?php

namespace App\Events;

use App\Models\Engineer;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class AppPushNotification
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $notification_data;

    public $engineer;

    /**
     * Create a new event instance.
     */
    public function __construct($notification_data, $engineer)
    {
        $this->notification_data = $notification_data;
        $this->engineer = $engineer;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return array<int, \Illuminate\Broadcasting\Channel>
     */
    public function broadcastOn(): array
    {
        return [
            new PrivateChannel('channel-name'),
        ];
    }
}
