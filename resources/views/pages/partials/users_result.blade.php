<div class="result user media">
  <div class="media">
    <div class="avatar media-left">
      <div class="image"><a title="{{ $user->name }}" href="{{ route('users.show', $user->id) }}">
          <img class="media-object img-thumbnail avatar avatar-66" src="{{ $user->present()->gravatar }}" alt="96"></a>
      </div>
    </div>
    <div class="media-body user-info">
      <div class="info">
        <a href="{{ route('users.show', $user->id) }}">{{ $user->name }}</a>
        @if ($user->present()->hasBadge())
            <div class="role-label">
                <a class="label label-success role" href="{{ route('roles.show', [$user->present()->badgeID()]) }}">{{{ $user->present()->badgeName() }}}</a>
            </div>
        @endif
      </div>
      <div class="info number">
        第 {{ $user->id }} 位会员
          ⋅
        <span title="注册日期">
            {{ Carbon\Carbon::parse($user->created_at)->format('Y-m-d') }}
        </span>

          ⋅ <span>{{ $user->follower_count }}</span> 关注者
          ⋅ <span>{{ $user->topic_count }}</span> 篇话题
          ⋅ <span>{{ $user->reply_count }}</span> 条回帖
          ⋅ <span>{{ $user->article_count }}</span> 篇文章
      </div>
    </div>
  </div>

</div>
<hr>
