    @component('components.searchForm', [
        'id' => 'organization-refined-search',
        'action' => route('admin.organization.async'),
        ])

        @slot('content')
            <div class="row">
                {{-- 組織コード --}}
                <div class="col-sm-4">
                    <div class="form-group">
                        <label>@lang('messages.organization_code')</label>
                        <input type="text" class="form-control form-control-sm" name="organization_code" id="organization_code">
                    </div>
                </div>

                {{-- 組織名 --}}
                <div class="col-sm-4">
                    <div class="form-group">
                        <label>@lang('messages.organization_name')</label>
                        <input type="text" class="form-control form-control-sm" name="organization_name" id="organization_name">
                    </div>
                </div>

                {{-- 代表者名 --}}
                <div class="col-sm-4">
                    <div class="form-group">
                        <label>@lang('messages.representative_name')</label>
                        <input type="text" class="form-control form-control-sm" name="representative_name"
                            id="representative_name">
                    </div>
                </div>


                {{-- 登録日時 --}}
                <div class="col-sm-4">
                    <div class="form-group">
                        <label>@lang('messages.registered_at')</label>
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

                {{-- edc連携 --}}
                <div class="col-sm-2">
                    <div class="form-group">
                        <label>@lang('messages.edc_link')</label>
                        <div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input form-control-sm" type="checkbox" name="edc_linked_flg" value="1"
                                    checked="">
                                <label class="form-check-label">@lang('messages.linked')</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input form-control-sm" type="checkbox" name="edc_linked_flg" value="0"
                                    checked="">
                                <label class="form-check-label">@lang('messages.unlinked')</label>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- 患者観察研究 --}}
                <div class="col-sm-2">
                    <div class="form-group">
                        <label>@lang('messages.patient_observation')</label>
                        <div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input form-control-sm" type="checkbox" name="patient_obs_approved_flg"
                                    value="1" checked="">
                                <label class="form-check-label">@lang('messages.approved')</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input form-control-sm" type="checkbox" name="patient_obs_approved_flg"
                                    value="0" checked="">
                                <label class="form-check-label">@lang('messages.unapproved')</label>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- スタータス --}}
                <div class="col-sm-2">
                    <div class="form-group">
                        <label>@lang('messages.status')</label>
                        <div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input form-control-sm" type="checkbox" name="disabled_flg" value="0"
                                    checked="">
                                <label class="form-check-label">@lang('messages.valid')</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input form-control-sm" type="checkbox" name="disabled_flg" value="1"
                                    checked="">
                                <label class="form-check-label">@lang('messages.invalid')</label>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endslot
    @endcomponent
