@extends('common.adminlte.page', [
    'nav_left_menu'     => 'Org.left-menu',
    'nav_top_menu'      => 'Org.top-menu',
    'nav_top_class'     => 'navbar-light navbar-white',
    'nav_left_class'    => 'sidebar-dark-primary',
])

@section('title', ' 組織ユーザーページ')

@section('role', '組織ユーザー')

@section('page_js')
    <script src="{{ mix('js/org/app.js') }}"></script>
    @yield('js')
@stop

@section('page_css')
    <link rel="stylesheet" href="{{ mix('css/org/app.css') }}">

    @yield('css')
@stop


