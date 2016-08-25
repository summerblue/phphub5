<div class="panel-footer operate">

    <div class="pull-left hidden-xs">
        <div class="social-share-cs "></div>
    </div>

  <div class="pull-right actions">

    @if ($currentUser && $manage_topics )
        <a data-ajax="post" id="topic-recomend-button" href="javascript:void(0);" data-url="{{ route('topics.recommend', [$topic->id]) }}" class="admin popover-with-html {{ $topic->is_excellent == 'yes' ? 'active' : ''}}" data-content="推荐主题，加精的帖子会出现在首页">
        <i class="fa fa-trophy"></i>
        </a>

        @if ($topic->order >= 0)
          <a data-ajax="post" id="topic-pin-button" href="javascript:void(0);" data-url="{{ route('topics.pin', [$topic->id]) }}" class="admin popover-with-html {{ $topic->order > 0 ? 'active' : '' }}" data-content="帖子置顶，会在列表页置顶">
            <i class="fa fa-thumb-tack"></i>
          </a>
        @endif

        @if ($topic->order <= 0)
            <a data-ajax="post" id="topic-sink-button" href="javascript:void(0);" data-url="{{ route('topics.sink', [$topic->id]) }}" class="admin popover-with-html {{ $topic->order < 0 ? 'active' : '' }}" data-content="沉贴，帖子将会被降低排序优先级">
                <i class="fa fa-anchor"></i>
            </a>
        @endif

        <a data-method="delete" id="topic-delete-button" href="javascript:void(0);" data-url="{{ route('topics.destroy', [$topic->id]) }}" data-content="{{ lang('Delete') }}" class="admin  popover-with-html">
            <i class="fa fa-trash-o"></i>
        </a>
    @endif

    @if ( $currentUser && ($manage_topics || $currentUser->id == $topic->user_id) )
      <a id="topic-append-button" href="javascript:void(0);" class="admin  popover-with-html" data-toggle="modal" data-target="#exampleModal" data-content="帖子附言，添加附言后所有参与讨论的用户都能收到消息提醒，包括点赞和评论的用户">
        <i class="fa fa-plus"></i>
      </a>

      <a id="topic-edit-button" href="{{ route('topics.edit', [$topic->id]) }}" data-content="{{ lang('Edit') }}" class="admin  popover-with-html">
        <i class="fa fa-pencil-square-o"></i>
      </a>
    @endif

  </div>
  <div class="clearfix"></div>
</div>


<div class="modal fade" id="exampleModal" tabindex="-1" role="" aria-labelledby="exampleModalLabel" >
  <div class="modal-dialog">
    <div class="modal-content">

      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="exampleModalLabel">{{ lang('Append Content') }}</h4>
      </div>

     <form method="POST" action="{{route('topics.append', $topic->id)}}" accept-charset="UTF-8">
         {!! csrf_field() !!}
        <div class="modal-body">

          <div class="alert alert-warning">
              {{ lang('append_notice') }}
          </div>

          <div class="form-group">
              <textarea class="form-control" style="min-height:20px" placeholder="{{ lang('Please using markdown.') }}" name="content" cols="50" rows="10"></textarea>
          </div>

          </div>

          <div class="modal-footer">
            <button type="button" class="btn btn-default" data-dismiss="modal">{{ lang('Close') }}</button>
            <button type="submit" class="btn btn-primary">{{ lang('Submit') }}</button>
          </div>

      </form>

    </div>
  </div>
</div>
