
<ul class="list">
    @foreach ($sidebarTopics as $sidebarTopic)
        <li>
            <a href="{{ route('topics.show', $sidebarTopic->id) }}" class="popover-with-html" data-content="{{{ $sidebarTopic->title }}}">
                {{{ $sidebarTopic->title }}}
            </a>
        </li>
    @endforeach
</ul>