
  <div class="votes-container panel panel-default padding-md">

    <div class="panel-body vote-box text-center">

        <div class="btn-group">

            <a data-ajax="post" href="javascript:void(0);" data-url="{{ route('topics.upvote', $topic->id) }}" title="{{ lang('Up Vote') }}"
                data-content="点赞相当于收藏，可以在个人页面的「赞过的话题」导航里查看"
                id="up-vote"
                <?php
                    $is_voted = $currentUser && $topic->votes()->ByWhom(Auth::id())->WithType('upvote')->exists();
                ?>
                class="vote btn btn-primary {{ $topic->user->payment_qrcode ?: 'btn-inverted' }} popover-with-html {{  $is_voted ? 'active' :'' }}" >
                @if ($is_voted)
                已赞过
                @else
                <i class="fa fa-thumbs-up" aria-hidden="true"></i>
                点赞
                @endif

            </a>

            @if( $topic->user->payment_qrcode )
                <div class="or"></div>
                <button class="btn btn-warning popover-with-html"  data-toggle="modal" data-target="#payment-qrcode-modal" data-content="如果觉得我的文章对您有用，请随意打赏。你的支持将鼓励我继续创作！<br>可以修改个人资料「支付二维码」开启打赏功能。">
                    <i class="fa fa-heart" aria-hidden="true"></i>
                    打赏
                </button>
            @endif
        </div>

        <div class="voted-users">

            @if(count($votedUsers))
                <div class="user-lists">
                    @foreach($votedUsers as $votedUser)
                        <a href="{{ route('users.show', $votedUser->id) }}" data-userId="{{ $votedUser->id }}">
                            <img class="img-thumbnail avatar avatar-middle" src="{{ $votedUser->present()->gravatar() }}" style="width:48px;height:48px;">
                        </a>
                    @endforeach
                </div>
            @else
                <div class="user-lists">

                </div>
                <div class="vote-hint">
                    成为第一个点赞的人吧 <img title=":bowtie:" alt=":bowtie:" class="emoji" src="https://dn-phphub.qbox.me/assets/images/emoji/bowtie.png" align="absmiddle"></img>
                </div>
            @endif

            <a class="voted-template" href="" data-userId="" style="display:none">
                <img class="img-thumbnail avatar avatar-middle" src="" style="width:48px;height:48px;">
            </a>
        </div>

    </div>
  </div>

  <!-- Reply List -->
  <div class="replies panel panel-default list-panel replies-index" id="replies">

    <div class="panel-heading">
        <div class="total">{{ lang('Total Reply Count') }}: <b>{{ $replies->total() }}</b> </div>

        <div class="order-links">
            <a class="btn btn-default btn-sm {{ active_class( ! if_query('order_by', 'vote_count')) }} popover-with-html" data-content="按照时间排序" href="{{ $topic->link(['order_by' => 'created_at', '#replies']) }}" role="button">时间</a>
            <a class="btn btn-default btn-sm {{ active_class(if_query('order_by', 'vote_count')) }} popover-with-html" data-content="按照投票排序" href="{{ $topic->link(['order_by' => 'vote_count', '#replies'])  }}" role="button">投票</a>
        </div>

    </div>

    <div class="panel-body">

      @if (count($replies))
        @include('topics.partials.replies', ['manage_topics' => $currentUser ? $currentUser->can("manage_topics") : false])
        <div id="replies-empty-block" class="empty-block hide">{{ lang('No comments') }}~~</div>
      @else
        <ul class="list-group row"></ul>
        <div id="replies-empty-block" class="empty-block">{{ lang('No comments') }}~~</div>
      @endif

      <!-- Pager -->
      <div class="pull-right" style="padding-right:20px">
        {!! $replies->appends(Request::except('page'))->render() !!}
      </div>
    </div>
  </div>

  <!-- Reply Box -->
  <div class="reply-box form box-block">

    @include('layouts.partials.errors')

    <form method="POST" action="{{ route('replies.store') }}" accept-charset="UTF-8" id="reply-form">
      {!! csrf_field() !!}
      <input type="hidden" name="topic_id" value="{{ $topic->id }}" />

        @include('topics.partials.composing_help_block')

        <div class="alert alert-dismissable alert-info">
            <i class="fa fa-info" aria-hidden="true"></i> &nbsp;&nbsp;请勿发布不友善或者负能量的内容。与人为善，比聪明更重要！
        </div>

        <div class="form-group">
            @if ($currentUser)
                @if ($currentUser->verified)
                <textarea class="form-control" rows="5" placeholder="{{ lang('Please using markdown.') }}" style="overflow:hidden" id="reply_content" name="body" cols="50"></textarea>
                @else
                <textarea class="form-control" disabled="disabled" rows="5" placeholder="{{ lang('You need to verify the email for commenting.') }}" name="body" cols="50"></textarea>
                @endif
            @else
                <textarea class="form-control" disabled="disabled" rows="5" placeholder="{{ lang('User Login Required for commenting.') }}" name="body" cols="50"></textarea>
            @endif
        </div>

        <div class="form-group reply-post-submit">
            <input class="btn btn-primary {{ $currentUser ? '' :'disabled'}}" id="reply-create-submit" type="submit" value="{{ lang('Reply') }}">
            <span class="help-inline" title="Or Command + Enter">Ctrl+Enter</span>
        </div>

        <div class="box preview markdown-reply" id="preview-box" style="display:none;"></div>

    </form>
  </div>
