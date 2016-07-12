<!--
______                            _              _                                     _
| ___ \                          | |            | |                                   | |
| |_/ /___ __      __ ___  _ __  | |__   _   _  | |      __ _  _ __  __ _ __   __ ___ | |
|  __// _ \\ \ /\ / // _ \| '__| | '_ \ | | | | | |     / _` || '__|/ _` |\ \ / // _ \| |
| |  | (_) |\ V  V /|  __/| |    | |_) || |_| | | |____| (_| || |  | (_| | \ V /|  __/| |
\_|   \___/  \_/\_/  \___||_|    |_.__/  \__, | \_____/ \__,_||_|   \__,_|  \_/  \___||_|
                                          __/ |
                                         |___/
  ========================================================
                                           phphub.org

  --------------------------------------------------------
  Laravel: v4.2.8
-->

<!DOCTYPE html>
<html lang="zh">
	<head>

		<meta charset="UTF-8">

		<title>
			@section('title')
PHPHub  - PHP & Laravel的中文社区
			@show
		</title>

		<meta name="viewport" content="width=device-width, initial-scale=1.0" />

		<meta name="keywords" content="PHP,Laravel,PHP论坛,Laravel论坛,PHP社区,Laravel社区" />
		<meta name="author" content="The PHP China Community." />
		<meta name="description" content="@section('description') PHP China 是 PHP 和 Laravel 的中文社区，致力于推动 Laravel, php-fig 等国外 PHP 新技术, 新理念在中国的发展。 @show" />
        <meta name="_token" content="{{ csrf_token() }}">

        <link rel="stylesheet" href="{{ elixir('assets/css/styles.css') }}">

        <link rel="shortcut icon" href="{{ cdn('favicon.ico') }}"/>

        <script>
            Config = {
                'cdnDomain': '{{ getCdnDomain() }}',
                'user_id': {{ $currentUser ? $currentUser->id : 0 }},
                'routes': {
                    'notificationsCount' : '{{ route('notifications.count') }}',
                    'upload_image' : '{{ route('upload_image') }}'
                },
                'token': '{{ csrf_token() }}'
            };

			var ShowCrxHint = '{{isset($show_crx_hint) ? $show_crx_hint : 'no'}}';
        </script>

	    @yield('styles')

	</head>
	<body id="body">

		<div id="wrap">

			@include('layouts.partials.nav')

			<div class="container">

				@include('flash::message')

				@yield('content')

			</div>

		</div>

	  <div id="footer" class="footer">
	    <div class="container small">
	      <p class="pull-left">
	      	<i class="fa fa-heart-o"></i> Made With Love By <a href="http://estgroupe.com/" style="color:#989898;">The EST Group</a>. <br>
			&nbsp;<i class="fa fa-lightbulb-o"></i> Inspired by v2ex & ruby-china.
	      </p>

	      <p class="pull-right">
	      	<a href="http://www.ucloud.cn/?utm_source=zanzhu&utm_campaign=phphub&utm_medium=display&utm_content=yejiao&ytag=phphubyejiao" target="_blank"><img src="https://dn-phphub.qbox.me/uploads/images/201605/03/1/dYfOYswiQY.png" width="98" data-toggle="tooltip" data-placement="top" title="本站服务器由 UCloud 赞助"></a>.
	      </p>
	    </div>
	  </div>

        <script src="{{ elixir('assets/js/scripts.js') }}"></script>

	    @yield('scripts')

        @if (App::environment() == 'production')
		<script>
          (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
          (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
          m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
          })(window,document,'script','//www.google-analytics.com/analytics.js','ga');

          ga('create', '{{ getenv('GA_Tracking_ID') }}', 'auto');
          ga('send', 'pageview');

        </script>
        @endif

		@if ( config('app.debug') )
	    <script type="text/javascript">
	        document.write('<script src="//localhost:35729/livereload.js?snipver=1" type="text/javascript"><\/script>')
	    </script>
	    @endif

	</body>
</html>
