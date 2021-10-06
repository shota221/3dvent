@extends('common.adminlte.static.page')


@section('page_css')
    @yield('css')
@stop

@section('content_width', '500px')

@section('page_js')
    @yield('js')
@stop

@section('page_modal')
    @yield('modal')
@stop

@section('page_hidden')
    @yield('hidden')
@stop


@section('classes_body', 'hold-transition login-page sidebar-mini text-sm')

{{-- 
/***********************
    TITLE
************************/ 
--}}
@section('title_suffix')
@lang('messages.login')
@stop
{{-- 
/***********************
    CONTENT_HEADER
************************/ 
--}}
@section('content_header')
@lang('messages.login')
@stop
