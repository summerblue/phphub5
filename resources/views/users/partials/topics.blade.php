<ul class="list-group">

  @foreach ($topics as $index => $topic)
   <li class="list-group-item" >

        @if (isset($is_blog))
            <a href="{{ route('users.show', [$topic->user_id]) }}" title="{{{ $topic->user->name }}}" class="avatar-wrap">
                <img class="avatar avatar-small" alt="{{{ $topic->user->name }}}" src="{{ $topic->user->present()->gravatar }}"/>
            </a>
        @endif

      <a href="{{ $topic->link() }}" title="{{{ $topic->title }}}" class="title">
        {{{ str_limit($topic->title, '100') }}}
      </a>

      <span class="meta">
          @if ($topic->isArticle())

              <a href="{{ $topic->blogs->first()->link() }}" title="{{{ $topic->blogs->first()->name }}}">
                {{{ $topic->blogs->first()->name }}}
              </a>

          @else
              <a href="{{ route('categories.show', [$topic->category->id]) }}" title="{{{ $topic->category->name }}}">
                {{{ $topic->category->name }}}
              </a>
          @endif

        <span> ⋅ </span>
        {{ $topic->vote_count }} {{ lang('Up Votes') }}
        <span> ⋅ </span>
        {{ $topic->reply_count }} {{ lang('Replies') }}
        <span> ⋅ </span>
        <span class="timeago">{{ $topic->created_at }}</span>

      </span>

  </li>
  @endforeach

</ul>


@if (if_route('users.show') && $user->topic_count > count($topics))
    <div class="panel-footer" style="margin-top: 10px">
        <a href="{{ route('users.topics', $user->id) }}" class="btn btn-default btn-sm">
            所有话题
        </a>
    </div>
@endif
