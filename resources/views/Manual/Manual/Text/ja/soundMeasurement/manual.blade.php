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

    @include(
        'Manual.Manual.Text.ja.soundMeasurement._content_how_to_use', 
        [
            'title' => '利用方法'
        ]
    )

    @include(
        'Manual.Manual.Text.ja.soundMeasurement._content_use_microphone', 
        [
            'title' => 'マイクの利用を拒否した場合の再設定方法'
        ]
    )

@stop




