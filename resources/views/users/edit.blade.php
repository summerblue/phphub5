@extends('layouts.default')

@section('title')
{{ lang('Edit Profile') }} | @parent
@stop

@section('content')

<div class="users-show">

  <div class="col-md-3 main-col">
    @include('users.partials.setting_nav')
  </div>

  <div class="col-md-9  left-col ">

    <div class="panel panel-default padding-md">

      <div class="panel-body ">

        <h2><i class="fa fa-cog" aria-hidden="true"></i> {{ lang('Edit Profile') }}</h2>
        <hr>

        @include('layouts.partials.errors')

        <form class="form-horizontal" method="POST" action="{{ route('users.update', $user->id) }}" accept-charset="UTF-8" enctype="multipart/form-data">
            <input name="_method" type="hidden" value="PATCH">
            {!! csrf_field() !!}

            <div class="form-group">
                <label for="" class="col-sm-2 control-label">性别</label>
                <div class="col-sm-6">
                    <select class="form-control" name="gender">
                      <option value="unselected" {{ $user->gender == 'unselected' ? 'selected' : '' }}>未选择</option>
                      <option value="male" {{ $user->gender == 'male' ? 'selected' : '' }}>男</option>
                      <option value="female" {{ $user->gender == 'female' ? 'selected' : '' }}>女</option>
                    </select>
                </div>

                <div class="col-sm-4 help-block"></div>
            </div>

            <div class="form-group">
                <label for="" class="col-sm-2 control-label">{{ lang('GitHub Name') }}</label>
                <div class="col-sm-6">
                    <input class="form-control" name="github_name" type="text" value="{{ $user->github_name }}">
                </div>

                <div class="col-sm-4 help-block">
                    请跟 GitHub 上保持一致
                </div>
            </div>

            <div class="form-group">
                <label for="" class="col-sm-2 control-label">{{ lang('Email') }}</label>
                <div class="col-sm-6">
                    <input class="form-control" name="email" type="text" value="{{ $user->email }}">
                </div>
                <div class="col-sm-4 help-block">
                    {{ lang('Email example: name@website.com') }}
                </div>
            </div>

          <div class="form-group">
              <label for="" class="col-sm-2 control-label">{{ lang('Real Name') }}</label>
              <div class="col-sm-6">
                  <input class="form-control" name="real_name" type="text" value="{{ $user->real_name }}">
              </div>
              <div class="col-sm-4 help-block">
                {{ lang('Real Name example: 李小明') }}
              </div>
          </div>

          <div class="form-group">
              <label for="" class="col-sm-2 control-label">{{lang('City')}}</label>
              <div class="col-sm-6">
                  <input class="form-control" name="city" type="text" value="{{ $user->city }}">
              </div>
              <div class="col-sm-4 help-block">
                    {{lang('City example: BeiJing')}}
              </div>
          </div>

          <div class="form-group">
              <label for="" class="col-sm-2 control-label">{{ lang('Company') }}</label>
              <div class="col-sm-6">
                  <input class="form-control" name="company" type="text" value="{{ $user->company }}">
              </div>
              <div class="col-sm-4 help-block">
                {{ lang('Company example: Alibaba') }}
              </div>
          </div>

          <div class="form-group">
              <label for="" class="col-sm-2 control-label">{{ lang('Weibo Username') }}</label>
              <div class="col-sm-6">
                  <input class="form-control" name="weibo_name" type="text" value="{{ $user->weibo_name}}">
              </div>
              <div class="col-sm-4 help-block">
                    {{ lang('Weibo Username example: PHPHub') }}
              </div>
          </div>

          <div class="form-group">
              <label for="" class="col-sm-2 control-label">微博个人页面</label>
              <div class="col-sm-6">
                  <input class="form-control" name="weibo_link" type="text" value="{{ $user->weibo_link}}">
              </div>
              <div class="col-sm-4 help-block">
                微博个人主页链接，如：http://weibo.com/laravelnews
              </div>
          </div>

          <div class="form-group">
              <label for="" class="col-sm-2 control-label">{{ lang('twitter_placeholder') }}</label>
              <div class="col-sm-6">
                  <input class="form-control" name="twitter_account" type="text" value="{{ $user->twitter_account}}">
              </div>
              <div class="col-sm-4 help-block">
                {{ lang('twitter_placeholder_hint') }}
              </div>
          </div>

          <div class="form-group">
              <label for="" class="col-sm-2 control-label">{{ lang('LinkedIn') }}</label>
              <div class="col-sm-6">
                  <input class="form-control" name="linkedin" type="text" value="{{ $user->linkedin}}">
              </div>
              <div class="col-sm-4 help-block">
                你的 <a href="https://www.linkedin.com">LinkedIn</a> 主页完整 URL 地址，如：https://cn.linkedin.com/in/summerblue
              </div>
          </div>

          <div class="form-group">
              <label for="" class="col-sm-2 control-label">{{ lang('personal_website_placebolder') }}</label>
              <div class="col-sm-6">
                  <input class="form-control" name="personal_website" type="text" value="{{ $user->personal_website }}">
              </div>
              <div class="col-sm-4 help-block">
                    {{ lang('personal_website_placebolder_hint') }}
              </div>
          </div>

            <div class="form-group">
                <label for="wechat_qrcode" class="col-sm-2 control-label">微信账号二维码</label>
                <div class="col-sm-6">
                    <input type="file" name="wechat_qrcode">
                    @if($user->wechat_qrcode)
                        <img class="payment-qrcode" src="{{ $user->wechat_qrcode }}" alt="" />
                    @endif
                </div>
                <div class="col-sm-4 help-block">
                    你的微信个人账号，或者订阅号
                </div>
            </div>

            <div class="form-group">
                <label for="" class="col-sm-2 control-label">支付二维码</label>
                <div class="col-sm-6">
                    <input type="file" name="payment_qrcode">

                    @if($user->payment_qrcode)
                        <img class="payment-qrcode" src="{{ $user->payment_qrcode }}" alt="" />
                    @endif
                </div>
                <div class="col-sm-4 help-block">
                      文章打赏时使用，微信支付二维码
                </div>
            </div>

          <div class="form-group">
              <label for="" class="col-sm-2 control-label">{{ lang('introduction_placeholder') }}</label>
              <div class="col-sm-6">
                  <textarea class="form-control" rows="3" name="introduction" cols="50">{{ $user->introduction }}</textarea>
              </div>
              <div class="col-sm-4 help-block">
                    {{ lang('introduction_placeholder_hint') }}，大部分情况下会在你的头像和名字旁边显示
              </div>
          </div>

          <div class="form-group">
              <label for="" class="col-sm-2 control-label">署名</label>
              <div class="col-sm-6">
                  <textarea class="form-control" rows="3" name="signature" cols="50">{{ $user->signature }}</textarea>
              </div>
              <div class="col-sm-4 help-block">
                 文章署名，会拼接在每一个你发表过的帖子内容后面。支持 Markdown。
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
