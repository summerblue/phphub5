<div class="box">
    <div class="padding-md ">
        <div class="list-group text-center">
          <a href="{{ route('users.edit', $user->id) }}" class="list-group-item {{ navViewActive('users.edit') }}">
              <i class="text-md fa fa-list-alt" aria-hidden="true"></i>
              &nbsp;{{ lang('Basic Info') }}
          </a>
          <a href="{{ route('users.edit_avatar', $user->id) }}" class="list-group-item {{ navViewActive('users.edit_avatar') }}">
              <i class="text-md fa fa-picture-o" aria-hidden="true"></i>
               &nbsp;{{ lang('Change Avatar') }}
           </a>
          <a href="{{ route('users.edit_email_notify', $user->id) }}" class="list-group-item {{ navViewActive('users.edit_email_notify') }}">
              <i class="text-md fa fa-bell" aria-hidden="true"></i>
              &nbsp;{{ lang('Notification Settings') }}
          </a>
          <a href="{{ route('users.edit_social_binding', $user->id) }}" class="list-group-item {{ navViewActive('users.edit_social_binding') }}">
              <i class="text-md fa fa-flask" aria-hidden="true"></i>
              &nbsp;{{ lang('Social Account Binding') }}
          </a>
          <a href="{{ route('users.edit_password', $user->id) }}" class="list-group-item {{ navViewActive('users.edit_password') }}">
              <i class="text-md fa fa-lock" aria-hidden="true"></i>
              &nbsp;修改密码
          </a>
        </div>
    </div>

</div>
