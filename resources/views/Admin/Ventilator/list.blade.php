<table class="table table-striped">
    <tr>
        <th>
            <div class="form-check"><input type="checkbox" class="form-check-input position-static" id="bulk-check">
            </div>
        </th>
        <th>@lang('messages.ventilator_code')</th>
        <th>@lang('messages.serial_number')</th>
        <th>@lang('messages.affiliation_organization')</th>
        <th>@lang('messages.registered_user_name')</th>
        <th>@lang('messages.expiration_date')</th>
        <th>@lang('messages.registered_at')</th>
        <th>@lang('messages.ventilator_bug')</th>
        <th>@lang('messages.ventilator_value_list')</th>
    </tr>
    @foreach ($ventilator_paginator as $ventilator)
        <tr data-id="{{ $ventilator->id }}" data-serial_number="{{ $ventilator->serial_number }}"
            data-gs1_code="{{ $ventilator->gs1_code }}"
            data-organization_name="{{ $ventilator->organization_name }}"
            data-registered_user_name="{{ $ventilator->registered_user_name }}"
            data-expiration_date="{{ $ventilator->expiration_date }}"
            data-start_using_at="{{ $ventilator->start_using_at }}">
            <td class="align-middle">
                <div class="form-check"><input type="checkbox" class="form-check-input item-check position-static">
                </div>
            </td>
            <td class="align-middle"><a href="#" class="show-edit-modal"
                    data-url="{{ route('admin.ventilator.patient', ['id' => $ventilator->id]) }}"
                    data-method="GET">{{ $ventilator->gs1_code }}</a>
            </td>
            <td class="align-middle">{{ $ventilator->serial_number }}</td>
            <td class="align-middle">{{ $ventilator->organization_name }}</td>
            <td class="align-middle">{{ $ventilator->registered_user_name }}</td>
            <td class="align-middle">{{ $ventilator->expiration_date }}</td>
            <td class="align-middle">{{ $ventilator->start_using_at }}</td>
            @if ($ventilator->has_bug)
                <td class="align-middle">
                    <a href="#" class="show-ventilator-bug-list-modal" data-url="{{ route('admin.ventilator.bugs') }}"
                        data-method="GET">@lang('messages.exists')</a>
                </td>
            @else
                <td class="align-middle">
                    @lang('messages.none')
                </td>
            @endif
            <td class="align-middle"><a href="#">@lang('messages.detail')</a></td>
        </tr>
    @endforeach
</table>
<div class="row">
    <button type="button" class="btn btn-danger" data-url="{{ route('admin.ventilator.bulk_delete') }}"
        data-method="delete" id="btn-bulk-delete">@lang('messages.bulk_delete')</button>
</div>
<div class="mt-3">{{ $ventilator_paginator->links('components.pagination') }}</div>
