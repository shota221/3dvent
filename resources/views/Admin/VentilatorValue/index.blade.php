@extends('Admin.page')

{{-- /***********************
    CSS
************************/ --}}
@section('css')
    <style>
        table {
            "table-layout:fixed;"
        }

        th,
        td {
            white-space: nowrap;
        }
    </style>
@stop

{{-- /***********************
    JS
************************/ --}}
@section('js')
    <script src="js/admin/ventilator_value/index.js"></script>
@stop

@section('hiddens')
    <input type="hidden" id="has-page-editable-role" value="true" />
@stop

@section('content')
    <div class="container-fluid">
        <div id = "alert-message"></div>
        <div class="card card-default color-pallete-box">
            <div class="card-header">
                {{-- 機器観察研究データ管理 --}}
                <h3>@lang('messages.ventilator_value_management')</h3>
            </div>
            <div class="card-body">
                <div class="row d-flex justify-content-between my-3">
                    <div class="col-sm-4">
                        {{-- 機器観察研究データ一覧 --}}
                        <h4>@lang('messages.ventilator_value_list')</h4>
                    </div>
                </div>
                @include('Admin.VentilatorValue.searchForm')
                <div id="paginated-list">
                    @include('Admin.VentilatorValue.list')
                </div>
            </div>
        </div>
    </div>
@stop

@section('modal')
    @include('Admin.VentilatorValue.editModal')
@stop
