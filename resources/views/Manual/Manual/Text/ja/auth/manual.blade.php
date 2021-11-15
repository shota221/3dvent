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

    @include(
        'Manual.Manual.Text.ja.auth._content_how_to_use', 
        [
            'title' => '利用方法'
        ]
    )

    @include(
        'Manual.Manual.Text.ja.auth._content_transition_destination', 
        [
            'title' => '「ログインせずに利用」もしく「ログイン」を押下後の遷移先'
        ]
    )

    @include(
        'Manual.Manual.Text.ja.auth._content_use_location_information', 
        [
            'title' => '位置情報の利用を拒否した場合の再設定方法'
        ]
    )

@stop




