@extends('layouts.default')

@section('title')
{{ lang('Edit Email Notify') }} | @parent
@stop

@section('content')

<div class="users-show">

  <div class="col-md-3 main-col " >
    @include('users.partials.setting_nav')
  </div>

  <div class="main-col col-md-9 left-col">

    <div class="panel panel-default padding-md">

      <div class="panel-body ">

        <h2><i class="fa fa-cog" aria-hidden="true"></i> {{ lang('Edit Email Notify') }}</h2>
        <hr>

        @include('layouts.partials.errors')

        <form class="form-horizontal" method="POST" action="{{ route('users.update_email_notify', $user->id) }}" accept-charset="UTF-8">

            {!! csrf_field() !!}


            <div class="form-group">
                <label for="inputEmail3" class="col-sm-3 control-label">{{ lang('Send email when replied') }}</label>
                <div class="col-sm-9">
                  <input type="checkbox" class="bootstrap-switch" name="email_notify_enabled" {{ $user->email_notify_enabled == 'yes' ? 'checked' : '' }}>
                </div>
              </div>
<br>
<br>

          <div class="form-group">
            <div class="col-sm-offset-2 col-sm-10">
              <input class="btn btn-primary btn-lg" id="user-edit-submit" type="submit" value="{{ lang('Apply Changes') }}">
            </div>
          </div>


      </form>
      </div>

    </div>
  </div>


</div>

@stop
