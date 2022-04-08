    {{-- 編集 --}}
    @component('components.modal', [
        'id' => 'modal-ventilator_value-update',
        'form' => ['method' => 'PUT', 'action' => route('admin.ventilator_value.update'), 'name' =>
        'ventilator_value-update'],
        ])
        @slot('title')
            @lang('messages.ventilator_value_edit')
        @endslot

        @slot('content')
            <input type="hidden" name="id">
            <input type="hidden" name="organization_id">

            {{-- ステータス --}}
            <div class="form-group">
                <span name="status"></span>
            </div>

            {{-- 患者番号 --}}
            <div class="form-group">
                <label for="patient_code">@lang('messages.patient_code')</label>
                <div>
                    <input type="text" class="form-control" name="patient_code" readonly>
                </div>
            </div>

            {{-- 登録者 --}}
            <div class="form-group">
                <label for="registered_user_name">@lang('messages.registered_user_name')</label>
                <div>
                    <input type="text" class="form-control" name="registered_user_name" readonly>
                </div>
            </div>

            {{-- 身長 --}}
            <div class="form-group">
                <label for="height">@lang('messages.height')(@lang('units.height'))<span class="required"></span></label>
                <div>
                    <input type="text" class="form-control" name="height">
                </div>
            </div>

            {{-- 体重 --}}
            <div class="form-group">
                <label for="weight">@lang('messages.weight')(@lang('units.weight'))</label>
                <div>
                    <input type="text" class="form-control" name="weight">
                </div>
            </div>

            {{-- 性別 --}}
            <div class="form-group">
                <label class="d-block" for="gender">@lang('messages.gender')<span class="required"></span></label>
                <div class="form-check form-check-inline">
                    <label class="form-check-label"><input class="form-check-input" type="radio" name="gender" value="1"
                            required>@lang('messages.male')</label>
                </div>
                <div class="form-check form-check-inline">
                    <label class="form-check-label"><input class="form-check-input" type="radio" name="gender"
                            value="2">@lang('messages.female')</label>
                </div>
            </div>

            {{-- 設定圧 --}}
            <div class="form-group">
                <label for="airway_pressure">@lang('messages.airway_pressure')(@lang('units.airway_pressure'))<span class="required"></span></label>
                <div>
                    <input type="text" class="form-control" name="airway_pressure">
                </div>
            </div>

            {{-- 空気流量 --}}
            <div class="form-group">
                <label for="air_flow">@lang('messages.air_flow')(@lang('units.air_flow'))<span class="required"></span></label>
                <div>
                    <input type="text" class="form-control" name="air_flow">
                </div>
            </div>

            {{-- 酸素流量 --}}
            <div class="form-group">
                <label for="o2_flow">@lang('messages.o2_flow')(@lang('units.o2_flow'))<span class="required"></span></label>
                <div>
                    <input type="text" class="form-control" name="o2_flow">
                </div>
            </div>


            {{-- 使用状況 --}}
            <div class="form-group">
                <label for="status_use">@lang('messages.status_use')@lang('messages.status_use')</label>
                <select class="form-control select" name="status_use">
                    <option></option>
                    <option value="1">@lang('messages.respiratory_failure')</option>
                    <option value="2">@lang('messages.surgery')</option>
                    <option value="3">@lang('messages.inspection_procedure')</option>
                    <option value="4">@lang('messages.other')</option>
                </select>
            </div>

            {{-- 使用状況（その他の場合） --}}
            <div class="form-group">
                <label for="status_use_other">@lang('messages.status_use_other')@lang('messages.status_use_other')</label>
                <div>
                    <textarea class="form-control" name="status_use_other" rows="3" disabled></textarea>
                </div>
            </div>

            {{-- 経皮的酸素飽和度 --}}
            <div class="form-group">
                <label for="spo2">@lang('messages.spo2')(@lang('units.spo2'))</label>
                <div>
                    <input type="text" class="form-control" name="spo2">
                </div>
            </div>

            {{-- 終末呼気炭酸ガス濃度 --}}
            <div class="form-group">
                <label for="etco2">@lang('messages.etco2')(@lang('units.etco2'))</label>
                <div>
                    <input type="text" class="form-control" name="etco2">
                </div>
            </div>

            {{-- pao2 --}}
            <div class="form-group">
                <label for="pao2">@lang('messages.pao2')(@lang('units.pao2'))</label>
                <div>
                    <input type="text" class="form-control" name="pao2">
                </div>
            </div>

            {{-- paco2 --}}
            <div class="form-group">
                <label for="paco2">@lang('messages.paco2')(@lang('units.paco2'))</label>
                <div>
                    <input type="text" class="form-control" name="paco2">
                </div>
            </div>

            {{-- 確認しました --}}
            <div class="form-group">
                <div class="form-check form-check-inline">
                    <label class="form-check-label font-weight-bold" for="confirmed_flg">
                        <input class="form-check-input" type="checkbox" value="1" name="confirmed_flg" id="confirmed_flg">
                        @lang('messages.confirm')
                    </label>
                </div>
            </div>

        @endslot
    @endcomponent
