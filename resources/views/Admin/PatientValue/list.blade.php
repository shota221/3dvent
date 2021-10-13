<table class="table table-responsive table-striped" style="table-layout:fixed;">
    <tr>
        <th>
            <div class="form-check">
                <input type="checkbox" class="form-check-input position-static" id="bulk-check">
            </div>
        </th>
        {{--患者番号--}}
        <th>@lang('messages.patient_code')</th>

        {{--組織名--}}
        <th>@lang('messages.organization_name')</th>

        {{--登録者--}}
        <th>@lang('messages.registered_user_name')</th>

        {{--登録日時--}}
        <th>@lang('messages.registered_at')</th>

        {{--更新日時--}}
        <th>@lang('messages.updated_at')</th>

        {{--オプトアウト--}}
        <th>@lang('messages.opt_out')</th>

        {{--患者年齢--}}
        <th>@lang('messages.patient_age')</th>

        {{--MicroVent®を使用した原因病名--}}
        <th>@lang('messages.ventilator_disease_name')</th>

        {{--その他疾患名1--}}
        <th>@lang('messages.other_disease_name_1')</th>

        {{--その他疾患名2--}}
        <th>@lang('messages.other_disease_name_2')</th>

        {{--使用場所--}}
        <th>@lang('messages.used_place')</th>

        {{--病院名--}}
        <th>@lang('messages.hospital_name')</th>

        {{--国名--}}
        <th>@lang('messages.national_name')</th>

        {{--使用中止日時--}}
        <th>@lang('messages.discontinuation_at')</th>

        {{--使用中止時の転帰--}}
        <th>@lang('messages.outcome')</th>

        {{--使用中止後の呼吸不全治療--}}
        <th>@lang('messages.treatment')</th>

        {{--機器に関する有害事象--}}
        <th>@lang('messages.ventilator_adverse_event_flg')</th>

        {{--機器に関する有害事象の内容--}}
        <th>@lang('messages.ventilator_adverse_event_contents')</th>

        {{--編集--}}
        <th>@lang('messages.edit')</th>
    </tr>
    @foreach ($patient_values as $patient_value)
    <tr>
        <td>
            <div class="form-check">
                <input type="checkbox" class="form-check-input item-check position-static" value="{{ $patient_value->id }}">
            </div>
        </td>
        <td>{{ $patient_value->patient_code }}</td>
        <td>{{ $patient_value->organization_name }}</td>
        <td>{{ $patient_value->registered_user_name }}</td>
        <td>{{ $patient_value->registered_at }}</td>
        <td>{{ $patient_value->updated_at }}</td>
        
        @if ($patient_value->opt_out_flg)
        {{--有--}}
        <td>@lang('messages.exists')</td>
        @else
        {{--無--}}
        <td>@lang('messages.none')</td>
        @endif
        
        <td>{{ $patient_value->age }}</td>
        <td>{{ $patient_value->vent_disease_name }}</td>
        <td>{{ $patient_value->other_disease_name_1 }}</td>
        <td>{{ $patient_value->other_disease_name_2 }}</td>
        <td>{{ $patient_value->used_place_name }}</td>
        <td>{{ $patient_value->hospital }}</td>
        <td>{{ $patient_value->national }}</td>
        <td>{{ $patient_value->discontinuation_at }}</td>
        <td>{{ $patient_value->outcome_name }}</td>
        <td>{{ $patient_value->treatment_name }}</td>
        
        @if ($patient_value->adverse_event_flg)
        {{--有--}}
        <td>@lang('messages.exists')</td>
        @else
        {{--無--}}
        <td>@lang('messages.none')</td>
        @endif
        
        <td style="white-space: normal;">{{ $patient_value->adverse_event_contents }}</td>
        {{--編集--}}
        <td>
            <a 
                href="#" 
                class="show-edit-modal" 
                data-url="{{ route('admin.patient_value.detail', ['id' => $patient_value->id]) }}" 
                data-method="GET"
            >
                @lang('messages.edit')
            </a>
        </td>
    </tr>
    @endforeach
</table>

<div class="mt-3">{{ $patient_values->links('components.pagination') }}</div>

{{--選択項目を削除--}}
<div>
    <button type="btn" class="btn btn-danger btn-sm" data-url="{{ route('admin.patient_value.logical_delete') }}"
        data-method="delete" id="btn-bulk-delete">@lang('messages.bulk_delete')</button>
</div>