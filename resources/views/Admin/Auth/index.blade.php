@extends('common.adminlte.static.login')

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
@stop

{{-- 
/***********************
    TITLE
************************/ 
--}}
@section('title')
@lang('messages.project_administrator_page')
@stop
{{-- 
/***********************
    LOGO
************************/ 
--}}
@section('logo')
@lang('messages.project_administrator_page')
@stop
{{-- 
/***********************
    CONTENT
************************/ 
--}}
@section('content')
    <form>
        {{ csrf_field() }}

        {{-- Name field --}}
        <input type="text" name="accountOrPassword" hidden>
        <div class="form-group">
            <div>
                <input name="name" class="form-control" placeholder="@lang('messages.account')">
            </div>
        </div>

        {{-- Password field --}}
        <div class="form-group">
            <div>
                <input name="password" type="password" class="form-control" placeholder="@lang('messages.password')">
            </div>
        </div>

        {{-- Login field --}}
        <div class="row">
            <div class="col-7">
                <div class="checkbox icheck">
                    <input type="checkbox" name="remember" id="remember" value="1">
                    <label for="remember" class="no-margin">Remember me</label>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-7">
                <span 
                    id="show-password-reset-modal"
                    class="btn btn-default btn-block apply-password-reset">
                    @lang('messages.forgot_password')
                </span>
            </div>
            <div class="col-5">
                <button 
                    id = "login"
                    type="submit" 
                    class="btn btn-primary btn-block btn-submit"
                    data-url="{{ guess_route_path('login') }}"
                    data-method="POST"
                >
                @lang('messages.sign_in')
                </button>
            </div>
        </div>
    </form>
    <div id="overlay">
        <div class="cv-spinner">
            <span class="spinner"></span>
        </div>
    </div>
@stop

@section('modal')
    {{-- PASSWORD RESET APPLY MODAL --}}

    @component('components.modal', [
        'id'    => 'apply-password-reset-modal',
        'form'  => [ 
            'method' => 'POST', 
            'action' => guess_route_path('auth.async.apply_password_reset') ,
            'name'   => 'apply-password-reset'
        ],
    ])
        @slot('title')
            {{-- パスワード再設定メール送信 --}}
            @lang('messages.send_password_reset_email')
        @endslot

        @slot('content')
            <tr>
                {{-- 組織コード --}}
                <th width="160px">@lang('messages.organization_code')<span class="required"></span></th>
                <td>
                    <input 
                        class="form-control mb-2" 
                        type="text" 
                        name="code"/>
                </td>
                {{-- 登録メールアドレス --}}
                <th width="160px">@lang('messages.registered_email')<span class="required"></span></th>
                <td>
                    <input 
                        class="form-control" 
                        type="text" 
                        name="email" 
                        placeholder="xxx@yyy.com"/>
                </td>
            </tr>   
        @endslot
    @endcomponent
@stop
