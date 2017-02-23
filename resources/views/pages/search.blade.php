@extends('layouts.default')

@section('title')
{{ $query }} · 搜索结果 | @parent
@stop

@section('content')

<div class="panel panel-default list-panel search-results">
  <div class="panel-heading">

    @if ($filterd_noresult)
        <h3 class="panel-title ">
          <i class="fa fa-search"></i> 关键词 “{{ $query }}” 搜索范围：{{ $user->name }} <a class="popover-with-html" data-content="清除搜索范围" href="{{ route('search', ['q' => Input::get('q')]) }}"><i class="fa fa-times" aria-hidden="true"></i></a> 的结果为空。以下展示全局的搜索 {{ count($users) + $topics->total() }} 条：
        </h3>
    @else
        <h3 class="panel-title ">
         <i class="fa fa-search"></i> 关于 “{{ $query }}” 的搜索结果, 共 {{ count($users) + $topics->total() }} 条
          @if ($user->id > 0)
              。当前搜索范围：{{ $user->name }} <a class="popover-with-html" data-content="清除搜索范围" href="{{ route('search', ['q' => Input::get('q')]) }}"><i class="fa fa-times" aria-hidden="true"></i></a>
          @endif
        </h3>
    @endif

  </div>

  <div class="panel-body ">

        @if (count($users))
            @foreach ($users as $user_result)
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
