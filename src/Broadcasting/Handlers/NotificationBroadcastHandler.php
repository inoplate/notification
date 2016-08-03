<?php

namespace Inoplate\Notification\Broadcasting\Handlers;

use Illuminate\Contracts\Auth\Guard;

class NotificationBroadcastHandler
{
    /**
     * @var Illuminate\Contracts\Auth\Guard
     */
    protected $auth;

    /**
     * Create new NotificationBroadcastHandler instance
     * @param Guard $auth
     */
    public function __construct(Guard $auth)
    {
        $this->auth = $auth;
    }

    /**
     * Handle notification broadcasting
     * 
     * @param  string $topic
     * @param  array  $clients
     * @param  array  $payloads
     * 
     * @return void
     */
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

    /**
     * Handle notification broadcasting
     * 
     * @param  string $topic
     * @param  array  $clients
     * @param  array  $payloads
     * 
     * @return void
     */
    public function handleNotificationCount($topic, $clients, $payloads)
    {
        $blaclisted = [];
        $whitelisted = [];

        $owner = $payloads['owner'];
        $count = $payloads['total'];

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