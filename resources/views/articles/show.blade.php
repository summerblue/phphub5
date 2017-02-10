@extends('layouts.default')

@section('title')
{{ $topic->title }} | @parent
@stop

@section('content')

<div class="blog-pages">

          <div class="col-md-9 left-col pull-right">

              <div class="panel article-body">

                  <div class="panel-body">

                        <h1 class="text-center">
                            {{ $topic->title }}
                        </h1>

                        @if ($topic->is_draft == 'yes')
                            <div class="text-center alert alert-warning">
                                当前状态为 <i class="fa fa-file-text-o"></i> 草稿，仅作者可见，请前往 <a href="{{ route('articles.edit', $topic->id) }}" class="no-pjax">编辑发布</a>
                            </div>
                        @endif

                        <div class="article-meta text-center">
                            <i class="fa fa-clock-o"></i> <abbr title="{{ $topic->created_at }}" class="timeago">{{ $topic->created_at }}</abbr>
                            ⋅
                            <i class="fa fa-eye"></i> {{ $topic->view_count }}
                            ⋅
                            <i class="fa fa-thumbs-o-up"></i> {{ $topic->vote_count }}
                            ⋅
                            <i class="fa fa-comments-o"></i> {{ $topic->reply_count }}

                        </div>

                        @include('topics.partials.body', array('body' => $topic->body))

                        <div class="post-info-panel">
                            <p class="info">
                                <label class="info-title">版权声明：</label><i class="fa fa-fw fa-creative-commons"></i>自由转载-非商用-非衍生-保持署名（<a href="https://creativecommons.org/licenses/by-nc-nd/3.0/deed.zh">创意共享3.0许可证</a>）
                            </p>
                        </div>
                        <br>
                        <br>
                        @include('topics.partials.topic_operate', ['is_article' => true, 'manage_topics' => $currentUser ? ($currentUser->can("manage_topics") && $currentUser->roles->count() <= 5) : false])
                  </div>

              </div>

              @include('topics.partials.show_segment')
        </div>


        @if( $topic->user->payment_qrcode )
            @include('topics.partials.payment_qrcode_modal')
        @endif


      <div class="col-md-3 main-col pull-left">
          <div class="panel panel-default corner-radius">

            <div class="panel-body text-center topic-author-box">

                <div class="image blog-cover">
                    <a href="{{ route('users.articles', $user->id) }}" >
                        <img class=" avatar-112 avatar img-thumbnail" src="{{ img_crop($blog->cover, 224, 224) }}">
                    </a>
                </div>
                <div class="blog-name">
                    <h4>专栏：{{ $blog->name }}</h4>
                </div>
                <div class="blog-description">
                    {{ $blog->description ?: $user->name . '的个人专栏' }}
                </div>
                <hr>

                @if ($currentUser && ($currentUser->id == $user->id || Entrust::can('manage_users')) )
                  <div class="follow-box">
                      <a class="btn btn-primary btn-block" href="{{ route('blogs.edit') }}">
                        <i class="fa fa-edit"></i> 编辑专栏
                      </a>
                  </div>
                @endif
                {{--
                <div class="follow-box">
                    <a class="btn btn-primary btn-block" href="">
                      <i class="fa fa-eye"></i> 关注专栏
                    </a>
                </div> --}}

            </div>

          </div>

          <div class="panel panel-default corner-radius">

              <div class="panel-heading text-center">
                <h3 class="panel-title">作者：{{ $topic->user->name }}</h3>
              </div>

            <div class="panel-body text-center topic-author-box">
                @include('topics.partials.topic_author_box')

                @if(Auth::check() && $currentUser->id != $topic->user->id)
                    <span class="text-white">
                        <?php $isFollowing = $currentUser && $currentUser->isFollowing($topic->user->id) ?>
                        <hr>
                        <a data-method="post" class="btn btn-{{ !$isFollowing ? 'warning' : 'default' }} btn-block" href="javascript:void(0);" data-url="{{ route('users.doFollow', $topic->user->id) }}" id="user-edit-button">
                           <i class="fa {{!$isFollowing ? 'fa-plus' : 'fa-minus'}}"></i> {{ !$isFollowing ? lang('Follow') : lang('Unfollow') }}
                        </a>
                    </span>
                @endif
            </div>

          </div>
      </div>

</div>

@stop
