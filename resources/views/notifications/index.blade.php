@extends('inoplate-foundation::layouts.default')

@php($title = trans('inoplate-notification::labels.notification.title'))
@php($subtitle = trans('inoplate-notification::labels.notification.sub_title'))

@section('breadcrumbs')
@endsection

@section('content')
    @include('inoplate-foundation::partials.content-header')
    <section class="content">
        <div class="row">
            <div class="col-md-12">
                @include('inoplate-notification::notifications.list')
            </div>
        </div>
    </section>
@endsection

@addCss([
    'vendor/inoplate-notification/notifications/index.css'
])

@addJs([
    'vendor/inoplate-notification/vendor/autobahn.min.js',
    'vendor/inoplate-notification/notifications/index.js'
])