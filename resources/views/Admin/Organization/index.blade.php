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
                <h3>@lang('messages.organization_management')</h3>
            </div>
            <div class="card-body">
                <div class="row d-flex justify-content-between my-3">
                    <div class="col-sm-4">
                        <h4>@lang('messages.organization_list')</h4>
                    </div>
                    <div class="row">
                        <div class="ml-3 mr-3">
                            <button type="button" class="btn btn-primary"
                                id="show-register-modal">@lang('messages.register')</button>
                        </div>
                    </div>
                </div>
                {{-- todo 検索機能 --}}
                <div class="form-group">
                    <div class="input-group input-group-lg">
                        <input type="search" class="form-control form-control-lg" placeholder="Type your keywords here"
                            value="Lorem ipsum">
                        <div class="input-group-append">
                            <button type="search" class="btn btn-lg btn-default" id="search-organization">
                                <i class="fa fa-search"></i>
                            </button>
                        </div>
                    </div>
                </div>
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
