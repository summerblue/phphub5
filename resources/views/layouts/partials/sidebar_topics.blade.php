
<ul class="list">
    @foreach ($sidebarTopics as $sidebarTopic)
        <li>
            <a href="{{ $sidebarTopic->link() }}" class="popover-with-html" data-content="{{{ $sidebarTopic->title }}}">
                {{{ $sidebarTopic->title }}}
            </a>
        </li>
    @endforeach
</ul>
