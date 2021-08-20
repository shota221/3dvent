    {{-- 編集 --}}
    @component('components.modal', [
        'id' => 'modal-ventilator-import',
        'form' => ['method' => 'POST', 'action' => route('admin.ventilator.import_csv'), 'name' => 'ventilator-import'],
        ])
        @slot('title')
            @lang('messages.admin.ventilator_import')
        @endslot

        @slot('content')
            {{-- 対象組織名 TODO:select2 --}}
            <div class="form-group">
                <label for="organization_id">@lang('messages.admin.target_organization_name')<span class="required"></span></label>
                <div>
                    <input class="form-control" type="text" name="organization_id">
                </div>
            </div>
            {{-- CSVファイル選択 --}}
            <div class="form-group">
                <label for="csv_file">@lang('messages.admin.csv_file')<span class="required"></span></label>
                <input class="form-control-file" type="file" id="file" name="csv_file">
            </div>
        @endslot
    @endcomponent
