@extends('common.adminlte.page', [
'nav_left_menu' => 'Admin.left-menu',
'nav_top_menu' => 'Admin.top-menu',
'nav_top_class' => 'navbar-white',
'nav_left_class' => 'sidebar-dark-primary',
])

@section('title')
    @lang('messages.admin.project_administrator_page')
@stop

@section('role')
    @lang('messages.admin.project_administrator')
@stop

@section('page_js')
    <script src="{{ mix('js/admin/app.js') }}"></script>
    <script src="js/common/util/async.js"></script>
    <script src="js/common/util/pagination.js"></script>
    @yield('js')
@stop

@section('page_css')
    <link rel="stylesheet" href="{{ mix('css/admin/app.css') }}">

    @yield('css')
@stop

@section('page_modal')
    @yield('modal')
    {{-- 新規登録 --}}
    @component('components.modal', [
        'id' => 'modal-organization-create',
        'form' => ['method' => 'POST', 'action' => route('admin.organization.create'), 'name' => 'organization-create'],
        ])
        @slot('title')
            @lang('messages.admin.organization_create')
        @endslot

        @slot('content')
            {{-- 組織名 --}}
            <div class="form-group">
                <label for="organization_name">@lang('messages.admin.organization_name')<span class="required"></span></label>
                <div>
                    <input class="form-control" type="text" name="organization_name" id="organization_name" required>
                </div>
            </div>

            {{-- 代表者名 --}}
            <div class="form-group">
                <label for="representative_name">@lang('messages.admin.representative_name')<span class="required"></span></label>
                <div>
                    <input class="form-control" type="text" name="representative_name" id="representative_name"
                        required>
                </div>
            </div>

            {{-- 代表者メールアドレス --}}
            <div class="form-group">
                <label for="representative_email">@lang('messages.admin.representative_email')<span class="required"></span></label>
                <div>
                    <input class="form-control" type="text" name="representative_email" id="representative_email"
                        required>
                </div>
            </div>

            {{-- 組織コード --}}
            <div class="form-group">
                <label for="organization_code">@lang('messages.admin.organization_code')<span class="required"></span></label>
                <div>
                    <input class="form-control" type="text" name="organization_code" id="organization_code" required>
                </div>
            </div>

            {{-- ステータス --}}
            <div class="form-group">
                <label class="d-block">@lang('messages.admin.status')<span class="required"></span></label>

                <div class="form-check form-check-inline">
                    <label class="form-check-label"><input class="form-check-input" type="radio" name="disabled_flg" value="0"
                            checked required>@lang('messages.valid')</label>
                </div>
                <div class="form-check form-check-inline">
                    <label class="form-check-label"><input class="form-check-input" type="radio" name="disabled_flg"
                            value="1">@lang('messages.invalid')</label>
                </div>
            </div>

            {{-- EDC施設ID --}}
            <div class="form-group">
                <label for="edc_id">@lang('messages.admin.edc_id')</label>
                <div>
                    <input class="form-control" type="text" name="edc_id" id="edc_id">
                </div>
            </div>

            {{-- 患者観察研究承認ステータス --}}
            <div class="form-group">
                <label class="d-block">@lang('messages.admin.patient_observation_status')<span class="required"></span></label>

                <div class="form-check form-check-inline">
                    <label class="form-check-label"><input class="form-check-input" type="radio" name="patient_obs_approved_flg"
                            value="1" required checked>@lang('messages.admin.approved')</label>
                </div>
                <div class="form-check form-check-inline">
                    <label class="form-check-label"><input class="form-check-input" type="radio" name="patient_obs_approved_flg"
                            value="0" checked>@lang('messages.admin.unapproved')</label>
                </div>
            </div>
        @endslot

    @endcomponent

    {{-- 編集 --}}
    @component('components.modal', [
        'id' => 'modal-organization-edit',
        'form' => ['method' => 'PUT', 'action' => route('admin.organization.create'), 'name' => 'organization-edit'],
        ])
        @slot('title')
            @lang('messages.admin.organization_edit')
        @endslot

        @slot('content')
            {{-- 組織名 --}}
            <div class="form-group">
                <label for="organization_name">@lang('messages.admin.organization_name')<span class="required"></span></label>
                <div>
                    <input class="form-control" type="text" name="organization_name" id="organization_name" required>
                </div>
            </div>

            {{-- 代表者名 --}}
            <div class="form-group">
                <label for="representative_name">@lang('messages.admin.representative_name')<span class="required"></span></label>
                <div>
                    <input class="form-control" type="text" name="representative_name" id="representative_name"
                        required>
                </div>
            </div>

            {{-- 代表者メールアドレス --}}
            <div class="form-group">
                <label for="representative_email">@lang('messages.admin.representative_email')<span class="required"></span></label>
                <div>
                    <input class="form-control" type="text" name="representative_email" id="representative_email"
                        required>
                </div>
            </div>

            {{-- 組織コード --}}
            <div class="form-group">
                <label for="organization_code">@lang('messages.admin.organization_code')<span class="required"></span></label>
                <div>
                    <input class="form-control" type="text" name="organization_code" id="organization_code" required>
                </div>
            </div>

            {{-- ステータス --}}
            <div class="form-group">
                <label class="d-block">@lang('messages.admin.status')<span class="required"></span></label>

                <div class="form-check form-check-inline">
                    <label class="form-check-label"><input class="form-check-input" type="radio" name="disabled_flg" value="0"
                            checked required>@lang('messages.valid')</label>
                </div>
                <div class="form-check form-check-inline">
                    <label class="form-check-label"><input class="form-check-input" type="radio" name="disabled_flg"
                            value="1">@lang('messages.invalid')</label>
                </div>
            </div>

            {{-- EDC施設ID --}}
            <div class="form-group">
                <label for="edc_id">@lang('messages.admin.edc_id')</label>
                <div>
                    <input class="form-control" type="text" name="edc_id" id="edc_id">
                </div>
            </div>

            {{-- 患者観察研究承認ステータス --}}
            <div class="form-group">
                <label class="d-block">@lang('messages.admin.patient_observation_status')<span class="required"></span></label>

                <div class="form-check form-check-inline">
                    <label class="form-check-label"><input class="form-check-input" type="radio" name="patient_obs_approved_flg"
                            value="1" required checked>@lang('messages.admin.approved')</label>
                </div>
                <div class="form-check form-check-inline">
                    <label class="form-check-label"><input class="form-check-input" type="radio" name="patient_obs_approved_flg"
                            value="0" checked>@lang('messages.admin.unapproved')</label>
                </div>
            </div>
        @endslot
    @endcomponent
@stop

@section('page_hidden')
@stop
