<ul class="list-group">

@if (count($articles) && count($blog))
      @foreach ($articles as $index => $article)
       <li class="list-group-item" >

          <a href="{{ $article->link() }}" title="{{{ $article->title }}}">
            {{{ str_limit($article->title, '100') }}}
          </a>

          <span class="meta">

            <a href="{{ $article->blogs->first()->link() }}" title="{{{ $article->blogs->first()->name }}}">
              {{{ $article->blogs->first()->name }}}
            </a>
            <span> ⋅ </span>
            {{ $article->vote_count }} {{ lang('Up Votes') }}
            <span> ⋅ </span>
            {{ $article->reply_count }} {{ lang('Replies') }}
            <span> ⋅ </span>
            <span class="timeago">{{ $article->created_at }}</span>

          </span>

      </li>
      @endforeach
@endif
</ul>
