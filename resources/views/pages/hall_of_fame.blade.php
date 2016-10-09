@extends('layouts.default')

@section('title')
Laravel China 名人堂 @parent
@stop

@section('content')

<div class="hall_of_fames">

    <div class="box text-center site-intro rm-link-color">
        Laravel China 名人堂，用于推荐对 Laravel China 社区有突出贡献的用户。<a style="text-decoration: underline;" href="https://laravel-china.org/topics/2440">更多信息</a>
    </div>

    @foreach($users as $index => $user)

        <div class="col-lg-6 ">

            <div class="card {{ $index % 2 == 0 ? 'add-margin-right' : 'add-margin-left' }}">

                @if ($currentUser && $currentUser->id == $user->id)
                <a class="pull-right popover-with-html text-lg animated rubberBand edit-btn" href="{{ route('users.edit', $user->id) }}" data-content="{{ lang('Edit Profile') }}">
                    <i class="fa fa-cog"></i>
                </a>
                @endif

              <div class="media-left">
                <a href="{{ route('users.show', $user->id) }}">
                  <img src="{{ $user->present()->gravatar }}" class="img-thumbnail avatar" />
                </a>
              </div>
              <div class="media-body padding-top-sm">
                <div class="media-heading">
                    <a href="{{ route('users.show', [$user->id]) }}" title="{{{ $user->name }}}" class="remove-padding-left author">
                        {{{ $user->name }}}
                    </a>
                    @if($user->introduction)
                    <span class="introduction">
                         ，{{{ $user->introduction }}}
                    </span>
                    @endif
                </div>

                <div class="certification">
                    {!! $user->certification !!}
                </div>

                <ul class="list-inline">

                  @if ($user->real_name)
                    <li class="popover-with-html" data-content="{{ lang('Real Name') }}"><span class="org"><i class="fa fa-user"></i> {{{ $user->real_name }}}</span></li>
                  @endif

                  @if ($user->github_name)
                  <li class="popover-with-html" data-content="{{ $user->github_name }}">
                    <a href="https://github.com/{{ $user->github_name }}" target="_blank">
                      <i class="fa fa-github-alt"></i>
                    </a>
                  </li>
                  @endif

                  @if ($user->weibo_link)
                  <li class="popover-with-html" data-content="{{ $user->weibo_name }}">
                    <a href="{{ $user->weibo_link }}" rel="nofollow" class="weibo" target="_blank"><i class="fa fa-weibo"></i>
                    </a>
                  </li>
                  @endif

                  @if ($user->twitter_account)
                  <li class="popover-with-html" data-content="{{ $user->twitter_account }}">
                    <a href="https://twitter.com/{{ $user->twitter_account }}" rel="nofollow" class="twitter" target="_blank"><i class="fa fa-twitter"></i>
                    </a>
                </li>
                  @endif

                  @if ($user->linkedin)
                  <li class="popover-with-html" data-content="点击查看 LinkedIn 个人资料">
                    <a href="{{ $user->linkedin }}" rel="nofollow" class="linkedin" target="_blank"><i class="fa fa-linkedin"></i>
                    </a>
                </li>
                  @endif

                  @if ($user->personal_website)
                  <li class="popover-with-html" data-content="{{ $user->personal_website }}">
                    <a href="http://{{ $user->personal_website }}" rel="nofollow" target="_blank" class="url">
                      <i class="fa fa-globe"></i>
                    </a>
                </li>
                  @endif

                @if ($user->company)
                  <li class="popover-with-html" data-content="{{ $user->company }}"><i class="fa fa-users"></i> </li>
                @endif

                @if ($user->city)
                  <li class="popover-with-html" data-content="{{ $user->city }}"><i class="fa fa-map-marker"></i> </li>
                @endif

              </ul>



                <div class="clearfix"></div>
              </div>
            </div>

        </div>

    @endforeach


    @include('layouts.partials.topbanner')
</div>

@stop
