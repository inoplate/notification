<?php

namespace Inoplate\Notification\ModelObservers;

use Inoplate\Notification\Events\Notification\NewNotificationCreated;
use Inoplate\Foundation\App\Services\Events\Dispatcher as Events;

class NotificationObserver
{
    /**
     * @var Inoplate\Foundation\App\Services\Events\Dispatcher
     */
    protected $events;

    /**
     * Create new NotificationObserver instance
     * @param Events $events
     */
    public function __construct(Events $events)
    {
        $this->events = $events;
    }

    /**
     * Handle created event
     * 
     * @param  Eloquent $model
     * @return void
     */
    public function created($model)
    {
        $this->events->fire(new NewNotificationCreated($model));
    }
}