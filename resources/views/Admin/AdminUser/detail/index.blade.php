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
        @isset ( $alert_message->update)
        <div class="alert alert-success alert-dismissible" role="alert">
            <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            <strong>{{  $alert_message->update }}</strong> 
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
                <form action="{{ route('admin.admin_user.detail.confirmation',['id' => $user->id]) }}" method="POST">
                    @csrf
                    <div class="card-body">
                        <h4>システム管理者詳細・変更</h4>
                        <p>
                            <span class="required"></span>&nbsp;は必須です。
                        </p>
                    <div class="form-group">
                        <label for="last_name">姓<span class="required"></span></label>
                        <input class="form-control" type="text" name="last_name" id="last_name" placeholder='' required value="{{$user->last_name}}">
                        @isset($error_message->last_name)
                            <small class="text-danger">{{ $error_message->last_name }}</small>
                        @endisset
                    </div>
                    <div class="form-group">
                        <label for="first_name">名<span class="required"></span></label>
                        <input class="form-control" type="text" name="first_name" id="first_name" placeholder='' required value="{{$user->first_name}}">
                        @isset($error_message->first_name)
                            <small class="text-danger">{{ $error_message->first_name }}</small>
                        @endisset
                    </div>
                    <div class="form-group">
                        <label for="last_name_kana">セイ<span class="required"></span></label>
                        <input class="form-control" type="text" name="last_name_kana" id="last_name_kana" placeholder=''
                            required value="{{$user->last_name_kana}}">
                        @isset($error_message->last_name_kana)
                            <small class="text-danger">{{ $error_message->last_name_kana }}</small>
                        @endisset
                    </div>
                    <div class="form-group">
                        <label for="first_name_kana">メイ<span class="required"></span></label>
                        <input class="form-control" type="text" name="first_name_kana" id="first_name_kana" placeholder=''
                            required value="{{$user->first_name_kana}}">
                        @isset($error_message->first_name_kana)
                            <small class="text-danger">{{ $error_message->first_name_kana }}</small>
                        @endisset
                    </div>
                    <div class="form-group">
                        <label for="team">所属</label>
                        <input class="form-control" type="text" name="team" id="team" placeholder=''
                            value="{{$user->team}}">
                        @isset($error_message->team)
                            <small class="text-danger">{{ $error_message->team }}</small>
                        @endisset
                    </div>
                    <div class="form-group">
                        <label for="email">メールアドレス<span class="required"></span></label>
                        <input class="form-control" type="email" name="email" id="email" placeholder='' required value="{{$user->email}}">
                        @isset($error_message->email)
                            <small class="text-danger">{{ $error_message->email }}</small>
                        @endisset
                    </div>
                    <div class="form-group">
                        <label for="phone">電話番号<span class="required"></span></label>
                        <input class="form-control" type="text" name="phone" id="phone" placeholder='' required value="{{$user->phone}}">
                        @isset($error_message->phone)
                            <small class="text-danger">{{ $error_message->phone }}</small>
                        @endisset
                    </div>

                    <div class="form-group">
                        <label class="d-block">ステータス<span class="required"></span></label>

                        <div class="form-check form-check-inline">
                            <label class="form-check-label"><input class="form-check-input" type="radio"
                                    name="disabled_flg" value="0" {{$user->disabled_flg ? '':'checked'}} required>有効</label>
                        </div>
                        <div class="form-check form-check-inline">
                            <label class="form-check-label"><input class="form-check-input" type="radio"
                                    name="disabled_flg" value="1" {{$user->disabled_flg ? 'checked':''}}>無効</label>
                        </div>
                        @isset($error_message->disabled_flg)
                        <small class="text-danger d-block">{{ $error_message->disabled_flg }}</small>
                        @endisset
                    </div>
                    </div>
                    <div class="card-footer">
                    <div class="form-group">
                        <input type="hidden" name="id" value={{$user->id}}>
                        <button class="btn btn-small btn-primary btn-block">変更</button>
                    </div>
                    <div class="form-group">
                    <a class="btn btn-small btn-secondary btn-block" href="{{route('admin.admin_user.index')}}">システム管理者一覧に戻る</a>
                    </div>
                    </div>
                </form>
        </div>
    </div>


@stop
