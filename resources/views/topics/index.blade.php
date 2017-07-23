@extends('layouts.default')

@section('title')
{{ isset($category) ? $category->name : '话题列表'  }} @parent
@stop

@section('content')

<div class="col-md-9 topics-index main-col">

    @if (isset($category) && $category->id == config('phphub.life_category_id'))
        <div class="alert alert-info">
            『生活能为工作带来灵感，工作是为了更好的生活。』话题如旅行、移民、宠物等。发帖请遵守 <a style="text-decoration: underline;" href="https://laravel-china.org/topics/3022/community-posting-and-management">社区发帖和管理规范</a>。
        </div>
    @endif
    @if (isset($category) && $category->id == config('phphub.qa_category_id'))
        <div class="alert alert-info">
            在 LC，我们不提倡 <a href="{{ route('topics.show', 535) }}" style="text-decoration: underline;">新手提问</a> ，如果你编程遇到难题，请先 <a href="{{ route('topics.show', 3656) }}" style="text-decoration: underline;">搜索</a> 再 <a href="{{ route('topics.create', ['category_id' => config('phphub.qa_category_id')]) }}" class="btn btn-warning">提问</a>
        </div>
    @endif
    @if (isset($category) && $category->id === 1)
        <div class="alert alert-info">
            发布招聘贴前请必须仔细阅读 <a href="https://laravel-china.org/topics/817/laravel-china-recruitment-post-specification" style="text-decoration: underline;">Laravel China 招聘贴发布规范</a>，不按规范发帖会被管理员 <a href="https://laravel-china.org/topics/2802/description-of-shen" style="text-decoration: underline;">永久下沉</a>。<a href="{{ route('topics.create', ['category_id' => 1]) }}" class="btn btn-warning">发布招聘</a>
        </div>
    @endif
    <div class="panel panel-default">

        <div class="panel-heading">

          <ul class="list-inline topic-filter">
                <li class="popover-with-html" data-content="最后回复排序"><a {!! app(App\Models\Topic::class)->present()->topicFilter('default') !!}>活跃</a></li>
                <li class="popover-with-html" data-content="只看加精的话题"><a {!! app(App\Models\Topic::class)->present()->topicFilter('excellent') !!}>{{ lang('Excellent') }}</a></li>
                <li class="popover-with-html" data-content="点赞数排序"><a {!! app(App\Models\Topic::class)->present()->topicFilter('vote') !!}>{{ lang('Vote') }}</a></li>
                <li class="popover-with-html" data-content="发布时间排序"><a {!! app(App\Models\Topic::class)->present()->topicFilter('recent') !!}>{{ lang('Recent') }}</a></li>
                <li class="popover-with-html" data-content="无人问津的话题"><a {!! app(App\Models\Topic::class)->present()->topicFilter('noreply') !!}>{{ lang('Noreply') }}</a></li>
            </ul>

          <div class="clearfix"></div>
        </div>

        @if ( ! $topics->isEmpty())

            <div class="jscroll">
                <div class="panel-body remove-padding-horizontal">
                    @include('topics.partials.topics')
                </div>

                <div class="panel-footer text-right remove-padding-horizontal pager-footer">
                    <!-- Pager -->
                    {!! $topics->appends(Request::except('page', '_pjax'))->render() !!}
                </div>
            </div>

        @else
            <div class="panel-body">
                <div class="empty-block">{{ lang('Dont have any data Yet') }}~~</div>
            </div>
        @endif

    </div>

    <!-- Nodes Listing -->
    @include('nodes.partials.list')

</div>

@include('layouts.partials.sidebar')

@stop
