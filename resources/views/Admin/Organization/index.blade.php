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
    <script>
        //TODO: modal共通化
        $('#show-register-modal').click(function(e) {
            var registerModal = $('#modal-organization-create');
            registerModal.modal();
            var $btn = document.getElementById('async-organization-create');
            console.log($btn);
            $btn.addEventListener(
                'click',
                function(e) {
                    e.preventDefault;

                    var parameters = buildParameters(document.forms['organization-create'].elements);

                    var successCallback = function(data) {
                        registerModal.modal('hide');
                    }

                    executeAjax($btn, parameters, true, successCallback);
                }
            )
        });

        $('#modal-cancel').click(function() {
            var registerModal = $('#modal-organization-create');
            registerModal.modal('hide');
        });
    </script>
@stop

@section('hiddens')
    <input type="hidden" id="has-page-editable-role" value="true" />
@stop

@section('content')
    <div class="container-fluid">
        <div class="card card-default color-pallete-box">
            <div class="card-header">
                <h3>@lang('messages.admin.organization_management')</h3>
            </div>
            <div class="card-body">
                <div class="row d-flex justify-content-between my-3">
                    <div class="col-sm-4">
                        <h4>@lang('messages.admin.organization_list')</h4>
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
