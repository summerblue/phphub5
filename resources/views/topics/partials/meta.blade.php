<div class="meta inline-block" >

  <a href="{{ route('categories.show', $topic->category->id) }}" class="remove-padding-left">
    <i class="fa fa-folder text-md" aria-hidden="true"></i> {{{ $topic->category->name }}}
  </a>
  ⋅
  <a href="{{ route('users.show', $topic->user->id) }}">
    {{{ $topic->user->name }}}
  </a>

  ⋅
  {{ lang('at') }} <abbr title="{{ $topic->created_at }}" class="timeago">{{ $topic->created_at }}</abbr>
  ⋅

  @if (count($topic->lastReplyUser))
    {{ lang('Last Reply by') }}
      <a href="{{{ URL::route('users.show', [$topic->lastReplyUser->id]) }}}">
        {{{ $topic->lastReplyUser->name }}}
      </a>
     {{ lang('at') }} <abbr title="{{ $topic->updated_at }}" class="timeago">{{ $topic->updated_at }}</abbr>
    ⋅
  @endif

  {{ $topic->view_count }} {{ lang('Reads') }}
</div>
<div class="clearfix"></div>
