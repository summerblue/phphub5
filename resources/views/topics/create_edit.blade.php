@extends('layouts.default')

@section('title')
{{ isset($topic) ? '编辑话题' : lang('Create New Topic') }}_@parent
@stop

@section('content')

<div class="topic_create">

  <div class="col-md-8 main-col">

    <div class="reply-box form box-block">

      <div class="alert alert-warning">
          {!! lang('be_nice') !!}
      </div>

      @include('layouts.partials.errors')

      @if (isset($topic))
        <form method="POST" action="{{ route('topics.update', $topic->id) }}" accept-charset="UTF-8" id="topic-create-form">
        <input name="_method" type="hidden" value="PATCH">
      @else
      <form method="POST" action="{{ route('topics.store') }}" accept-charset="UTF-8" id="topic-create-form">
      @endif
        {!! csrf_field() !!}
        <div class="form-group">
            <select class="selectpicker form-control" name="category_id" id="category-select">

              <option value="" disabled {{ count($category) != 0 ?: 'selected' }}>{{ lang('Pick a category') }}</option>

              @foreach ($categories as $value)
                  {{-- 如果用户可以发布公告，并且是 id == 3 的话 --}}
                  @if($value->id != 3 || Auth::user()->can('compose_announcement'))
                      @if($value->id != config('app.admin_board_cid') || Auth::user()->can('access_board'))
                          <option value="{{ $value->id }}" {{ (count($category) != 0 && $value->id == $category->id) ? 'selected' : '' }} >{{ $value->name }}</option>
                      @endif
                  @endif
              @endforeach
            </select>
        </div>


        @foreach ($categories as $cat)
            <div class="category-hint alert alert-warning category-{{ $cat->id }} {{ count($category) && $cat->id == $category->id ? 'animated rubberBand ' : ''}}" style="{{ (count($category) && $cat->id == $category->id) ? '' : 'display:none' }}">
                {!! $cat->description !!}
            </div>
        @endforeach

        <div class="form-group">
            <input class="form-control" id="topic-title" placeholder="{{ lang('Please write down a topic') }}" name="title" type="text" value="{{ !isset($topic) ? '' : $topic->title }}">
        </div>

        @include('topics.partials.composing_help_block')

        <div class="form-group">
            <textarea class="form-control" rows="20" style="overflow:hidden" id="reply_content" placeholder="{{ lang('Please using markdown.') }}" name="body" cols="50">{{ !isset($topic) ? '' : $topic->body_original }}</textarea>
        </div>

        <div class="form-group status-post-submit">
            <input class="btn btn-primary" id="topic-create-submit" type="submit" value="{{ lang('Publish') }}">
        </div>

        <div class="box preview markdown-body" id="preview-box" style="display:none;"></div>

    </form>

    </div>
  </div>

  <div class="col-md-4 side-bar">

    <div class="panel panel-default corner-radius help-box">
      <div class="panel-heading text-center">
        <h3 class="panel-title">{{ lang('This kind of topic is not allowed.') }}</h3>
      </div>
      <div class="panel-body">
        <ul class="list">
          <li>请传播美好的事物，这里拒绝低俗、诋毁、谩骂等相关信息</li>
          <li>请尽量分享技术相关的话题，谢绝发布社会, 政治等相关新闻</li>
          <li>这里绝对不讨论任何有关盗版软件、音乐、电影如何获得的问题</li>
      </div>
    </div>

    <div class="panel panel-default corner-radius help-box">
      <div class="panel-heading text-center">
        <h3 class="panel-title">{{ lang('We can benefit from it.') }}</h3>
      </div>
      <div class="panel-body">
        <ul class="list">
          <li>分享生活见闻, 分享知识</li>
          <li>接触新技术, 讨论技术解决方案</li>
          <li>为自己的创业项目找合伙人, 遇见志同道合的人</li>
          <li>自发线下聚会, 加强社交</li>
          <li>发现更好工作机会</li>
          <li>甚至是开始另一个神奇的开源项目</li>
        </ul>
      </div>
    </div>

  </div>
</div>

@stop



@section('scripts')
<script type="text/javascript">

    $(document).ready(function()
    {
        $('#category-select').on('change', function() {
            var current_cid = $(this).val();
            $('.category-hint').hide();
            $('.category-'+current_cid).fadeIn();
        });
    });

</script>
@stop
