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
                <h3>システム管理者管理</h3>
            </div>
                <form action="{{ route('admin.admin_user.update',['id' => $id]) }}" method="post">
                    @csrf
                    @method('put')
                <div class="card-body">
                    <h4>システム管理者詳細・変更（確認画面）</h4>
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
                        <label class="font-weight-normal" for="team">・所属</label>
                        <div class="font-weight-bold ml-3">
                            <p>{{ $team }}</p>
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
                        <label class="font-weight-normal" for="phone">・ステータス</label>
                        <div class="font-weight-bold ml-3">
                            <p>{{ $disabled_flg ? trans('message.invalid') : trans('message.valid')}}</p>
                        </div>
                    </div>
                </div>
                <div class="card-footer">
                    <div class="form-group">
                        <button class="btn btn-small btn-primary btn-block">送信</button>
                    </div>
                        <div class="form-group">
                                <button name="back" class="btn btn-small btn-default btn-block">戻る</button>
                        </div>
                    </div>
        </div>
    </div>


@stop
