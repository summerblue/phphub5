@extends('layouts.default')

@section('title')
{{ $blog->name }} | @parent
@stop

@section('content')

<div class="blog-pages">

          <div class="col-md-9 left-col pull-right">

              <div class="panel article-body article-index">

                  <div class="panel-body">

                    <h1 class="all-articles">
                        专栏文章

                        @can('create-article', $blog)
                            <a href="{{ route('articles.create', ['blog_id' => $blog->id]) }}" class="btn btn-primary pull-right no-pjax"> <i class="fa fa-paint-brush"></i> 创作文章</a>
                        @endcan
                    </h1>

                      @if (count($topics))
                        @include('users.partials.topics', ['is_article' => true, 'is_blog' => true])
                        <div class="pull-right add-padding-vertically">
                            {!! $topics->render() !!}
                        </div>
                      @else
                        <div class="empty-block">{{ lang('Dont have any data Yet') }}~~</div>
                      @endif

                  </div>

              </div>

        </div>


      <div class="col-md-3 main-col pull-left">
          @include('blogs._info')
          @include('blogs._authors')
      </div>

</div>

@stop
