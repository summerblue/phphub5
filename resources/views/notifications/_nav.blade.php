<div class="box">
    <div class="padding-md">
        <div class="list-group text-center">
          <a href="{{ route('messages.index') }}" class="list-group-item big {{ active_class(if_uri_pattern(['messages*'])) }}">
              <i class="text-md fa fa-envelope" aria-hidden="true"></i>
              &nbsp;私信
               @if ($currentUser->message_count > 0)
                   <span class="badge badge-important" style="color: white;">
                      {{ $currentUser->message_count }}
                  </span>
               @endif
          </a>

          <a href="{{ route('notifications.index') }}" class="list-group-item big {{ active_class(if_route('notifications.index')) }}">
              <i class="text-md fa fa-bell" aria-hidden="true"></i>
               &nbsp;通知
               @if ($currentUser->notification_count > 0)
                   <span class="badge badge-important" style="color: white;">
                      {{ $currentUser->notification_count }}
                  </span>
               @endif
           </a>
        </div>
    </div>
</div>
