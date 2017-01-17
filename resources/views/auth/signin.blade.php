@extends('layouts.default')

@section('title')
用户登录 | @parent
@stop

@section('content')
  <div class="row">
    <div class="col-md-4 col-md-offset-4 floating-box">
      <div class="panel panel-default">
        <div class="panel-heading">
          <h3 class="panel-title">请登录</h3>
        </div>
        <div class="panel-body">

            @include('auth._login_form')

        </div>
      </div>
    </div>
  </div>

@stop
