<table class="table table-striped">
    <tr>
        <th>@lang('messages.ventilator_bug_name')</th>
        <th>@lang('messages.request_improvement')</th>
        <th>@lang('messages.registered_user_name')</th>
        <th>@lang('messages.registered_at')</th>
    </tr>
    @if (isset($bugs))
        @foreach ($bugs as $bug)
            <tr>
                <td class="align-middle">{{ $bug->bug_name }}</td>
                <td class="align-middle">{{ $bug->request_improvement }}</td>
                <td class="align-middle">{{ $bug->registered_user_name }}</td>
                <td class="align-middle">{{ $bug->registered_at }}</td>
            </tr>
        @endforeach
    @endif
</table>
