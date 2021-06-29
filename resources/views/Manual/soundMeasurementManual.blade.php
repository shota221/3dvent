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

@section('title', '音声測定')

@section('parent_content')

@include('Manual._content_top',['title' => '利用方法'])
<div class="card-body">
    <p>
        1，スマートフォンをMicroVentに近づけて、「録音」を押下してください。
    </p>
    <p>
        2，呼気、吸気ともにが4回以上録音できたら「停止」を押下してください。
    </p>
    <p>
        3，「平均呼気吸気時間計算」を押下してください。
    </p>
    <p>
        4，3により平均呼気時間、平均吸気時間が表示されます。
    </p>
    <p>
        5，「登録完了」の画面に遷移されます。
    </p>
</div>
@include('Manual._content_bottom')

@include('Manual._content_top',['title' => 'マイクの利用を拒否した場合の再設定方法'])
<div class="card-body">
    <div>
        <p>
            <b>・ios</b><br>
            [設定]-[プライバシー]-[マイク]にてMicroVentのマイク利用許可をＯＮにしてください。
        </p>
        <p>
            <b>・android</b><br>
            [設定]-[アプリ（またはアプリケーションを管理）]-[MicorVent] -[権限]にてマイク利用許可をONにしてください。
        </p>
    </div>
</div>
@include('Manual._content_bottom')


@stop




