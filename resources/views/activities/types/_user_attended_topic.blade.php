<li class="list-group-item media" style="margin-top: 0px;">
    <div class="avatar pull-left">
        <a href="{{ route('users.show', [$activity->user->id]) }}">
            <img class="media-object img-thumbnail avatar" alt="{{ $activity->user->name }}" src="{{ $activity->user->present()->gravatar }}"  style="width:38px;height:38px;"/>
        </a>
    </div>
    <div class="infos">
        <div class="media-heading">
            <a href="{{ route('users.show', [$activity->user->id]) }}">
                {{ $activity->user->name }}
            </a>
            @if ($activity->data['topic_type'] == 'article')
                关注了文章
            @else
                关注了话题
            @endif
            <a href="{{ $activity->data['topic_link'] }}" title="{{ $activity->data['topic_title'] }}">
                {{ str_limit($activity->data['topic_title'], '100') }}
            </a>
            <span class="meta">
                • {{ lang('at') }} • <span class="timeago">{{ $activity->created_at }}</span>
            </span>
        </div>
        <div class="media-body markdown-reply content-body">

        </div>
    </div>
</li>