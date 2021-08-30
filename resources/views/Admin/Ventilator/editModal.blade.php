    {{-- 編集 --}}
    @component('components.modal', [
        'id' => 'modal-ventilator-update',
        'form' => ['method' => 'PUT', 'action' => route('admin.ventilator.update'), 'name' => 'ventilator-update'],
        ])
        @slot('title')
            @lang('messages.ventilator_edit')
        @endslot

        @slot('content')
            {{-- MicroVentコード --}}
            <input type="hidden" name="id">
            <div class="form-group">
                <label for="gs1_code">@lang('messages.ventilator_code')</span></label>
                <div>
                    <input class="form-control" type="text" name="gs1_code" disabled>
                </div>
            </div>

            {{-- シリアル番号 --}}
            <div class="form-group">
                <label for="serial_number">@lang('messages.serial_number')</label>
                <div>
                    <input class="form-control" type="text" name="serial_number" disabled>
                </div>
            </div>

            {{-- 所属組織 --}}
            <div class="form-group">
                <label for="organization_name">@lang('messages.affiliation_organization')</label>
                <div>
                    <input class="form-control" type="text" name="organization_name" disabled>
                </div>
            </div>

            {{-- 登録者 --}}
            <div class="form-group">
                <label for="registered_user_name">@lang('messages.registered_user_name')</span></label>
                <div>
                    <input class="form-control" type="text" name="registered_user_name" disabled>
                </div>
            </div>

            {{-- 有効期限 --}}
            <div class="form-group">
                <label for="expiration_date">@lang('messages.expiration_date')</label>
                <div>
                    <input class="form-control" type="text" name="expiration_date" disabled>
                </div>
            </div>

            {{-- 患者番号 --}}
            <div class="form-group">
                <label for="patient_code">@lang('messages.patient_code')</label>
                <div>
                    <input class="form-control" type="text" name="patient_code" disabled>
                </div>
            </div>

            {{-- 使用開始日時 --}}
            <div class="form-group">
                <label for="start_using_at">@lang('messages.start_using_at')<span class="required"></span></label>
                <div>
                    <input class="form-control" type="text" name="start_using_at" required>
                </div>
            </div>
        @endslot
    @endcomponent
