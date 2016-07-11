@extends('layouts.default')

@section('title')
{{{ $user->name }}} {{ lang('Access Tokens') }}_@parent
@stop

@section('content')


<div class="users-show">

  <div class="col-md-3 box" style="padding: 15px 15px;">
    @include('users.partials.basicinfo')
  </div>

  <div class="main-col col-md-9 left-col">


  <div class="panel panel-default">

    @include('users.partials.infonav', ['current' => 'access_tokens'])

    <div class="panel-body remove-padding-vertically remove-padding-horizontal">

      @if (count($tokens))

	      <ul class="list-group">
          @foreach ($tokens as $index => $token)
           <li class="list-group-item" >
              <span style='color: #6e6e6e'>{{ $token->id }}</span>
              <a href="{{ route('users.access_tokens.revoke', $token->id) }}"><button class="btn btn-default btn-sm pull-right center" style="margin-top: -5px">Revoke</button></a>
              <span class="pull-right">{{ $token->created_at}} &nbsp;</span>
          </li>
          @endforeach
        </ul>
      @else
        <div class="empty-block">{{ lang('Dont have any access_token yet') }}~~</div>
      @endif

    </div>

  </div>
  </div>
</div>
@stop
