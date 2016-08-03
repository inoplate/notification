<?php 

namespace Inoplate\Notification\Listeners;

use Inoplate\Notification\Infrastructure\Repositories\Notification as NotificationRepository;
use Inoplate\Notification\Events\Notification\NotificationWasMarkedAsUnviewed;
use Inoplate\Notification\Events\Notification\NotificationUnviewedRecounted;
use Inoplate\Foundation\App\Services\Events\Dispatcher as Events;

class BroadcastNotificationRecounted
{
    /**
     * @var Inoplate\Foundation\App\Services\Events\Dispatcher
     */
    protected $events;

    /**
     * @var Inoplate\Notification\Infrastructure\Repositories\Notification
     */
    protected $notificationRepository;

    /**
     * Create new BroadcastNotificationRecounted instance
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
     * Handle event
     * 
     * @param  NotificationWasMarkedAsUnviewed $event
     * @return void
     */
    public function handle(NotificationWasMarkedAsUnviewed $event)
    {
        $userId = $event->userId;
        $total = $this->notificationRepository->countUnviewedByUserId($userId);

        $this->events->fire([ new NotificationUnviewedRecounted($total, $userId) ]);
    }
}