@extends('common.adminlte.master')

@section('master_css')
    @yield('page_css')
@stop

@section('master_js')
    @yield('page_js')
@stop

@section('master_modal')
    @yield('page_modal')
@stop

@section('master_hidden')
    @yield('page_hidden')
@stop

@section('classes_body', 'hold-transition sidebar-mini layout-fixed text-sm')

@section('body')
    <div class="wrapper">

        {{-- Top Navbar --}}
        @include('common.adminlte.partials.navbar', [
            'menu' => $nav_top_menu ?? null,
            'color_class' => $nav_top_class ?? null
        ])

        {{-- Left Main Sidebar --}}
        @include('common.adminlte.partials.left-sidebar', [
            'menu' => $nav_left_menu ?? null,
            'color_class' => $nav_left_class ?? null
        ])

        {{-- Content Wrapper --}}
        <!-- <div class="content-wrapper"> -->
        <!-- Content Wrapper. Contains page content -->
        <div 
            id="page" 
            class="content-wrapper"
            data-page-transactional="{{ isset($transactional) ? true : false }}"
            >


            {{-- Content Header --}}
            <div class="content-header">
                <div class="container-fluid">
                    @yield('content_header')
                </div>
            </div>

            {{-- Main Content --}}
            <div class="content">
                @yield('content')
            </div>

        </div>

        {{-- Spiner --}}
        @include('common.adminlte.static.spiner')

        {{-- Footer --}}
        @include('common.adminlte.partials.footer')

    </div>
@stop


