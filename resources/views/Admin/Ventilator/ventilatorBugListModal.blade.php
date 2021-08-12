    {{-- ユーザー一覧 --}}
    @component('components.modal', [
        'id' => 'modal-ventilator-bug-list',
        ])
        @slot('title')
            @lang('messages.admin.ventilator_bug_list')
        @endslot

        @slot('content')
        <div id="ventilator-list">
            @include('Admin.Ventilator.ventilatorBugList')
        </div>
        @endslot
    @endcomponent
