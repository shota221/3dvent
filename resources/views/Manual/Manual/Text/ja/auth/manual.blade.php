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

@section('title', '利用規約')

@section('parent_content')

    @include('Manual.Manual.Text.ja.auth._content_how_to_use')

    @include('Manual.Manual.Text.ja.auth._content_transition_destination')

    @include('Manual.Manual.Text.ja.auth._content_use_location_information')

@stop




