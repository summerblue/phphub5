@extends('layouts.default')

@section('title')
{{ lang('Edit Profile') }}_@parent
@stop

@section('content')

<div class="users-show">

  <div class="col-md-3 box" style="padding: 15px 15px;">
    @include('users.partials.setting_nav')
  </div>

  <div class="main-col col-md-9 left-col">

    <div class="panel panel-default padding-md">

      <div class="panel-body ">

        <h2><i class="fa fa-cog" aria-hidden="true"></i> {{ lang('Edit Profile') }}</h2>
        <hr>

        @include('layouts.partials.errors')

        <form class="form-horizontal" method="POST" action="{{ route('users.update', $user->id) }}" accept-charset="UTF-8">
            <input name="_method" type="hidden" value="PATCH">
            {!! csrf_field() !!}

            <div class="form-group">
                <label for="" class="col-sm-2 control-label">{{ lang('GitHub Name') }}</label>
                <div class="col-sm-10">
                    <input class="form-control" placeholder="{{ lang('GitHub Name likes: summerblue') }}" name="github_name" type="text" value="{{ $user->github_name }}">
                </div>
            </div>

            <div class="form-group">
                <label for="" class="col-sm-2 control-label">{{ lang('Email') }}</label>
                <div class="col-sm-10">
                    <input class="form-control" placeholder="{{ lang('Email example: name@website.com') }}" name="email" type="text" value="{{ $user->email }}">
                </div>
            </div>

          <div class="form-group">
              <label for="" class="col-sm-2 control-label">{{ lang('Real Name') }}</label>
              <div class="col-sm-10">
                  <input class="form-control" placeholder="{{ lang('Real Name example: 李小明') }}" name="real_name" type="text" value="{{ $user->real_name }}">
              </div>
          </div>

          <div class="form-group">
              <label for="" class="col-sm-2 control-label">{{lang('City')}}</label>
              <div class="col-sm-10">
                  <input class="form-control" placeholder="{{lang('City example: BeiJing')}}" name="city" type="text" value="{{ $user->city }}">
              </div>
          </div>

          <div class="form-group">
              <label for="" class="col-sm-2 control-label">{{ lang('Company') }}</label>
              <div class="col-sm-10">
                  <input class="form-control" placeholder="{{ lang('Company example: Alibaba') }}" name="company" type="text" value="{{ $user->company }}">
              </div>
          </div>

          <div class="form-group">
              <label for="" class="col-sm-2 control-label">{{ lang('Weibo Username') }}</label>
              <div class="col-sm-10">
                  <input class="form-control" placeholder="{{ lang('Weibo Username example: PHPHub') }}" name="weibo_name" type="text" value="{{ $user->weibo_name}}">
              </div>
          </div>

          <div class="form-group">
              <label for="" class="col-sm-2 control-label">{{ lang('Weibo ID') }}</label>
              <div class="col-sm-10">
                  <input class="form-control" placeholder="{{ lang('Weibo ID example: 5963322692') }}" name="weibo_id" type="text" value="{{ $user->weibo_id}}">
              </div>
          </div>

          <div class="form-group">
              <label for="" class="col-sm-2 control-label">{{ lang('twitter_placeholder') }}</label>
              <div class="col-sm-10">
                  <input class="form-control" placeholder="{{ lang('twitter_placeholder_hint') }}" name="twitter_account" type="text" value="{{ $user->twitter_account}}">
              </div>
          </div>

          <div class="form-group">
              <label for="" class="col-sm-2 control-label">{{ lang('personal_website_placebolder') }}</label>
              <div class="col-sm-10">
                  <input class="form-control" placeholder="{{ lang('personal_website_placebolder_hint') }}" name="personal_website" type="text" value="{{ $user->personal_website }}">
              </div>
          </div>

          <div class="form-group">
              <label for="" class="col-sm-2 control-label">{{ lang('introduction_placeholder') }}</label>
              <div class="col-sm-10">
                  <textarea class="form-control" rows="3" placeholder="{{ lang('introduction_placeholder_hint') }}" name="introduction" cols="50">{{ $user->introduction }}</textarea>
              </div>
          </div>
          <div class="form-group">
              <div class="col-sm-offset-2 col-sm-10">
                <input class="btn btn-primary" id="user-edit-submit" type="submit" value="{{ lang('Apply Changes') }}">
              </div>
            </div>
      </form>
      </div>

    </div>
  </div>


</div>




@stop
