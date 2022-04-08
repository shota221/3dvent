{{--編集--}}
@component('components.modal', [
    'id'   => 'edit-modal',
    'form' => [
        'method' => 'PUT',
        'action' => route('org.patient_value.update'),
        'name' => 'update'
        ],
    ])
    @slot('title')
        @lang('messages.patient_value_edit')
    @endslot

    @slot('content')
        <input type="hidden" name="id">
        {{--患者番号--}}
        <div class="form-group">
            <label for="patient_code">@lang('messages.patient_code')</label>
            <div>
                <input type="text" class="form-control" name="patient_code">
            </div>
        </div>

        {{--オプトアウト 除外しない/除外する(オプトアウト)--}}
        <div class="form-group">
            <label class="d-block">@lang('messages.opt_out')</label>
            <div class="form-check form-check-inline">
                <label class="form-check-label"><input class="form-check-input" type="radio" name="opt_out_flg" value="0" required>@lang('messages.unremove')</label>
            </div>
            <div class="form-check form-check-inline">
                <label class="form-check-label"><input class="form-check-input" type="radio" name="opt_out_flg" value="1">@lang('messages.remove')</label>
            </div>
        </div>

        {{--患者年齢--}}
        <div class="form-group">
            <label for="age">@lang('messages.patient_age')</label>
            <div>
                <input type="text" class="form-control" name="age">
            </div>
        </div>

        {{--MicroVent®を使用した原因病名--}}
        <div class="form-group">
            <label for="ventilator_disease_name">@lang('messages.ventilator_disease_name')</label>
            <div>
                <input type="text" class="form-control" name="vent_disease_name">
            </div>
        </div>

        {{--その他疾患名1--}}
        <div class="form-group">
            <label for="other_disease_name_1">@lang('messages.other_disease_name_1')</label>
            <div>
                <input type="text" class="form-control" name="other_disease_name_1">
            </div>
        </div>

        {{--その他疾患名2--}}
        <div class="form-group">
            <label for="other_disease_name_2">@lang('messages.other_disease_name_2')</label>
            <div>
                <input type="text" class="form-control" name="other_disease_name_2">
            </div>
        </div>

        {{--使用場所 1.救急車＋周囲/2.救命救急室/3.ICU/4.手術室/5.MRI室/6.その他検査室/7.ICU以外の病室/8.患者の家/9.その他の場所--}}
        <div class="form-group">
            <label for="used_place">@lang('messages.used_place')</label>
            <select class="form-control select" name="used_place">
                <option></option>
                <option value="1">@lang('messages.ambulance')</option>
                <option value="2">@lang('messages.emergency_room')</option>
                <option value="3">@lang('messages.icu')</option>
                <option value="4">@lang('messages.operating_room')</option>
                <option value="5">@lang('messages.mri')</option>
                <option value="6">@lang('messages.other_laboratories')</option>
                <option value="7">@lang('messages.non_icu')</option>
                <option value="8">@lang('messages.patient_house')</option>
                <option value="9">@lang('messages.other_places')</option>
            </select>
        </div>

        {{--病院名--}}
        <div class="form-group">
            <label for="hospital_name">@lang('messages.hospital_name')</label>
            <div>
                <input type="text" class="form-control" name="hospital">
            </div>
        </div>

        {{--国名--}}
        <div class="form-group">
            <label for="national_name">@lang('messages.national_name')</label>
            <div>
                <input type="text" class="form-control" name="national">
            </div>
        </div>

        {{--使用中止日時--}}
        <div class="form-group">
            <label for="discontinuation_at">@lang('messages.discontinuation_at')</label>
            <div>
                <input type="text" class="form-control datetime" name="discontinuation_at">
            </div>
        </div>

        {{--使用中止時の転帰 1.改善/2.不変/3.悪化/4.死亡--}}
        <div class="form-group">
            <label for="outcome">@lang('messages.outcome')</label>
            <select class="form-control select" name="outcome">
                <option></option>
                <option value="1">@lang('messages.improvement')</option>
                <option value="2">@lang('messages.immutable')</option>
                <option value="3">@lang('messages.deterioration')</option>
                <option value="4">@lang('messages.death')</option>
            </select>
        </div>

        {{--使用中止後の呼吸不全治療 1.なし/2.酸素投与のみ/3.NPPVに変更/4.他の人工呼吸器に変更/5.ECMO/6.その他--}}
        <div class="form-group">
            <label for="treatment">@lang('messages.treatment')</label>
            <select class="form-control select" name="treatment">
                <option></option>
                <option value="1">@lang('messages.none')</option>
                <option value="2">@lang('messages.oxygen_only')</option>
                <option value="3">@lang('messages.nppv')</option>
                <option value="4">@lang('messages.other_ventilator')</option>
                <option value="5">@lang('messages.ecmo')</option>
                <option value="6">@lang('messages.other')</option>
            </select>
        </div>

        {{--機器に関する有害事象 無/有--}}
        <div class="form-group">
            <label class="d-block">@lang('messages.ventilator_adverse_event_flg')</label>
            <div class="form-check form-check-inline">
                <label class="form-check-label"><input class="form-check-input" type="radio" name="adverse_event_flg" value="0" required>@lang('messages.none')</label>
            </div>
            <div class="form-check form-check-inline">
                <label class="form-check-label"><input class="form-check-input" type="radio" name="adverse_event_flg" value="1">@lang('messages.exists_adverse')</label>
            </div>
        </div>
        
        {{--機器に関する有害事象の内容--}}
        <div class="form-group">
            <label for="adverse_event_contents">@lang('messages.ventilator_adverse_event_contents')</label>
            <div>
                <textarea class="form-control" name="adverse_event_contents" rows="3"></textarea>
            </div>
        </div>

    @endslot
@endcomponent






