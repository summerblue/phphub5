
<ul class="list">
    @foreach ($sidebarTopics as $sidebarTopic)
        <li>
            <a href="{{ $sidebarTopic->link() }}" title="{{{ $sidebarTopic->title }}}">
                {{{ $sidebarTopic->title }}}
            </a>
        </li>
    @endforeach
</ul>
