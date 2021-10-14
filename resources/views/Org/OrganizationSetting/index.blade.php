@extends('Org.page')

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
    <script src="js/org/organization_setting/index.js"></script>
@stop

@section('content')
    <div class="container-fluid">
        <div id = "alert-message"></div>
        <div class="card card-default color-palette-box">
            <div class="card-header">
                <h3>@lang('messages.organization_setting_management')</h3>
            </div>
            <div class="card-body">
                <form id="setting-form" name="async-page-update">
                    <div>
                        <p class="font-weight-bold">
                            @lang('messages.common_setting')
                        </p>
                        <p class="font-weight-bold">
                            <span class="required"></span>&nbsp;@lang('messages.is_required_item')
                        </p>
                        <div class="form-group">
                            <label for="ventilator_value_scan_interval">@lang('messages.ventilator_value_scan_interval')<span class="required"></span></label>
                            <input 
                                class="form-control" 
                                type="text" 
                                name="ventilator_value_scan_interval" id="ventilator_value_scan_interval"
                                value={{$setting->ventilator_value_scan_interval}}
                            >
                        </div>
                        <div class="form-group">
                            <label for="vt_per_kg">@lang('messages.vt_per_kg')<span class="required"></span></label>
                            <input 
                                class="form-control" 
                                type="text" 
                                name="vt_per_kg" 
                                id="vt_per_kg"
                                value={{$setting->vt_per_kg}}
                            >
                        </div>
                    </div>
                </form>
            </div>
            <div class="card-footer">
                <div class="form-group">
                    <button 
                        class="btn btn-small btn-success btn-block" 
                        id="async-page-update" 
                        data-url="{{ route('org.setting.update') }}"
                        data-method="PUT">@lang('messages.update')
                    </button>
                </div>
            </div>
        </div>
    </div>
@stop


