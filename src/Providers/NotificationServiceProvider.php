<?php

namespace Inoplate\Notification\Providers;

use Inoplate\Notification\Notification;
use Inoplate\Notification\ModelObservers;
use Inoplate\Foundation\Providers\AppServiceProvider as ServiceProvider;

class NotificationServiceProvider extends ServiceProvider
{   
    /**
     * @var array
     */
    protected $providers = [
        \Inoplate\Velatchet\VelatchetServiceProvider::class,
        \Inoplate\Notifier\Laravel\NotifierServiceProvider::class,
        \Inoplate\Notification\Providers\RouteServiceProvider::class,
        \Inoplate\Notification\Providers\CommandServiceProvider::class,
        \Inoplate\Notification\Providers\EventServiceProvider::class,
    ];

    /**
     * Boot package
     * 
     * @return void
     */
    public function boot()
    {
        $this->loadPublic();
        $this->loadView();
        $this->loadTranslation();
        $this->loadConfiguration();
        $this->registerBroadcastTopicHandler();

        $this->app['authis']->intercept('notification.admin.notifications.mark-as-viewed.put', function($user, $ability, $resource) {            
            return $resource->isBelongsTo($user);
        });

        $events = $this->app['Inoplate\Foundation\App\Services\Events\Dispatcher'];
        Notification::Observe(new ModelObservers\NotificationObserver($events));

        view()->composer(
            'inoplate-notification::notifications.navbar',
            'Inoplate\Notification\Http\ViewComposers\NotificationViewComposer'
        );
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        parent::register();
        $this->app->bind('Inoplate\Notifier\NotifRepository', 
            'Inoplate\Notification\Infrastructure\Repositories\Notification');
    }

    protected function registerBroadcastTopicHandler()
    {
        $this->app['ratchet.handlers']->register('notification', 
            'Inoplate\Notification\Broadcasting\Handlers\NotificationBroadcastHandler@handleNotification');

        $this->app['ratchet.handlers']->register('notification.count', 
            'Inoplate\Notification\Broadcasting\Handlers\NotificationBroadcastHandler@handleNotificationCount');
    }

    /**
     * Publish public assets
     * @return void
     */
    protected function loadPublic()
    {
        $this->publishes([
            __DIR__.'/../../public' => public_path('vendor/inoplate-notification'),
        ], 'public');
    }

    /**
     * Load package's views
     * 
     * @return void
     */
    protected function loadView()
    {
        $this->loadViewsFrom(__DIR__.'/../../resources/views', 'inoplate-notification');

        $this->publishes([
            __DIR__.'/../../resources/views' => resource_path('views/vendor/inoplate-notification'),
        ], 'views');
    }

    /**
     * Load packages's translation
     * 
     * @return void
     */
    protected function loadTranslation()
    {
        $this->loadTranslationsFrom(__DIR__.'/../../resources/lang', 'inoplate-notification');
    }

    /**
     * Load package configuration
     * 
     * @return void
     */
    protected function loadConfiguration()
    {
        $this->mergeConfigFrom(
            __DIR__.'/../../config/notification.php', 'inoplate.notification'
        );

        $this->publishes([
            __DIR__.'/../../config/notification.php' => config_path('inoplate/notification.php'),
        ], 'config');
    }
}