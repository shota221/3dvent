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
                                id="btn-show-register_modal">@lang('messages.register')</button>
                        </div>
                    </div>
                </div>
                <form action="#">
                    <div class="row">
                        <div class="col-md-10 offset-md-1">
                            <div class="row">
                                <div class="col-6">
                                    <div class="form-group">
                                        <label>Result Type:</label>
                                        <select class="select2 select2-hidden-accessible" multiple="" data-placeholder="Any"
                                            style="width: 100%;" data-select2-id="1" tabindex="-1" aria-hidden="true">
                                            <option>Text only</option>
                                            <option>Images</option>
                                            <option>Video</option>
                                        </select><span class="select2 select2-container select2-container--default"
                                            dir="ltr" data-select2-id="2" style="width: 100%;"><span class="selection"><span
                                                    class="select2-selection select2-selection--multiple" role="combobox"
                                                    aria-haspopup="true" aria-expanded="false" tabindex="-1"
                                                    aria-disabled="false">
                                                    <ul class="select2-selection__rendered">
                                                        <li class="select2-search select2-search--inline"><input
                                                                class="select2-search__field" type="search" tabindex="0"
                                                                autocomplete="off" autocorrect="off" autocapitalize="none"
                                                                spellcheck="false" role="searchbox" aria-autocomplete="list"
                                                                placeholder="Any" style="width: 919.885px;"></li>
                                                    </ul>
                                                </span></span><span class="dropdown-wrapper"
                                                aria-hidden="true"></span></span>
                                    </div>
                                </div>
                                <div class="col-3">
                                    <div class="form-group">
                                        <label>Sort Order:</label>
                                        <select class="select2 select2-hidden-accessible" style="width: 100%;"
                                            data-select2-id="3" tabindex="-1" aria-hidden="true">
                                            <option selected="" data-select2-id="5">ASC</option>
                                            <option>DESC</option>
                                        </select><span class="select2 select2-container select2-container--default"
                                            dir="ltr" data-select2-id="4" style="width: 100%;"><span class="selection"><span
                                                    class="select2-selection select2-selection--single" role="combobox"
                                                    aria-haspopup="true" aria-expanded="false" tabindex="0"
                                                    aria-disabled="false" aria-labelledby="select2-jo58-container"><span
                                                        class="select2-selection__rendered" id="select2-jo58-container"
                                                        role="textbox" aria-readonly="true" title="ASC">ASC</span><span
                                                        class="select2-selection__arrow" role="presentation"><b
                                                            role="presentation"></b></span></span></span><span
                                                class="dropdown-wrapper" aria-hidden="true"></span></span>
                                    </div>
                                </div>
                                <div class="col-3">
                                    <div class="form-group">
                                        <label>Order By:</label>
                                        <select class="select2 select2-hidden-accessible" style="width: 100%;"
                                            data-select2-id="6" tabindex="-1" aria-hidden="true">
                                            <option selected="" data-select2-id="8">Title</option>
                                            <option>Date</option>
                                        </select><span class="select2 select2-container select2-container--default"
                                            dir="ltr" data-select2-id="7" style="width: 100%;"><span class="selection"><span
                                                    class="select2-selection select2-selection--single" role="combobox"
                                                    aria-haspopup="true" aria-expanded="false" tabindex="0"
                                                    aria-disabled="false" aria-labelledby="select2-z9x8-container"><span
                                                        class="select2-selection__rendered" id="select2-z9x8-container"
                                                        role="textbox" aria-readonly="true" title="Title">Title</span><span
                                                        class="select2-selection__arrow" role="presentation"><b
                                                            role="presentation"></b></span></span></span><span
                                                class="dropdown-wrapper" aria-hidden="true"></span></span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
                <div class="form-group">
                    <div class="input-group input-group-lg">
                        <input type="search" class="form-control form-control-lg" placeholder="Type your keywords here"
                            value="Lorem ipsum">
                        <div class="input-group-append">
                            <button type="submit" class="btn btn-lg btn-default">
                                <i class="fa fa-search"></i>
                            </button>
                        </div>
                        <table class="table table-striped">
                            <tr>
                                <th>@lang('messages.admin.organization_name')</th>
                                <th>@lang('messages.admin.organization_code')</th>
                                <th>@lang('messages.admin.representative_name')</th>
                                <th>@lang('messages.admin.representative_email')</th>
                                <th>@lang('messages.admin.edc_coordination')</th>
                                <th>@lang('messages.admin.patient_observation')</th>
                                <th>@lang('messages.admin.registered_at')</th>
                                <th>@lang('messages.admin.status')</th>
                            </tr>
                        </table>

                    </div>
                </div>
            </div>
        </div>
    </div>
@stop
