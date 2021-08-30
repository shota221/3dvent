    {{-- ユーザー一覧 --}}
    @component('components.modal', [
        'id' => 'modal-user-list',
        ])
        @slot('title')
            @lang('messages.user_list')
        @endslot

        @slot('content')
        <div id="user-list">
            @include('Admin.Organization.userList')
        </div>
        @endslot
    @endcomponent
