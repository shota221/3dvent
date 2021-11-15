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

    @include(
        'Manual.Manual.Text.ja.manualMeasurement._content_how_to_use', 
        [
            'title' => '利用方法'
        ]
    )

@stop




