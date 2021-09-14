@extends('common.adminlte.master')

@section('master_css')
    @yield('page_css')
@stop

@section('master_js')
    <script src="js/common/util/async.js"></script>
    <script src="js/common/util/form.js"></script>
    <script src="js/auth.js"></script>
    @yield('page_js')
@stop

@section('master_modal')
    @yield('page_modal')
@stop

@section('master_hidden')
    @yield('page_hidden')
@stop

@section('body')
    <div class="login-box" style="width: @yield('content_width');">

        {{-- Logo --}}
        <div class="login-logo">
            <a href="">
                <!-- <img src="{{ asset(config('adminlte.logo_img')) }}" height="50"> -->
                <!-- <b>Admin</b>LTE -->
                @yield('logo')
            </a>
        </div>

        {{-- Card Box --}}
        <div class="card">

            {{-- Card Body --}}
            <div class="card-body login-card-body">
                <p class="login-box-msg">@yield('content_header')</p>

                @yield('content')

                @yield('content_footer')
            </div>

        </div>

    </div>
@stop
