@extends('layouts.default')

@section('title')
{{ lang('Permission Deny') }}
@stop

@section('content')
  <div class="row">
    <div class="col-md-4 col-md-offset-4">
      <div class="panel panel-default">
        <div class="panel-heading">
          <h3 class="panel-title">{{ lang('Notice') }}</h3>
        </div>
        <div class="panel-body">

          <form method="GET" action="{{route('login')}}" accept-charset="UTF-8">

            <fieldset>
              <div class="alert alert-warning">
                {!! lang('You dont have permission to proceed.') !!}
              </div>

            @if ( ! $currentUser)
            <input class="btn btn-lg btn-primary btn-block" id="login-required-submit" type="submit" value="{{trans('Login with Github')}}">
            @endif


            </fieldset>

        </form>

        </div>
      </div>
    </div>
  </div>

@stop
