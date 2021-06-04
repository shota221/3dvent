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

    <script src="{{ mix('js/admin/auth.js') }}"></script>
@stop

{{-- 
/***********************
    TITLE
************************/ 
--}}
@section('title', '管理者ページ')

{{-- 
/***********************
    LOGO
************************/ 
--}}
@section('logo', '管理者ページ')

{{-- 
/***********************
    CONTENT
************************/ 
--}}
@section('content')
    <form action="{{ guess_route_path('login') }}" method="post">
        {{ csrf_field() }}

        @if ($errors->has('global'))
            <div class="invalid-feedback" style="display: block;">
                <strong>{{ $errors->first('global') }}</strong>
            </div>
        @endif

        {{-- Email field --}}
        <div class="input-group mb-3">
            <input type="email" name="email" class="form-control {{ $errors->has('email') ? 'is-invalid' : '' }}"
                   value="{{ old('email') }}" placeholder="メールアドレス" autofocus>
            <div class="input-group-append">
                <div class="input-group-text">
                    <span class="fas fa-envelope"></span>
                </div>
            </div>
            @if ($errors->has('email'))
                <div class="invalid-feedback">
                    <strong>{{ $errors->first('email') }}</strong>
                </div>
            @endif
        </div>

        {{-- Password field --}}
        <div class="input-group mb-3">
            <input type="password" name="password" class="form-control {{ $errors->has('password') ? 'is-invalid' : '' }}"
                   placeholder="パスワード">
            <div class="input-group-append">
                <div class="input-group-text">
                    <span class="fas fa-lock"></span>
                </div>
            </div>
            @if ($errors->has('password'))
                <div class="invalid-feedback">
                    <strong>{{ $errors->first('password') }}</strong>
                </div>
            @endif
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
                <span class="btn btn-default btn-block apply-password-reset">パスワードを忘れた方</span>
            </div>
            <div class="col-5">
                <button type="submit" class="btn btn-primary btn-block btn-submit">サインイン</button>
            </div>
        </div>

    </form>
@stop

@section('modal')
    {{-- PASSWORD RESET APPLY MODAL --}}

    @component('components.modal', [
        'id'    => 'modal-apply-password-reset',
        'form'  => [ 'method' => 'POST', 'action' => guess_route_path('auth.async.apply_password_reset') ],
        'sync'  => true,
    ])
        @slot('title')
            パスワード再設定メール送信
        @endslot

        @slot('content')
            <tr>
                <th width="160px">登録メールアドレス<span class="required"></span></th>
                <td>
                    <input 
                        class="form-control" 
                        type="text" 
                        name="email" 
                        placeholder="xxx@yyy.com" 
                        data-validation-types='["required"]' 
                        data-validation-title="メールアドレス" />
                </td>
            </tr>   
        @endslot
    @endcomponent
@stop