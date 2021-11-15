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

@section('title', '2D code reading')

@section('parent_content')

    @include(
        'Manual.Manual.Text.ja.qr._content_before_use', 
        [
            'title' => 'MicroVent使用前に'
        ]
    )

    @include(
        'Manual.Manual.Text.ja.qr._content_how_to_use', 
        [
            'title' => '利用方法'
        ]
    )

    @include(
        'Manual.Manual.Text.ja.qr._content_transition_destination', 
        [
            'title' => '二次元コード読込後の遷移先'
        ]
    )

    @include(
        'Manual.Manual.Text.ja.qr._content_use_camera', 
        [
            'title' => 'カメラの利用を拒否した場合の再設定方法'
        ]
    )

@stop




