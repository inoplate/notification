var elixir = require('laravel-elixir');

elixir(function(mix){
    mix.less('notifications/index.less', 'public/notifications')
       .coffee('notifications/index.coffee', 'public/notifications')
       .less('notifications/navbar.less', 'public/notifications')
       .coffee('notifications/navbar.coffee', 'public/notifications')
       .less('notifications/list.less', 'public/notifications')
       .coffee('notifications/list.coffee', 'public/notifications');
})