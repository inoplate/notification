<?php

namespace Inoplate\Notification\Events\Notification;

use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class NewNotificationCreated implements ShouldBroadcast
{
    /**
     * Notification object
     * 
     * @var array
     */
    public $notification;

    /**
     * Create new NewNotificationCreated instance
     * 
     * @param array $notification
     */
    public function __construct($notification)
    {
        $this->notification = $notification;
    }

    /**
     * Get the channels the event should be broadcast on.
     *
     * @return array
     */
    public function broadcastOn()
    {
        return ['notification'];
    }
}