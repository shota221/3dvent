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

@section('title', '手動測定')

@section('parent_content')

@include('Manual._content_top',['title' => '利用方法'])
<div class="card-body">
    <p>
        1，吸気1回目、呼気1回目、吸気2回目、呼気2回目に手動にて測定した結果を入力してください。
    </p>
    <p>
        2，「平均呼気吸気時間計算」を押下してください。
    </p>
    <p>
        3，2により平均呼気時間、平均吸気時間が表示されます
    </p>
    <p>
        4，「登録して計算」を押下してください。
    </p>
    <p>
        5，「登録完了」の画面に遷移されます。
    </p>
</div>
@include('Manual._content_bottom')

@stop




