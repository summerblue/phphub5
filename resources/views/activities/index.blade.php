@extends('layouts.default')

@section('title')
最新动态 | @parent
@stop

@section('content')

<div class="col-md-9 topics-index feed-list main-col">

    <div class="panel panel-default">

        <div class="panel-heading">
            <ul class="nav nav-tabs">
                <li role="presentation" class="active"><a href="#">关注动态</a></li>
                <li role="presentation"><a href="#">所有动态</a></li>
                <li role="presentation"><a href="#">我的动态</a></li>
            </ul>
        </div>

        @if ( ! $activities->isEmpty())

            <div class="jscrolxxl">
                <div class="panel-body remove-padding-horizontal">
                    <ul class="list-group row feed-list">
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
