<table class="table table-striped">
    <tr>
        <th>@lang('messages.user_name')</th>
        <th>@lang('messages.organization_name')</th>
        <th>@lang('messages.registered_at')</th>
        <th>@lang('messages.status')</th>
    </tr>
    @foreach ($organization_admin_users as $organization_admin_user)
    <tr>
        <td>
            <a  
                href="#" 
                class="show-edit-modal" 
                data-url="{{ route('admin.org_admin_user.detail', ['id' => $organization_admin_user->id]) }}"
                data-method="GET"
            >
                {{ $organization_admin_user->name }}
            </a>
        </td>
        <td>{{ $organization_admin_user->organization_name }}</td>
        <td>{{ $organization_admin_user->created_at }}</td>
        <td>
        @if (! $organization_admin_user->disabled_flg)
        <div class="badge badge-primary">@lang('messages.valid')
        @else
        <div class="badge badge-secondary">@lang('messages.invalid')
        @endif
        </div></td>
    </tr>
    @endforeach
</table>
<div class="mt-3">{{ $organization_admin_users->links('components.pagination') }}</div>