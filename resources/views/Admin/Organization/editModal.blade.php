    {{-- 編集 --}}
    @component('components.modal', [
        'id' => 'modal-organization-update',
        'form' => ['method' => 'PUT', 'action' => route('admin.organization.update'), 'name' => 'organization-update'],
        ])
        @slot('title')
            @lang('messages.organization_edit')
        @endslot

        @slot('content')
            {{-- 組織名 --}}
            <input type="hidden" name="id">
            <div class="form-group">
                <label for="organization_name">@lang('messages.organization_name')<span class="required"></span></label>
                <div>
                    <input class="form-control" type="text" name="organization_name" required>
                </div>
            </div>

            {{-- 代表者名 --}}
            <div class="form-group">
                <label for="representative_name">@lang('messages.representative_name')<span
                        class="required"></span></label>
                <div>
                    <input class="form-control" type="text" name="representative_name" required>
                </div>
            </div>

            {{-- 代表者メールアドレス --}}
            <div class="form-group">
                <label for="representative_email">@lang('messages.representative_email')<span
                        class="required"></span></label>
                <div>
                    <input class="form-control" type="text" name="representative_email" required>
                </div>
            </div>

            {{-- 組織コード --}}
            <div class="form-group">
                <label for="organization_code">@lang('messages.organization_code')<span class="required"></span></label>
                <div>
                    <input class="form-control" type="text" name="organization_code" required>
                </div>
            </div>

            {{-- ステータス 有効/無効 --}}
            <div class="form-group">
                <label class="d-block">@lang('messages.status')<span class="required"></span></label>

                <div class="form-check form-check-inline">
                    <label class="form-check-label"><input class="form-check-input" type="radio" name="disabled_flg" value="0"
                            required>@lang('messages.valid')</label>
                </div>
                <div class="form-check form-check-inline">
                    <label class="form-check-label"><input class="form-check-input" type="radio" name="disabled_flg"
                            value="1">@lang('messages.invalid')</label>
                </div>
            </div>

            {{-- EDC施設ID --}}
            <div class="form-group">
                <label for="edcid">@lang('messages.edcid')</label>
                <div>
                    <input class="form-control" type="text" name="edcid">
                </div>
            </div>

            {{-- 患者観察研究承認ステータス 承認済/未承認--}}
            <div class="form-group">
                <label class="d-block">@lang('messages.patient_observation_status')<span class="required"></span></label>

                <div class="form-check form-check-inline">
                    <label class="form-check-label"><input class="form-check-input" type="radio" name="patient_obs_approved_flg"
                            value="1" required>@lang('messages.approved')</label>
                </div>
                <div class="form-check form-check-inline">
                    <label class="form-check-label"><input class="form-check-input" type="radio" name="patient_obs_approved_flg"
                            value="0">@lang('messages.unapproved')</label>
                </div>
            </div>
        @endslot
    @endcomponent
