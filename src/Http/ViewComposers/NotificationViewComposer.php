<?php

namespace Inoplate\Notification\Http\ViewComposers;

use Inoplate\Notifier\NotifRepository;
use Illuminate\Contracts\Auth\Guard;
use Illuminate\View\View;

class NotificationViewComposer
{
    /**
     * @var Inoplate\Notifier\NotifRepository
     */
    protected $notifRepository;

    /**
     * @var Illuminate\Contracts\Auth\Guard
     */
    protected $auth;

    /**
     * Create new NotificationViewComposer instance
     * 
     * @param NotifRepository $notifRepository
     * @param Guard           $auth
     */
    public function __construct(NotifRepository $notifRepository, Guard $auth)
    {
        $this->notifRepository = $notifRepository;
        $this->auth = $auth;
    }

    /**
     * Composer notification view composer
     * 
     * @param  View   $view
     * @return response
     */
    public function compose(View $view)
    {
        $userId = $this->auth->user()->id;
        $unviewedNotification = $this->notifRepository->countUnviewedByUserId($userId) ?: '';

        $view->with('unviewedNotification', $unviewedNotification);
    }
}