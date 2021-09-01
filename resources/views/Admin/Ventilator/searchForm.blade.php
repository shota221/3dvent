    @component('components.searchForm', [
        'id' => 'ventilator-refined-search',
        'action' => route('admin.ventilator.async'),
        ])

        @slot('content')
            <div class="row">

                {{-- 組織名 --}}
                <div class="col-sm-4">
                    <div class="form-group">
                        <label for="organization_id">@lang('messages.organization_name')</label>
                        <div>
                            <select class="form-control form-control-sm select" name="organization_id"
                                id="search-organization-name">
                                <option></option>
                            </select>
                        </div>
                    </div>
                </div>

                {{-- シリアルNo. --}}
                <div class="col-sm-4">
                    <div class="form-group">
                        <label>@lang('messages.serial_number')</label>
                        <input type="text" class="form-control form-control-sm" name="serial_number" id="serial_number">
                    </div>
                </div>

                {{-- 登録者名 --}}
                <div class="col-sm-4">
                    <div class="form-group">
                        <label>@lang('messages.registered_user_name')</label>
                        <input type="text" class="form-control form-control-sm" name="registered_user_name" id="registered_user_name">
                    </div>
                </div>

                {{-- 有効期限 --}}
                <div class="col-sm-4">
                    <div class="form-group">
                        <label>@lang('messages.expiration_date')</label>
                        <div class="row">
                            <div class="col-sm-5">
                                <input type="date" class="form-control form-control-sm" name="expiration_date_from">
                            </div>
                            &nbsp;〜&nbsp;
                            <div class="col-sm-5">
                                <input type="date" class="form-control form-control-sm" name="expiration_date_to">
                            </div>
                        </div>
                    </div>
                </div>

                {{-- 使用開始日時 --}}
                <div class="col-sm-4">
                    <div class="form-group">
                        <label>@lang('messages.start_using_at')</label>
                        <div class="row">
                            <div class="col-sm-5">
                                <input type="date" class="form-control form-control-sm" name="start_using_at_from">
                            </div>
                            &nbsp;〜&nbsp;
                            <div class="col-sm-5">
                                <input type="date" class="form-control form-control-sm" name="start_using_at_to">
                            </div>
                        </div>
                    </div>
                </div>

                {{-- 不具合 --}}
                <div class="col-sm-4">
                    <div class="form-group">
                        <label>@lang('messages.ventilator_bug')</label>
                        <div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input form-control-sm" type="checkbox" name="has_bug" value="1"
                                    checked="">
                                <label class="form-check-label">@lang('messages.exists')</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input form-control-sm" type="checkbox" name="has_bug" value="0"
                                    checked="">
                                <label class="form-check-label">@lang('messages.none')</label>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        @endslot
    @endcomponent
