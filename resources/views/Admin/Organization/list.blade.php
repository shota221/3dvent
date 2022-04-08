<table class="table table-striped">
    <tr>
        {{-- 組織名 --}}
        <th>@lang('messages.organization_name')</th>
        {{-- 組織コード --}}
        <th>@lang('messages.organization_code')</th>
        {{-- 代表者名 --}}
        <th>@lang('messages.representative_name')</th>
        {{-- EDC連携 --}}
        <th>@lang('messages.edc_link')</th>
        {{-- 患者観察研究ステータス --}}
        <th>@lang('messages.patient_observation')</th>
        {{-- 登録日時 --}}
        <th>@lang('messages.registered_at')</th>
        {{-- ステータス --}}
        <th>@lang('messages.status')</th>
        {{-- ユーザー一覧 --}}
        <th>@lang('messages.user_list')</th>
    </tr>
    @foreach ($organization_paginator as $organization)
        <tr data-id="{{ $organization->id }}" data-organization_name="{{ $organization->organization_name }}"
            data-organization_code="{{ $organization->organization_code }}"
            data-representative_name="{{ $organization->representative_name }}"
            data-representative_email="{{ $organization->representative_email }}"
            data-disabled_flg="{{ $organization->disabled_flg }}" data-edcid="{{ $organization->edcid }}"
            data-patient_obs_approved_flg="{{ $organization->patient_obs_approved_flg }}"
            data-language_code="{{ $organization->language_code }}">
            <td class="align-middle"><a href="#" class="show-edit-modal">{{ $organization->organization_name }}</a>
            </td>
            <td class="align-middle">{{ $organization->organization_code }}</td>
            <td class="align-middle">{{ $organization->representative_name }}</td>
            @if ($organization->edc_linked_flg)
                <td>
                    <div class="badge badge-primary">@lang('messages.linked')</div>
                </td>
            @else
                <td>
                    <div class="badge badge-secondary">@lang('messages.unlinked')</div>
                </td>
            @endif
            @if ($organization->patient_obs_approved_flg)
                <td>
                    <div class="badge badge-primary">@lang('messages.approved')</div>
                </td>
            @else
                <td>
                    <div class="badge badge-secondary">@lang('messages.unapproved')</div>
                </td>
            @endif
            <td class="align-middle">{{ $organization->registered_at }}</td>
            @if (!$organization->disabled_flg)
                <td>
                    <div class="badge badge-primary">@lang('messages.valid')</div>
                </td>
            @else
                <td>
                    <div class="badge badge-secondary">@lang('messages.invalid')</div>
                </td>
            @endif
            <td class="align-middle"><a href="#" class="show-user-list-modal" data-url="{{route('admin.organization.users')}}" data-method="GET">@lang('messages.user_list')</a></td>
        </tr>
    @endforeach
</table>
<div class="mt-3">{{ $organization_paginator->links('components.pagination') }}</div>
<div id="overlay">
    <div class="cv-spinner">
        <span class="spinner"></span>
    </div>
</div>
