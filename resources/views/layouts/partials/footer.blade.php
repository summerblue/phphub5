<footer class="footer">
      <div class="container">
        <div class="row footer-top">

          <div class="col-sm-5 col-lg-5">
              <h4>
                  <img src="https://dn-phphub.qbox.me/assets/images/banner_transparent.png" alt="" width="160"/>
                  <span class="pull-right text-md padding-top-lg" style="padding-top: 18px;">
                      <a class="popover-with-html" data-content="查看源代码" target="_blank" style="padding-right:8px" href="https://github.com/summerblue/phphub5"><i class="fa fa-github-alt" aria-hidden="true"></i></a>
                      <a class="popover-with-html" data-content="关注 PHPHub 微博账号" target="_blank" style="padding-right:8px" href="http://weibo.com/phphub"><i class="fa fa-weibo" aria-hidden="true"></i></a>
                      <a class="popover-with-html" data-content="扫码关注微信订阅号：PHPHub" target="_blank" style="padding-right:8px" href="https://dn-phphub.qbox.me/uploads/images/201607/19/1/5KAh3F8EhD.jpg"><i class="fa fa-weixin" aria-hidden="true"></i></a>
                      <a class="popover-with-html" data-content="下载 Chrome 消息通知插件" target="_blank" style="padding-right:8px" href="https://chrome.google.com/webstore/detail/phphub-notifier/fcopfkdgikhodlcjkjdppdfkbhmehdon"><i class="fa fa-chrome" aria-hidden="true"></i></a>
                  </span>
              </h4>
              <p class="padding-top-xsm">我们是中国最靠谱的 PHP &amp; Laravel 开发者社区，致力于打造一个 PHPer 分享创造、结识伙伴、协同互助的平台。</p>
              <span style="font-size:0.9em">♥ Do what you love, love what you do. - <a href="http://estgroupe.com/">The EST Group</a></span>
          </div>

          <div class="col-sm-6 col-lg-6 col-lg-offset-1">

              <div class="row">
                <div class="col-sm-3">
                  <h4>赞助商</h4>
                  <ul class="list-unstyled">
                      @if(isset($banners['footer-sponsor']))
                          @foreach($banners['footer-sponsor'] as $banner)
                              <a href="{{ $banner->link }}" target="_blank"><img src="{{ $banner->image_url }}" class="popover-with-html footer-sponsor-link" width="98" data-placement="top" data-content="{{ $banner->title }}"></a>
                          @endforeach
                      @endif
                  </ul>
                </div>

                  <div class="col-sm-3">
                    <h4>{{ lang('Site Status') }}</h4>
                    <ul class="list-unstyled">
                        <li>{{ lang('Total User') }}: {{ $siteStat->user_count }} </li>
                        <li>{{ lang('Total Topic') }}: {{ $siteStat->topic_count }} </li>
                        <li>{{ lang('Total Reply') }}: {{ $siteStat->reply_count }} </li>
                    </ul>
                  </div>
                  <div class="col-sm-3">
                    <h4>其他信息</h4>
                    <ul class="list-unstyled">
                        <li><a href="/about">关于我们</a></li>
                        <li><a href="{{ route('hall_of_fames') }}"><i class="fa fa-star" aria-hidden="true"></i> {{ lang('Hall of Fame') }}</a></li>
                        <li class="popover-with-html" data-content="新手 QQ 群">Q 群：579866868</li>
                    </ul>
                  </div>
                  <div class="col-sm-3">
                      <h4>微信订阅号</h4>
                    <img class="image-border popover-with-html" data-content="扫码，或者搜索微信订阅号：PHPHub" src="https://dn-phphub.qbox.me/uploads/images/201607/19/1/5KAh3F8EhD.jpg" style="width:100px;height:100px;">
                  </div>

                </div>

          </div>
        </div>
        <br>
        <br>
      </div>
    </footer>
