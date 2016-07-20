<div class="padding-md">
    <div class="list-group text-center">
      <a href="{{ route('users.edit', $currentUser->id) }}" class="list-group-item {{ navViewActive('users.edit') }}">
          <i class="text-md fa fa-list-alt" aria-hidden="true"></i>
          &nbsp;{{ lang('Basic Info') }}
      </a>
      <a href="{{ route('users.edit_avatar', $currentUser->id) }}" class="list-group-item {{ navViewActive('users.edit_avatar') }}">
          <i class="text-md fa fa-picture-o" aria-hidden="true"></i>
           &nbsp;{{ lang('Change Avatar') }}
       </a>
      <a href="{{ route('users.edit_email_notify', $currentUser->id) }}" class="list-group-item {{ navViewActive('users.edit_email_notify') }}">
          <i class="text-md fa fa-bell" aria-hidden="true"></i>
          &nbsp;{{ lang('Notification Settings') }}
      </a>
      <a href="{{ route('users.edit_social_binding', $currentUser->id) }}" class="list-group-item {{ navViewActive('users.edit_social_binding') }}">
          <i class="text-md fa fa-flask" aria-hidden="true"></i>
          &nbsp;{{ lang('Social Account Binding') }}
      </a>
    </div>
</div>
