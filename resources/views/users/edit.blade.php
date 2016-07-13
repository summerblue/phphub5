@extends('layouts.default')

@section('title')
编辑个人资料_@parent
@stop

@section('content')

<div class="users-show">

  <div class="col-md-3 box" style="padding: 15px 15px;">
    @include('users.partials.basicinfo')
  </div>

  <div class="main-col col-md-9 left-col">

    <div class="panel panel-default">

      <div class="panel-body ">

        <div class="alert alert-warning">
          {!! lang('avatar_notice') !!} {!! link_to_route('users.refresh_cache', lang('Update Cache'), $user->id) !!} .
        </div>

        @include('layouts.partials.errors')

        <form method="POST" action="{{ route('users.update', $user->id) }}" accept-charset="UTF-8">
            <input name="_method" type="hidden" value="PATCH">
            {!! csrf_field() !!}

            <div class="form-group">
                <input class="form-control" placeholder="{{ lang('GitHub Name') }}" name="github_name" type="text" value="{{ $user->github_name }}">
            </div>

          <div class="form-group">
              <input class="form-control" placeholder="{{ lang('Real Name') }}" name="real_name" type="text" value="{{ $user->real_name }}">
          </div>

          <div class="form-group">
              <input class="form-control" placeholder="{{lang('City')}}" name="city" type="text" value="{{ $user->city }}">
          </div>

          <div class="form-group">
              <input class="form-control" placeholder="{{ lang('Company') }}" name="company" type="text" value="{{ $user->company }}">
          </div>

          <div class="form-group">
              <input class="form-control" placeholder="{{ lang('twitter_placeholder') }}" name="twitter_account" type="text" value="{{ $user->twitter_account}}">
          </div>

          <div class="form-group">
              <input class="form-control" placeholder="{{ lang('personal_website_placebolder') }}" name="personal_website" type="text" value="{{ $user->personal_website }}">
          </div>

          <div class="form-group">
              <textarea class="form-control" rows="3" placeholder="{{ lang('introduction_placeholder') }}" name="introduction" cols="50">{{ $user->introduction }}</textarea>
          </div>

          <div class="form-group status-post-submit">
              <input class="btn btn-primary" id="user-edit-submit" type="submit" value="{{ lang('Publish') }}">
          </div>


      </form>
      </div>

    </div>
  </div>


</div>




@stop
