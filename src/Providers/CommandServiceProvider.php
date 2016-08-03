<?php

namespace Inoplate\Notification\Providers;

use Inoplate\Foundation\Providers\CommandServiceProvider as ServiceProvider;

class CommandServiceProvider extends ServiceProvider
{
    /**
     * Commands to register
     * 
     * @var array
     */
    protected $commands = [
        'Inoplate\Notification\Jobs\MarkNotificationAsViewed' => 
            'Inoplate\Notification\Handlers\Job\MarkNotificationAsViewedHandler@handle',
    ];
}