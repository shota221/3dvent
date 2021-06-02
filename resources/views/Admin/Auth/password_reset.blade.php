@extends('common.adminlte.static.content')

{{-- 
/***********************
    CSS
************************/ 
--}}
@section('css')
    <link rel="stylesheet" href="{{ mix('css/admin/app.css') }}">
@stop

{{-- 
/***********************
    JS
************************/ 
--}}
@section('js')
    <script src="{{ mix('js/admin/app.js') }}"></script>

    <script src="{{ mix('js/admin/password_reset.js') }}"></script>
@stop

{{-- 
/***********************
    TITLE
************************/ 
--}}
@section('title', '自治体ユーザーページ')

{{-- 
/***********************
    LOGO
************************/ 
--}}
@section('logo', '自治体ユーザーページ')

{{-- 
/***********************
    TITLE
************************/ 
--}}
@section('title_suffix', 'パスワード再設定')

{{-- 
/***********************
    CONTENT_HEADER
************************/ 
--}}
@section('content_header', 'パスワード再設定')

{{-- 
/***********************
    CONTENT
************************/ 
--}}

@section('content_width', '500px')
@section('content')
        <form class="form-horizontal" method=PUT action="{{ route_path('admin.auth.async.reset_password') }}"> 
            <input type="hidden" name="token" value="{{ $token }}" />

            <div class="box box-default box-solid no-border no-shadow">
                <div class="box-body">
                    <p>
                        <span class="required"></span>&nbsp;は必須です。
                    </p>
                    <table class="table dl-table">
                        <tbody>
                            <tr>
                                <th>登録メールアドレス<span class="required"></span></th>
                                <td height="50px">
                                    <input 
                                        type="text" 
                                        class="form-control" 
                                        name="email" 
                                        placeholder="xxx@yyy.com" 
                                        data-validation-types='["required"]' />
                                </td>
                            </tr>
                            <tr>
                                <th>新パスワード<span class="required"></span></th>
                                <td height="50px">
                                    <input 
                                        type="password" 
                                        class="form-control" 
                                        name="password" 
                                        placeholder="8文字以上で入力" 
                                        data-validation-types='["required"]' />
                                </td>
                            </tr>
                            <tr>
                                <th>パスワード確認<span class="required"></span></th>
                                <td height="50px">
                                    <input 
                                        type="password" 
                                        class="form-control" 
                                        name="passwordConfirm" 
                                        placeholder="確認のため入力"
                                        data-validation-types='["required"]' />
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <div class="box-footer">
                    <div class="row">
                        <div class="col-6">
                            <button 
                                type="submit" 
                                class="btn btn-block btn-primary btn-submit"
                                data-with-validation="true">再設定</button>
                        </div>
                        <div class="col-6">
                            <button class="btn btn-block btn-default btn-cancel">キャンセル</button>
                        </div>
                    </div>
                    
                </div>
            </div>
            
        </form>
@stop
