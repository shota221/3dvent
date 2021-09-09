@extends('Org.page')

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
    <script src="js/org/user/index.js"></script>
@stop

@section('content')
<div class="container-fluid">
    <div id = "alert-message"></div>
    <div class="card card-default color-pallete-box">
        <div class="card-header">
            {{--ユーザー管理--}}
            <h3>@lang('messages.user_management')</h3>
        </div>
        <div class="card-body">
            {{--絞込検索--}}
            @include('Org.User.searchForm')
            {{--ユーザーデータ一覧--}}
            <div class="row d-flex justify-content-between my-3">
                <h4>@lang('messages.user_list')</h4>
                <button type="button" id="show-register-modal" class="btn btn-primary btn-sm mr-2">@lang('messages.register')</button>
            </div>
            <div id="paginated-list">
                @include('Org.User.list')
            </div>
        </div>
    </div>
</div>
@stop

@section('modal')
    @include('Org.User.modal')
@stop