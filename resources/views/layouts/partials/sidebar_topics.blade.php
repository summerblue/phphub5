
<ul class="list">
    @foreach ($sidebarTopics as $index => $sidebarTopic)
        <li>
            <a href="{{ $sidebarTopic->link() }}" title="{{{ $sidebarTopic->title }}}">

                @if (isset($numbered))
                    @if ($index === 0)
                        🏆
                    @else
                        {{ $index+1 }}.
                    @endif
                @endif

                 {{{ $sidebarTopic->title }}}
            </a>
        </li>
    @endforeach
</ul>
