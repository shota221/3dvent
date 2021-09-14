<!-- User Account Menu -->
<li class="dropdown user user-menu">
  <!-- Menu Toggle Button -->
  <a href="#" class="dropdown-toggle nav-link" data-toggle="dropdown">
    <!-- The user image in the navbar-->
    <!-- <img src="dist/img/user2-160x160.jpg" class="user-image" alt="User Image"> -->
    <i class="fa fa-user"></i>&nbsp;

    <!-- hidden-xs hides the username on small devices so only the image appears. -->
    <span class="hidden-xs">{{ Auth::user()->name }}</span>
  </a>
  <ul class="dropdown-menu">
    <!-- The user image in the menu -->
    <li class="user-header user-box" style="height: auto;">
      <!-- <img src="dist/img/user2-160x160.jpg" class="img-circle" alt="User Image"> -->
      <div class="icon"><i class="ion ion-ios-person"></i></div>
      <div class="label">User</div>
      <p>
        {{ Auth::user()->name }}
      </p>
      <p>
        <small>{{ Auth::user()->email }}</small>
      </p>
      <p>
        <span 
          class="btn btn-sm btn-success edit-profile ladda-button" 
          data-style="zoom-in">
          <span class="glyphicon glyphicon-pencil"></span>&nbsp;@lang('messages.edit_profile')
        </span>
      </p>
      <div class="pull-left">
        
      </div>
      <div class="pull-right">

      </div>
    </li>
    
    <!-- Menu Footer-->
    <li class="user-footer">

      <div class="text-center">
        <a href="{{ guess_route_path('logout') }}">
            <i class="fa fa-fw fa-power-off"></i> LOGOUT
        </a>
      </div>
    </li>
  </ul>
</li>