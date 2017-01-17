@extends('layouts.default')

@section('title')
{{ lang('Edit Social Binding') }} | @parent
@stop

@section('content')

<div class="users-show">

  <div class="col-md-3 main-col ">
    @include('users.partials.setting_nav')
  </div>

  <div class="main-col col-md-9 left-col">

    <div class="panel panel-default padding-md">

      <div class="panel-body ">

        <h2><i class="fa fa-cog" aria-hidden="true"></i> {{ lang('Edit Social Binding') }}</h2>
        <hr>

        @include('layouts.partials.errors')

        <div class="alert alert-warning">
          {{ lang('Setup multiple Bindings allow you to login to the same account with different binding site account.') }}
        </div>


        <form class="form-horizontal" method="POST" action="{{ route('users.update_email_notify', $user->id) }}" accept-charset="UTF-8">

            {!! csrf_field() !!}


            <div class="form-group">

                <label for="inputEmail3" class="col-sm-3 control-label">{{ lang('Register Binding') }}</label>
                <div class="col-sm-9">

                    <a class="btn btn-success login-btn weichat-login-btn {{ $user->register_source == 'wechat' ? '' : 'hide' }}" role="button">
                      <i class="fa fa-weixin"></i>
                      {{ lang('WeChat') }}
                    </a>

                    <a class="btn btn-info login-btn {{ $user->register_source == 'github' ? '' : 'hide' }}" role="button">
                      <i class="fa fa-github-alt"></i>
                      {{ lang('GitHub') }}
                    </a>

                    <span class="padding-sm">{{ lang('Not allow to change register binding account') }}</span>

                </div>

              </div>

            <div class="form-group">

                <label for="inputEmail3" class="col-sm-3 control-label">{{ lang('Available Bindings') }}</label>
                <div class="col-sm-9">

                    @if($user->register_source != 'wechat')
                    @if($user->wechat_openid)
                    <a href="javascript:void(0);" class="btn btn-success login-btn">
                    @else
                    <a href="{{ URL::route('auth.oauth', ['driver' => 'wechat']) }}" class="btn btn-default login-btn">
                    @endif
                      <i class="fa fa-weixin"></i>
                      {{ lang('WeChat') }}
                    </a>
                    @endif

                    @if($user->register_source != 'github')
                        @if($user->github_id > 0)
                        <a href="javascript:void(0);" class="btn btn-info login-btn">
                        @else
                        <a href="{{ URL::route('auth.oauth', ['driver' => 'github']) }}" class="btn btn-default login-btn">
                        @endif
                          <i class="fa fa-github-alt"></i>
                          {{ lang('GitHub') }}
                        </a>
                    @endif

                    @if($user->github_id > 0 && $user->wechat_openid)
                        <span class="padding-sm">{{ lang('Already binded to this account') }}</span>
                    @else
                        <span class="padding-sm">{{ lang('Click to bind to this account') }}</span>
                    @endif

                </div>
              </div>
<br>
<br>

      </form>


      </div>

    </div>
  </div>


</div>




@stop
