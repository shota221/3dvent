<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>

    {{-- Base Meta Tags --}}
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    {{-- Custom Meta Tags --}}
    @yield('meta_tags')

    {{-- Title --}}
    <title>
        @yield('title') | @yield('title_suffix')
    </title>

    {{-- Base Stylesheets --}}
    <link rel="stylesheet" href="{{ mix('css/common/adminlte/app.css') }}">

    {{-- Custom Stylesheets (post AdminLTE) --}}
    @yield('master_css')

    {{-- Favicon --}}
    <link rel="shortcut icon" href="{{ asset('favicons/favicon.ico') }}" />

</head>

<body class="@yield('classes_body')">
    <!-- NOSCIPT -->
    <noscript>
        <div style="color:red;background-color:#FFF;padding:10px;margin:10px;border:1px solid red;">
            Javascriptを有効にしてください。
        </div>
    </noscript>

    {{-- Body Content --}}
    @yield('body')

    {{-- Modal Content --}}
    @yield('master_modal')

    {{-- Hidden Content --}}
    @yield('master_hidden')

    {{-- Base Scripts --}}
    <script src="{{ mix('js/common/adminlte/app.js') }}"></script>

    {{-- Custom Scripts --}}
    @yield('master_js')

</body>

</html>
