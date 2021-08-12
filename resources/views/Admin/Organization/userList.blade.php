<table class="table table-striped">
    <tr>
        <th>@lang('messages.admin.user_name')</th>
        <th>@lang('messages.admin.user_authority')</th>
        <th>@lang('messages.admin.status')</th>
    </tr>
    @if (isset($users))
        @foreach ($users as $user)
            <tr>
                <td class="align-middle">{{ $user->name }}</td>
                <td class="align-middle">{{ $user->authority }}</td>
                @if (!$user->disabled_flg)
                    <td>
                        <div class="badge badge-primary">@lang('messages.valid')</div>
                    </td>
                @else
                    <td>
                        <div class="badge badge-secondary">@lang('messages.invalid')</div>
                    </td>
                @endif
            </tr>
        @endforeach
    @endif
</table>
