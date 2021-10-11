    {{-- バグ一覧 --}}
    @component('components.modal', [
        'id' => 'modal-ventilator-bug-list',
        ])
        @slot('title')
            @lang('messages.ventilator_bug_list')
        @endslot

        @slot('content')
        <div id="ventilator-bug-list">
            @include('Org.Ventilator.ventilatorBugList')
        </div>
        @endslot
    @endcomponent
