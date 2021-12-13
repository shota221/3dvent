<li class="nav-item">
    <a href="{{ route('admin.organization.index') }}"
        class="nav-link {{ is_current_route('admin.organization.*') ? 'active' : '' }}">
        <i class="far fa-hospital"></i>
        <p>
            @lang('messages.organization_management')
        </p>
    </a>
</li>
<li class="nav-item">
    <a href="{{ route('admin.org_admin_user.index') }}"
        class="nav-link {{ is_current_route('admin.org_admin_user.*') ? 'active' : '' }}">
        <i class="fas fa-users"></i>
        <p>
            @lang('messages.organization_admin_user_management')
        </p>
    </a>
</li>
<li class="nav-item">
    <a href="{{ route('admin.ventilator.index') }}"
        class="nav-link {{ is_current_route('admin.ventilator.*') ? 'active' : '' }}">
        <i class="far fa-square"></i>
        <p>
            @lang('messages.ventilator_management')
        </p>
    </a>
</li>
<li class="nav-item">
    <a href="{{ route('admin.ventilator_value.index') }}"
        class="nav-link {{ is_current_route('admin.ventilator_value.*') ? 'active' : '' }}">
        <i class="far fa-list-alt"></i>
        <p>
            @lang('messages.ventilator_value_management')
        </p>
    </a>
</li>
<li class="nav-item">
    <a href="{{ route('admin.patient_value.index') }}"
        class="nav-link {{ is_current_route('admin.patient_value.*') ? 'active' : '' }}">
        <i class="far fa-list-alt"></i>
        <p>
            @lang('messages.patient_value_management')
        </p>
    </a>
</li>
<li class="nav-item">
    <a href="{{ route('admin.chat_support.index') }}" target="_blank" class="nav-link">
        <i class="fas fa-comment-dots"></i>
        <p>
            @lang('messages.chat_support')
        </p>
    </a>
</li>
