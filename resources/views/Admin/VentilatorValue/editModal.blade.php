    {{-- 編集 --}}
    @component('components.modal', [
        'id' => 'modal-ventilator_value-update',
        'form' => ['method' => 'PUT', 'action' => route('admin.ventilator_value.update'), 'name' => 'ventilator_value-update'],
        ])
        @slot('title')
            @lang('messages.ventilator_value_edit')
        @endslot

        @slot('content')
           {{-- TODO:整理 --}}
        @endslot
    @endcomponent
