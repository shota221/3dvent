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
                <h3>@lang('messages.ventilator_management')</h3>
            </div>
            <div class="card-body">
                <div class="row d-flex justify-content-between my-3">
                    <div class="col-sm-4">
                        <h4>@lang('messages.ventilator_list')</h4>
                    </div>
                    <div class="col-sm-8 pull-right">
                        <button type="button" class="btn btn-primary float-right"
                            id="show-import-modal">@lang('messages.csv_import')</button>
                        <form id="csv-export" method="get" action="{{ route('admin.ventilator.export_csv') }}">
                            <button type="submit" class="btn btn-success mr-1 float-right"
                                id="btn-csv-export">@lang('messages.csv_export')</button>
                        </form>
                    </div>
                </div>
                {{-- todo 検索機能 --}}
                <div class="form-group">
                    <div class="input-group input-group-lg">
                        <input type="search" class="form-control form-control-lg" placeholder="Type your keywords here"
                            value="Lorem ipsum">
                        <div class="input-group-append">
                            <button type="search" class="btn btn-lg btn-default" id="search-ventilator">
                                <i class="fa fa-search"></i>
                            </button>
                        </div>
                    </div>
                </div>
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
