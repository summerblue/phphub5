@extends('layouts.default')

@section('title')
{{ lang('Edit Social Binding') }}_@parent
@stop

@section('content')

<div class="users-show">

  <div class="col-md-3 box" style="padding: 15px 15px;">
    @include('users.partials.setting_nav')
  </div>

  <div class="main-col col-md-9 left-col">

    <div class="panel panel-default padding-md">

      <div class="panel-body ">

        <h2><i class="fa fa-cog" aria-hidden="true"></i> {{ lang('Edit Social Binding') }}</h2>
        <hr>

        @include('layouts.partials.errors')

        <form method="POST" action="{{ route('users.update', $user->id) }}" accept-charset="UTF-8">

            {!! csrf_field() !!}

          <div class="form-group status-post-submit">
              <input class="btn btn-primary btn-lg" id="user-edit-submit" type="submit" value="{{ lang('Apply Changes') }}">
          </div>


      </form>
      </div>

    </div>
  </div>


</div>




@stop
