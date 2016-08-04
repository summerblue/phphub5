

<ul class="nav nav-tabs user-info-nav" role="tablist">
  <li class="{{ navViewActive('users.show') }}">
  	<a href="{{ route('users.show', $user->id) }}" >{{ lang('GitHub Info') }}</a>
  </li>
  <li class="{{ navViewActive('users.following') }}">
  	<a href="{{ route('users.following', $user->id) }}" >{{ lang('Following User') }}</a>
  </li>
  <li class="{{ navViewActive('users.topics') }}">
  	<a href="{{ route('users.topics', $user->id) }}" >{{ lang('Posted Topics') }}</a>
  </li>
  <li class="{{ navViewActive('users.replies') }}">
  	<a href="{{ route('users.replies', $user->id) }}" >{{ lang('Replies') }}</a>
  </li>
  <li class="{{ navViewActive('users.votes') }}">
  	<a href="{{ route('users.votes', $user->id) }}" >{{ lang('Voted Topics') }}</a>
  </li>

  @if(Auth::check() && Auth::id() == $user->id)
  <!-- <li class="{{ navViewActive('users.access_tokens') }}">
    <a href="{{ route('users.access_tokens', $user->id) }}" >{{ lang('Access Tokens') }}</a>
  </li> -->
  @endif
</ul>
