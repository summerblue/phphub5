<ul class="list-group">

  @foreach ($active_users as $index => $active_user)
   <li class="list-group-item" >

      <a href="{{ route('users.show', [$active_user->id]) }}" title="{{{ $active_user->name }}}">

        <img class="media-object img-thumbnail avatar avatar-small inline-block " src="{{ $active_user->present()->gravatar }}">

        {{{ $active_user->name }}}


        @if($active_user->introduction)
        <span class="introduction">
             - {{{ $active_user->introduction }}}
        </span>
        @endif
    </a>

  </li>
  @endforeach

</ul>
