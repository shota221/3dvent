@component('components.searchForm', [
    'id' => 'patient-refined-search',
    'action' => route('org.patient_value.search'),
    ])

    @slot('content')
    <div class="row">

        {{--患者番号--}}
        <div class="col-sm-4">
            <div class="form-group">
                <label>@lang('messages.patient_code')</label>
                <input type="text" class="form-control form-control-sm" name="patient_code" id="patient_code">
            </div>
        </div>
        
        {{--登録者--}}
        <div class="col-sm-4">
            <div class="form-group">
                <label>@lang('messages.registered_user_name')</label>
                <input type="text" class="form-control form-control-sm" name="registered_user_name" id="registered_user_name">
            </div>
        </div>

        {{--登録日--}}
        <div class="col-sm-4">
            <div class="form-group">
                <label>@lang('messages.registered_date')</label>
                <div class="row">
                    <div class="col-sm-5">
                        <input type="text" class="form-control form-control-sm date" name="registered_at_from">
                    </div>
                    &nbsp;〜&nbsp;
                    <div class="col-sm-5">
                        <input type="text" class="form-control form-control-sm date" name="registered_at_to">
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endslot
@endcomponent