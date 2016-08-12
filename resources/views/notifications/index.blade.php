@extends('layouts.default')

@section('title')
{{ lang('My Notifications') }} @parent
@stop

@section('content')

<div class="panel panel-default">

    <div class="panel-heading">
      {{ lang('My Notifications') }}
    </div>

    @if (count($notifications))

        <div class="panel-body remove-padding-horizontal notification-index">

            <ul class="list-group row">
                @foreach ($notifications as $notification)
                 <li class="list-group-item media" style="margin-top: 0px;">

                    @if ((count($notification->topic) || $notification->type=='follow') && count($notification->fromUser))
                        <div class="avatar pull-left">
                            <a href="{{ route('users.show', [$notification->from_user_id]) }}">
                                <img class="media-object img-thumbnail avatar" alt="{{{ $notification->fromUser->name }}}" src="{{ $notification->fromUser->present()->gravatar }}"  style="width:38px;height:38px;"/>
                            </a>
                        </div>

                        <div class="infos">

                          <div class="media-heading">

                            <a href="{{ route('users.show', [$notification->from_user_id]) }}">
                                {{{ $notification->fromUser->name }}}
                            </a>
                             •
                            {{ $notification->present()->lableUp }}

                            @if($notification->type!='follow')
                            <a href="{{ route('topics.show', [$notification->topic->id]) }}{{{ !empty($notification->reply_id) ? '#reply' . $notification->reply_id : '' }}}" title="{{{ $notification->topic->title }}}">
                                {{{ str_limit($notification->topic->title, '100') }}}
                            </a>
                            @endif
                            <span class="meta">
                                 • {{ lang('at') }} • <span class="timeago">{{ $notification->created_at }}</span>
                            </span>
                          </div>
                          <div class="media-body markdown-reply content-body">
                                {!! $notification->body !!}
                          </div>

                        </div>
                    @else
                      <div class="deleted text-center">{{ lang('Data has been deleted.') }}</div>
                    @endif
                </li>
                @endforeach
            </ul>


        </div>

        <div class="panel-footer text-right remove-padding-horizontal pager-footer">
            <!-- Pager -->
            {!! $notifications->render() !!}
        </div>

    @else
        <div class="panel-body">
            <div class="empty-block">{{ lang('You dont have any notice yet!') }}</div>
        </div>
    @endif

</div>


@stop