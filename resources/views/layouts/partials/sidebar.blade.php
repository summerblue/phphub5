<div class="col-md-3 side-bar">

    @if (isset($topic))
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
  @endif


  @if (isset($userTopics) && count($userTopics))
  <div class="panel panel-default corner-radius">
    <div class="panel-heading text-center">
      <h3 class="panel-title">{{ $topic->user->name }} 的其他话题</h3>
    </div>
    <div class="panel-body">
      @include('layouts.partials.sidebar_topics', ['sidebarTopics' => $userTopics])
    </div>
  </div>
  @endif


  @if (isset($categoryTopics) && count($categoryTopics))
  <div class="panel panel-default corner-radius">
    <div class="panel-heading text-center">
      <h3 class="panel-title">{{ lang('Same Category Topics') }}</h3>
    </div>
    <div class="panel-body">
      @include('layouts.partials.sidebar_topics', ['sidebarTopics' => $categoryTopics])
    </div>
  </div>
  @endif

@if (Route::currentRouteName() == 'topics.index')

  <div class="panel panel-default corner-radius">
    <div class="panel-body text-center">
      <div class="btn-group">
        <a href="{{ URL::route('topics.create') }}" class="btn btn-primary btn-lg btn-inverted">
          <i class="fa fa-paint-brush" aria-hidden="true"></i> {{ lang('New Topic') }}
        </a>
      </div>
    </div>
  </div>
@endif

<div class="panel panel-default corner-radius" style="
    text-align: center;
    background-color: transparent;
    border: none;
">
<a href="https://laravel-china.org/topics/3383" rel="nofollow" title="" style="">
      <img src="https://dn-phphub.qbox.me/uploads/images/201612/09/1/qASrRyKNj0.jpg" style="width: 100%;border-radius: 0px;box-shadow: none;border: 1px solid #ffafaf;"></a>
</div>

<div class="panel panel-default corner-radius" style="
    text-align: center;
    background-color: transparent;
    border: none;
">
<a href="https://laravel-china.org/topics/3213" rel="nofollow" title="" style="">
      <img src="https://dn-phphub.qbox.me/uploads/images/201612/09/1/06fkozmGAH.jpg" style="width: 100%;border-radius: 0px;box-shadow: none;border: 1px solid #e2e1e1;"></a>
</div>



@if(isset($banners['sidebar-resources']))
<div class="panel panel-default corner-radius sidebar-resources">
  <div class="panel-heading text-center">
    <h3 class="panel-title">推荐资源</h3>
  </div>
  <div class="panel-body">
    <ul class="list list-group ">
        @foreach($banners['sidebar-resources'] as $banner)
            <li class="list-group-item ">
                <a href="{{ $banner->link }}" class="popover-with-html no-pjax" data-content="{{{ $banner->title }}}">
                    <img class="media-object inline-block " src="{{ $banner->image_url }}">
                    {{{ $banner->title }}}
                </a>
            </li>
        @endforeach
    </ul>
  </div>
</div>
@endif

@if (Route::currentRouteName() == 'topics.index')
    <div class="panel panel-default corner-radius panel-active-users">
      <div class="panel-heading text-center">
        <h3 class="panel-title">{{ lang('Active Users') }}（<a href="{{ route('hall_of_fames') }}"><i class="fa fa-star" aria-hidden="true"></i> {{ lang('Hall of Fame') }}</a>）</h3>
      </div>
      <div class="panel-body">
        @include('topics.partials.active_users')
      </div>
    </div>

<div class="panel panel-default corner-radius panel-hot-topics">
  <div class="panel-heading text-center">
    <h3 class="panel-title">{{ lang('Hot Topics') }}</h3>
  </div>
  <div class="panel-body">
    @include('layouts.partials.sidebar_topics', ['sidebarTopics' => $hot_topics])
  </div>
</div>

@endif


  <div class="panel panel-default corner-radius">
    <div class="panel-body text-center sidebar-sponsor-box">
        @if(isset($banners['sidebar-sponsor']))
            @foreach($banners['sidebar-sponsor'] as $banner)
                <a class="sidebar-sponsor-link" href="{{ $banner->link }}" target="_blank">
                    <img src="{{ $banner->image_url }}" class="popover-with-html" data-content="{{ $banner->title }}" width="100%">
                </a>
            @endforeach
        @endif
  </div>
  </div>

  @if (isset($links) && count($links))
    <div class="panel panel-default corner-radius">
      <div class="panel-heading text-center">
        <h3 class="panel-title">{{ lang('Links') }}</h3>
      </div>
      <div class="panel-body text-center" style="padding-top: 5px;">
        @foreach ($links as $link)
            <a href="{{ $link->link }}" target="_blank" rel="nofollow" title="{{ $link->title }}" style="padding: 3px;">
                <img src="{{ $link->cover }}" style="width:150px; margin: 3px 0;">
            </a>
        @endforeach
      </div>
    </div>
  @endif

@if (isset($randomExcellentTopics) && count($randomExcellentTopics))

<div class="panel panel-default corner-radius panel-hot-topics">
  <div class="panel-heading text-center">
    <h3 class="panel-title">{{ lang('Recommend Topics') }}</h3>
  </div>
  <div class="panel-body">
    @include('layouts.partials.sidebar_topics', ['sidebarTopics' => $randomExcellentTopics])
  </div>
</div>

@endif

@if (Route::currentRouteName() == 'topics.index')

<div class="panel panel-default corner-radius">
  <div class="panel-heading text-center">
    <h3 class="panel-title">{{ lang('App Download') }}</h3>
  </div>
  <div class="panel-body text-center" style="padding: 7px;">
    <a href="https://laravel-china.org/topics/1531" target="_blank" rel="nofollow" title="">
      <img src="https://dn-phphub.qbox.me/uploads/images/201512/08/1/cziZFHqkm8.png" style="width:240px;">
    </a>
  </div>
</div>

@endif


<div class="box text-center">
  <p style="margin-bottom: 10px;margin-top: 10px;">订阅 Laravel 资讯</p>
  <img class="image-border popover-with-html" data-content="扫码，或者搜索微信订阅号：「Laravel资讯」"
    src="https://dn-phphub.qbox.me/uploads/images/201612/15/1/MGig6IACCQ.png" style="width:80%">
  <br/><br/>
</div>


<div class="panel panel-default corner-radius" style="color:#a5a5a5">
  <div class="panel-body text-center">
      <a href="http://estgroupe.com/" style="color:#a5a5a5">
          <img src="https://dn-phphub.qbox.me/uploads/images/201612/12/1/iq7WQc2iuW.png" style="width: 20px;margin-right: 4px;margin-top: -4px;">
          <span style="margin-top: 7px;display: inline-block;">
              优帆远扬 - 创造不息，交付不止
          </span>
      </a>
  </div>

</div>

</div>
<div class="clearfix"></div>
