<div class="notifications-container" data-websocket="ws://inoplate.dev:{{ config('inoplate.velatchet.zmq.port') }}">
    <ul class="notifications-wrapper"></ul>
    <div class="loader">
        <a href="{{ route('notification.admin.notifications.index.get') }}"></a>
    </div>
</div>

@addCss([
    'vendor/inoplate-notification/notifications/list.css'
])

@addJs([
    'vendor/inoplate-foundation/vendor/within-viewport/withinviewport.js',
    'vendor/inoplate-foundation/vendor/within-viewport/jquery.withinviewport.js',
    'vendor/inoplate-notification/notifications/list.js'
])