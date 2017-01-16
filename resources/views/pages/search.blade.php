@extends('layouts.default')

@section('title')
{{ $query }} · 搜索结果 | @parent
@stop

@section('content')

<div class="panel panel-default list-panel search-results">
  <div class="panel-heading">
    <h3 class="panel-title ">
     <i class="fa fa-search"></i> 关于 “{{ $query }}” 的搜索结果, 共 {{ count($users) + $topics->total() }} 条
    </h3>

  </div>

  <div class="panel-body ">

        @if (count($users))
            @foreach ($users as $user)
                @include('pages.partials.users_result')
            @endforeach
        @endif

        @if (count($topics))
            @foreach ($topics as $topic)
                @include('pages.partials.topics_result')
            @endforeach
        @endif

        @if ((count($topics)+count($users)) <= 0)
            <div class="empty-block">{{ lang('Dont have any data Yet') }}~~</div>
        @endif

  </div>

  <div class="panel-footer">
      {!! $topics->appends(Request::except('page', '_pjax'))->render() !!}
  </div>
</div>

@stop


@section('scripts')

<script type="text/javascript">

    $(document).ready(function()
    {
        var query = '{{ $query }}';
        var results = query.match(/("[^"]+"|[^"\s]+)/g);
        results.forEach(function(entry) {
            $('.search-results').highlight(entry);
        });
    });

</script>
@stop
