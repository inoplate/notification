<li class="dropdown notifications-menu" data-websocket="{{ config('inoplate.notification.ws_domain') }}:{{ config('inoplate.velatchet.zmq.port') }}">
    <a href="#" class="dropdown-toggle" data-toggle="dropdown" aria-expanded="true">
      <i class="fa fa-bell-o"></i>
      <span class="label label-danger notif-count">{{$unviewedNotification}}</span>
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
    'vendor/inoplate-notification/vendor/autobahn.min.js',
    'vendor/inoplate-foundation/vendor/within-viewport/withinviewport.js',
    'vendor/inoplate-foundation/vendor/within-viewport/jquery.withinviewport.js',
    'vendor/inoplate-notification/notifications/navbar.js'
])