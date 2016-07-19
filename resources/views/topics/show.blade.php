@extends('layouts.default')

@section('title')
{{{ $topic->title }}}_@parent
@stop

@section('description')
{{{ $topic->excerpt }}}
@stop

@section('content')

<div class="col-md-9 topics-show main-col">
  <!-- Topic Detial -->
  <div class="topic panel panel-default padding-md">
    <div class="infos panel-heading">

      <h1 class="panel-title topic-title">{{{ $topic->title }}}</h1>

      <div class="votes">

        <a data-ajax="post" href="javascript:void(0);" data-url="{{ route('topics.upvote', $topic->id) }}" title="{{ lang('Up Vote') }}" id="up-vote" class="vote {{ $currentUser && $topic->votes()->ByWhom(Auth::id())->WithType('upvote')->count() ? 'active' :'' }}">
            <li class="fa fa-chevron-up"></li> <span id="vote-count"> {{ $topic->vote_count }}</span>
        </a>
         &nbsp;
        <a data-ajax="post" href="javascript:void(0);" data-url="{{ route('topics.downvote', $topic->id) }}" title="{{ lang('Down Vote') }}" id="down-vote" class="vote {{ $currentUser && $topic->votes()->ByWhom(Auth::id())->WithType('downvote')->count() ? 'active' :'' }}">
            <li class="fa fa-chevron-down"></li>
        </a>
      </div>

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

    @include('topics.partials.topic_operate')
  </div>


  <div class="replies panel panel-default padding-md topic-author-box">
    <div class="panel-body ">
        <div class="media">
          <div class="media-left">
            <a href="{{ route('users.show', $topic->user->id) }}">
              <img src="{{ $topic->user->present()->gravatar }}" style="width:65px; height:65px;" class="img-thumbnail avatar" />
            </a>
          </div>
          <div class="media-body padding-top-sm">
            <div class="media-heading">
                <a href="{{ route('users.show', [$topic->user_id]) }}" title="{{{ $topic->user->name }}}" class="remove-padding-left author">
                    {{{ $topic->user->name }}}
                </a>
                @if($topic->user->introduction)
                <span class="introduction">
                     ，{{{ $topic->user->introduction }}}
                </span>
                @endif
            </div>


            <ul class="list-inline">

              @if ($topic->user->real_name)
                <li class="adr"><span class="org">{{{ $topic->user->real_name }}}</span></li>
              @endif

              @if ($topic->user->present()->hasBadge())
                <li><span class="label label-warning" style="position:relative">{{{ $topic->user->present()->badgeName() }}}</span></li>
              @endif

              @if ($topic->user->github_name)
              <li>
                <a href="https://github.com/{{ $topic->user->github_name }}" target="_blank">
                  <i class="fa fa-github-alt"></i> {{ $topic->user->github_name }}
                </a>
              </li>
              @endif

              @if ($topic->user->weibo_id)
              <li>
                <a href="http://weibo.com/u/{{ $topic->user->weibo_id }}" rel="nofollow" class="weibo" target="_blank"><i class="fa fa-weibo"></i> {{{ '@' . ($topic->user->weibo_name ?: $topic->user->weibo_id) }}}
                </a>
              </li>
              @endif

              @if ($topic->user->company)
                <li class="adr"><span class="org"><i class="fa fa-child"></i> {{{ $topic->user->company }}}</span></li>
              @endif

              @if ($topic->user->city)
                <li class="adr"><span class="org"><i class="fa fa-map-marker"></i> {{{ $topic->user->city }}}</span></li>
              @endif



              @if ($topic->user->twitter_account)
              <li>
                <a href="https://twitter.com/{{ $topic->user->twitter_account }}" rel="nofollow" class="twitter" target="_blank"><i class="fa fa-twitter"></i> {{{ '@' . $topic->user->twitter_account }}}
                </a>
            </li>
              @endif

              @if ($topic->user->personal_website)
              <li>
                <a href="http://{{ $topic->user->personal_website }}" rel="nofollow" target="_blank" class="url">
                  <i class="fa fa-globe"></i> {{{ str_limit($topic->user->personal_website, 22) }}}
                </a>
            </li>
              @endif
          </ul>

            <div class="clearfix"></div>
          </div>
        </div>

    </div>
  </div>


  <div class="replies panel panel-default padding-md">

    <div class="panel-body ">
        <div class="social-share-cs "></div>
    </div>
  </div>

  <!-- Reply List -->
  <div class="replies panel panel-default list-panel replies-index padding-md">
    <div class="panel-heading">
      <div class="total">{{ lang('Total Reply Count') }}: <b>{{ $replies->total() }}</b> </div>
    </div>

    <div class="panel-body">

      @if (count($replies))
        @include('topics.partials.replies')
        <div id="replies-empty-block" class="empty-block hide">{{ lang('No comments') }}~~</div>
      @else
        <ul class="list-group row"></ul>
        <div id="replies-empty-block" class="empty-block">{{ lang('No comments') }}~~</div>
      @endif

      <!-- Pager -->
      <div class="pull-right" style="padding-right:20px">
        {!! $replies->appends(Request::except('page'))->render() !!}
      </div>
    </div>
  </div>

  <!-- Reply Box -->
  <div class="reply-box form box-block">

    @include('layouts.partials.errors')

    <form method="POST" action="{{ route('replies.store') }}" accept-charset="UTF-8" id="reply-form">
      {!! csrf_field() !!}
      <input type="hidden" name="topic_id" value="{{ $topic->id }}" />

        @include('topics.partials.composing_help_block')

        <div class="form-group">
            @if ($currentUser)
                <textarea class="form-control" rows="5" placeholder="{{ lang('Please using markdown.') }}" style="overflow:hidden" id="reply_content" name="body" cols="50"></textarea>
            @else
                <textarea class="form-control" disabled="disabled" rows="5" placeholder="{{ lang('User Login Required for commenting.') }}" name="body" cols="50"></textarea>
            @endif
        </div>

        <div class="form-group status-post-submit">
            <input class="btn btn-primary {{ $currentUser ? :'disabled'}}" id="reply-create-submit" type="submit" value="{{ lang('Reply') }}">
            <span class="help-inline" title="Or Command + Enter">Ctrl+Enter</span>
        </div>

        <div class="box preview markdown-reply" id="preview-box" style="display:none;"></div>

    </form>
  </div>


</div>

@include('layouts.partials.sidebar')
@include('layouts.partials.bottombanner')
@stop

@section('scripts')
<script type="text/javascript">

    $(document).ready(function()
    {
        var $config = {
            title               : '{{{ $topic->title }}} from PHPHub - PHP，Laravel的中文社区 #laravel# @phphub',
            wechatQrcodeTitle   : "微信扫一扫：分享", // 微信二维码提示文字
            wechatQrcodeHelper  : '<p>微信里点“发现”，扫一下</p><p>二维码便可将本文分享至朋友圈。</p>',
        };

        socialShare('.social-share-cs', $config);
    });

</script>
@stop