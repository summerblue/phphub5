@extends('layouts.default')

@section('title')
用户登录 | @parent
@stop

@section('content')
  <div class="row">
    <div class="col-md-4 col-md-offset-4 floating-box">
      <div class="panel panel-default">
        <div class="panel-heading">
          <h3 class="panel-title">请登录</h3>
        </div>
        <div class="panel-body">

            <form method="POST" action="{{ route('auth.login') }}" accept-charset="UTF-8">
                {{ csrf_field() }}

                <div class="form-group {{{ $errors->has('email') ? 'has-error' : '' }}}">
                    <label class="control-label" for="email">{{ lang('Email') }}</label>
                    <input class="form-control" name="email" type="text" value="{{ old('email') }}" placeholder="请填写 Email">
                    {!! $errors->first('email', '<span class="help-block">:message</span>') !!}
                </div>

                <div class="form-group {{{ $errors->has('password') ? 'has-error' : '' }}}">
                    <label class="control-label" for="password">密 码</label>
                    <input class="form-control" name="password" type="password" value="{{ old('password') }}" placeholder="请填写密码">
                    {!! $errors->first('password', '<span class="help-block">:message</span>') !!}
                </div>

                <button type="submit" class="btn btn-lg btn-success btn-block">
                    <i class="fa fa-btn fa-sign-in"></i> 登录
                </button>

                <hr>

                <fieldset class="form-group">
                  <a class="btn btn-lg btn-default btn-block" id="login-required-submit" href="{{ URL::route('auth.oauth', ['driver' => 'github']) }}"><i class="fa fa-github-alt"></i> {{lang('Login with GitHub')}}</a>
                  <a class="btn btn-lg btn-default btn-block" href="{{ URL::route('auth.oauth', ['driver' => 'wechat']) }}"><i class="fa fa-weixin" ></i> {{lang('Login with WeChat')}}</a>
                </fieldset>
            </form>

        </div>
      </div>
    </div>
  </div>

@stop
