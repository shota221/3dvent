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

@section('title', 'GS1 code reading')

@section('parent_content')

    @include('Manual.Manual.Text.en.qr._content_before_use')

    @include('Manual.Manual.Text.en.qr._content_how_to_use')

    @include('Manual.Manual.Text.en.qr._content_transition_destination')

    @include('Manual.Manual.Text.en.qr._content_use_camera')

@stop




