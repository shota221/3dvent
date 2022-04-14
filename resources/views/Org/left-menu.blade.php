<li class="nav-item">
    <a href="{{ route('org.setting.index') }}"
        class="nav-link {{ is_current_route('org.setting.*') ? 'active' : '' }}">
        <i class="fas fa-cog"></i>
        <p class="text-wrap">
            @lang('messages.organization_setting_management')
        </p>
    </a>
</li>

@if (OrgUserGate::canReadUser(Auth::user()))
<li class="nav-item">
    <a href="{{ route('org.user.index') }}"
        class="nav-link {{ is_current_route('org.user.*') ? 'active' : '' }}">
        <i class="fas fa-users"></i>
        <p class="text-wrap">
            @lang('messages.user_management')
        </p>
    </a>
</li>
@endif

<li class="nav-item">
    <a href="{{ route('org.ventilator.index') }}"
        class="nav-link {{ is_current_route('org.ventilator.*') ? 'active' : '' }}">
        <i class="far fa-square"></i>
        <p class="text-wrap">
            @lang('messages.ventilator_management')
        </p>
    </a>
</li>
<li class="nav-item">
    <a href="{{ route('org.ventilator_value.index') }}"
        class="nav-link {{ is_current_route('org.ventilator_value.*') ? 'active' : '' }}">
        <i class="far fa-list-alt"></i>
        <p class="text-wrap">
            @lang('messages.ventilator_value_management')
        </p>
    </a>
</li>
<li class="nav-item">
    <a href="{{ route('org.patient_value.index') }}"
        class="nav-link {{ is_current_route('org.patient_value.*') ? 'active' : '' }}">
        <i class="far fa-list-alt"></i>
        <p class="text-wrap">
            @lang('messages.patient_value_management')
        </p>
    </a>
</li>