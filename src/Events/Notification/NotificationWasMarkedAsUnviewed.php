<?php

namespace Inoplate\Notification\Events\Notification;

class NotificationWasMarkedAsUnviewed
{
    /**
     * @var mixed
     */
    public $userId;

    /**
     * Create new NotificationWasMarkedAsUnviewed instance
     * 
     * @param mixed $userId
     */
    public function __construct($userId)
    {
        $this->userId = $userId;
    }
}