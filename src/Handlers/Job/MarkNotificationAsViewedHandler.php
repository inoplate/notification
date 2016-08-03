<?php

namespace Inoplate\Notification\Handlers\Job;

use Inoplate\Notification\Infrastructure\Repositories\Notification as NotificationRepository;
use Inoplate\Notification\Jobs\MarkNotificationAsViewed;
use Inoplate\Notification\Events\Notification\NotificationWasMarkedAsUnviewed;
use Inoplate\Foundation\App\Services\Events\Dispatcher as Events;

class MarkNotificationAsViewedHandler
{
    /**
     * @var Inoplate\Notification\Infrastructure\Repositories\Notification
     */
    protected $notificationRepository;

    /**
     * @var Inoplate\Foundation\App\Services\Events\Dispatcher
     */
    protected $events;

    /**
     * Create new MarkNotificationAsViewedHandler instance
     * 
     * @param Events                 $events                 
     * @param NotificationRepository $notificationRepository
     */
    public function __construct(Events $events, NotificationRepository $notificationRepository)
    {
        $this->events = $events;
        $this->notificationRepository = $notificationRepository;
    }

    /**
     * Handle job
     * 
     * @param  MarkNotificationAsViewed $job
     * @return void
     */
    public function handle(MarkNotificationAsViewed $job)
    {
        $userId = $job->userId;
        $this->notificationRepository->setNotificationAsViewed($userId);

        $this->events->fire([ new NotificationWasMarkedAsUnviewed($userId) ]);
    }
}