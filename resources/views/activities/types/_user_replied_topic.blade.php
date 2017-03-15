<li class="list-group-item media" >
    <div class="avatar pull-left">
        <a href="{{ route('users.show', [$activity->user->id]) }}">
            <img class="media-object img-thumbnail avatar" alt="{{ $activity->user->name }}" src="{{ $activity->user->present()->gravatar }}" />
        </a>
    </div>
    <div class="infos">
        <div class="media-heading">

            <i class="fa fa-comment-o" aria-hidden="true"></i>

            <a href="{{ route('users.show', [$activity->user->id]) }}">
                {{ $activity->user->name }}
            </a>

            @if ($activity->data['topic_type'] == 'article')
                评论了文章
                <?php
                    $topic_title = "《" . str_limit($activity->data['topic_title'], '100') . "》";
                ?>
            @else
                评论了话题
                <?php
                    $topic_title = str_limit($activity->data['topic_title'], '100');
                ?>
            @endif

            <a href="{{ $activity->data['topic_link'] }}#reply{{ $activity->data['reply_id'] }}" title="{{ $activity->data['topic_title'] }}">
                {{ $topic_title }}
            </a>

             <span class="meta pull-right">
                 <span class="timeago">{{ $activity->created_at }}</span>
            </span>
        </div>
        <div class="media-body markdown-reply content-body">
            {!! $activity->data['body'] !!}
        </div>
    </div>
</li>