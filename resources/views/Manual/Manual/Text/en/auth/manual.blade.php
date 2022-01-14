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

@section('title', 'Terms and Conditions')

@section('parent_content')

    @include('Manual.Manual.Text.en.auth._content_how_to_use')

    @include('Manual.Manual.Text.en.auth._content_transition_destination')

    @include('Manual.Manual.Text.en.auth._content_use_location_information')

@stop




