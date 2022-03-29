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

@section('title', 'How to Use MicroVent®V3')

@section('parent_content')
    
    @include('Manual.Manual.Text.en.qr._content_before_use')

    @include('Manual.Manual.Text.en.ventilatorSetting._content_how_to_use')

    @include('Manual.Manual.Text.en.soundMeasurement._content_how_to_use')

    @include('Manual.Manual.Text.en.manualMeasurement._content_how_to_use')

    @include('Manual.Manual.Text.en.ventilatorResult._content_how_to_use')

@stop




