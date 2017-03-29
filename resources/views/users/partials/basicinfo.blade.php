<div class="box">

    <div class="padding-sm user-basic-info">
        <div style="">

        <div class="media">
            <div class="media-left">
              <div class="image">
                  @if ($currentUser && $currentUser->id == $user->id)
                  <a href="{{ route('users.edit_avatar', $user->id) }}" class="popover-with-html" data-content="修改头像">
                      <img class="media-object avatar-112 avatar img-thumbnail" src="{{ $user->present()->gravatar(200) }}">
                  </a>
                  @else
                  <img class="media-object avatar-112 avatar img-thumbnail" src="{{ $user->present()->gravatar(200) }}">
                  @endif
              </div>

            @if ($user->present()->hasBadge())
                <div class="role-label">
                    <a class="label label-success role" href="{{ route('roles.show', [$user->present()->badgeID()]) }}">{{{ $user->present()->badgeName() }}}</a>
                </div>
            @endif

            </div>
            <div class="media-body">
                <h3 class="media-heading">
                    {{ $user->name }}
                </h3>
              <div class="item">

                @if ($user->gender == 'male')

                    <svg width="14" height="16" viewBox="0 0 14 14" class="Icon Icon--male" aria-hidden="true" style="height: 16px; width: 14px;fill:#9fadc7;top: 2px;position: relative;"><title></title><g><path d="M3.025 10.64c-1.367-1.366-1.367-3.582 0-4.95 1.367-1.366 3.583-1.366 4.95 0 1.367 1.368 1.367 3.584 0 4.95-1.367 1.368-3.583 1.368-4.95 0zm10.122-9.368c-.002-.414-.34-.75-.753-.753L8.322 0c-.413-.002-.746.33-.744.744.002.413.338.75.75.752l2.128.313c-.95.953-1.832 1.828-1.832 1.828-2.14-1.482-5.104-1.27-7.013.64-2.147 2.147-2.147 5.63 0 7.777 2.15 2.148 5.63 2.148 7.78 0 1.908-1.91 2.12-4.873.636-7.016l1.842-1.82.303 2.116c.003.414.34.75.753.753.413.002.746-.332.744-.745l-.52-4.073z" fill-rule="evenodd"></path></g></svg>

                @elseif($user->gender == 'female')

                    <svg width="14" height="16" viewBox="0 0 12 16" class="Icon Icon--female" aria-hidden="true" style="height: 16px; width: 14px;fill: #e89090;top: 2px;position: relative;"><title></title><g><path d="M6 0C2.962 0 .5 2.462.5 5.5c0 2.69 1.932 4.93 4.485 5.407-.003.702.01 1.087.01 1.087H3C1.667 12 1.667 14 3 14s1.996-.006 1.996-.006v1c0 1.346 2.004 1.346 1.998 0-.006-1.346 0-1 0-1S7.658 14 8.997 14c1.34 0 1.34-2-.006-2.006H6.996s-.003-.548-.003-1.083C9.555 10.446 11.5 8.2 11.5 5.5 11.5 2.462 9.038 0 6 0zM2.25 5.55C2.25 3.48 3.93 1.8 6 1.8c2.07 0 3.75 1.68 3.75 3.75C9.75 7.62 8.07 9.3 6 9.3c-2.07 0-3.75-1.68-3.75-3.75z" fill-rule="evenodd"></path></g></svg>

                @endif

                {{ $user->real_name }}
              </div>
              <div class="item">
                第 {{ $user->id }} 位会员
              </div>
              <div class="item number">
                注册于 <span class="timeago">{{ $user->created_at }}</span>
              </div>

              @if($user->last_actived_at)
              <div class="item number">
                活跃于 <span class="timeago">{{ $user->last_actived_at }}</span>
              </div>
              @endif

            </div>
          </div>

        </div>

        <hr>

        <div class="follow-info row">
            <div class="col-xs-4">
              <a class="counter" href="{{ route('users.followers', $user->id) }}">{{ $user->follower_count }}</a>
              <a class="text" href="{{ route('users.followers', $user->id) }}">关注者</a>
            </div>
            <div class="col-xs-4">
                <a class="counter" href="{{ route('users.replies', $user->id) }}">{{ $user->reply_count +  $user->topic_count}}</a>
                <a class="text" href="{{ route('users.replies', $user->id) }}">讨论</a>
            </div>
            <div class="col-xs-4">
                <a class="counter" href="{{ route('users.articles', $user->id) }}">{{ $user->article_count }}</a>
                <a class="text" href="{{ route('users.topics', $user->id) }}">文章</a>
            </div>
        </div>

        <hr>

        <div class="topic-author-box text-center">
            <ul class="list-inline">

              @if ($user->github_name)
              <li class="popover-with-html" data-content="{{ $user->github_name }}">
                <a href="https://github.com/{{ $user->github_name }}" target="_blank">
                  <i class="fa fa-github-alt"></i> GitHub
                </a>
              </li>
              @endif

              @if ($user->weibo_link)
              <li class="popover-with-html" data-content="{{ $user->weibo_name }}">
                <a href="{{ $user->weibo_link }}" rel="nofollow" class="weibo" target="_blank"><i class="fa fa-weibo"></i> Weibo
                </a>
              </li>
              @endif

              @if ($user->wechat_qrcode)
              <li class="popover-with-html" data-content="<img src='{{ $user->wechat_qrcode }}' style='width:100%'>">
                <i class="fa fa-wechat"></i> WeChat
              </li>
              @endif

              @if ($user->twitter_account)
              <li class="popover-with-html" data-content="{{ $user->twitter_account }}">
                <a href="https://twitter.com/{{ $user->twitter_account }}" rel="nofollow" class="twitter" target="_blank"><i class="fa fa-twitter"></i> Twitter
                </a>
            </li>
              @endif

              @if ($user->linkedin)
              <li class="popover-with-html" data-content="点击查看 LinkedIn 个人资料">
                <a href="{{ $user->linkedin }}" rel="nofollow" class="linkedin" target="_blank"><i class="fa fa-linkedin"></i> LinkedIn
                </a>
            </li>
              @endif

              @if ($user->personal_website)
              <li class="popover-with-html" data-content="{{ $user->personal_website }}">
                <a href="http://{{ $user->personal_website }}" rel="nofollow" target="_blank" class="url">
                  <i class="fa fa-globe"></i> {{ lang('Website') }}
                </a>
            </li>
              @endif

            @if ($user->company)
              <li class="popover-with-html" data-content="{{ $user->company }}"><i class="fa fa-users"></i> {{{ lang('Company') }}}</li>
            @endif

            @if ($user->city)
              <li class="popover-with-html" data-content="{{ $user->city }}"><i class="fa fa-map-marker"></i> {{{ lang('City') }}}</li>
            @endif

          </ul>

        </div>

        @if(Auth::check())
            <hr>
        @endif

        @if ($currentUser && ($currentUser->id == $user->id))
          <a class="btn btn-primary btn-block" href="{{ route('users.edit', $user->id) }}" id="user-edit-button">
            <i class="fa fa-edit"></i> {{ lang('Edit Profile') }}
          </a>
        @endif

        @if(Auth::check() && $currentUser->id != $user->id)
        <!--{{$isFollowing= $currentUser && $currentUser->isFollowing($user->id) ? true : false}}-->

        <a data-method="post" class="btn btn-{{ !$isFollowing ? 'warning' : 'default' }} btn-block" href="javascript:void(0);" data-url="{{ route('users.doFollow', $user->id) }}" id="user-edit-button">
           <i class="fa {{!$isFollowing ? 'fa-plus' : 'fa-minus'}}"></i> {{ !$isFollowing ? '关注 Ta' : '已关注' }}
        </a>

        <a class="btn btn-default btn-block" href="{{ route('messages.create', $user->id) }}" >
           <i class="fa fa-envelope-o"></i> 发私信
        </a>
        @endif
{{--
        @if ($currentUser && Entrust::can('manage_users') && $currentUser->id != $user->id && $currentUser->roles->count() < 5)
          <a data-method="post" class="btn btn-{{ $user->is_banned == 'yes' ? 'warning' : 'danger' }} btn-block" href="javascript:void(0);" data-url="{{ route('users.blocking', $user->id) }}" id="user-edit-button" onclick=" return confirm('{{ lang('Are you sure want to '. ($user->is_banned == 'yes' ? 'unblock' : 'block') . ' this User?') }}')">
            <i class="fa fa-times"></i> {{ $user->is_banned == 'yes' ? lang('Unblock User') : lang('Block User') }}
          </a>
          <a class="btn btn-info btn-block" href="{{ url('admin/users/' . $user->id) }}" >
            <i class="fa fa-eye"></i> 后台管理
          </a>
        @endif --}}

    </div>

</div>

@if ($currentUser && $currentUser->id == $user->id && Auth::user()->draft_count > 0)
    <div class="text-center alert alert-warning">
        <a href="{{ route('users.drafts') }}" style="color:inherit;"><i class="fa fa-file-text-o"></i> 草稿 {{ Auth::user()->draft_count }} 篇</a>
    </div>
@endif

<div class="box text-center">

   <div class="padding-sm user-basic-nav">
       <ul class="list-group">
             <a href="{{ route('users.articles', $user->id) }}" class="{{ navViewActive('users.articles') }}">
                 <li class="list-group-item"><i class="text-md fa fa-headphones"></i> Ta 发布的文章</li>
             </a>

             <a href="{{ route('users.topics', $user->id) }}" class="{{ navViewActive('users.topics') }}">
                 <li class="list-group-item"><i class="text-md fa fa-list-ul"></i> Ta 发布的话题</li>
             </a>

             <a href="{{ route('users.replies', $user->id) }}" class="{{ navViewActive('users.replies') }}">
                 <li class="list-group-item"><i class="text-md fa fa-comment"></i> Ta 发表的回复</li>
             </a>

             <a href="{{ route('users.following', $user->id) }}" class="{{ navViewActive('users.following') }}">
                 <li class="list-group-item"><i class="text-md fa fa-eye"></i> Ta 关注的用户</li>
             </a>

             <a href="{{ route('users.votes', $user->id) }}" class="{{ navViewActive('users.votes') }}">
                 <li class="list-group-item"><i class="text-md fa fa-thumbs-up"></i> Ta 赞过的话题</li>
             </a>

       </ul>
   </div>

</div>

@if(Auth::check() && Auth::id() == $user->id)
  @include('users.partials.login_QR')
@endif
