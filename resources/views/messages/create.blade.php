@extends('layouts.default')

@section('title')
新建私信 | @parent
@stop

@section('content')

<div class="messages">

    <div class="col-md-3 main-col">
        @include('notifications._nav')
    </div>

    <div class="col-md-9  left-col ">

        <div class="panel panel-default padding-sm">

            <div class="panel-heading ">
                <h1>
                    发私信
                </h1>
            </div>

            <div class="panel-body">

                <div>
                    <a href="{{ route('users.show', [$recipient->id]) }}" title="{{ $recipient->name }}">
                        <img class="avatar avatar-small" alt="{{ $recipient->name }}" src="{{ $recipient->present()->gravatar }}"/>
                        {{ $recipient->name }}
                    </a>
                </div>
                <br>

                <form class="form-horizontal" method="POST" action="{{ route('messages.store') }}" accept-charset="UTF-8">
                    {!! csrf_field() !!}
                    <input name="recipient_id" type="hidden" value="{{ $recipient->id }}">

                        @include('layouts.partials.errors')

                        <div class="form-group">

                              <div class="col-sm-8">
                                  <textarea class="form-control" rows="5" name="message" cols="50" id="reply_content" required></textarea>
                              </div>
                              <div class="col-sm-4 help-block">
                                    <ul>
                                        <li>可以使用 Markdown</li>
                                        <li>支持黏贴上传图片</li>
                                        <li>支持 emoji 表情</li>
                                    </ul>
                              </div>
                        </div>

                        <button type="submit" class="btn btn-primary loading-on-clicked"><i class="fa fa-paper-plane" aria-hidden="true"></i> 发送</button>

                </form>
            </div>
        </div>
    </div>
</div>


@stop


