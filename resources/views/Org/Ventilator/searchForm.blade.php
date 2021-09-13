    @component('components.searchForm', [
        'id' => 'ventilator-refined-search',
        'action' => route('org.ventilator.search'),
        ])

        @slot('content')
            <div class="row">
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
                                <input type="text" class="form-control form-control-sm date" name="expiration_date_from">
                            </div>
                            &nbsp;〜&nbsp;
                            <div class="col-sm-5">
                                <input type="text" class="form-control form-control-sm date" name="expiration_date_to">
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
                                <input type="text" class="form-control form-control-sm date" name="start_using_at_from">
                            </div>
                            &nbsp;〜&nbsp;
                            <div class="col-sm-5">
                                <input type="text" class="form-control form-control-sm date" name="start_using_at_to">
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
                                    checked="" id="search-has_bug-1">
                                <label class="form-check-label" for="search-has_bug-1">@lang('messages.exists')</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input form-control-sm" type="checkbox" name="has_bug" value="0"
                                    checked="" id="search-has_bug-0">
                                <label class="form-check-label" for="search-has_bug-0">@lang('messages.none')</label>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        @endslot
    @endcomponent
