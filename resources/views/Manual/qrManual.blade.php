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

@section('title', 'QR読込')

@section('parent_content')


@include('Manual._content_top',['title' => 'MicroVent使用前に'])
<div class="card-body">
    <p>
        MicroVentをテストラングに装着し動作させ、呼吸動作が安定していることを確認してください。   
    </p>
    <div>
    ・初期設定値
        <ul>
            <li>設定圧20cm</li>
            <li>空気流量9L/分</li>
            <li>酸素流量3L/分</li>
        </ul>

    </div>
</div>
@include('Manual._content_bottom')

@include('Manual._content_top',['title' => '利用方法'])
<div class="card-body">
    <p>
        1，カメラの利用を許可してMicroVent側面にあるQRコードを画面に表示されている枠内に収まるように撮影してください。（自動でＱＲコードが読み込まれます。）
    </p>
    <p>
        2，画面遷移されます。（下記「ＱＲコード読込後の遷移先」参照）
    </p>
</div>
@include('Manual._content_bottom')

@include('Manual._content_top',['title' => 'ＱＲコード読込後の遷移先'])
<div class="card-body">
    <div>
        <p><b>・未ログインユーザーもしくはMicroVent所属組織とログインユーザー所属組織に齟齬がある場合</b><br>→「利用規約」画面に遷移</p>
        <div><b>・ログインユーザーの場合</b>
            <ul>
                <li><i>MicroVent利用患者情報未登録</i><br>→「患者情報入力」画面に遷移</li>
                <li><i>MicroVent利用患者情報登録済み</i><br>→「機器設定値入力」画面に遷移</li>
            </ul>
        </div>
    </div>
</div>
@include('Manual._content_bottom')


@include('Manual._content_top',['title' => 'カメラの利用を拒否した場合の再設定方法'])
<div class="card-body">
    <div>
        <p>
            <b>・ios</b><br>
            [設定]-[プライバシー]-[カメラ]にてMicroVentのカメラ利用許可をＯＮにしてください。
        </p>
        <p>
            <b>・android</b><br>
            [設定]-[アプリ（またはアプリケーションを管理）]-[MicorVent] -[権限]にてカメラ利用許可をONにしてください。
        </p>
    </div>
</div>
@include('Manual._content_bottom')


@stop




