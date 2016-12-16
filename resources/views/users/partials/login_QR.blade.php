<div class="box text-center">
  <p style="margin-bottom: 10px;margin-top: 10px;"><a href="https://laravel-china.org/topics/1531">客户端</a> 登录二维码</p>
  <img style="height: 180px; width=180px;" " src="data:image/png;base64,{{ base64_encode($user->present()->loginQR(180)) }}">
  <br/><br/>
<form method="POST" action="{{ route('users.regenerate_login_token') }}" accept-charset="UTF-8">
    {!! csrf_field() !!}
    <div style="margin-bottom: 8px;color: #999;font-size: 0.9em;">登录失败请点击</div>
    <input class="btn btn-sm btn-default" style="margin-bottom: 13px;" id="topic-create-submit" type="submit" value="重新生成">
</form>

</div>
