<table class="table table-responsive table-striped">
    <tr>
        <th>
            <div class="form-check"><input type="checkbox" class="form-check-input position-static" id="bulk-check">
            </div>
        </th>
        {{-- MicroVentコード --}}
        <th>@lang('messages.ventilator_code')</th>
        {{-- 患者番号 --}}
        <th>@lang('messages.patient_code')</th>
        {{-- 所属組織 --}}
        <th>@lang('messages.affiliation_organization')</th>
        {{-- 登録者 --}}
        <th>@lang('messages.registered_user_name')</th>
        {{-- 登録日時 --}}
        <th>@lang('messages.registered_at')</th>
        {{-- 更新日時 --}}
        <th>@lang('messages.updated_at')</th>
        {{-- ステータス --}}
        <th>@lang('messages.status')</th>
        {{-- 身長 --}}
        <th>@lang('messages.height')</th>
        {{-- 体重 --}}
        <th>@lang('messages.weight')</th>
        {{-- 性別 --}}
        <th>@lang('messages.gender')</th>
        {{-- 設定圧 --}}
        <th>@lang('messages.airway_pressure')</th>
        {{-- 空気流量 --}}
        <th>@lang('messages.air_flow')</th>
        {{-- 酸素流量 --}}
        <th>@lang('messages.o2_flow')</th>
        {{-- FiO2 --}}
        <th>@lang('messages.fio2')</th>
        {{-- RR --}}
        <th>@lang('messages.rr')</th>
        {{-- Estimated Vt --}}
        <th>@lang('messages.estimated_vt')</th>
        {{-- Estimated MV --}}
        <th>@lang('messages.estimated_mv')</th>
        {{-- Estimated PEEP --}}
        <th>@lang('messages.estimated_peep')</th>
        {{-- 使用状況 --}}
        <th>@lang('messages.status_use')</th>
        {{-- 使用状況（その他の場合） --}}
        <th>@lang('messages.status_use_other')</th>
        {{-- 経皮酸素飽和度 --}}
        <th>@lang('messages.spo2')</th>
        {{-- 終末呼気炭酸ガス濃度 --}}
        <th>@lang('messages.etco2')</th>
        {{-- PaO2 --}}
        <th>@lang('messages.pao2')</th>
        {{-- PaCO2 --}}
        <th>@lang('messages.paco2')</th>
        {{-- 編集 --}}
        <th>@lang('messages.edit')</th>
    </tr>
    @foreach ($ventilator_values as $ventilator_value)
        <tr>
            <td class="align-middle">
                <div class="form-check"><input type="checkbox" class="form-check-input item-check position-static"
                        value={{ $ventilator_value->id }}>
                </div>
            </td>
            <td class="align-middle">{{ $ventilator_value->gs1_code }}</td>
            <td class="align-middle">{{ $ventilator_value->patient_code }}</td>
            <td class="align-middle">{{ $ventilator_value->organization_name }}</td>
            <td class="align-middle">{{ $ventilator_value->registered_user_name }}</td>
            <td class="align-middle">{{ $ventilator_value->registered_at }}</td>
            <td class="align-middle">{{ $ventilator_value->updated_at }}</td>
            <td>
                @if ($ventilator_value->fixed_flg)
                    <div class="badge badge-primary">@lang('messages.fixed_value')</div>
                @endif
                @if ($ventilator_value->confirmed_flg)
                    <div class="badge badge-success">@lang('messages.confirmed')</div>
                @endif
            </td>
            <td class="align-middle">{{ $ventilator_value->height }}</td>
            <td class="align-middle">{{ $ventilator_value->weight }}</td>
            <td class="align-middle">{{ $ventilator_value->gender }}</td>
            <td class="align-middle">{{ $ventilator_value->airway_pressure }}</td>
            <td class="align-middle">{{ $ventilator_value->air_flow }}</td>
            <td class="align-middle">{{ $ventilator_value->o2_flow }}</td>
            <td class="align-middle">{{ $ventilator_value->fio2 }}</td>
            <td class="align-middle">{{ $ventilator_value->rr }}</td>
            <td class="align-middle">{{ $ventilator_value->estimated_vt }}</td>
            <td class="align-middle">{{ $ventilator_value->estimated_mv }}</td>
            <td class="align-middle">{{ $ventilator_value->estimated_peep }}</td>
            <td class="align-middle">{{ $ventilator_value->status_use }}</td>
            <td class="align-middle">{{ $ventilator_value->status_use_other }}</td>
            <td class="align-middle">{{ $ventilator_value->spo2 }}</td>
            <td class="align-middle">{{ $ventilator_value->etco2 }}</td>
            <td class="align-middle">{{ $ventilator_value->pao2 }}</td>
            <td class="align-middle">{{ $ventilator_value->paco2 }}</td>
            {{-- 編集 --}}
            <td>
                <a href="#" class="show-edit-modal" data-id="{{ $ventilator_value->id }}"
                    data-url="{{ route('admin.ventilator_value.detail') }}" data-method="GET">
                    @lang('messages.edit')
                </a>
            </td>
        </tr>
        </tr>
    @endforeach

</table>
<div class="row">
    <button type="button" class="btn btn-danger" data-url="{{ route('admin.ventilator_value.bulk_delete') }}"
        data-method="delete" id="btn-bulk-delete">@lang('messages.bulk_delete')</button>
</div>
<div class="mt-3">{{ $ventilator_values->links('components.pagination') }}</div>