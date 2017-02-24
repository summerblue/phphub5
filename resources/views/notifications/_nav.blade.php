<div class="box">
    <div class="padding-md">
        <div class="list-group text-center">
          <a href="" class="list-group-item big ">
              <i class="text-md fa fa-envelope" aria-hidden="true"></i>
              &nbsp;消息
              <span class="badge badge-{{ $currentUser->notification_count > 0 ? 'important' : 'fade' }}">
                  {{ $currentUser->notification_count }}
              </span>
          </a>

          <a href="" class="list-group-item big {{ active_class(if_route('notifications.index')) }}">
              <i class="text-md fa fa-bell" aria-hidden="true"></i>
               &nbsp;通知
              <span class="badge badge-{{ $currentUser->notification_count > 0 ? 'important' : 'fade' }}">
                  {{ $currentUser->notification_count }}
              </span>
           </a>
        </div>
    </div>
</div>
