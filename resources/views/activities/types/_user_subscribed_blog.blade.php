<li class="list-group-item media" >
    <div class="avatar pull-left">
        <a href="{{ route('users.show', [$activity->user->id]) }}">
            <img class="media-object img-thumbnail avatar" alt="{{ $activity->user->name }}" src="{{ $activity->user->present()->gravatar }}" />
        </a>
    </div>
    <div class="infos">
        <div class="media-heading">

            <i class="fa fa-headphones" aria-hidden="true"></i>

            <a href="{{ route('users.show', [$activity->user->id]) }}">
                {{ $activity->user->name }}
            </a>
                关注了专栏
             <a href="{{ $activity->data['blog_link'] }}" title="{{ $activity->data['blog_name'] }}">
                {{ str_limit($activity->data['blog_name'], '100') }}
            </a>
             <span class="meta pull-right">
                 <span class="timeago">{{ $activity->created_at }}</span>
            </span>
        </div>
    </div>
</li>