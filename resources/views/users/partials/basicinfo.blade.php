<div class="padding-md">
    <div style="text-align: center;">

        @if ($currentUser && $currentUser->id == $user->id)
        <a class="avatar-edit" href="{{ route('users.edit_avatar', $user->id) }}">
            <i class="fa fa-pencil-square"></i>
        </a>
        @endif

        <img src="{{ $user->present()->gravatar(380) }}" class="img-thumbnail users-show-avatar" style="width:100%;margin: 4px 0px 15px;min-height:190px">

    </div>

    <dl class="dl-horizontal">

      <dt><lable>&nbsp; </lable></dt><dd> {{ lang('User ID:') }} {{ $user->id }}</dd>

      <dt><label>Name:</label></dt><dd><strong>{{{ $user->name }}}</strong></dd>

      @if ($user->present()->hasBadge())
        <dt><label>Role:</label></dt><dd><span class="label label-warning">{{{ $user->present()->badgeName() }}}</span></dd>
      @endif

      @if ($user->real_name)
        <dt class="adr"><label> {{ lang('Real Name') }}:</label></dt><dd><span class="org">{{{ $user->real_name }}}</span></dd>
      @endif

      @if ($user->github_name)
      <dt><label>GitHub:</label></dt>
      <dd>
        <a href="https://github.com/{{ $user->github_name }}" target="_blank">
          <i class="fa fa-github-alt"></i> {{ $user->github_name }}
        </a>
      </dd>
      @endif

      @if ($user->company)
        <dt class="adr"><label> {{ lang('Company') }}:</label></dt><dd><span class="org">{{{ $user->company }}}</span></dd>
      @endif

      @if ($user->city)
        <dt class="adr"><label> {{ lang('City') }}:</label></dt><dd><span class="org"><i class="fa fa-map-marker"></i> {{{ $user->city }}}</span></dd>
      @endif

      @if ($user->weibo_id)
      <dt><label><span>{{ lang('Weibo') }}</span>:</label></dt>
      <dd>
        <a href="http://weibo.com/u/{{ $user->weibo_id }}" rel="nofollow" class="weibo" target="_blank"><i class="fa fa-weibo"></i> {{{ $user->weibo_name }}}
        </a>
      </dd>
      @endif

      @if ($user->twitter_account)
      <dt><label><span>Twitter</span>:</label></dt>
      <dd>
        <a href="https://twitter.com/{{ $user->twitter_account }}" rel="nofollow" class="twitter" target="_blank"><i class="fa fa-twitter"></i> {{{ $user->twitter_account }}}
        </a>
      </dd>
      @endif

      @if ($user->personal_website)
      <dt><label>{{ lang('Blog') }}:</label></dt>
      <dd>
        <a href="http://{{ $user->personal_website }}" rel="nofollow" target="_blank" class="url">
          <i class="fa fa-globe"></i> {{{ str_limit($user->personal_website, 22) }}}
        </a>
      </dd>
      @endif

      <dt>
        <label>Since:</label>
      </dt>
      <dd><span>{{ $user->created_at }}</span></dd>

      @if($user->present()->lastActivedAt)
      <dt>
        <label>{{ lang('Actived') }}:</label>
      </dt>
      <dd><span class="timeago">{{ $user->present()->lastActivedAt }}</span></dd>
      @endif
    </dl>
    <div class="clearfix"></div>

    @if ($currentUser && ($currentUser->id == $user->id || Entrust::can('manage_users')))
      <a class="btn btn-primary btn-block" href="{{ route('users.edit', $user->id) }}" id="user-edit-button">
        <i class="fa fa-edit"></i> {{ lang('Edit Profile') }}
      </a>
    @endif

    @if(Auth::check() && $currentUser->id != $user->id)
    <!--{{$isFollowing= $currentUser && $currentUser->isFollowing($user->id) ? true : false}}-->

    <a data-method="post" class="btn btn-{{ !$isFollowing ? 'warning' : 'danger' }} btn-block" href="javascript:void(0);" data-url="{{ route('users.doFollow', $user->id) }}" id="user-edit-button">
       <i class="fa {{!$isFollowing ? 'fa-plus' : 'fa-minus'}}"></i> {{ !$isFollowing ? lang('Follow') : lang('Unfollow') }}
    </a>
    @endif

    @if ($currentUser && Entrust::can('manage_users') && ($currentUser->id != $user->id))
      <a data-method="post" class="btn btn-{{ $user->is_banned == 'yes' ? 'warning' : 'danger' }} btn-block" href="javascript:void(0);" data-url="{{ route('users.blocking', $user->id) }}" id="user-edit-button" onclick=" return confirm('{{ lang('Are you sure want to '. ($user->is_banned == 'yes' ? 'unblock' : 'block') . ' this User?') }}')">
        <i class="fa fa-times"></i> {{ $user->is_banned == 'yes' ? lang('Unblock User') : lang('Block User') }}
      </a>
    @endif

    {{-- @if(Auth::check() && Auth::id() == $user->id)
      @include('users.partials.login_QR')
    @endif --}}

</div>
