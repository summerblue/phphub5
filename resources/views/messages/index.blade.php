@extends('layouts.default')

@section('title')
我的私信 | @parent
@stop

@section('content')

<div class="messages">

<div class="col-md-3 main-col">
    @include('notifications._nav')
</div>

<div class="col-md-9  left-col ">

    <div class="panel panel-default padding-sm">

        <div class="panel-heading">
            <h1>我的消息</h1>
        </div>

        @if($threads->count() > 0)

            <div class="panel-body remove-padding-horizontal notification-index">

                <ul class="list-group row">
                    @foreach($threads as $thread)
                    <?php $unread_messagesCount = $thread->userUnreadMessagesCount($currentUser->id) ?>

                     <li class="list-group-item media {{ $unread_messagesCount > 0 ? 'unread' : '' }}" style="margin-top: 0px;">
                        <?php
                            $participant = $thread->participant();
                        ?>
                        <div class="avatar pull-left">
                            <a href="{{ route('users.show', [$participant->id]) }}">
                                <img class="media-object img-thumbnail avatar" alt="{{ $participant->name }}" src="{{ $participant->present()->gravatar }}"  style="width:48px;height:48px;"/>
                            </a>
                        </div>

                        <div class="infos">

                          <div class="media-heading">

                            @if ($thread->latestMessage->user_id == $currentUser->id)
                                我发送给
                            @endif

                            <a href="{{ route('users.show', [$participant->id]) }}">
                                {{ $participant->name }}
                            </a>
                            <span class="meta">
                                 ⋅ {{ lang('at') }} ⋅ <span class="timeago">{{ $thread->latestMessage->created_at }}</span>
                            </span>：
                          </div>

                          <div class="media-body markdown-reply content-body">
                                {!! $thread->latestMessage->body  !!}
                          </div>

                            <div class="message-meta">
                                <p>

                                <a href="{{ route('messages.show', $thread->id) }}" class="normalize-link-color ">

                                    @if ($unread_messagesCount > 0)

                                    <span style="color:#ff7b00;">
                                         <i class="fa fa-commenting-o" aria-hidden="true"></i>
                                        {{ $unread_messagesCount }} 条未读消息
                                    </span>

                                    @else
                                        <i class="fa fa-commenting-o" aria-hidden="true"></i>
                                        查看对话
                                    @endif

                                </a>
                                </p>
                            </div>
                        </div>
                    </li>
                    @endforeach
                </ul>
            </div>

            <div class="panel-footer text-right remove-padding-horizontal pager-footer">
                {!! $threads->render() !!}
            </div>

        @else
            <div class="panel-body">
                <div class="empty-block">消息列表为空！</div>
            </div>
        @endif

    </div>
</div>
</div>


@stop
