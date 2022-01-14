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

@section('title', 'Sound acquisition & analysis')

@section('parent_content')

    @include('Manual.Manual.Text.en.soundMeasurement._content_how_to_use')

    @include('Manual.Manual.Text.en.soundMeasurement._content_use_microphone')

@stop




