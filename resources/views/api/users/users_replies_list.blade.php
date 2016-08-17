
@extends('api.layouts.default')

@section('title')
评论列表
@stop

@section('content')

<ul class="list-comment">
    @if(!count($replies))
        <div class="content-blank">暂无内容</div>
    @endif
    @foreach($replies as $reply)
        <?php
            if (! $reply->topic) {
                continue;
            }
        ?>
        <li class="list-comment-item">
            <a class="avatar" href="{{ schema_url('users', ['id' => $reply->topic->user->id]) }}">
                <img class="avatar" src="{{  $reply->user->present()->gravatar() }}">
            </a>
            <div class="info">
                <div class="meta">
                    <a class="topic-title" href="{{ schema_url('topics', ['id' => $reply->topic->id]) }}">{{ $reply->topic->title }}</a>
                    <span>•</span>
                    <abbr>{{ $reply->created_at }}</abbr>
                </div>
                <div class="markdown-content">
                    {!! $reply->body !!}
                </div>
            </div>
        </li>
    @endforeach
</ul>

@stop

