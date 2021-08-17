<table class="table table-striped">
    <tr>
        <th>ユーザー名</th>
        <th>組織名</th>
        <th>登録日時</th>
        <th>ステータス</th>
    </tr>
    @foreach ($organization_admin_users as $organization_admin_user)
    <tr>
        <td>
            <a  
                href="#" 
                class="show-edit-modal" 
                data-id="{{ $organization_admin_user->id }}"
                data-url="{{ route('admin.org_admin_user.edit') }}"
                data-method="GET"
            >
                {{ $organization_admin_user->name }}
            </a>
        </td>
        <td>{{ $organization_admin_user->organization_name }}</td>
        <td>{{ $organization_admin_user->created_at }}</td>
        <td>
        @if ($organization_admin_user->status === trans('messages.valid'))
        <div class="badge badge-primary">
        @else
        <div class="badge badge-secondary">
        @endif
        {{ $organization_admin_user->status }}</div></td>
    </tr>
    @endforeach
</table>
<div class="mt-3">{{ $organization_admin_users->links('components.pagination') }}</div>