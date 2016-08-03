<?php

namespace Inoplate\Notification\Jobs;

use Inoplate\Foundation\Domain\Command;

class MarkNotificationAsViewed
{
    /**
     * @var mixed
     */
    public $userId;

    /**
     * Create new MarkNotificationAsViewed instance
     * 
     * @param mixed $userId
     */
    public function __construct($userId)
    {
        $this->userId = $userId;
    }
}