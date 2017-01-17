@extends('layouts.default')

@section('title')
{{ lang('Photo Upload') }} | @parent
@stop

@section('content')

<div class="users-show">

  <div class="col-md-3 main-col ">
    @include('users.partials.setting_nav')
  </div>

  <div class="main-col col-md-9 left-col">

    <div class="panel panel-default padding-md">

      <div class="panel-body padding-bg">

        <h2><i class="fa fa-picture-o" aria-hidden="true"></i> {{ lang('Please Choose a Photo') }}</h2>
        <hr>

        @include('layouts.partials.errors')

        <form method="POST" action="{{ route('users.update_avatar', $user->id) }}" enctype="multipart/form-data" accept-charset="UTF-8">
            <input name="_method" type="hidden" value="PATCH">
            {!! csrf_field() !!}

            <div id="image-preview-div">
              <label for="exampleInputFile">{{ lang('Selected image:') }}</label>
              <br>
              <img id="preview-img" class="avatar-preview-img" src="{{$user->present()->gravatar(380)}}">
            </div>
            <div class="form-group">
              <input type="file" name="avatar" id="file" required>
            </div>
            <br>

            <button class="btn btn-lg btn-primary" id="upload-button" type="submit" >{{ lang('Photo Upload') }}</button>

            <div class="alert alert-info" id="loading" style="display: none;" role="alert">
              {{ lang('Uploading image...') }}
              <div class="progress">
                <div class="progress-bar progress-bar-striped active" role="progressbar" aria-valuenow="45" aria-valuemin="0" aria-valuemax="100" style="width: 100%">
                </div>
              </div>
            </div>
            <div id="message"></div>
      </form>

    </div>
  </div>
  </div>


</div>

@stop
