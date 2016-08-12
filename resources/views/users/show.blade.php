@extends('layouts.default')

@section('title')
{{{ $user->name }}} {{ lang('Basic Info') }}_@parent
@stop

@section('content')

<div class="users-show  row">

  <div class="col-md-3">
        @include('users.partials.basicinfo')
  </div>

  <div class="main-col col-md-9 left-col">

    @if ($user->introduction)
      <div class="box text-center">{{{ $user->introduction }}}</div>
    @endif

    @if ($user->is_banned == 'yes')
      <div class="text-center alert alert-info"><b>{{ lang('This user is banned!') }}</b></div>
    @endif

    <div class="panel panel-default">
        <div class="panel-heading">
          {{ lang('Recent Topics') }}
        </div>

        <div class="panel-body">
            @if (count($topics))
              @include('users.partials.topics')
            @else
              <div class="empty-block">{{ lang('Dont have any data Yet') }}~~</div>
            @endif
        </div>
    </div>


    <div class="panel panel-default">
        <div class="panel-heading">
          {{ lang('Recent Replies') }}
        </div>

        <div class="panel-body">
            @if (count($replies))
              @include('users.partials.replies')
            @else
              <div class="empty-block">{{ lang('Dont have any comment yet') }}~~</div>
            @endif
        </div>
    </div>

  </div>


</div>




@stop
