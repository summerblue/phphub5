

<ul class="nav nav-tabs user-info-nav" role="tablist">
  <li class="{{ $user->present()->userinfoNavActive('users.show') }}">
  	<a href="{{ route('users.show', $user->id) }}" >{{ lang('Basic Info') }}</a>
  </li>
  <li class="{{ $user->present()->userinfoNavActive('users.replies') }}">
  	<a href="{{ route('users.replies', $user->id) }}" >{{ lang('Replies') }}</a>
  </li>
  <li class="{{ $user->present()->userinfoNavActive('users.topics') }}">
  	<a href="{{ route('users.topics', $user->id) }}" >{{ lang('Topics') }}</a>
  </li>
  <li class="{{ $user->present()->userinfoNavActive('users.favorites') }}">
  	<a href="{{ route('users.favorites', $user->id) }}" >{{ lang('Favorites') }}</a>
  </li>
  @if(Auth::check() && Auth::id() == $user->id)
  <!-- <li class="{{ $user->present()->userinfoNavActive('users.access_tokens') }}">
    <a href="{{ route('users.access_tokens', $user->id) }}" >{{ lang('Access Tokens') }}</a>
  </li> -->
  @endif
</ul>
