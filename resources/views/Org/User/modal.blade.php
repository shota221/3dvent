{{--新規登録--}}
@component('components.modal', [
    'id'   => 'register-modal',
    'form' => ['method' => 'POST', 'action' => route('org.user.create'),
    'name' => 'create'],
    ])
    @slot('title')
        @lang('messages.user_create')
    @endslot

    @slot('content')
        {{--ユーザー名--}}
        <div class="form-group">
            <label for="name">@lang('messages.user_name')<span class="required"></span></label>
            <div>
                <input type="text" class="form-control" name="name" required>
            </div>
        </div>
        
        {{--メールアドレス--}}
        <div class="form-group">
            <label for="email">@lang('messages.email')</label>
            <div>
                <input type="text" class="form-control" name="email" required>
            </div>
        </div>
        
        {{--権限--}}
        <div class="form-group">
            <label for="org_authority_type">@lang('messages.user_authority')<span class="required"></span></label>
            <select class="form-control select" name="org_authority_type">
                <option></option>
                @foreach (OrgUserGate::AUTHORITIES as $key => $value)
                <option value="{{$value['type']}}">@lang('messages.' . $key)</option>
                @endforeach
            </select>
        </div>

        {{-- ステータス --}}
        <div class="form-group">
            <label class="d-block">@lang('messages.status')<span class="required"></span></label>
            <div class="form-check form-check-inline">
                <label class="form-check-label">
                    <input class="form-check-input" type="radio" name="disabled_flg" value="0"
                        checked required>@lang('messages.valid')
                </label>
            </div>
            <div class="form-check form-check-inline">
                <label class="form-check-label">
                    <input class="form-check-input" type="radio" name="disabled_flg"
                        value="1">@lang('messages.invalid')
                </label>
            </div>
        </div>
        
        {{--パスワード--}}
        <div class="form-group">
            <label for="password">@lang('messages.password')<span class="required"></span></label>
            <div>
                <input type="password" class="form-control" name="password" placeholder="@lang('messages.password_placeholder')" required>
            </div>
        </div>
        
        {{--パスワード(確認用)--}}
        <div class="form-group">
            <label for="password_confirmation">@lang('messages.password_confirmation')<span class="required"></span></label>
            <div>
                <input type="password" class="form-control" name="password_confirmation" required>
            </div>
        </div>
    @endslot
@endcomponent

{{--csvインポート--}}
@component('components.modal', [
    'id'   => 'csv-import-modal',
    'form' => ['method' => 'POST', 'action' => route('org.user.import_user_csv_data'),
    'name' => 'csv-import'],
    ])
    @slot('title')
        @lang('messages.csv_import')
    @endslot

    @slot('content')        
        {{-- CSVファイル選択 --}}
        <div class="form-group">
            <label for="csv_file">@lang('messages.csv_file')<span class="required"></span></label>
            <input class="form-control-file" type="file" id="file" name="csv_file">
        </div>
    @endslot
@endcomponent

{{--編集--}}
@component('components.modal', [
    'id'   => 'edit-modal',
    'form' => ['method' => 'PUT', 'action' => route('org.user.update'),
    'name' => 'update'],
    ])
    @slot('title')
        @lang('messages.user_edit')
    @endslot

    @slot('content')
        <input type="hidden" name="id">

        {{--ユーザー名--}}
        <div class="form-group">
            <label for="name">@lang('messages.user_name')<span class="required"></span></label>
            <div>
                <input type="text" class="form-control" name="name" required>
            </div>
        </div>
        
        {{--メールアドレス--}}
        <div class="form-group">
            <label for="email">@lang('messages.email')<span class="required"></span></label>
            <div>
                <input type="text" class="form-control" name="email" required>
            </div>
        </div>

        {{--権限--}}
        <div class="form-group">
            <label for="org_authority_type">@lang('messages.user_authority')</label>
            <select class="form-control select" name="org_authority_type">
                <option></option>
                @foreach (OrgUserGate::AUTHORITIES as $key => $value)
                <option value="{{$value['type']}}">@lang('messages.' . $key)</option>
                @endforeach
            </select>
        </div>

        {{-- ステータス --}}
        <div class="form-group">
            <label class="d-block">@lang('messages.status')<span class="required"></span></label>
            <div class="form-check form-check-inline">
                <label class="form-check-label"><input class="form-check-input" type="radio" name="disabled_flg" value="0" required>@lang('messages.valid')</label>
            </div>
            <div class="form-check form-check-inline">
                <label class="form-check-label"><input class="form-check-input" type="radio" name="disabled_flg" value="1">@lang('messages.invalid')</label>
            </div>
        </div>


        {{--パスワード変更--}}
        <div class="form-group">
            <label class="d-block">@lang('messages.change_login_password')</label>
            <div class="form-check form-check-inline">
                <label class="form-check-label"><input class="form-check-input" type="checkbox" name="password_changed" id="password-changed" value="1">@lang('messages.change_password')</label>
            </div>
        </div>
        
        <div class="password-change-field collapse">
            {{--パスワード--}}
            <div class="form-group">
                <label for="password">@lang('messages.password')<span class="required"></span></label>
                <div>
                    <input type="password" class="form-control" name="password" placeholder="@lang('messages.password_placeholder')" required>
                </div>
            </div>
            
            {{--パスワード(確認用)--}}
            <div class="form-group">
                <label for="password_confirmation">@lang('messages.password_confirmation')<span class="required"></span></label>
                <div>
                    <input type="password" class="form-control" name="password_confirmation" required>
                </div>
            </div>
        </div>

    @endslot
@endcomponent








