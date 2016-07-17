<ul class="list-group">

  @foreach ($followingUsers as $index => $followingUser)
   <li class="list-group-item" >

      <a href="{{ route('users.show', [$followingUser->id]) }}" title="{{{ $followingUser->name }}}">

        <!-- <img class="avatar-topnav" alt="{{{ $followingUser->name }}}" src=""> -->
        <img class="media-object img-thumbnail avatar avatar-small inline-block " src="{{ $followingUser->present()->gravatar }}">

        {{{ $followingUser->name }}}
      </a>

        @if($followingUser->introduction)
        <span class="introduction">
             - {{{ $followingUser->introduction }}}
        </span>
        @endif

  </li>
  @endforeach

</ul>
