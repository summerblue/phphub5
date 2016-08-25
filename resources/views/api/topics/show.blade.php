@extends('api.layouts.default')

@section('title')
$topic->title
@stop

@section('content')

    <div class="header-title">
        <h3>{{ $topic->title }}</h3>

        <p>{{ $topic->created_at }} ⋅ {{ $topic->view_count }} 阅读</p>
    </div>

    <div class="markdown-content topic-body">
        {!! $topic->body !!}
    </div>

@stop