<ul class="list-group">

  @foreach ($users as $index => $followUser)
   <li class="list-group-item" >

      <a href="{{ route('users.show', [$followUser->id]) }}" title="{{{ $followUser->name }}}">

        <!-- <img class="avatar-topnav" alt="{{{ $followUser->name }}}" src=""> -->
        <img class="media-object img-thumbnail avatar avatar-small inline-block " src="{{ $followUser->present()->gravatar }}">

        {{{ $followUser->name }}}
      </a>

        @if($followUser->introduction)
        <span class="introduction">
             - {{{ $followUser->introduction }}}
        </span>
        @endif

  </li>
  @endforeach

</ul>
