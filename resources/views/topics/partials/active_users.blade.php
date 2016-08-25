<div class="users-label">

  @foreach ($active_users as $index => $active_user)
      @if ($active_user->is_banned != 'yes')
         <a class="popover-with-html users-label-item" href="{{ route('users.show', [$active_user->id]) }}" data-content="{{{ $active_user->name . ($active_user->introduction ? ' - ' . $active_user->introduction : '')}}}">
            <img class="avatar-small inline-block" src="{{ $active_user->present()->gravatar }}"> {{{ $active_user->name }}}
        </a>
      @endif
  @endforeach

</div>
