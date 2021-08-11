    {{-- ユーザー一覧 --}}
    @component('components.modal', [
        'id' => 'modal-users-list',
        ])
        @slot('title')
            @lang('messages.admin.users_list')
        @endslot

        @slot('content')
        <div id="users-list">
            @include('Admin.Organization.usersList')
        </div>
        @endslot
    @endcomponent
