@extends('common.adminlte.page', [
    'nav_left_menu'  => 'Admin.left-menu',
    'nav_top_menu'   => 'Admin.top-menu',
    'nav_top_class'  => 'navbar-light navbar-white',
    'nav_left_class' => 'sidebar-dark-primary',
])

@section('title')
    @lang('messages.project_administrator_page')
@stop

@section('role')
    @lang('messages.project_administrator')
@stop

@section('page_js')
    <script src="{{ mix('js/admin/app.js') }}"></script>
    <script src="js/common/util/form.js"></script>
    <script src="js/common/util/async.js"></script>
    <script src="js/account.js"></script>
    <script src="js/switch_language.js"></script>
    @yield('js')
@stop

@section('page_css')
    <link rel="stylesheet" href="{{ mix('css/admin/app.css') }}">

    @yield('css')
@stop

@section('page_modal')
    @yield('modal')
        
    {{-- PROFILE EDIT MODAL --}}

    @component('components.modal', [
        'id'    => 'profile-edit-modal',
        'form'  => [ 
            'method' => 'PUT', 
            'action' => guess_route_path('account.async.profile'),
            'name'   => 'profile-update' 
        ],
    ])
        @slot('title')
            @lang('messages.edit_profile')
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
                <label for="email">@lang('messages.email')<span class="required"></span></label>
                <div>
                    <input type="text" class="form-control" name="email" required>
                </div>
            </div>

            {{--パスワード変更--}}
            <div class="form-group">
                <label class="d-block">@lang('messages.change_login_password')</label>
                <div class="form-check form-check-inline">
                    <label class="form-check-label"><input class="form-check-input" type="checkbox" name="password_changed" value="1">@lang('messages.change_password')</label>
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
@stop

@section('page_hidden')
@stop
