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
                关注了用户
             <a href="{{ $activity->data['following_link'] }}" title="{{ $activity->data['following_name'] }}">
                {{ str_limit($activity->data['following_name'], '100') }}
            </a>
            <span class="meta">
                • {{ lang('at') }} • <span class="timeago">{{ $activity->created_at }}</span>
            </span>
        </div>
        <div class="media-body markdown-reply content-body">

        </div>
    </div>
</li>