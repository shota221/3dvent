    {{-- 編集 --}}
    @component('components.modal', [
        'id' => 'modal-ventilator-update',
        'form' => ['method' => 'PUT', 'action' => route('org.ventilator.update'), 'name' => 'ventilator-update'],
        ])
        @slot('title')
            {{-- MicroVent編集 --}}
            @lang('messages.ventilator_edit')
        @endslot

        @slot('content')
            {{-- MicroVentコード --}}
            <input type="hidden" name="id">
            <div class="form-group">
                <label for="gs1_code">@lang('messages.ventilator_code')</span></label>
                <div>
                    <input class="form-control" type="text" name="gs1_code" readonly>
                </div>
            </div>

            {{-- シリアル番号 --}}
            <div class="form-group">
                <label for="serial_number">@lang('messages.serial_number')</label>
                <div>
                    <input class="form-control" type="text" name="serial_number" readonly>
                </div>
            </div>

            {{-- 最寄りの都市 --}}
            <div class="form-group">
                <label for="nearest_city">@lang('messages.nearest_city')</span></label>
                <div>
                    <input class="form-control" type="text" name="nearest_city" readonly>
                </div>
            </div>

            {{-- 登録者 --}}
            <div class="form-group">
                <label for="registered_user_name">@lang('messages.registered_user_name')</span></label>
                <div>
                    <input class="form-control" type="text" name="registered_user_name" readonly>
                </div>
            </div>

            {{-- 有効期限 --}}
            <div class="form-group">
                <label for="expiration_date">@lang('messages.expiration_date')</label>
                <div>
                    <input class="form-control" type="text" name="expiration_date" readonly>
                </div>
            </div>

            {{-- 患者番号 --}}
            <div class="form-group">
                <label for="patient_code">@lang('messages.patient_code')</label>
                <div>
                    <input class="form-control" type="text" name="patient_code" readonly>
                </div>
            </div>

            {{-- 使用開始日時 --}}
            <div class="form-group">
                <label for="start_using_at">@lang('messages.start_using_at')<span class="required"></span></label>
                <div>
                    <input type="text" class="form-control datetime" name="start_using_at" required>
                </div>
            </div>
        @endslot
    @endcomponent
