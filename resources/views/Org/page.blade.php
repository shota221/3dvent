@extends('common.adminlte.page', [
    'nav_left_menu'     => 'Org.left-menu',
    'nav_top_menu'      => 'Org.top-menu',
    'nav_top_class'     => 'navbar-light navbar-white',
    'nav_left_class'    => 'sidebar-dark-primary',
])

@section('title')
@lang('messages.organization_user_page')
@stop

@section('role')
@lang('messages.organization_user')
@stop

@section('page_js')
    <script src="{{ mix('js/org/app.js') }}"></script>
    <script src="js/common/util/form.js"></script>
    <script src="js/common/util/async.js"></script>
    @yield('js')
@stop

@section('page_css')
    <link rel="stylesheet" href="{{ mix('css/org/app.css') }}">

    @yield('css')
@stop

@section('page_modal')
    @yield('modal')

@stop

@section('page_hidden')
@stop

