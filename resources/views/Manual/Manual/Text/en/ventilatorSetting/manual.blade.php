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

@section('title', 'Device setting value input')

@section('parent_content')

    @include(
        'Manual.Manual.Text.ja.ventilatorSetting._content_how_to_use', 
        [
            'title1' => '初回入力',
            'title2' => '入力2回目以降（1回換気量の決定）',
            'title3' => '入力2回目以降（1回換気量決定後の呼吸器回数の決定）',
        ]
    )

@stop




