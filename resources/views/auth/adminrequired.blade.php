@extends('layouts.default')

@section('title')
{{ lang('Permission Deny') }}
@stop

@section('content')
  <div class="row">
    <div class="col-md-4 col-md-offset-4 floating-box">
      <div class="panel panel-default">
        <div class="panel-heading">
          <h3 class="panel-title">{{ lang('Notice') }}</h3>
        </div>
        <div class="panel-body">

            <div class="alert alert-warning">
              {!! lang('You dont have permission to proceed.') !!}
            </div>
            @if ( ! $currentUser)
                @include('auth._login_form')
            @endif

        </div>
      </div>
    </div>
  </div>

@stop
