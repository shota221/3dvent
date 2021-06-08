@extends('Manual.page')

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

@section('title', '利用規約')

@section('parent_content')

@include('Manual._content_top',['title' => '利用方法'])
<div class="card-body">
    <p>
        1，画面にある利用規約を読んで「利用規約に同意する」にチェックを入れてください。
    </p>
    <p>
        2，「ログインせずに利用」もしくはアカウントとパスワードを入力し「ログイン」を押下してください。
    </p>
    <p>
        3，画面遷移されます。（下記「「ログインせずに利用」もしく「ログイン」を押下後の遷移先」参照）
    </p>
</div>
@include('Manual._content_bottom')

@include('Manual._content_top',['title' => '「ログインせずに利用」もしく「ログイン」を押下後の遷移先'])
<div class="card-body">
    <div>
        <p><b>・MicroVent利用患者情報未登録の場合</b><br>→「患者情報入力」画面に遷移</p>
        <p><b>・MicroVent利用患者情報登録済みの場合</b><br>→「機器設定値入力」画面に遷移</p>
    </div>
</div>
@include('Manual._content_bottom')


@include('Manual._content_top',['title' => '位置情報の利用を拒否した場合の再設定方法'])
<div class="card-body">
    <div>
        <p>
            <b>・ios</b><br>
            [設定]-[プライバシー]-[位置情報サービス]-[Microvent]にて「このAppの使用中のみ許可」にチェックをいれてください。
        </p>
        <p>
            <b>・android</b><br>
            [設定]-[位置情報]-[アプリの権限]-[Microvent]にて「アプリの使用中のみ許可」にチェックをいれてください。
        </p>
    </div>
</div>
@include('Manual._content_bottom')


@stop




