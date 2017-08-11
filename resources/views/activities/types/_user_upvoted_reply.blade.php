<li class="list-group-item media" >
    <div class="avatar pull-left">
        <a href="{{ route('users.show', [$activity->user->id]) }}">
            <img class="media-object img-thumbnail avatar" alt="{{ $activity->user->name }}" src="{{ $activity->user->present()->gravatar }}" />
        </a>
    </div>
    <div class="infos">
        <div class="media-heading">

            <i class="fa fa-thumbs-o-up" aria-hidden="true"></i>

            <a href="{{ route('users.show', [$activity->user->id]) }}">
                {{ $activity->user->name }}
            </a>
            @if ($activity->data['topic_type'] == 'article')
                赞了文章
            @elseif ($activity->data['topic_type'] == 'share_link')
                赞了链接
            @else
                赞了话题
            @endif
             <a href="{{ $activity->data['topic_link'] }}#reply{{ $activity->data['reply_id'] }}" title="{{ $activity->data['topic_title'] }}">
                {{ str_limit($activity->data['topic_title'], '100') }}
            </a>
            @if (isset($activity->data['reply_user_name']))
                下 <a href="{{ $activity->data['topic_link'] }}#reply{{ $activity->data['reply_id'] }}">{{ '@'.$activity->data['reply_user_name'] }} 的评论</a>：
            @else
                下的 <a href="{{ $activity->data['topic_link'] }}#reply{{ $activity->data['reply_id'] }}">评论</a>：
            @endif

             <span class="meta pull-right">
                 <span class="timeago">{{ $activity->created_at }}</span>
            </span>
        </div>
        <div class="media-body markdown-reply content-body">
            {!! $activity->data['body'] !!}
        </div>
    </div>
</li>