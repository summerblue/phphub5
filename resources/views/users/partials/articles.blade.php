<ul class="list-group">

@if (count($articles) && count($blog))
      @foreach ($articles as $index => $article)
       <li class="list-group-item" >

          <a href="{{ route('articles.show', [$article->id]) }}" title="{{{ $article->title }}}">
            {{{ str_limit($article->title, '100') }}}
          </a>

          <span class="meta">

            {{-- <a href="{{ route('categories.show', [$blog->id]) }}" title="{{{ $blog->name }}}"> --}}
              {{{ $blog->name }}}
            {{-- </a> --}}
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
