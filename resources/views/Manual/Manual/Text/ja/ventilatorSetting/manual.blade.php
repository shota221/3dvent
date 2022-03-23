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

@section('title', '呼吸器マネジメント')

@section('parent_content')

    @include('Manual.Manual.Text.ja.ventilatorSetting._content_how_to_use')

@stop




