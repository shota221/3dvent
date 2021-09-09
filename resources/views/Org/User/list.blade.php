<table class="table table-striped">
    <tr>
        <th>
            <div class="form-check">
                <input type="checkbox" class="form-check-input position-static" id="bulk-check">
            </div>
        </th>
        {{--ユーザー名--}}
        <th>@lang('messages.user_name')</th>
        
        {{--権限--}}
        <th>@lang('messages.authority')</th>

        {{--登録日時--}}
        <th>@lang('messages.registered_at')</th>

        {{--ステータス--}}
        <th>@lang('messages.status')</th>
    </tr>
    @foreach ($users as $user)
    <tr>
        <td>
            <div class="form-check">
                <input type="checkbox" class="form-check-input item-check position-static" value="{{ $user->id }}">
            </div>
        </td>
        <td>
            <a  
                href="#" 
                class="show-edit-modal" 
                data-id="{{ $user->id }}"
                data-url="{{ route('org.user.detail') }}"
                data-method="GET"
            >
                {{ $user->name }}
            </a>
        </td>
        <td>{{ $user->authority }}</td>
        <td>{{ $user->created_at }}</td>
        <td>

        @if (! $user->disabled_flg)
        {{--有効--}}
        <div class="badge badge-primary">@lang('messages.valid')
        @else
        {{--無効--}}
        <div class="badge badge-secondary">@lang('messages.invalid')
        @endif
        </div></td>
    </tr>
    @endforeach
</table>

<div class="mt-3">{{ $users->links('components.pagination') }}</div>

{{--選択項目を削除--}}
<div>
    <button 
        type="btn" 
        class="btn btn-danger btn-sm" 
        data-url="{{ route('org.user.logical_delete') }}"
        data-method="delete" 
        id="btn-bulk-delete">
        @lang('messages.bulk_delete')
    </button>
</div>