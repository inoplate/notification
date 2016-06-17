<?php

namespace Inoplate\Notification\ModelObservers;

use Inoplate\Notification\Events\Notification\NewNotificationCreated;
use Inoplate\Foundation\App\Services\Events\Dispatcher as Events;

class NotificationObserver
{
    protected $events;

    public function __construct(Events $events)
    {
        $this->events = $events;
    }

    public function created($model)
    {
        $this->events->fire(new NewNotificationCreated($model));
    }
}