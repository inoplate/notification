<?php

namespace Inoplate\Notification;

use Roseffendi\Authis\Resource;
use Roseffendi\Authis\User;
use Inoplate\Notifier\Laravel\Notification as BaseNotification;

class Notification extends BaseNotification implements Resource
{
    /**
     * Define notification user relationship
     * 
     * @return Model
     */
    public function user()
    {
        return $this->belongsTo('Inoplate\Account\User');
    }

    /**
     * Determine if resource belongs to user
     * 
     * @param  User    $user
     * @return boolean       [description]
     */
    public function isBelongsTo(User $user)
    {
        return $user->id() === $this->user_id;
    }
}