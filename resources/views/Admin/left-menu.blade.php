<li class="nav-item has-treeview">
    <a href="{{ route('admin.organization.index') }}"
        class="nav-link {{ is_current_route('admin.organization*') ? 'active' : '' }}">
        <i class="fas fa-users"></i>
        <p>
            @lang('messages.organization_management')
            <i class="fas fa-angle-left right"></i>
        </p>
    </a>
    <a href="{{ route('admin.ventilator.index') }}"
        class="nav-link {{ is_current_route('admin.ventilator*') ? 'active' : '' }}">
        <i class="fas fa-users"></i>
        <p>
            @lang('messages.ventilator_management')
            <i class="fas fa-angle-left right"></i>
        </p>
    </a>
</li>
