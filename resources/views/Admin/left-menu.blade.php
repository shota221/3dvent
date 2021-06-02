<li class="nav-item has-treeview">
    <a href="{{ route('admin.localgov_user.index') }}"
        class="nav-link {{ is_current_route('admin.localgov_user*') ? 'active' : '' }}">
        <i class="fas fa-users"></i>
        <p>
            自治体ユーザー管理
            <i class="fas fa-angle-left right"></i>
        </p>
    </a>
    <ul class="nav nav-treeview">
        <li class="nav-item">
            <a href="{{ route('admin.localgov_user.index') }}"
                class="nav-link  {{ is_current_route('admin.localgov_user.index*') ? 'active' : '' }}">
                <i class="fas fa-th-list"></i>
                <p>一覧</p>
            </a>
        </li>
        <li class="nav-item">
            <a href="{{ route('admin.localgov_user.registration') }}"
                class="nav-link {{ is_current_route('admin.localgov_user.registration*') ? 'active' : '' }}">
                <i class="fas fa-user-plus"></i>
                <p>新規登録</p>
            </a>
        </li>
    </ul>
</li>
<li class="nav-item has-treeview">
    <a href="{{ route('admin.sv_user.index') }}"
        class="nav-link {{ is_current_route('admin.sv_user*') ? 'active' : '' }}">
        <i class="fas fa-chalkboard-teacher"></i>
        <p>
            データ閲覧者管理
            <i class="fas fa-angle-left right"></i>
        </p>
    </a>
    <ul class="nav nav-treeview">
        <li class="nav-item">
            <a href="{{ route('admin.sv_user.index') }}"
                class="nav-link {{ is_current_route('admin.sv_user.index*') ? 'active' : '' }}">
                <i class="fas fa-th-list"></i>
                <p>一覧</p>
            </a>
        </li>
        <li class="nav-item">
            <a href="{{ route('admin.sv_user.registration') }}"
                class="nav-link {{ is_current_route('admin.sv_user.registration*') ? 'active' : '' }}">
                <i class="fas fa-user-plus"></i>
                <p>新規登録</p>
            </a>
        </li>
    </ul>
</li>
<li class="nav-item has-treeview">
    <a href="{{ route('admin.admin_user.index') }}"
        class="nav-link {{ is_current_route('admin.admin_user*') ? 'active' : '' }}">
        <i class="fas fa-user-tie"></i>
        <p>
            システム管理者管理
            <i class="fas fa-angle-left right"></i>
        </p>
    </a>
    <ul class="nav nav-treeview">
        <li class="nav-item">
            <a href="{{ route('admin.admin_user.index') }}"
                class="nav-link {{ is_current_route('admin.admin_user.index*') ? 'active' : '' }}">
                <i class="fas fa-th-list"></i>
                <p>一覧</p>
            </a>
        </li>
        <li class="nav-item">
            <a href="{{ route('admin.admin_user.registration') }}"
                class="nav-link {{ is_current_route('admin.admin_user.registration*') ? 'active' : '' }}">
                <i class="fas fa-user-plus"></i>
                <p>新規登録</p>
            </a>
        </li>
    </ul>
</li>
<li class="nav-item has-treeview">
    <a href="{{ route('admin.shelter.list') }}"
        class="nav-link {{ is_current_route('admin.shelter*') ? 'active' : '' }}">
        <i class="fas fa-map-marked-alt"></i>
        <p>
            避難所管理
            <i class="fas fa-angle-left right"></i>
        </p>
    </a>
    <ul class="nav nav-treeview">
        <li class="nav-item">
            <a href="{{ route('admin.shelter.list') }}"
                class="nav-link {{ is_current_route('admin.shelter.list*') ? 'active' : '' }}">
                <i class="fas fa-th-list"></i>
                <p>一覧</p>
            </a>
        </li>
        <li class="nav-item">
            <a href="{{ route('admin.shelter.new') }}"
                class="nav-link {{ is_current_route('admin.shelter.new*') ? 'active' : '' }}">
                <i class="fas fa-user-plus"></i>
                <p>新規登録</p>
            </a>
        </li>
    </ul>
</li>
<li class="nav-item has-treeview">
    <a href="{{ route('admin.format.list') }}"
        class="nav-link {{ is_current_route('admin.format*') ? 'active' : '' }}">
        <i class="far fa-list-alt"></i>
        <p>
            帳票管理
            <i class="fas fa-angle-left right"></i>
        </p>
    </a>
    <ul class="nav nav-treeview">
        <li class="nav-item">
            <a href="{{ route('admin.format.list') }}"
                class="nav-link {{ is_current_route('admin.format.list*') ? 'active' : '' }}">
                <i class="fas fa-th-list"></i>
                <p>一覧</p>
            </a>
        </li>
        <li class="nav-item">
            <a href="{{ route('admin.format.new') }}"
                class="nav-link {{ is_current_route('admin.format.new*') ? 'active' : '' }}">
                <i class="fas fa-user-plus"></i>
                <p>新規登録</p>
            </a>
        </li>
    </ul>
</li>
<li class="nav-item has-treeview">
    <a href="{{ route('admin.deployment_template.list') }}"
        class="nav-link {{ is_current_route('admin.deployment_template*') ? 'active' : '' }}">
        <i class="fas fa-table"></i>
        <p>
            展開用テンプレート管理
            <i class="fas fa-angle-left right"></i>
        </p>
    </a>
    <ul class="nav nav-treeview">
        <li class="nav-item">
            <a href="{{ route('admin.deployment_template.list') }}"
                class="nav-link {{ is_current_route('admin.deployment_template.list*') ? 'active' : '' }}">
                <i class="fas fa-th-list"></i>
                <p>一覧</p>
            </a>
        </li>
        <li class="nav-item">
            <a href="{{ route('admin.deployment_template.new') }}"
                class="nav-link {{ is_current_route('admin.deployment_template.new*') ? 'active' : '' }}">
                <i class="fas fa-user-plus"></i>
                <p>新規登録</p>
            </a>
        </li>
    </ul>
</li>
<li class="nav-item">
    <a href="{{ route('admin.report_registration.index') }}"
        class="nav-link {{ is_current_route('admin.report_registration*') ? 'active' : '' }}">
        <i class="far fa-file-alt"></i>
        <p>
            帳票データ登録
        </p>
    </a>
</li>
<li class="nav-item">
    <a href="{{ route('admin.ocr.index') }}"
        class="nav-link {{ is_current_route('admin.ocr*') ? 'active' : '' }}">
        <i class="far fa-file-alt"></i>
        <p>
            帳票回答OCR解析
        </p>
    </a>
</li>
{{-- <li class="nav-item">
  <a href="#" class="nav-link">
    <i class="fas fa-sign-out-alt"></i>
  <p>
    ログアウト
  </p>
  </a>
</li> --}}
