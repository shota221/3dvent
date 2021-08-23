@extends('Admin.page')

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
    <script src="js/admin/organization_admin_user/index.js"></script>
@stop

@section('content')
<div class="container-fluid">
    <div id = "alert-message"></div>
    <div class="card card-default color-pallete-box">
        <div class="card-header">
            <h3>@lang('messages.organization_admin_user_management')</h3>
        </div>
        <div class="card-body">
            <h6>絞込検索</h6>
            <div class="post">
                <form id="async-search-form">
                    <div class="row">
                        <div class="col-sm-4">
                            <div class="form-group" data-url="{{ route('admin.org_admin_user.async.organization_data') }}" data-method="GET" id="async-organization-data" >
                                <label>@lang('messages.organization_name')</label>
                                <select class="form-control form-control-sm select" name="organization_name" id="select2-organization-name">
                                    <option></option>
                                </select>
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <div class="form-group">
                                <label>@lang('messages.user_name')</label>
                                <input type="text" class="form-control form-control-sm" name="name">
                            </div>
                        </div>
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
                        <div class="col-sm-4">
                            <div class="form-group">
                                <label>@lang('messages.status')</label>
                                <div>
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input form-control-sm" type="checkbox" name="disabled_flg" value="0" checked>
                                        <label class="form-check-label">@lang('messages.valid')</label>
                                    </div>
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input form-control-sm" type="checkbox" name="disabled_flg" value="1"  checked>
                                        <label class="form-check-label">@lang('messages.invalid')</label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
                <div class="btn-toolbar">
                    <div class="btn-group">
                        <button class="btn btn-default btn-sm" id="clear-search-form">@lang('messages.clear_search_form')</button>
                    </div>
                    <div class="btn-group ml-auto">
                        <button class="btn btn-secondary btn-sm" data-url="{{ route('admin.org_admin_user.search') }}" data-method="GET" id="async-search">@lang('messages.search')</button>
                    </div>
                </div>
            </div>
            <div class="row d-flex justify-content-between my-3">
                <h4>@lang('messages.organization_admin_user_list')</h4>
                <button type="button" id="show-register-modal" class="btn btn-primary btn-sm mr-2">@lang('messages.register')</button>
            </div>
            <div id="paginated-list">
                @include('Admin.OrganizationAdminUser.list')
            </div>
        </div>
    </div>
</div>
@stop

@section('modal')
    @include('Admin.OrganizationAdminUser.modal')
@stop