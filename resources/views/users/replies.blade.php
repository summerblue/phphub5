@extends('layouts.default')

@section('title')
{{{ $user->name }}} {{ lang('Reply List') }}_@parent
@stop

@section('content')


<div class="users-show">

  <div class="col-md-3 box" style="padding: 15px 15px;">
    @include('users.partials.basicinfo')
  </div>

  <div class="main-col col-md-9 left-col">


  <div class="panel panel-default">

    @include('users.partials.infonav', ['current' => 'replies'])

    <div class="panel-body remove-padding-vertically remove-padding-horizontal">

      @if (count($replies))
	      @include('users.partials.replies')
	      <div class="pull-right add-padding-vertically">
	        {!! $replies->render() !!}
	      </div>
      @else
	       <div class="empty-block">{{ lang('Dont have any comment yet') }}~~</div>
      @endif

    </div>

  </div>
</div>
</div>

@stop
