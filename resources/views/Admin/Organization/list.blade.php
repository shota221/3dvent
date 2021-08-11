<table class="table table-striped">
    <tr>
        <th>@lang('messages.admin.organization_name')</th>
        <th>@lang('messages.admin.organization_code')</th>
        <th>@lang('messages.admin.representative_name')</th>
        <th>@lang('messages.admin.edc_link')</th>
        <th>@lang('messages.admin.patient_observation')</th>
        <th>@lang('messages.admin.registered_at')</th>
        <th>@lang('messages.admin.status')</th>
        <th>@lang('messages.admin.users_list')</th>
    </tr>
    @foreach ($organization_paginator as $organization)
        <tr data-id="{{ $organization->id }}" data-organization_name="{{ $organization->organization_name }}"
            data-organization_code="{{ $organization->organization_code }}"
            data-representative_name="{{ $organization->representative_name }}"
            data-representative_email="{{ $organization->representative_email }}"
            data-disabled_flg="{{ $organization->disabled_flg }}" data-edcid="{{ $organization->edcid }}"
            data-patient_obs_approved_flg="{{ $organization->patient_obs_approved_flg }}">
            <td class="align-middle"><a href="#" class="show-edit-modal">{{ $organization->organization_name }}</a>
            </td>
            <td class="align-middle">{{ $organization->organization_code }}</td>
            <td class="align-middle">{{ $organization->representative_name }}</td>
            @if ($organization->edc_linked_flg)
                <td>
                    <div class="badge badge-primary">@lang('messages.admin.linked')</div>
                </td>
            @else
                <td>
                    <div class="badge badge-secondary">@lang('messages.admin.unlinked')</div>
                </td>
            @endif
            @if ($organization->patient_obs_approved_flg)
                <td>
                    <div class="badge badge-primary">@lang('messages.admin.approved')</div>
                </td>
            @else
                <td>
                    <div class="badge badge-secondary">@lang('messages.admin.unapproved')</div>
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
            <td class="align-middle"><a href="#" class="show-users-list-modal" data-url="{{route('admin.organization.users')}}" data-method="GET">@lang('messages.list')</a></td>
        </tr>
    @endforeach
</table>
<div class="mt-3">{{ $organization_paginator->links('components.pagination') }}</div>
<div id="overlay">
    <div class="cv-spinner">
        <span class="spinner"></span>
    </div>
</div>
