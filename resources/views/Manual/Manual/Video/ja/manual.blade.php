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

@section('title', 'MicroVent®V3の使い方')

{{-- TODO サンプル動画を変更する  --}}
@section('parent_content')


    @include(
    'Manual.Manual.Video._content_top', 
        [
            'title' => 'sample1', 
            'url'   => 'https://www.youtube.com/watch?v=pgj6esQUjE0'
        ]
    )
    
    @include('Manual.Manual.Video._content_bottom')
    
    
    @include(
        'Manual.Manual.Video._content_top', 
        [
            'title' => 'sample2', 
            'url'   => 'https://www.youtube.com/watch?v=pgj6esQUjE0'
        ]
    )
    
    @include('Manual.Manual.Video._content_bottom')

@stop




