@extends('layouts.default')

@section('title')
最新动态 | @parent
@stop

@section('content')

<div class="col-md-9 topics-index main-col">

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

        @if ( ! $activities->isEmpty())

            <div class="jscrolxxl">
                <div class="panel-body remove-padding-horizontal">
                    <ul class="list-group row">
                        <?php
                             $indentifiers = [];
                        ?>
                        @foreach ($activities as $activity)
                            @unless($activity->type == 'UserPublishedNewTopic' && in_array($activity->indentifier, $indentifiers))
                                @include('activities.types._' . snake_case(class_basename($activity->type)))
                            @endunless
                            <?php
                                if ($activity->type == 'BlogHasNewArticle') {
                                    $indentifiers[] = $activity->indentifier;
                                }
                            ?>
                        @endforeach
                    </ul>

                </div>

                <div class="panel-footer text-right remove-padding-horizontal pager-footer">
                    <!-- Pager -->
                    {!! $activities->appends(Request::except('page', '_pjax'))->render() !!}
                </div>
            </div>

        @else
            <div class="panel-body">
                <div class="empty-block">{{ lang('Dont have any data Yet') }}~~</div>
            </div>
        @endif

    </div>

</div>

@include('layouts.partials.sidebar')

@stop
