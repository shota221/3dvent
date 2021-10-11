    @component('components.searchForm', [
        'id' => 'ventilator_value-refined-search',
        'action' => route('org.ventilator_value.search'),
        ])

        @slot('content')
            <div class="row">
                {{-- Microventコード --}}
                <div class="col-sm-4">
                    <div class="form-group">
                        <label>@lang('messages.ventilator_code')</label>
                        <input type="text" class="form-control form-control-sm" name="gs1_code">
                    </div>
                </div>

                {{-- 患者番号 --}}
                <div class="col-sm-4">
                    <div class="form-group">
                        <label>@lang('messages.patient_code')</label>
                        <input type="text" class="form-control form-control-sm" name="patient_code">
                    </div>
                </div>

                {{-- 登録者 --}}
                <div class="col-sm-4">
                    <div class="form-group">
                        <label>@lang('messages.registered_user_name')</label>
                        <input type="text" class="form-control form-control-sm" name="registered_user_name">
                    </div>
                </div>

                {{-- 登録日 --}}
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

                {{-- ステータス --}}
                <div class="col-sm-4">
                    <div class="form-group">
                        <label>@lang('messages.status')</label>
                        <div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input form-control-sm" type="checkbox" name="fixed_flg" value="1" id="search-fixed_flg-1">
                                <label class="form-check-label" for="search-fixed_flg-1">@lang('messages.show_fixed_values')</label>
                            </div>
                        </div>
                        <div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input form-control-sm" type="checkbox" name="confirmed_flg" value="1"
                                    checked="" id="search-confirmed_flg-1">
                                <label class="form-check-label" for="search-confirmed_flg-1">@lang('messages.confirmed')</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input form-control-sm" type="checkbox" name="confirmed_flg" value="0"
                                    checked="" id="search-confirmed_flg-0">
                                <label class="form-check-label" for="search-confirmed_flg-0">@lang('messages.unconfirmed')</label>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        @endslot
    @endcomponent
