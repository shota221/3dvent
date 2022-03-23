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

@section('title', 'Manual Measurement')

@section('parent_content')

    @include('Manual.Manual.Text.en.manualMeasurement._content_how_to_use')

@stop




