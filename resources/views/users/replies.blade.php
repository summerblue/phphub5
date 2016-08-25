@extends('layouts.default')

@section('title')
{{{ $user->name }}} {{ lang('Reply List') }}_@parent
@stop

@section('content')


<div class="users-show row">

  <div class="col-md-3">
        @include('users.partials.basicinfo')
  </div>

  <div class="main-col col-md-9 left-col">

  <ol class="breadcrumb">
      <li><a href="{{ route('users.show', $user->id) }}">个人中心</a></li>
      <li class="active">Ta 发表的回复（{{ $user->reply_count }}）</li>
  </ol>

  <div class="panel panel-default">

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
