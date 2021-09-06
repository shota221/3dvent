<li class="nav-item has-treeview">
    <a href="{{ route('admin.organization.index') }}"
        class="nav-link {{ is_current_route('admin.organization.*') ? 'active' : '' }}">
        <i class="fas fa-users"></i>
        <p>
            @lang('messages.organization_management')
            <i class="fas fa-angle-left right"></i>
        </p>
    </a>
    <a href="{{ route('admin.org_admin_user.index') }}"
        class="nav-link {{ is_current_route('admin.org_admin_user.*') ? 'active' : '' }}">
        <i class="fas fa-users"></i>
        <p>
            @lang('messages.organization_admin_user_management')
            <i class="fas fa-angle-left right"></i>
        </p>
    </a>
    <a href="{{ route('admin.ventilator.index') }}"
        class="nav-link {{ is_current_route('admin.ventilator.*') ? 'active' : '' }}">
        <i class="fas fa-users"></i>
        <p>
            @lang('messages.ventilator_management')
            <i class="fas fa-angle-left right"></i>
        </p>
    </a>
    <a href="{{ route('admin.ventilator_value.index') }}"
        class="nav-link {{ is_current_route('admin.ventilator_value.*') ? 'active' : '' }}">
        <i class="fas fa-users"></i>
        <p>
            @lang('messages.ventilator_value_management')
            <i class="fas fa-angle-left right"></i>
        </p>
    </a>
    <a href="{{ route('admin.patient_value.index') }}"
        class="nav-link {{ is_current_route('admin.patient_value.*') ? 'active' : '' }}">
        <i class="fas fa-users"></i>
        <p>
            @lang('messages.patient_value_management')
            <i class="fas fa-angle-left right"></i>
        </p>
    </a>
</li>
