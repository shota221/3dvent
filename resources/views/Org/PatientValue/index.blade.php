@extends('Org.page')

{{--
/***********************
CSS
************************/
--}}
@section('css')
<style>
    th, td {
        white-space: nowrap; 
    }
</style>
@stop

{{--
/***********************
JS
************************/
--}}
@section('js')
    <script src="js/org/patient_value/index.js"></script>
@stop

@section('content')
<div class="container-fluid">
    <div id = "alert-message"></div>
    <div class="card card-default color-pallete-box">
        <div class="card-header">
            {{--患者観察研究データ管理--}}
            <h3>@lang('messages.patient_value_management')</h3>
        </div>
        <div class="card-body">
            {{--患者観察研究データ一覧--}}
            <div class="row d-flex justify-content-between my-3">
                <h4>@lang('messages.patient_value_list')</h4>
            </div>
            {{--絞込検索--}}
            @include('Org.PatientValue.searchForm')

            <div id="paginated-list">
                @include('Org.PatientValue.list')
            </div>
        </div>
    </div>
</div>
@stop

@section('modal')
    @include('Org.PatientValue.modal')
@stop