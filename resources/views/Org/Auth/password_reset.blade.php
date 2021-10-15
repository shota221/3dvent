@extends('common.adminlte.static.content')

{{-- 
/***********************
    CSS
************************/ 
--}}
@section('css')
    <link rel="stylesheet" href="{{ mix('css/org/app.css') }}">
@stop

{{-- 
/***********************
    JS
************************/ 
--}}
@section('js')
    <script src="{{ mix('js/org/app.js') }}"></script>
    <script src="/js/password_reset.js"></script>

@stop

{{-- 
/***********************
    TITLE
************************/ 
--}}
@section('title')
@lang('messages.organization_user_page')
@stop
{{-- 
/***********************
    LOGO
************************/ 
--}}
@section('logo')
@lang('messages.organization_user_page')
@stop

{{-- 
/***********************
    TITLE
************************/ 
--}}
@section('title_suffix')
@lang('messages.resetting_password')
@stop

{{-- 
/***********************
    CONTENT_HEADER
************************/ 
--}}
@section('content_header')
@lang('messages.resetting_password')
@stop
{{-- 
/***********************
    CONTENT
************************/ 
--}}

@section('content_width', '500px')
@section('content')
        <form name="password-reset"> 
            <input type="hidden" name="token" value="{{ $token }}" />

            <div class="box box-default box-solid no-border no-shadow">
                <div class="box-body">
                    <p>
                        <span class="required"></span>&nbsp;@lang('messages.is_required_item')
                    </p>
                    <table class="table dl-table">
                        <tbody>
                            <tr>
                                {{-- 組織コード --}}
                                <th>@lang('messages.organization_code')<span class="required"></span></th>
                                <td height="50px">
                                    <input 
                                        type="text" 
                                        class="form-control" 
                                        name="code" />
                                </td>
                            </tr>
                            <tr>
                                {{-- 登録メールアドレス --}}
                                <th>@lang('messages.registered_email')<span class="required"></span></th>
                                <td height="50px">
                                    <input 
                                        type="text" 
                                        class="form-control" 
                                        name="email" 
                                        placeholder="xxx@yyy.com" />
                                </td>
                            </tr>
                            <tr>
                                {{-- 新パスワード --}}
                                <th>@lang('messages.new_password')<span class="required"></span></th>
                                <td height="50px">
                                    <input 
                                        type="password" 
                                        class="form-control" 
                                        name="password" 
                                        placeholder="@lang('messages.password_placeholder')" />
                                </td>
                            </tr>
                            <tr>
                                {{-- パスワード(確認用) --}}
                                <th>@lang('messages.password_confirmation')<span class="required"></span></th>
                                <td height="50px">
                                    <input 
                                        type="password" 
                                        class="form-control" 
                                        name="password-confirmation" />
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <div class="box-footer">
                    <div class="row">
                        <div class="col-12">
                            <button
                                id="async-password-reset"
                                type="submit" 
                                class="btn btn-block btn-primary btn-submit"
                                data-url="{{ route_path('org.auth.async.reset_password') }}"
                                data-method="PUT"
                            >
                                {{-- 再設定 --}}
                                @lang('messages.resetting')
                            </button>
                        </div>
                    </div>
                </div>
            </div>
            
        </form>
@stop
