@extends('Admin.page')

{{--
/***********************
CSS
************************/
--}}
@section('css')
@stop

{{--
/***********************
JS
************************/
--}}
@section('js')
@stop
@section('content')
    <div class="container-fluid">
        @isset ( $alert_message->registration )
            <div class="alert alert-success alert-dismissible" role="alert">
                <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span
                        aria-hidden="true">&times;</span></button>
                <strong>{{ $alert_message->registration }}</strong>
            </div>
        @endisset
        @isset ( $alert_message->duplicate_entry)
        <div class="alert alert-warning alert-dismissible">
            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
            <h5><i class="icon fas fa-exclamation-triangle"></i>{{ $alert_message->duplicate_entry }}</h5>
            既存ユーザーにシステム管理者権限を追加したい場合は、管理者にお問い合わせください。
          </div>
        @endisset
        <div class="card card-default color-pallete-box">
            <div class="card-header">
                <h3>システム管理者管理</h3>
            </div>
                <form action="{{ route('admin.admin_user.confirmation') }}" method="POST">
                    @csrf
                    <div class="card-body">
                        <h4>システム管理者新規作成</h4>
                        <p>
                            <span class="required"></span>&nbsp;は必須です。
                        </p>
                    <div class="form-group">
                        <label for="last_name">姓<span class="required"></span></label>
                        <div>
                            <input class="form-control" type="text" name="last_name" id="last_name" placeholder='' 
                            value="{{ old('last_name') }}" required>
                        </div>
                        @isset($error_message->last_name)
                            <small class="text-danger">{{ $error_message->last_name }}</small>
                        @endisset
                    </div>
                    <div class="form-group">
                        <label for="first_name">名<span class="required"></span></label>
                        <div>
                            <input class="form-control" type="text" name="first_name" id="first_name" placeholder=''
                            value="{{ old('first_name') }}" required>
                        </div>
                        @isset($error_message->first_name)
                            <small class="text-danger">{{ $error_message->first_name }}</small>
                        @endisset
                    </div>
                    <div class="form-group">
                        <label for="last_name_kana">セイ<span class="required"></span></label>
                        <div>
                            <input class="form-control" type="text" name="last_name_kana" id="last_name_kana" placeholder=''
                            value="{{ old('last_name_kana') }}" required>
                        </div>
                        @isset($error_message->last_name_kana)
                            <small class="text-danger">{{ $error_message->last_name_kana }}</small>
                        @endisset
                    </div>
                    <div class="form-group">
                        <label for="first_name_kana">メイ<span class="required"></span></label>
                        <div>
                            <input class="form-control" type="text" name="first_name_kana" id="first_name_kana"
                                placeholder='' value="{{ old('first_name_kana') }}" required>
                        </div>
                        @isset($error_message->first_name_kana)
                            <small class="text-danger">{{ $error_message->first_name_kana }}</small>
                        @endisset
                    </div>
                    <div class="form-group">
                        <label for="team">所属</label>
                        <div>
                            <input class="form-control" type="text" name="team" id="team" placeholder='' 
                            value="{{ old('team') }}">
                        </div>
                        @isset($error_message->team)
                            <small class="text-danger">{{ $error_message->team }}</small>
                        @endisset
                    </div>
                    <div class="form-group">
                        <label for="email">メールアドレス<span class="required"></span></label>
                        <div>
                            <input class="form-control" type="email" name="email" id="email" placeholder='' 
                            value="{{ old('email') }}" required>
                        </div>
                        @isset($error_message->email)
                            <small class="text-danger">{{ $error_message->email }}</small>
                        @endisset
                    </div>
                    <div class="form-group">
                        <label for="phone">電話番号<span class="required"></span></label>
                        <div>
                            <input class="form-control" type="text" name="phone" id="phone" placeholder='' 
                            value="{{ old('phone') }}" required>
                        </div>
                        @isset($error_message->phone)
                            <small class="text-danger">{{ $error_message->phone }}</small>
                        @endisset
                    </div>
                    <div class="form-group">
                        <label for="password">パスワード<span class="required"></span></label>
                        <div>
                            <input class="form-control" type="password" name="password" id="password" placeholder='8文字以上で入力'
                                required>
                        </div>
                        @isset($error_message->password)
                            <small class="text-danger">{{ $error_message->password }}</small>
                        @endisset
                    </div>
                    <div class="form-group">
                        <label for="password_confirmation">パスワード（確認用）<span class="required"></span></label>
                        <div>
                            <input class="form-control" type="password" name="password_confirmation"
                                id="password_confirmation" placeholder='入力確認' required>
                        </div>
                    </div>
                    </div>
                    <div class="card-footer">
                    <div class="form-group">
                        <button class="btn btn-small btn-success btn-block">確認</button>
                    </div>
                    <div class="form-group">
                        <a class="btn btn-small btn-secondary btn-block"
                            href="{{ route('admin.admin_user.index') }}">システム管理者一覧に戻る</a>
                    </div>
                    </div>
                </form>
        </div>
    </div>


@stop
