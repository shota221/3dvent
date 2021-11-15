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

@section('title', '電子マニュアル')

@section('parent_content')
    
    @include(
        'Manual.Manual.Text.ja.qr._content_before_use', 
        [
            'title' => 'MicroVent使用前に'
        ]
    )

    @include(
        'Manual.Manual.Text.ja.ventilatorSetting._content_how_to_use', 
        [
            'title1' => '機器設定値初回入力',
            'title2' => '機器設定値入力2回目以降（1回換気量の決定）',
            'title3' => '機器設定値入力2回目以降（1回換気量決定後の呼吸器回数の決定）',
        ]
    )

    @include(
        'Manual.Manual.Text.ja.soundMeasurement._content_how_to_use', 
        [
            'title' => '音声測定利用方法'
        ]
    )

    @include(
        'Manual.Manual.Text.ja.manualMeasurement._content_how_to_use', 
        [
            'title' => '手動測定利用方法'
        ]
    )

    @include(
        'Manual.Manual.Text.ja.ventilatorResult._content_how_to_use', 
        [
            'title1' => '登録完了時利用方法',
            'title2' => '1回換気量の決定',
            'title3' => '呼吸器回数の決定（1回換気量決定後）'
        ]
    )

@stop




