{{--新規登録--}}
@component('components.modal', [
    'id'   => 'register-modal',
    'form' => ['method' => 'POST', 'action' => route('admin.org_admin_user.create'),
    'name' => 'create'],
    ])
    @slot('title')
        @lang('messages.organization_admin_user_create')
    @endslot

    @slot('content')
        {{--組織コード--}}
        <div class="form-group">
            <label for="code">@lang('messages.organization_code')<span class="required"></span></label>
            <div>
                <input type="text" class="form-control" name="code" required>
            </div>
        </div>

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

{{--編集--}}
@component('components.modal', [
    'id'   => 'edit-modal',
    'form' => ['method' => 'PUT', 'action' => route('admin.org_admin_user.update'),
    'name' => 'update'],
    ])
    @slot('title')
        @lang('messages.organization_admin_user_edit')
    @endslot

    @slot('content')
        <input type="hidden" name="id">
        {{--組織コード--}}
        <div class="form-group">
            <label for="code">@lang('messages.organization_code')</span></label>
            <div>
                <input type="text" class="form-control" name="code" readonly>
            </div>
        </div>

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






