@extends('layouts.default')

@section('title')
修改密码 | @parent
@stop

@section('content')

<div class="users-show">

  <div class="col-md-3 main-col">
    @include('users.partials.setting_nav')
  </div>

  <div class="col-md-9  left-col ">

    <div class="panel panel-default padding-md">

      <div class="panel-body ">

        <h2><i class="fa fa-lock" aria-hidden="true"></i> 修改密码</h2>
        <hr>

        @include('layouts.partials.errors')

        <form class="form-horizontal" method="POST" action="{{ route('users.update_password', $user->id) }}" accept-charset="UTF-8">
            <input name="_method" type="hidden" value="PATCH">
            {!! csrf_field() !!}

            <div class="form-group">
              <label class="col-md-2 control-label">邮 箱：</label>
              <div class="col-md-6">
                <input name="" class="form-control" type="text" value="{{ $user->email }}" disabled>
                <input name="email" type="hidden" value="{{ $user->email }}">
              </div>
              <div class="col-sm-4 help-block">
                  设置密码后将可以使用此邮箱登录。
              </div>
            </div>

            <div class="form-group">
              <label class="col-md-2 control-label">密 码：</label>
              <div class="col-md-6">
                <input type="password" class="form-control" name="password" required>
              </div>
            </div>

            <div class="form-group">
              <label class="col-md-2 control-label">确认密码：</label>
              <div class="col-md-6">
                <input type="password" class="form-control" name="password_confirmation" required>
              </div>
            </div>

          <div class="form-group">
              <div class="col-sm-offset-2 col-sm-6">
                <input class="btn btn-primary" id="user-edit-submit" type="submit" value="{{ lang('Apply Changes') }}">
              </div>
            </div>
      </form>
      </div>

    </div>
  </div>


</div>




@stop
