    @component('components.searchForm', [
        'id' => 'ventilator_value-refined-search',
        'action' => route('admin.ventilator_value.search'),
        ])

        @slot('content')
        {{-- TODO:検索フォーム --}}
        @endslot
    @endcomponent
