<?php

namespace Inoplate\Notification\Broadcasting\Handlers;

use Illuminate\Contracts\Auth\Guard;

class NotificationBroadcastHandler
{
    protected $auth;

    public function __construct(Guard $auth)
    {
        $this->auth = $auth;
    }

    public function handleNotification($topic, $clients, $payloads)
    {
        $blaclisted = [];
        $whitelisted = [];

        $notification = $payloads['notification'];

        foreach ($clients as $client) {
            $client->session->start();

            $userId = $client->session->get($this->auth->getName());
            $sessionId = $client->WAMP->sessionId;

            if($userId == $notification['user_id']) {
                $whitelisted[] = $sessionId;
            }else {
                $blaclisted[] = $sessionId;
            }
        }

        $topic->broadcast(compact('notification'), $blaclisted, $whitelisted);
    }

    public function handleNotificationCount($topic, $clients, $payloads)
    {
        $blaclisted = [];
        $whitelisted = [];

        $owner = $payloads['owner'];
        $count = $payloads['totalUnviewed'];

        foreach ($clients as $client) {
            $client->session->start();

            $userId = $client->session->get($this->auth->getName());
            $sessionId = $client->WAMP->sessionId;

            if($userId == $owner) {
                $whitelisted[] = $sessionId;
            }else {
                $blaclisted[] = $sessionId;
            }
        }

        $topic->broadcast(compact('count'), $blaclisted, $whitelisted);
    }
}