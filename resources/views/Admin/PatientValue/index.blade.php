@extends('Admin.page')

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
    <script src="js/admin/patient_value/index.js"></script>
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
            {{--絞込検索--}}
            <h6>@lang('messages.refined_search')</h6>
            <div class="post">
                <form id="async-search-form">
                    <div class="row">
    
                        {{--組織名--}}
                        <div class="col-sm-4">
                            <div class="form-group" data-url="{{ route('admin.patient_value.async.organization_data') }}" data-method="GET" id="async-organization-data">
                                <label>@lang('messages.organization_name')</label>
                                <select class="form-control form-control-sm select" name="organization_name" id="select2-organization-name">
                                    <option></option>
                                </select>
                            </div>
                        </div>

                        {{--患者番号--}}
                        <div class="col-sm-4">
                            <div class="form-group">
                                <label>@lang('messages.patient_code')</label>
                                <input type="text" class="form-control form-control-sm" name="patient_code" id="patient_code" disabled>
                            </div>
                        </div>
                        
                        {{--登録者--}}
                        <div class="col-sm-4">
                            <div class="form-group" data-url="{{ route('admin.patient_value.async.registered_user_data') }}" data-method="GET" id="async-registered-user-data">
                                <label>@lang('messages.registered_user_name')</label>
                                <select class="form-control form-control-sm select" name="registered_user_name" id="select2-registered-user-name" disabled>
                                    <option></option>
                                </select>
                            </div>
                        </div>

                        {{--登録日--}}
                        <div class="col-sm-4">
                            <div class="form-group">
                                <label>@lang('messages.registered_date')</label>
                                <div class="row">
                                    <div class="col-sm-5">
                                        <input type="date" class="form-control form-control-sm" name="registered_at_from">
                                    </div>
                                    &nbsp;〜&nbsp;
                                    <div class="col-sm-5">
                                        <input type="date" class="form-control form-control-sm" name="registered_at_to">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
                <div class="btn-toolbar">
                    
                    {{--検索条件をクリア--}}
                    <div class="btn-group">
                        <button class="btn btn-default btn-sm" id="clear-search-form">@lang('messages.clear_search_form')</button>
                    </div>
                    
                    {{--検索--}}
                    <div class="btn-group ml-auto">
                        <button class="btn btn-secondary btn-sm" data-url="{{ route('admin.patient_value.search') }}" data-method="GET" id="async-search">@lang('messages.search')</button>
                    </div>
                </div>
            </div>

            {{--患者観察研究データ一覧--}}
            <div class="row d-flex justify-content-between my-3">
                <h4>@lang('messages.patient_value_list')</h4>
            </div>

            <div id="paginated-list">
                @include('Admin.PatientValue.list')
            </div>
        </div>
    </div>
</div>
@stop

@section('modal')
    @include('Admin.PatientValue.modal')
@stop