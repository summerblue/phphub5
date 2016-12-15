
<div class="markdown-body" id="emojify">
{!! $body !!}

@if ($topic->user->signature)
    <a class="popover-with-html" data-content="作者署名" target="_blank" style="display: block;width: 30px;color: #ccc;margin: 22px 0 8px;" href="https://laravel-china.org/topics/3422"><i class="fa fa-paw" aria-hidden="true"></i></a>
    {!! $topic->user->present()->formattedSignature() !!}
@endif

</div>
