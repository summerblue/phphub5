<footer class="footer">
      <div class="container">
        <div class="row footer-top">

          <div class="col-sm-5 col-lg-5">

              <p class="padding-top-xsm">我们是高品质的 Laravel 开发者社区，致力于为 Laravel 开发者提供一个分享创造、结识伙伴、协同互助的平台。</p>

              <div class="text-md " >
                  <a class="popover-with-html" data-content="反馈问题" target="_blank" style="padding-right:8px" href="mailto:summer@estgroupe.com"><i class="fa fa-envelope" aria-hidden="true"></i></a>
                  <a class="popover-with-html" data-content="查看源代码" target="_blank" style="padding-right:8px" href="https://github.com/summerblue/phphub5"><i class="fa fa-github-alt" aria-hidden="true"></i></a>
                  <a class="popover-with-html" data-content="关注「Laravel资讯」" target="_blank" style="padding-right:8px" href="http://weibo.com/laravelnews"><i class="fa fa-weibo" aria-hidden="true"></i></a>
                  <a class="popover-with-html" data-content="扫码关注微信订阅号：「Laravel资讯」" target="_blank" style="padding-right:8px" href="https://dn-phphub.qbox.me/uploads/images/201612/15/1/MGig6IACCQ.png"><i class="fa fa-weixin" aria-hidden="true"></i></a>
                  <a class="popover-with-html" data-content="下载 Chrome 消息通知插件" target="_blank" style="padding-right:8px" href="https://chrome.google.com/webstore/detail/phphub-notifier/fcopfkdgikhodlcjkjdppdfkbhmehdon"><i class="fa fa-chrome" aria-hidden="true"></i></a>
              </div>
              <br>
              <span style="font-size:0.9em">
                  Powered by <a href="https://github.com/summerblue/phphub5" target="_blank" style="color:inherit">PHPHub</a>
              </span><br>
              <span style="font-size:0.9em">
                  Designed by <span style="color: #e27575;font-size: 14px;">❤</span> <a href="https://github.com/summerblue" target="_blank" style="color:inherit">Summer</a>
              </span>
          </div>

          <div class="col-sm-6 col-lg-6 col-lg-offset-1">

              <div class="row">
                <div class="col-sm-4">
                  <h4>赞助商</h4>
                  <ul class="list-unstyled">
                      @if(isset($banners['footer-sponsor']))
                          @foreach($banners['footer-sponsor'] as $banner)
                              <a href="{{ $banner->link }}" target="_blank"><img src="{{ $banner->image_url }}" class="popover-with-html footer-sponsor-link" width="98" data-placement="top" data-content="{{ $banner->title }}"></a>
                          @endforeach
                      @endif
                  </ul>
                </div>

                  <div class="col-sm-4">
                    <h4>{{ lang('Site Status') }}</h4>
                    <ul class="list-unstyled">
                        <li>{{ lang('Total User') }}: {{ $siteStat->user_count }} </li>
                        <li>{{ lang('Total Topic') }}: {{ $siteStat->topic_count }} </li>
                        <li>{{ lang('Total Reply') }}: {{ $siteStat->reply_count }} </li>
                    </ul>
                  </div>
                  <div class="col-sm-4">
                    <h4>其他信息</h4>
                    <ul class="list-unstyled">
                        <li><a href="{{ route('sites.index') }}"><i class="fa fa-globe text-md"></i> 推荐网站</a></li>
                        <li><a href="/about"><i class="fa fa-info-circle" aria-hidden="true"></i> 关于我们</a></li>
                        <li><a href="{{ route('hall_of_fames') }}"><i class="fa fa-star" aria-hidden="true"></i> {{ lang('Hall of Fame') }}</a></li>
                        <li><a href="http://yousails.mikecrm.com/4Dh5uWU"><i class="fa fa-thumbs-up" aria-hidden="true"></i> 软件外包服务</a></li>
                    </ul>
                  </div>

                </div>

          </div>
        </div>
        <br>
        <br>
      </div>
    </footer>
