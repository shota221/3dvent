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

@section('title', '患者情報入力')

@section('parent_content')

@include('Manual._content_top',['title' => '利用方法'])
<div class="card-body">
    <p>
        1，身長、性別、患者番号（患者番号は未入力可。）を入力してください。
    </p>
    <p>
        2，「次へ」を押下してください。
    </p>
    <p>
        3，「機器設定値入力」の画面に遷移されます。
    </p>
</div>
@include('Manual._content_bottom')

@stop




