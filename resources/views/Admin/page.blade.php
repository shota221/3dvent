@extends('common.adminlte.page', [
    'nav_left_menu' => 'Admin.left-menu',
    'nav_top_menu'  => 'Admin.top-menu',
    'nav_top_class' => 'navbar-white',
    'nav_left_class' => 'sidebar-dark-primary',
])

@section('title')
    @lang('messages.admin.project_administrator_page')
@stop

@section('role')
    @lang('messages.admin.project_administrator')
@stop

@section('page_js')
    @yield('js')
@stop

@section('page_css')
    {{-- <link rel="stylesheet" href="{{ mix('css/admin/app.css') }}"> --}}

    @yield('css')
@stop

@section('page_hidden')
@stop
