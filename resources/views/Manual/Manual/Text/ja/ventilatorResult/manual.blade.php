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

@section('title', '登録完了')

@section('parent_content')

    @include(
        'Manual.Manual.Text.ja.ventilatorResult._content_how_to_use', 
        [
            'title1' => '利用方法',
            'title2' => '1回換気量の決定',
            'title3' => '呼吸器回数の決定（1回換気量決定後）'
        ]
    )

@stop




