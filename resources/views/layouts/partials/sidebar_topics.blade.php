
<ul class="list">
  @foreach ($sidebarTopics as $sidebarTopic)
      @if($sidebarTopic->user->is_banned !== 'yes')
        <li>
            <a href="{{ route('topics.show', $sidebarTopic->id) }}" class="popover-with-html" data-content="{{{ $sidebarTopic->title }}}">
                {{{ $sidebarTopic->title }}}
            </a>
        </li>
      @endif
  @endforeach
</ul>