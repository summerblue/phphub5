@if (isset($nodes) && count($nodes))
<div class="panel panel-default node-panel">
  <div class="panel-heading">
    <h3 class="panel-title text-center">{{ lang('All Nodes') }}</h3>
  </div>

  <div class="panel-body remove-padding-vertically remove-padding-bottom">
  <dl class="dl-horizontal">
      @foreach ($nodes['top'] as $index => $top_node)
        <dt>{{{ $top_node->name }}}</dt>
      <dd>

        <ul class="list-inline">
          @foreach ($nodes['second'][$top_node->id] as $snode)
              <li><a href="{{ route('nodes.show', [$snode->id]) }}">{{{ $snode->name }}}</a></li>
          @endforeach
        </ul>

      </dd>

        @if (count($nodes['top']) != $index +1 )
          <div class="divider"></div>
        @endif

      @endforeach
  </dl>
  </div>
</div>

@endif
