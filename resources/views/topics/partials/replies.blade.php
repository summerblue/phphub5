<ul class="list-group row">

  @foreach ($replies as $index => $reply)
   <li class="list-group-item media"
           @if($reply->vote_count >= 2)
                style="margin-top: 0px; background-color: #fffce9"
           @else
                style="margin-top: 0px;"
           @endif
           >

    <div class="avatar pull-left">
      <a href="{{ route('users.show', [$reply->user_id]) }}">
        <img class="media-object img-thumbnail avatar avatar-middle" alt="{{{ $reply->user->name }}}" src="{{ $reply->user->present()->gravatar }}"  style="width:48px;height:48px;"/>
      </a>
    </div>

    <div class="infos">

      <div class="media-heading">

        <a href="{{ route('users.show', [$reply->user_id]) }}" title="{{{ $reply->user->name }}}" class="remove-padding-left author">
            {{{ $reply->user->name }}}
        </a>
        @if($reply->user->introduction)
        <span class="introduction">
             ，{{{ str_limit($reply->user->introduction, 68) }}}
        </span>
        @endif

        <span class="operate pull-right">

          @if ($currentUser && $reply->user_id != $currentUser->id)
          <a class="comment-vote" data-ajax="post" id="reply-up-vote-{{ $reply->id }}" href="javascript:void(0);" data-url="{{ route('replies.vote', $reply->id) }}" title="{{ lang('Vote Up') }}">
             <i class="fa fa-thumbs-o-up" style="font-size:14px;"></i> <span class="vote-count">{{ $reply->vote_count ?: '' }}</span>
          </a>
          <span> •  </span>
          @endif

          @if ($currentUser && ($currentUser->can("manage_topics") || $currentUser->id == $reply->user_id) )
            <a id="reply-delete-{{ $reply->id }}" data-ajax="delete"  href="javascript:void(0);" data-url="{{route('replies.destroy', [$reply->id])}}" title="{{lang('Delete')}}">
                <i class="fa fa-trash-o"></i>
            </a>
            <span> •  </span>
          @endif
          <a class="fa fa-reply" href="javascript:void(0)" onclick="replyOne('{{{$reply->user->name}}}');" title="回复 {{{$reply->user->name}}}"></a>
        </span>

        <div class="meta">
            <a name="reply{{ $topic->present()->replyFloorFromIndex($index) }}" class="anchor" href="#reply{{ $topic->present()->replyFloorFromIndex($index) }}" aria-hidden="true">#{{ $topic->present()->replyFloorFromIndex($index) }}</a>

            <span> •  </span>
            <abbr class="timeago" title="{{ $reply->created_at }}">{{ $reply->created_at }}</abbr>
        </div>

      </div>

      <div class="media-body markdown-reply content-body">
{!! $reply->body !!}
      </div>

    </div>

  </li>
  @endforeach

</ul>
