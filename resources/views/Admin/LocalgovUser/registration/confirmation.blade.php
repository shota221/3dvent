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
        <div class="card card-default color-pallete-box">
            <div class="card-header">
                <h3>自治体ユーザー管理</h3>
            </div>
                <form action="{{ route('admin.localgov_user.create') }}" method="POST">
                    @csrf
            <div class="card-body">
                <h4>自治体ユーザー新規作成（確認画面）</h4>
                    <div class="form-group">
                        <label class="font-weight-normal" for="localgov_code">・市区町村コード</label>
                        <div class="font-weight-bold ml-3">
                            <p>{{ $localgov_code }}</p>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="font-weight-normal" for="name">・氏名</label>
                        <div class="font-weight-bold ml-3">
                            <p>{{ $last_name.' '.$first_name }}</p>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="font-weight-normal" for="name_kana">・氏名（カナ）</label>
                        <div class="font-weight-bold ml-3">
                            <p>{{ $last_name_kana.' '.$first_name_kana }}</p>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="font-weight-normal" for="email">・メールアドレス</label>
                        <div class="font-weight-bold ml-3">
                            <p>{{ $email }}</p>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="font-weight-normal" for="phone">・電話番号</label>
                        <div class="font-weight-bold ml-3">
                            <p>{{ $phone }}</p>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="font-weight-normal" for="password">・パスワード</label>
                        <div class="font-weight-bold ml-3">
                            <p>{{ str_repeat('*', strlen($password)) }}</p>
                        </div>
                    </div>
            </div>
            <div class="card-footer">
                        <input name="localgov_code" value="{{$localgov_code}}" type="hidden">
                        <input name="last_name" value="{{$last_name}}" type="hidden">
                        <input name="first_name" value="{{$first_name}}" type="hidden">
                        <input name="last_name_kana" value="{{$last_name_kana}}" type="hidden">
                        <input name="first_name_kana" value="{{$first_name_kana}}" type="hidden">
                        <input name="email" value="{{$email}}" type="hidden">
                        <input name="phone" value="{{$phone}}" type="hidden">
                        <div class="form-group">
                            <button class="btn btn-small btn-primary btn-block">送信</button>
                        </div>
                            <div class="form-group">
                                    <button name="back" class="btn btn-small btn-default btn-block">戻る</button>
                            </div>
            </div>
                </form>
            
        </div>
    </div>


@stop
