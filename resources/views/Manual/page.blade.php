<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>被災者アセスメント調査票</title>
    <!-- Tell the browser to be responsive to screen width -->
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <!-- csrf token -->
    <meta content="{{ csrf_token() }}" name="csrf-token">

    <link rel="stylesheet" href="css/common/adminlte/app.css">

    @yield('css')

    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
    <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
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

<script src="js/common/adminlte/app.js"></script>

@yield('js')

</body>
</html>