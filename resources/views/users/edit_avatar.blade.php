@extends('layouts.default')

@section('title')
编辑头像_@parent
@stop

@section('content')

<div class="users-show">

  <div class="col-md-3 box" style="padding: 15px 15px;">
    @include('users.partials.basicinfo')
  </div>

  <div class="main-col col-md-9 left-col">

    <div class="panel panel-default">

      <div class="panel-body ">
        @include('layouts.partials.errors')

        <form method="POST" action="{{ route('users.update_avatar', $user->id) }}" enctype="multipart/form-data" accept-charset="UTF-8">
            <input name="_method" type="hidden" value="PATCH">
            {!! csrf_field() !!}

            <div id="image-preview-div" style="display: none">
              <label for="exampleInputFile">Selected image:</label>
              <br>
              <img id="preview-img" src="noimage">
            </div>
            <div class="form-group">
              <input type="file" name="avatar" id="file" required>
            </div>
            <button class="btn btn-lg btn-primary" id="upload-button" type="submit" disabled>Upload image</button>

            <div class="alert alert-info" id="loading" style="display: none;" role="alert">
              Uploading image...
              <div class="progress">
                <div class="progress-bar progress-bar-striped active" role="progressbar" aria-valuenow="45" aria-valuemin="0" aria-valuemax="100" style="width: 100%">
                </div>
              </div>
            </div>
            <div id="message"></div>
          </div>
      </form>
      </div>

    </div>
  </div>


</div>

@stop
