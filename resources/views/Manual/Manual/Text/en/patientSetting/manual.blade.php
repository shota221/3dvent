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

@section('title', 'Patient information input')

@section('parent_content')

    @include(
        'Manual.Manual.Text.ja.patientSetting._content_how_to_use', 
        [
            'title' => '利用方法'
        ]
    )

@stop




