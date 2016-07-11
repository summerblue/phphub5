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

          {{ Form::open(['route'=>'login', 'method'=>'get']) }}

            <fieldset>
              <div class="alert alert-warning">
                {{ lang('You dont have permission to proceed.') }}
              </div>

            @if ( ! $currentUser)
                {{ Form::submit(lang('Login with Github'), ['class' => 'btn btn-lg btn-success btn-block', 'id' => 'login-required-submit']) }}
            @endif


            </fieldset>

          {{ Form::close() }}

        </div>
      </div>
    </div>
  </div>

@stop
