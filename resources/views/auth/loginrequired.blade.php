@extends('layouts.default')

@section('title')
{{ lang('User Login Require') }}_@parent
@stop

@section('content')
  <div class="row">
    <div class="col-md-4 col-md-offset-4">
      <div class="panel panel-default">
        <div class="panel-heading">
          <h3 class="panel-title">{{ lang('Login') }}</h3>
        </div>
        <div class="panel-body">

          <form method="GET" action="{{route('login')}}" accept-charset="UTF-8">

            <fieldset>
              <div class="alert alert-warning">
                  {!! lang('You need to login to proceed.') !!}
              </div>
              <input class="btn btn-lg btn-primary btn-block" id="login-required-submit" type="submit" value="{{trans('Login with GitHub')}}">
            </fieldset>

        </form>

        </div>
      </div>
    </div>
  </div>

@stop
