<?php

namespace Inoplate\Notification\Http\Controllers;

use Inoplate\Notifier\NotifRepository;
use Inoplate\Foundation\Http\Controllers\Controller;
use Inoplate\Foundation\App\Services\Events\Dispatcher as Events;
use Inoplate\Notification\Events\Notification\NotificationViewed;
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

    public function getIndex(Request $request)
    {
        $userId = $request->user()->id;
        $page = $request->input('page') ?: 1;

        $perPage = config('inoplate.notification.per_page', 10);
        $items = collect($this->notifRepository->get($userId, $page, $perPage));
        $total = $this->notifRepository->count($userId);

        $paginator = new LengthAwarePaginator($items, $total, $perPage, $page);
        $paginator->setPath('/admin/inoplate-notification/notifications');

        $items = $paginator->items();
        $unviewed = array_filter($items, function($item){
            return $item['viewed'] == 0;
        });

        // $this->markAsViewed($unviewed);

        return $this->getResponse('inoplate-notification::notifications.index', ['notifications' => $paginator->toArray()]);
    }

    public function putMarkAsViewed(Request $request, $notification)
    {
        if($notification['viewed'] == 0) {
            $notification['viewed'] = 1;
            $this->notifRepository->update($notification['id'], $notification->toArray());
            $this->events->fire(new NotificationViewed($this->notifRepository, $notification['user_id']));
        }
    }
}