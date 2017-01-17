@extends('layouts.default')

@section('title')
{{{ $user->name }}} {{ lang('Following User') }}_@parent
@stop

@section('content')

<div class="users-show row">

    <div class="col-md-3">
          @include('users.partials.basicinfo')
    </div>

  <div class="main-col col-md-9 left-col">

  <ol class="breadcrumb">
      <li><a href="{{ route('users.show', $user->id) }}">个人中心</a></li>
      <li class="active">Ta 关注的用户</li>
  </ol>

  <div class="panel panel-default">


    <div class="panel-body">

      @if (count($users))
	    @include('users.partials.users')
	    <div class="pull-right add-padding-vertically">
	        {!! $users->render() !!}
	    </div>
      @else
        <div class="empty-block">{{ lang('Dont have any data Yet') }}~~</div>
      @endif

    </div>

  </div>
</div>
</div>

@stop
