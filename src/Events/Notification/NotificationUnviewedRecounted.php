<?php

namespace Inoplate\Notification\Events\Notification;

use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class NotificationUnviewedRecounted implements ShouldBroadcast
{
    /**
     * @var int
     */
    public $total;

    /**
     * @var mixed
     */
    public $owner;

    /**
     * Create new NotificationUnviewedRecounted instance
     * 
     * @param int $total  
     * @param mixed $owner
     */
    public function __construct($total, $owner)
    {
        $this->total = $total;
        $this->owner = $owner;
    }

    /**
     * Get the channels the event should be broadcast on.
     *
     * @return array
     */
    public function broadcastOn()
    {
        return ['notification.count'];
    }
}