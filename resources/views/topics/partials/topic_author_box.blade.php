
<div class="replies panel panel-default padding-md topic-author-box">
<div class="panel-body ">


    <div class="media">

        @if ($currentUser && $currentUser->id == $topic->user->id)
        <a class="pull-right popover-with-html text-lg animated rubberBand edit-btn" href="{{ route('users.edit', $topic->user->id) }}" data-content="{{ lang('Edit Profile') }}">
            <i class="fa fa-cog"></i>
        </a>
        @endif

      <div class="media-left">
        <a href="{{ route('users.show', $topic->user->id) }}">
          <img src="{{ $topic->user->present()->gravatar }}" style="width:65px; height:65px;" class="img-thumbnail avatar" />
        </a>
      </div>
      <div class="media-body padding-top-sm">
        <div class="media-heading">
            <a href="{{ route('users.show', [$topic->user_id]) }}" title="{{{ $topic->user->name }}}" class="remove-padding-left author">
                {{{ $topic->user->name }}}
            </a>
            @if($topic->user->introduction)
            <span class="introduction">
                 ，{{{ $topic->user->introduction }}}
            </span>
            @endif
        </div>


        <ul class="list-inline">

          @if ($topic->user->real_name)
            <li class="popover-with-html" data-content="{{ lang('Real Name') }}"><span class="org"><i class="fa fa-user"></i> {{{ $topic->user->real_name }}}</span></li>
          @endif

          @if ($topic->user->present()->hasBadge())
            <li class="popover-with-html" data-content="{{ lang('User Role') }}"><i class="fa fa-graduation-cap" aria-hidden="true"></i> {{{ $topic->user->present()->badgeName() }}}</li>
          @endif

          @if ($topic->user->github_name)
          <li>
            <a href="https://github.com/{{ $topic->user->github_name }}" target="_blank">
              <i class="fa fa-github-alt"></i> {{ $topic->user->github_name }}
            </a>
          </li>
          @endif

          @if ($topic->user->weibo_id)
          <li>
            <a href="http://weibo.com/u/{{ $topic->user->weibo_id }}" rel="nofollow" class="weibo" target="_blank"><i class="fa fa-weibo"></i> {{{ $topic->user->weibo_name ?: $topic->user->weibo_id }}}
            </a>
          </li>
          @endif

          @if ($topic->user->company)
            <li class="popover-with-html" data-content="{{ lang('Company') }}"><i class="fa fa-users"></i> {{{ $topic->user->company }}}</li>
          @endif

          @if ($topic->user->city)
            <li class="adr"><i class="fa fa-map-marker"></i> {{{ $topic->user->city }}}</li>
          @endif



          @if ($topic->user->twitter_account)
          <li>
            <a href="https://twitter.com/{{ $topic->user->twitter_account }}" rel="nofollow" class="twitter" target="_blank"><i class="fa fa-twitter"></i> {{{ $topic->user->twitter_account }}}
            </a>
        </li>
          @endif

          @if ($topic->user->personal_website)
          <li>
            <a href="http://{{ $topic->user->personal_website }}" rel="nofollow" target="_blank" class="url">
              <i class="fa fa-globe"></i> {{{ str_limit($topic->user->personal_website, 22) }}}
            </a>
        </li>
          @endif
      </ul>

        <div class="clearfix"></div>
      </div>
    </div>

</div>
</div>
