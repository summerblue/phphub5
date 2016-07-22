@extends('layouts.default')

@section('title')
{{ lang('Topic List') }} @parent
@stop

@section('content')

<div class="col-md-9 topics-index main-col">
    <div class="panel panel-default">

        <div class="panel-heading">
          @if (isset($category))
            <div class="pull-left panel-title">{{ lang('Current Category') }}: {{{ $category->name }}}</div>
          @endif

          @include('topics.partials.filter')

          <div class="clearfix"></div>
        </div>

        @if ( ! $topics->isEmpty())

            <div class="panel-body remove-padding-horizontal">
                @include('topics.partials.topics', ['column' => false])
            </div>

            <div class="panel-footer text-right remove-padding-horizontal pager-footer">
                <!-- Pager -->
                {!! $topics->appends(Request::except('page', '_pjax'))->render() !!}
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

@if (!Input::get('filter') && !isset($category))
@include('layouts.partials.topbanner')
@endif
@include('layouts.partials.bottombanner')
@stop
