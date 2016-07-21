@extends('layouts.default')

@section('title')
{{ lang('Email Verification Require') }}_@parent
@stop

@section('content')
  <div class="row">
    <div class="col-md-4 col-md-offset-4 floating-box">
      <div class="panel panel-default">
        <div class="panel-heading">
          <h3 class="panel-title">{{ lang('Email Verification') }}</h3>
        </div>
        <div class="panel-body">
            <form method="POST" id="email-verification-required-form" action="{{route('users.send-verification-mail')}}" accept-charset="UTF-8">
            {!! csrf_field() !!}
            <fieldset>
              <div class="alert alert-warning">
                  邮箱未激活，请前往 {{ \Auth::user()->email }} 查收激活邮件，激活后才能完整地使用社区功能，如发帖和回帖。
                  <br /><br />
                  未收到邮件？请点击以下按钮重新发送验证邮件。
              </div>
              <a class="btn btn-lg btn-primary btn-block" id="email-verification-required-submit" href="javascript:$('#email-verification-required-form').submit();"><i class="fa fa-paper-plane" aria-hidden="true"></i> {{lang('Resend Verification Mail')}}</a>
            </fieldset>
            </form>
        </div>
      </div>
    </div>
  </div>

@stop
