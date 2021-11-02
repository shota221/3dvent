@extends('Admin.page')

{{-- /***********************
    CSS
************************/ --}}
@section('css')
@stop

{{-- /***********************
    JS
************************/ --}}
@section('js')
    <script src="js/admin/ventilator/index.js"></script>
@stop

@section('hiddens')
    <input type="hidden" id="has-page-editable-role" value="true" />
@stop

@section('content')
    <div class="container-fluid">
        <div class="card card-default color-pallete-box">
            <div class="card-header">
                {{-- MicroVent管理 --}}
                <h3>@lang('messages.ventilator_management')</h3>
            </div>
            <div class="card-body">
                <div class="row d-flex justify-content-between my-3">
                    <div class="col-sm-4">
                        {{-- MicroVent一覧 --}}
                        <h4>@lang('messages.ventilator_list')</h4>
                    </div>
                    <div class="col-sm-8 pull-right">
                        {{-- CSVインポート --}}
                        <button type="button" class="btn btn-primary float-right ladda-button"
                            id="show-import-modal">@lang('messages.csv_import')</button>
                        <input type="hidden" id="check-import-queue-status"
                            data-url="{{ route('admin.ventilator.async.queue_status_input_ventilator_data') }}"
                            data-method="GET">
                        {{-- CSVエクスポート --}}
                        <button type="button" class="btn btn-success mr-1 float-right ladda-button" id="btn-csv-export"
                            data-url="{{ route('admin.ventilator.async.queue_output_ventilator_data') }}"
                            data-method="POST">@lang('messages.csv_export')
                        </button>
                        <input type="hidden" id="check-export-queue-status"
                            data-url="{{ route('admin.ventilator.async.queue_status_output_ventilator_data') }}"
                            data-method="GET">
                        <input type="hidden" id="export-csv" data-url="{{ route('admin.ventilator.export_csv') }}"
                            data-method="GET">
                        s
                    </div>
                </div>
                {{-- 絞り込み検索 --}}
                @include('Admin.Ventilator.searchForm')
                <div id="paginated-list">
                    @include('Admin.Ventilator.list')
                </div>
            </div>
        </div>
    </div>
@stop

@section('modal')
    @include('Admin.Ventilator.editModal')
    @include('Admin.Ventilator.importModal')
    @include('Admin.Ventilator.ventilatorBugListModal')
@stop
