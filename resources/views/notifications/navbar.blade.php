<li class="dropdown notifications-menu" data-websocket="ws://inoplate.dev:{{ config('inoplate.velatchet.zmq.port') }}">
    <a href="#" class="dropdown-toggle" data-toggle="dropdown" aria-expanded="true">
      <i class="fa fa-bell-o"></i>
      <span class="label label-warning">10</span>
    </a>
    <ul class="dropdown-menu">
      <li class="header"></li>
      <li>
          <ul class="notifications-wrapper">
              <li class="loader">
                  <a href="{{ route('notification.admin.notifications.index.get') }}"></a>
              </li>
          </ul>
      </li>
      <li class="footer">
          <a href="{{ route('notification.admin.notifications.index.get') }}">
              {{ trans('inoplate-notification::labels.notification.view_all') }}
          </a>
      </li>
    </ul>
</li>

@addCss([
    'vendor/inoplate-notification/notifications/navbar.css'
])

@addJs([
    'http://autobahn.s3.amazonaws.com/js/autobahn.min.js',
    'vendor/inoplate-foundation/vendor/within-viewport/withinviewport.js',
    'vendor/inoplate-foundation/vendor/within-viewport/jquery.withinviewport.js',
    'vendor/inoplate-notification/notifications/navbar.js'
])