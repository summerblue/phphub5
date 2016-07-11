<div style="text-align: center; margin: 20px 0 15px">
  <p style="margin-bottom: 10px">客户端登录二维码</p>
  <img style="height: 180px; width=180px;" " src="data:image/png;base64,{{ base64_encode($user->present()->loginQR(180)) }}">
  <br/><br/>
  <a href="{{ route('users.regenerate_login_token') }}">
  	<button class="btn btn-sm btn-default">重新生成</button> 
  </a>
</div>