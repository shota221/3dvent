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

@section('title', '動作音収集測定')

@section('parent_content')

    @include('Manual.Manual.Text.ja.soundMeasurement._content_how_to_use')

    @include('Manual.Manual.Text.ja.soundMeasurement._content_use_microphone')

@stop




