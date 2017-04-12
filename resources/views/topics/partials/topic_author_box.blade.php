

@if ($currentUser && $currentUser->id == $topic->user->id)
<a class="popover-with-html text-lg animated rubberBand edit-btn" href="{{ route('users.edit', $topic->user->id) }}" data-content="{{ lang('Edit Profile') }}">
    <i class="fa fa-cog"></i>
</a>
@endif

<a href="{{ route('users.show', $topic->user->id) }}">
  <img src="{{ $topic->user->present()->gravatar }}" style="width:80px; height:80px;margin:5px;" class="img-thumbnail avatar" />
</a>


<div class="media-body padding-top-sm">
    @if($topic->user->introduction)
    <div class="media-heading">

        @if ($topic->user->gender == 'male')

            <svg width="14" height="16" viewBox="0 0 14 14" class="Icon Icon--male" aria-hidden="true" style="height: 16px; width: 14px;fill:#9fadc7;margin-bottom: 3px"><title></title><g><path d="M3.025 10.64c-1.367-1.366-1.367-3.582 0-4.95 1.367-1.366 3.583-1.366 4.95 0 1.367 1.368 1.367 3.584 0 4.95-1.367 1.368-3.583 1.368-4.95 0zm10.122-9.368c-.002-.414-.34-.75-.753-.753L8.322 0c-.413-.002-.746.33-.744.744.002.413.338.75.75.752l2.128.313c-.95.953-1.832 1.828-1.832 1.828-2.14-1.482-5.104-1.27-7.013.64-2.147 2.147-2.147 5.63 0 7.777 2.15 2.148 5.63 2.148 7.78 0 1.908-1.91 2.12-4.873.636-7.016l1.842-1.82.303 2.116c.003.414.34.75.753.753.413.002.746-.332.744-.745l-.52-4.073z" fill-rule="evenodd"></path></g></svg>

        @elseif($topic->user->gender == 'female')

            <svg width="14" height="16" viewBox="0 0 12 16" class="Icon Icon--female" aria-hidden="true" style="height: 16px; width: 14px;fill: #e89090; margin-bottom: 3px"><title></title><g><path d="M6 0C2.962 0 .5 2.462.5 5.5c0 2.69 1.932 4.93 4.485 5.407-.003.702.01 1.087.01 1.087H3C1.667 12 1.667 14 3 14s1.996-.006 1.996-.006v1c0 1.346 2.004 1.346 1.998 0-.006-1.346 0-1 0-1S7.658 14 8.997 14c1.34 0 1.34-2-.006-2.006H6.996s-.003-.548-.003-1.083C9.555 10.446 11.5 8.2 11.5 5.5 11.5 2.462 9.038 0 6 0zM2.25 5.55C2.25 3.48 3.93 1.8 6 1.8c2.07 0 3.75 1.68 3.75 3.75C9.75 7.62 8.07 9.3 6 9.3c-2.07 0-3.75-1.68-3.75-3.75z" fill-rule="evenodd"></path></g></svg>

        @endif

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

@if ($topic->user->city)
  <li><i class="fa fa-map-marker"></i> {{ $topic->user->city }}</li>
@endif

<br>
  @if ($topic->user->github_name)
  <li>
    <a href="https://github.com/{{ $topic->user->github_name }}" target="_blank" class="popover-with-html" data-content="{{ $topic->user->github_name }}">
      <i class="fa fa-github-alt"></i>
    </a>
  </li>
  @endif

  @if ($topic->user->weibo_link)
  <li>
    <a href="{{ $topic->user->weibo_link }}" rel="nofollow" class="weibo" target="_blank"><i class="fa fa-weibo"></i>
    </a>
  </li>
  @endif

    @if ($topic->user->wechat_qrcode)
    <li class="popover-with-html" data-content="<img src='{{ $topic->user->wechat_qrcode }}' style='width:100%'>">
      <i class="fa fa-wechat"></i>
    </li>
    @endif

  @if ($topic->user->twitter_account)
  <li>
    <a href="https://twitter.com/{{ $topic->user->twitter_account }}" data-content="{{ $topic->user->twitter_account }}" rel="nofollow" class="twitter popover-with-html" target="_blank"><i class="fa fa-twitter"></i>
    </a>
</li>
  @endif

  @if ($topic->user->linkedin)
  <li class="popover-with-html" data-content="点击查看 LinkedIn 个人资料">
    <a href="{{ $topic->user->linkedin }}" rel="nofollow" class="linkedin" target="_blank"><i class="fa fa-linkedin"></i>
    </a>
  </li>
  @endif

  @if ($topic->user->personal_website)
  <li>
    <a href="http://{{ $topic->user->personal_website }}" data-content="{{ $topic->user->personal_website }}" rel="nofollow" target="_blank" class="url popover-with-html">
      <i class="fa fa-globe"></i>
    </a>
</li>
  @endif

@if ($topic->user->company)
  <li class="popover-with-html" data-content="{{ $topic->user->company }}"><i class="fa fa-users"></i></li>
@endif


</ul>

<div class="clearfix"></div>
</div>
