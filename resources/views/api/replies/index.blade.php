@extends('api.layouts.default')

@section('title')
评论列表
@stop

@section('content')

<ul class="list-comment">
    @if(!count($replies))
        <div class="content-blank">暂无内容</div>
    @endif

    @foreach($replies as $index => $reply)
    <li class="list-comment-item">
        <a class="avatar" href="{{ schema_url('users', ['id' => $reply->user->id]) }}">
            <img class="avatar" src="{{ $reply->user->present()->gravatar() }}">
        </a>
        <div class="info">
            <div class="meta">
                <a href="{{ schema_url('users', ['id' => $reply->user->id]) }}">{{ $reply->user->name }}</a>
                <span> ⋅ </span>
                <abbr>{{ $reply->created_at }}</abbr>
                <span> ⋅ </span>
                <a class="anchor" href="#{{ $index+1 }}">#{{ $index+1 }}</a>
            </div>
            <div class="markdown-content">
                {!! $reply->body !!}
            </div>
        </div>
    </li>
    @endforeach
</ul>


@stop
