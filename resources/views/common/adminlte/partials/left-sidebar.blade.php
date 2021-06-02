<aside class="main-sidebar {{ isset($color_class) ? $color_class : 'sidebar-dark-primary' }} elevation-4 text-sm">

    {{-- Sidebar brand logo 
    <a href="/">

        <img src=""
             alt="TEST"
             class="logo-xs">

        <img src=""
             alt="test"
             class="logo-xl">
    </a>
    --}}

    {{-- Sidebar menu --}}
    <div class="sidebar">     
        <div class="user-panel mt-3 pb-3 mb-3 d-flex">
            <div class="info">
                <a>
                    @yield('role')
                </a>
            </div>
        </div>
        <nav class="mt-2">
            <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
                
                <li class="nav-header">MENU</li>

                @if (isset($menu))
                    @include($menu)
                @endif

            </ul>
        </nav>
    </div>

</aside>
