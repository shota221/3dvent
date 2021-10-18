@component('components.searchForm', [
    'id' => 'user-refined-search',
    'action' => route('org.user.search'),
    ])

    @slot('content')
    <div class="row">
        
        {{--ユーザー名--}}
        <div class="col-sm-4">
            <div class="form-group">
                <label>@lang('messages.user_name')</label>
                <input type="text" class="form-control form-control-sm" name="name">
            </div>
        </div>
                
        {{--権限--}}
        <div class="col-sm-4">
            <label for="org_authority_type">@lang('messages.user_authority')</label>
            <select class="form-control form-control-sm select" name="org_authority_type">
                <option></option>
                @foreach (OrgUserGate::AUTHORITIES as $key => $value)
                <option value="{{$value['type']}}">@lang('messages.' . $key)</option>
                @endforeach
            </select>
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
        
        {{--ステータス--}}
        <div class="col-sm-4">
            <div class="form-group">
                <label>@lang('messages.status')</label>
                <div>
                    <div class="form-check form-check-inline">
                        <input class="form-check-input form-control-sm" type="checkbox" name="disabled_flg" value="0" checked>
                        <label class="form-check-label">@lang('messages.valid')</label>
                    </div>
                    <div class="form-check form-check-inline">
                        <input class="form-check-input form-control-sm" type="checkbox" name="disabled_flg" value="1"  checked>
                        <label class="form-check-label">@lang('messages.invalid')</label>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endslot
@endcomponent