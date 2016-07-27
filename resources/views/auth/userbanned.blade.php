@extends('layouts.default')

@section('title')
{{ lang('Operation Deny') }}
@stop

@section('content')
  <div class="row">
    <div class="col-md-4 col-md-offset-4 floating-box">
      <div class="panel panel-default">
        <div class="panel-heading">
          <h3 class="panel-title">{{ lang('Notice') }}</h3>
        </div>
        <div class="panel-body">

          <form method="GET" action="{{ route('login')}}" accept-charset="UTF-8">
            <fieldset>
              <div class="alert alert-warning">
                {!! lang('Sorry, You account is banned.') !!}
              </div>
            </fieldset>
        </form>

        </div>
      </div>
    </div>
  </div>

@stop
