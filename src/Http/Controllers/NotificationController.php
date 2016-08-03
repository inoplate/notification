<?php

namespace Inoplate\Notification\Http\Controllers;

use Inoplate\Notifier\NotifRepository;
use Inoplate\Foundation\Http\Controllers\Controller;
use Inoplate\Foundation\App\Services\Events\Dispatcher as Events;
use Inoplate\Notification\Events\Notification\NotificationViewed;
use Inoplate\Foundation\App\Services\Bus\Dispatcher as Bus;
use Inoplate\Notification\Jobs\MarkNotificationAsViewed;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;

class NotificationController extends Controller
{
    protected $notifRepository;

    protected $events;

    public function __construct(NotifRepository $notifRepository, Events $events)
    {
        $this->notifRepository = $notifRepository;
        $this->events = $events;
    }

    public function getIndex(Request $request, Bus $bus)
    {
        $userId = $request->user()->id;
        $page = $request->input('page') ?: 1;

        $perPage = config('inoplate.notification.per_page', 10);
        $items = collect($this->notifRepository->get($userId, $page, $perPage));
        $total = $this->notifRepository->count($userId);

        $paginator = new LengthAwarePaginator($items, $total, $perPage, $page);
        $paginator->setPath('/admin/inoplate-notification/notifications');

        $items = $paginator->items();

        return $this->getResponse('inoplate-notification::notifications.index', ['notifications' => $paginator->toArray()]);
    }

    public function putMarkAsViewed(Request $request, Bus $bus)
    {
        $userId = $request->user()->id;
        $this->markAsViewed($bus, $userId);        
    }

    protected function markAsViewed(Bus $bus, $userId)
    {
        $bus->dispatch( new MarkNotificationAsViewed($userId));
    }
}