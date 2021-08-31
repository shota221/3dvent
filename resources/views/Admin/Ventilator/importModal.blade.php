    @component('components.modal', [
        'id' => 'modal-ventilator-import',
        'form' => ['method' => 'POST', 'action' => route('admin.ventilator.import_csv'), 'name' => 'ventilator-import'],
        ])
        @slot('title')
            {{-- インポート --}}
            @lang('messages.ventilator_import')
        @endslot

        @slot('content')
            {{-- 対象組織名 --}}
            <div class="form-group" data-url="{{ route('admin.org_admin_user.async.organization_data') }}" data-method="GET" id="async-organization-data">
                <label for="organization_id">@lang('messages.target_organization_name')<span class="required"></span></label>
                <div>
                    <select class="form-control form-control-sm select" name="organization_id" id="select2-organization-name">
                        <option></option>
                    </select>
                </div>
            </div>
            {{-- CSVファイル選択 --}}
            <div class="form-group">
                <label for="csv_file">@lang('messages.csv_file')<span class="required"></span></label>
                <input class="form-control-file" type="file" id="file" name="csv_file">
            </div>
        @endslot
    @endcomponent
