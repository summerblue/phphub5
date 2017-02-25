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

            <div class="panel-body">
                <div><a href="{{ route('messages.index') }}" class="normalize-link-color"><i class="fa fa-arrow-left" aria-hidden="true"></i> 返回</a></div>
                <br>
                <div>
                    <p>发私信给 <a href="{{ route('users.show', $participant->id) }}" class="">{{ $participant->name }}</a> ：</p>
                </div>

                <form class="form-horizontal" method="POST" action="{{ route('messages.store') }}" accept-charset="UTF-8">
                    {!! csrf_field() !!}

                    <input name="recipient_id" type="hidden" value="{{ $participant->id }}">
                    <input name="thread_id" type="hidden" value="{{ $thread->id }}">

                        @include('layouts.partials.errors')

                        <div class="form-group">
                              <div class="col-sm-8">
                                  <textarea class="form-control" rows="3" name="message" cols="50" id="reply_content" required></textarea>
                              </div>
                              <div class="col-sm-4 help-block">
                                    <ul>
                                        <li>可以使用 Markdown</li>
                                        <li>支持黏贴上传图片</li>
                                        <li>支持 emoji 表情</li>
                                    </ul>
                              </div>
                        </div>

                        <button type="submit" class="btn btn-primary"><i class="fa fa-paper-plane" aria-hidden="true"></i> 发送</button>

                </form>

                <hr>

                <ul class="list-group row">
                    @foreach($messages as $index => $message)
                     <li class="list-group-item media {{ ($unread_message_count > 0 && $index < $unread_message_count) ? 'unread' : ''  }}" style="margin-top: 0px;"  >
                        <div class="avatar pull-left">
                            <a href="{{ route('users.show', [$message->user->id]) }}">
                                <img class="media-object img-thumbnail avatar" alt="{{ $message->user->name }}" src="{{ $message->user->present()->gravatar }}"  style="width:48px;height:48px;"/>
                            </a>
                        </div>

                        <div class="infos">

                          <div class="media-heading">

                            @if ($message->user_id == $currentUser->id)
                                我
                            @else
                                <a href="{{ route('users.show', [$message->user->id]) }}">
                                    {{ $message->user->name }}
                                </a>
                            @endif
                            ：
                          </div>

                          <div class="media-body markdown-reply content-body">
                                {!! $message->body  !!}
                          </div>

                            <div>
                                <p><span class="timeago">{{ $message->created_at }}</span></p>
                            </div>
                        </div>
                    </li>
                    @endforeach
                </ul>
            </div>
        </div>
    </div>
</div>


@stop


