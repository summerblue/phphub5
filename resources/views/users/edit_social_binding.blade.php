@extends('layouts.default')

@section('title')
{{ lang('Edit Social Binding') }}_@parent
@stop

@section('content')

<div class="users-show">

  <div class="col-md-3 box" style="padding: 15px 15px;">
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

                    <a class="btn btn-success login-btn weichat-login-btn" role="button">
                      <i class="fa fa-weixin"></i>
                      {{ lang('WeChat') }}
                    </a>
                    <span class="padding-sm">{{ lang('Not allow to change register binding account') }}</span>

                    <a href="{{ URL::route('auth.oauth', ['driver' => 'github']) }}" class="btn btn-info login-btn hide">
                      <i class="fa fa-github-alt"></i>
                      {{ lang('Github') }}
                    </a>

                </div>

              </div>

            <div class="form-group">

                <label for="inputEmail3" class="col-sm-3 control-label">{{ lang('Available Bindings') }}</label>
                <div class="col-sm-9">

                    <a href="{{ URL::route('auth.oauth', ['driver' => 'weixin']) }}" class="btn btn-success login-btn weichat-login-btn hide">
                      <i class="fa fa-weixin"></i>
                      {{ lang('WeChat') }}
                    </a>

                    <a href="{{ URL::route('auth.oauth', ['driver' => 'github']) }}" class="btn btn-default login-btn">
                      <i class="fa fa-github-alt"></i>
                      {{ lang('Github') }}
                    </a>

                    <span class="padding-sm">{{ lang('Click to bind to this account') }}</span>
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
