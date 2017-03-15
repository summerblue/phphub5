<li class="list-group-item media" >
    <div class="avatar pull-left">
        <a href="{{ route('users.show', [$activity->user->id]) }}">
            <img class="media-object img-thumbnail avatar" alt="{{ $activity->user->name }}" src="{{ $activity->user->present()->gravatar }}" />
        </a>
    </div>
    <div class="infos">
        <div class="media-heading">

            <i class="fa fa-plus" aria-hidden="true"></i>

            <a href="{{ route('users.show', [$activity->user->id]) }}">
                {{ $activity->user->name }}
            </a>
             在
             <a href="{{ $activity->data['topic_link'] }}" title="{{ $activity->data['topic_title'] }}">
                {{ str_limit($activity->data['topic_title'], '100') }}
            </a>
            中增加了附言
              <span class="meta pull-right">
                 <span class="timeago">{{ $activity->created_at }}</span>
            </span>
        </div>
        <div class="media-body markdown-reply content-body">
            {!! $activity->data['body'] !!}
        </div>
    </div>
</li>