<div role="navigation" class="navbar navbar-default navbar-static-top topnav">
  <div class="container">
    <div class="navbar-header">

      <a href="/" class="navbar-brand">PHPHub</a>
    </div>
    <div id="top-navbar-collapse" class="navbar-collapse">
      <ul class="nav navbar-nav">
        <li class="{{ (Request::is('topics*') ? ' active' : '') }}"><a href="{{ route('topics.index') }}">{{ lang('Topics') }}</a></li>
        <li class="{{ Request::is('categories/1') ? ' active' : '' }}"><a href="{{ route('categories.show', 1) }}">{{ lang('Jobs') }}</a></li>
        <li class="{{ (Request::is('sites') ? ' active' : '') }}"><a href="{{ route('sites.index') }}">{{ lang('Sites') }}</a></li>
        <li class="{{ (Request::is('hall_of_fames') ? ' active' : '') }}"><a href="{{ route('hall_of_fames') }}">{{ lang('Hall of Fame') }}</a></li>
      </ul>

      <div class="navbar-right">
          <form method="GET" action="{{route('search')}}" accept-charset="UTF-8" class="navbar-form navbar-left" target="_blank">
            <div class="form-group">
            <input class="form-control search-input mac-style" placeholder="{{lang('Search')}}" name="q" type="text">
            </div>
          </form>

        <ul class="nav navbar-nav github-login" >
          @if (Auth::check())
              <li>
                  <a href="{{ route('notifications.index') }}" class="text-warning">
                      <span class="badge badge-{{ $currentUser->notification_count > 0 ? 'important' : 'fade' }}" id="notification-count">
                          {{ $currentUser->notification_count }}
                      </span>
                  </a>
              </li>

                @if (Auth::user()->can('visit_admin'))
                  <li>
                      <a href="/admin" class="text-md  ">
                          <i class="fa fa-tachometer"></i>
                      </a>
                  </li>
                @endif
                <li>
                    <a class="animated rubberBand nav-edit-btn text-md margin-top-2px" href="{{ route('users.edit', $currentUser->id) }}" data-content="{{ lang('Edit Profile') }}">
                        <i class="fa fa-cog"></i>
                    </a>
                </li>


              <li>
                  <a href="{{ route('users.show', $currentUser->id) }}">
                      <img class="avatar-topnav" alt="Summer" src="{{ $currentUser->present()->gravatar }}" />
                       {{{ $currentUser->name }}}
                  </a>
              </li>
              <li>
                  <a id="login-out" class="button" href="{{ URL::route('logout') }}" data-lang-loginout="{{ lang('Are you sure want to logout?') }}">
                      <i class="fa fa-sign-out"></i> {{ lang('Logout') }}
                  </a>
              </li>
          @else
              <a href="{{ URL::route('auth.oauth', ['driver' => 'wechat']) }}" class="btn btn-success login-btn weichat-login-btn">
                <i class="fa fa-weixin"></i>
                {{ lang('Login') }}
              </a>

              <a href="{{ URL::route('auth.oauth', ['driver' => 'github']) }}" class="btn btn-info login-btn">
                <i class="fa fa-github-alt"></i>
                {{ lang('Login') }}
              </a>
          @endif
        </ul>
      </div>
    </div>

  </div>
</div>
