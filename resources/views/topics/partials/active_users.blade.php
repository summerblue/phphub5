<ul class="list-group">

  @foreach ($active_users as $index => $active_user)

  @if ($active_user->is_banned != 'yes')
   <li class="list-group-item" >

      <a class="popover-with-html" href="{{ route('users.show', [$active_user->id]) }}" data-content="{{{ $active_user->name . ($active_user->introduction ? ' - ' . $active_user->introduction : '')}}}">

        <img class="media-object img-thumbnail avatar avatar-small inline-block " src="{{ $active_user->present()->gravatar }}">

        {{{ $active_user->name }}}

        @if($active_user->introduction)
        <span class="introduction">
             - {{{ $active_user->introduction }}}
        </span>
        @endif
    </a>

  </li>
  @endif

  @endforeach

</ul>
