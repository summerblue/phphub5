

@if ($currentUser && $currentUser->id == $topic->user->id)
<a class="pull-right popover-with-html text-lg animated rubberBand edit-btn" href="{{ route('users.edit', $topic->user->id) }}" data-content="{{ lang('Edit Profile') }}">
    <i class="fa fa-cog"></i>
</a>
@endif

<a href="{{ route('users.show', $topic->user->id) }}">
  <img src="{{ $topic->user->present()->gravatar }}" style="width:80px; height:80px;margin:5px;" class="img-thumbnail avatar" />
</a>


<div class="media-body padding-top-sm">
@if($topic->user->introduction)
<div class="media-heading">


@if ($topic->user->present()->hasBadge())
    <div class="role-label text-white">
        <a class="label label-success role" href="{{ route('roles.show', [$topic->user->present()->badgeID()]) }}">{{{ $topic->user->present()->badgeName() }}}</a>
    </div>
@endif

    <span class="introduction">
         {{{ $topic->user->introduction }}}
    </span>

</div>
@endif

<ul class="list-inline">

  @if ($topic->user->real_name)
    <li class="popover-with-html" data-content="{{ lang('Real Name') }}"><span class="org"><i class="fa fa-user"></i> {{{ $topic->user->real_name }}}</span></li>
  @endif

  @if ($topic->user->github_name)
  <li>
    <a href="https://github.com/{{ $topic->user->github_name }}" target="_blank">
      <i class="fa fa-github-alt"></i> GitHub
    </a>
  </li>
  @endif

  @if ($topic->user->weibo_link)
  <li>
    <a href="{{ $topic->user->weibo_link }}" rel="nofollow" class="weibo" target="_blank"><i class="fa fa-weibo"></i> WeiBo
    </a>
  </li>
  @endif

    @if ($topic->user->wechat_qrcode)
    <li class="popover-with-html" data-content="<img src='{{ $topic->user->wechat_qrcode }}' style='width:100%'>">
      <i class="fa fa-wechat"></i> WeChat
    </li>
    @endif

  @if ($topic->user->twitter_account)
  <li>
    <a href="https://twitter.com/{{ $topic->user->twitter_account }}" rel="nofollow" class="twitter" target="_blank"><i class="fa fa-twitter"></i> Twitter
    </a>
</li>
  @endif

  @if ($topic->user->linkedin)
  <li class="popover-with-html" data-content="点击查看 LinkedIn 个人资料">
    <a href="{{ $topic->user->linkedin }}" rel="nofollow" class="linkedin" target="_blank"><i class="fa fa-linkedin"></i> LinkedIn
    </a>
  </li>
  @endif

  @if ($topic->user->personal_website)
  <li>
    <a href="http://{{ $topic->user->personal_website }}" rel="nofollow" target="_blank" class="url">
      <i class="fa fa-globe"></i> Website
    </a>
</li>
  @endif

@if ($topic->user->company)
  <li class="popover-with-html" data-content="{{ $topic->user->company }}"><i class="fa fa-users"></i> {{{ lang('Company') }}}</li>
@endif

@if ($topic->user->city)
  <li class="popover-with-html" data-content="{{ $topic->user->city }}"><i class="fa fa-map-marker"></i> {{{ lang('City') }}}</li>
@endif

</ul>

<div class="clearfix"></div>
</div>
