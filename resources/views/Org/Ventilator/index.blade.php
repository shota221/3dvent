@extends('Org.page')

{{-- /***********************
    CSS
************************/ --}}
@section('css')
@stop

{{-- /***********************
    JS
************************/ --}}
@section('js')
    <script src="js/org/ventilator/index.js"></script>
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
                </div>
                {{-- 絞り込み検索 --}}
                @include('Org.Ventilator.searchForm')
                <div id="paginated-list">
                    @include('Org.Ventilator.list')
                </div>
            </div>
        </div>
    </div>
@stop

@section('modal')
    @include('Org.Ventilator.editModal')
    @include('Org.Ventilator.ventilatorBugListModal')
@stop
