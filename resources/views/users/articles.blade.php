@extends('layouts.default')

@section('title')
{{{ $user->name }}} 的文章列表 | @parent
@stop

@section('content')

<div class="users-show row">

  <div class="col-md-3">
        @include('users.partials.basicinfo')
  </div>

  <div class="main-col col-md-9 left-col">

  <ol class="breadcrumb">
      <li><a href="{{ route('users.show', $user->id) }}">个人中心</a></li>
      @if(Request::is('users/drafts'))
          <li class="active">我的草稿（{{ $user->draft_count }}）</li>
      @else
          <li class="active">Ta 发表的文章（{{ $user->article_count }}）</li>
      @endif
  </ol>

  <div class="panel panel-default">

    <div class="panel-body">

      @if (count($topics))
	    @include('users.partials.topics', ['is_article' => true])
	    <div class="pull-right add-padding-vertically">
	        {!! $topics->render() !!}
	    </div>
      @else
        <div class="empty-block">{{ lang('Dont have any data Yet') }}~~</div>
      @endif

    </div>

  </div>
</div>
</div>

@stop
