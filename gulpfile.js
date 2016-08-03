var gulp = require("gulp");
var elixir = require('laravel-elixir');
var shell = require('gulp-shell');
var task = elixir.Task;

elixir.extend('publishAssets', function() {
    new task('publishAssets', function() {
        return gulp.src("").pipe(shell("cd ../../../ && php artisan vendor:publish --provider=\"Inoplate\\Notification\\Providers\\NotificationServiceProvider\" --tag=public --force"));
    }).watch("resources/assets/**");
});

elixir(function(mix){
    mix.less('notifications/index.less', 'public/notifications')
       .coffee('notifications/index.coffee', 'public/notifications')
       .less('notifications/navbar.less', 'public/notifications')
       .coffee('notifications/navbar.coffee', 'public/notifications')
       .less('notifications/list.less', 'public/notifications')
       .coffee('notifications/list.coffee', 'public/notifications')
       .publishAssets();
})