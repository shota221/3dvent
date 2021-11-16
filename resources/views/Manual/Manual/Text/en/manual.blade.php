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

@section('title', 'Electronic manual')

@section('parent_content')
    
    @include('Manual.Manual.Text.ja.qr._content_before_use')

    @include('Manual.Manual.Text.ja.ventilatorSetting._content_how_to_use')

    @include('Manual.Manual.Text.ja.soundMeasurement._content_how_to_use')

    @include('Manual.Manual.Text.ja.manualMeasurement._content_how_to_use')

    @include('Manual.Manual.Text.ja.ventilatorResult._content_how_to_use')

@stop




