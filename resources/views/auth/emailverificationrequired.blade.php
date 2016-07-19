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

            <fieldset>
              <div class="alert alert-warning">
                  {!! lang('You need to verification email to proceed.') !!}
              </div>
              <a class="btn btn-lg btn-primary btn-block" id="login-required-submit" href="{{ URL::route('users.send-verification-mail') }}"><i class="fa fa-paper-plane" aria-hidden="true"></i> {{lang('Resend Verification Mail')}}</a>
            </fieldset>

        </div>
      </div>
    </div>
  </div>

@stop
