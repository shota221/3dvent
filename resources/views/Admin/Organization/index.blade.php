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
    <script src="js/admin/organization/index.js"></script>
@stop

@section('hiddens')
    <input type="hidden" id="has-page-editable-role" value="true" />
@stop

@section('content')
    <div class="container-fluid">
        <div class="card card-default color-pallete-box">
            <div class="card-header">
                {{-- 組織管理 --}}
                <h3>@lang('messages.organization_management')</h3>
            </div>
            <div class="card-body">
                <div class="row d-flex justify-content-between my-3">
                    <div class="col-sm-4">
                        {{-- 組織一覧 --}}
                        <h4>@lang('messages.organization_list')</h4>
                    </div>
                    <div class="row">
                        <div class="ml-3 mr-3">
                            {{-- 新規登録 --}}
                            <button type="button" class="btn btn-primary"
                                id="show-register-modal">@lang('messages.register')</button>
                        </div>
                    </div>
                </div>
                @include('Admin.Organization.searchForm')
                <div id="paginated-list">
                    @include('Admin.Organization.list')
                </div>
            </div>
        </div>
    </div>
@stop

@section('modal')
    @include('Admin.Organization.editModal')
    @include('Admin.Organization.registerModal')
    @include('Admin.Organization.userListModal')
@stop
