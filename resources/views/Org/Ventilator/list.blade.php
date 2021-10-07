<table class="table table-striped">
    <tr>
        {{-- Microventコード --}}
        <th>@lang('messages.ventilator_code')</th>
        {{-- シリアルナンバー --}}
        <th>@lang('messages.serial_number')</th>
        {{-- 登録者名 --}}
        <th>@lang('messages.registered_user_name')</th>
        {{-- 有効期限 --}}
        <th>@lang('messages.expiration_date')</th>
        {{-- 使用開始日時 --}}
        <th>@lang('messages.start_using_at')</th>
        {{-- 不具合 --}}
        <th>@lang('messages.ventilator_bug')</th>
        {{-- 詳細 --}}
        <th>@lang('messages.ventilator_value_list')</th>
    </tr>
    @foreach ($ventilator_paginator as $ventilator)
        <tr data-id="{{ $ventilator->id }}" data-serial_number="{{ $ventilator->serial_number }}"
            data-gs1_code="{{ $ventilator->gs1_code }}"
            data-registered_user_name="{{ $ventilator->registered_user_name }}"
            data-expiration_date="{{ $ventilator->expiration_date }}"
            data-start_using_at="{{ $ventilator->start_using_at }}">
            <td class="align-middle"><a href="#" class="show-edit-modal"
                    data-url="{{ route('org.ventilator.patient', ['id' => $ventilator->id]) }}"
                    data-method="GET">{{ $ventilator->gs1_code }}</a>
            </td>
            <td class="align-middle">{{ $ventilator->serial_number }}</td>
            <td class="align-middle">{{ $ventilator->registered_user_name }}</td>
            <td class="align-middle">{{ $ventilator->expiration_date }}</td>
            <td class="align-middle">{{ $ventilator->start_using_at }}</td>
            @if ($ventilator->has_bug)
                <td class="align-middle">
                    <a href="#" class="show-ventilator-bug-list-modal"
                        data-url="{{ route('org.ventilator.bugs') }}"
                        data-method="GET">@lang('messages.exists')</a>
                </td>
            @else
                <td class="align-middle">
                    @lang('messages.none')
                </td>
            @endif
            <td class="align-middle">
                <form method="POST" class="show-ventilator_values" action="{{route('org.ventilator_value.by_ventilator')}}">
                    @csrf
                    <input type="hidden" name="ventilator_id" value={{ $ventilator->id }}>
                    <a href="#">@lang('messages.detail')</a>
                </form>
            </td>
        </tr>
    @endforeach
</table>
<div class="mt-3">{{ $ventilator_paginator->links('components.pagination') }}</div>
