@extends('common.adminlte.static.page')


@section('page_css')
    @yield('css')
@stop

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
@section('title_suffix', 'ログイン')

{{-- 
/***********************
    CONTENT_HEADER
************************/ 
--}}
@section('content_header', 'ログイン')
