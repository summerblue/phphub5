<div class="meta inline-block" >

  <a href="{{ route('categories.show', $topic->category->id) }}" class="remove-padding-left">
    <i class="fa fa-folder text-md" aria-hidden="true"></i> {{{ $topic->category->name }}}
  </a>
  ⋅
  <a class="author" href="{{ route('users.show', $topic->user->id) }}">
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

@if ($topic->source && in_array($topic->source, ['iOS', 'Android']))
⋅
via
    <a href="https://laravel-china.org/topics/1531" target="_blank" class="popover-with-html" data-content="来自手机客户端">
      <i class="text-md fa fa-{{ $topic->source == 'iOS' ? 'apple' : 'android' }}" aria-hidden="true"></i> {{ $topic->source == 'iOS' ? 'iOS 客户端' : '安卓客户端' }}
    </a>

@endif
</div>
<div class="clearfix"></div>
