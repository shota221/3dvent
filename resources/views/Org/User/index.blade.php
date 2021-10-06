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
            <div class="row d-flex justify-content-between my-3">
                {{--ユーザーデータ一覧--}}
                <h4>@lang('messages.user_list')</h4>
                <div class="row">
                    <form method="get" action="{{ route('org.user.export_user_csv_format') }}">
                        <button type="submit" class="btn btn-success mr-2">@lang('messages.csv_format_export')</button>
                    </form>
                    <button type="button" id="show-csv-import-modal" class="btn btn-primary mr-2">@lang('messages.csv_import')</button>
                    <button type="button" id="show-register-modal" class="btn btn-primary mr-2">@lang('messages.register')</button>
                </div>
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