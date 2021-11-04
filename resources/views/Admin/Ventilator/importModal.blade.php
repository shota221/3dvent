    @component('components.modal', [
        'id' => 'modal-ventilator-import',
        'form' => ['method' => 'POST', 'action' => route('admin.ventilator.async.queue_input_ventilator_data'), 'name' => 'ventilator-import'],
        ])
        @slot('title')
            {{-- インポート --}}
            @lang('messages.ventilator_import')
        @endslot

        @slot('content')
            {{-- 対象組織名 --}}
            <div class="form-group" data-url="{{ route('admin.organization.search_list') }}" data-method="GET" id="async-organization-data">
                <label for="organization_id">@lang('messages.target_organization_name')<span class="required"></span></label>
                <div>
                    <select class="select2-organization-name form-control form-control-sm select" name="organization_id" id="import-organization-name">
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
