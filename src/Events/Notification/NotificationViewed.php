<?php

namespace Inoplate\Notification\Events\Notification;

use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class NotificationViewed implements ShouldBroadcast
{
    /**
     * Notification owner
     * 
     * @var mixed
     */
    public $owner;

    /**
     * Total unviewed notification
     * 
     * @var int
     */
    public $totalUnviewed;

    /**
     * Create new NotificationViewed instance
     * 
     * @param array $notification
     */
    public function __construct($notificationRepository, $owner)
    {
        $this->totalUnviewed = $notificationRepository->countUnviewedByUserId($owner);
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