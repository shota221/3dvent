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

@section('title', 'Manual measurement')

@section('parent_content')

    @include(
        'Manual.Manual.Text.ja.manualMeasurement._content_how_to_use', 
        [
            'title' => '利用方法'
        ]
    )

@stop




