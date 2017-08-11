@extends('layouts.default')

@section('title')
{{{ $topic->title }}} | @parent
@stop

@section('description')
{{{ $topic->excerpt }}}
@stop

@section('wechat_icon')
    @if ($cover = $topic->cover())
        <img src="{{ img_crop($cover->link, 512, 512) }}" alt="">
    @else
        @parent
    @endif
@stop

@section('content')

<div class="col-md-9 topics-show main-col">
  <!-- Topic Detial -->
  <div class="topic panel panel-default">
    <div class="infos panel-heading">

      <h1 class="panel-title topic-title">
        @if ($topic->isShareLink())
            <a href="{{ $topic->share_link->link }}" target="_blank">
                <i class="fa fa-link"></i>
            </a>
        @endif
        {{{ $topic->title }}}
        </h1>

      @include('topics.partials.meta')
    </div>

    <div class="content-body entry-content panel-body ">

      @include('topics.partials.body', array('body' => $topic->body))

      <div data-lang-excellent="{{ lang('This topic has been mark as Excenllent Topic.') }}" data-lang-wiki="{{ lang('This is a Community Wiki.') }}" class="ribbon-container">
        @include('topics.partials.ribbon')
      </div>
    </div>
    <div class="appends-container" data-lang-append="{{ lang('Append') }}">
      @foreach ($topic->appends as $index => $append)

          <div class="appends">
              <span class="meta">{{ lang('Append') }} {{ $index }} &nbsp;·&nbsp; <abbr title="{{ $append->created_at }}" class="timeago">{{ $append->created_at }}</abbr></span>
              <div class="sep5"></div>
              <div class="markdown-reply append-content">
                  {!! $append->content !!}
              </div>
          </div>

      @endforeach
    </div>
    @if($revisionHistory && in_array($revisionHistory->key, ['is_excellent', 'order']))
    <div class="admin-operation">
        <?php
        $revisionAdmin = \App\Models\User::find($revisionHistory->user_id);
        $adminOperation = '';
        if ($revisionHistory->key == 'is_excellent') {
            $adminOperation = $revisionHistory->new_value == 'yes' ? '加精' : '解除加精';
        }

        if ($revisionHistory->key == 'order') {
            if ($revisionHistory->new_value < 0) {
                $adminOperation = '沉帖';
            } elseif ($revisionHistory->new_value > 0) {
                $adminOperation = '置顶';
            } elseif ($revisionHistory->new_value == 0) {
                $adminOperation = $revisionHistory->old_value > 0 ? '取消置顶' : '取消沉帖';
            }
        }
        ?>
        @if($adminOperation)
        本帖由 <a href="{{route('users.show', $revisionAdmin->id)}}" target="_blank">{{$revisionAdmin->name}}</a> 于 {{$revisionHistory->created_at->diffForHumans()}} {{$adminOperation}}
        @endif
    </div>
    @endif
    @include('topics.partials.topic_operate', ['manage_topics' => $currentUser ? ($currentUser->can("manage_topics") && $currentUser->roles->count() <= 5) : false])
  </div>


  @include('topics.partials.show_segment')

</div>

@if( $topic->user->payment_qrcode )
    @include('topics.partials.payment_qrcode_modal')
@endif

@include('layouts.partials.sidebar')

@include('layouts.partials.bottombanner')

@stop

@section('scripts')
<script type="text/javascript">

    $(document).ready(function()
    {
        var $config = {
            title               : '{{{ $topic->title }}} | from LC #laravel-china# {{ $topic->user->id != 1 ? '@summer_charlie' : '' }} {{ $topic->user->weibo_name ? '@'.$topic->user->weibo_name : '' }}',
            wechatQrcodeTitle   : "微信扫一扫：分享", // 微信二维码提示文字
            wechatQrcodeHelper  : '<p>微信里点“发现”，扫一下</p><p>二维码便可将本文分享至朋友圈。</p>',
            image               : "{{ $cover ? $cover->link : 'https://dn-phphub.qbox.me/uploads/images/201701/29/1/pQimFCe1r5.png' }}",
            sites               : ['weibo','wechat',  'facebook', 'twitter', 'google','qzone', 'qq', 'douban'],
        };

        socialShare('.social-share-cs', $config);

        Config.following_users =  @if($currentUser) {!!$currentUser->present()->followingUsersJson()!!} @else [] @endif;
        PHPHub.initAutocompleteAtUser();
    });

</script>
@stop
