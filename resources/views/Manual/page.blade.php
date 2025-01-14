<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>MicroVent操作マニュアル</title>
    <!-- Tell the browser to be responsive to screen width -->
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <!-- csrf token -->
    <meta content="{{ csrf_token() }}" name="csrf-token">

    <link rel="stylesheet" href="{{ mix('css/common/adminlte/app.css') }}">

    @yield('css')

</head>

<body>
<!-- NOSCIPT -->
<noscript>
    <div style="color:red;background-color:#FFF;padding:10px;margin:10px;border:1px solid red;">
        Javascriptを有効にしてください。
    </div>
</noscript>

<div class="container my-5">
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-body" id="form-content">
                <div class="lead my-3 font-weight-bold text-center">@yield('title')</div>
                    @yield('parent_content')
                </div>
            </div>
        </div>
    </div>
</div>

<script src="{{ mix('js/common/adminlte/app.js') }}"></script>

@yield('js')
<script src="/js/manual/index.js"></script>
<script src="/js/common/util/form.js"></script>
<script src="/js/common/util/async.js"></script>

</body>
</html>
