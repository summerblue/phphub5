<ul class="list-group row">

  @foreach ($replies as $index => $reply)

      @if($index+1 == count($replies))
          <a name="last-reply" class="anchor" href="#last-reply" aria-hidden="true"></a>
      @endif

   <li class="list-group-item media"
           @if($reply->vote_count >= 2)
                style="margin-top: 0px; background-color: #fffce9"
           @else
                style="margin-top: 0px;"
           @endif
           >

    <div class="avatar avatar-container pull-left">
      <a href="{{ route('users.show', [$reply->user_id]) }}">
        <img class="media-object img-thumbnail avatar avatar-middle" alt="{{{ $reply->user->name }}}" src="{{ $reply->user->present()->gravatar }}"  style="width:55px;height:55px;"/>
      </a>
      @if ($reply->user->present()->hasBadge())
          <div>
              <a class="label label-success role" href="{{ route('roles.show', [$reply->user->present()->badgeID()]) }}">{{{ $reply->user->present()->badgeName() }}}</a>
          </div>
      @endif
    </div>

    <div class="infos">

      <div class="media-heading">

        <a href="{{ route('users.show', [$reply->user_id]) }}" title="{{{ $reply->user->name }}}" class="remove-padding-left author">
            {{{ $reply->user->name }}}
        </a>

        @if ($reply->user->present()->isAdmin())
            <a class="label label-success mod-label popover-with-html" data-content="管理员" href="{{ route('roles.show', [$reply->user->present()->badgeID()]) }}">MOD</a>
        @endif

        @if($reply->user->introduction)
        <span class="introduction">
            {{{ $reply->user->introduction }}}
        </span>
        @endif

        <span class="operate pull-right">

          @if ($currentUser && $reply->user_id != $currentUser->id)
          <a class="comment-vote" data-ajax="post" id="reply-up-vote-{{ $reply->id }}" href="javascript:void(0);" data-url="{{ route('replies.vote', $reply->id) }}" title="{{ lang('Vote Up') }}">
             <i class="fa fa-thumbs-o-up" style="font-size:14px;"></i> <span class="vote-count">{{ $reply->vote_count ?: '' }}</span>
          </a>
          <span> ⋅  </span>
          @endif

          @if ($currentUser && ($manage_topics || $currentUser->id == $reply->user_id) )
            <a id="reply-delete-{{ $reply->id }}" data-ajax="delete"  href="javascript:void(0);" data-url="{{route('replies.destroy', [$reply->id])}}" title="{{lang('Delete')}}">
                <i class="fa fa-trash-o"></i>
            </a>
            <span> ⋅  </span>
          @endif
          <a class="fa fa-reply" href="javascript:void(0)" onclick="replyOne('{{{$reply->user->name}}}');" title="回复 {{{$reply->user->name}}}"></a>
        </span>

        <div class="meta">
            <a name="reply{{ $reply->id }}" id="reply{{ $reply->id }}" class="anchor" href="#reply{{ $reply->id }}" aria-hidden="true">#{{ $topic->present()->replyFloorFromIndex($index) }}</a>


            <span> ⋅  </span>
            <abbr class="timeago" title="{{ $reply->created_at }}">{{ $reply->created_at }}</abbr>

            @if ($reply->source && in_array($reply->source, ['iOS', 'Android']))
            ⋅ via
                <a href="https://laravel-china.org/topics/1531" target="_blank" class="popover-with-html" data-content="来自手机客户端">
                   <i class="text-md fa fa-{{ $reply->source == 'iOS' ? 'apple' : 'android' }}" aria-hidden="true"></i> {{ $reply->source == 'iOS' ? 'iOS 客户端' : '安卓客户端' }}
                </a>

            @endif
        </div>

      </div>

      <div class="media-body markdown-reply content-body">
{!! $reply->body !!}
      </div>

    </div>

  </li>
  @endforeach

</ul>
