<?php

// Protected routes
// Only authenticated and authorized user can access this endpoints

$router->group(['prefix' => 'admin', 'middleware' => ['auth']], function($router){
    $router->model('notification', 'Inoplate\Notification\Notification');
    $router->get('inoplate-notification/notifications', ['uses' => 'NotificationController@getIndex', 'as' => 'notification.admin.notifications.index.get']);
    $router->put('inoplate-notification/notifications/mark-as-viewed', ['uses' => 'NotificationController@putMarkAsViewed', 'as' => 'notification.admin.notifications.mark-as-viewed.put']);
});