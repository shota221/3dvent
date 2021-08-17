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
            <h3>組織管理者アカウント管理</h3>
        </div>
        <div class="card-body">
            <h6>絞込検索</h6>
            <div class="post">
                <div class="row">
                    <div class="col-sm-4">
                        <div class="form-group">
                            <label>組織名</label>
                            <select class="form-control form-control-sm">
                                <option></option>
                                <option>1</option>
                                <option>2</option>
                                <option>3</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-sm-4">
                        <div class="form-group">
                            <label>ユーザー名</label>
                            <input type="text" class="form-control form-control-sm">
                        </div>
                    </div>
                    <div class="col-sm-4">
                        <div class="form-group">
                            <label>登録日</label>
                            <div class="row">
                                <div class="col-sm-5">
                                    <input type="date" class="form-control form-control-sm">
                                </div>
                                &nbsp;〜&nbsp;
                                <div class="col-sm-5">
                                    <input type="date" class="form-control form-control-sm">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-4">
                        <div class="form-group">
                            <label>ステータス</label>
                            <div>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input form-control-sm" type="checkbox" checked>
                                    <label class="form-check-label">有効</label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input form-control-sm" type="checkbox" checked>
                                    <label class="form-check-label">無効</label>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="btn-toolbar">
                    <div class="btn-group">
                        <button class="btn btn-default btn-sm">検索条件をクリア</button>
                    </div>
                    <div class="btn-group ml-auto">
                        <button class="btn btn-secondary btn-sm">検索</button>
                    </div>
                </div>
            </div>
            <div class="row d-flex justify-content-between my-3">
                <h4>組織管理者アカウント一覧</h4>
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