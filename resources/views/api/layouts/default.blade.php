<!doctype html>
<html lang="ch_zh">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width,initial-scale=1,user-scalable=no">
    <title>
        @section('title')
PHPHub  - PHP & Laravel 中文社区
        @show
    </title>
    <link rel="stylesheet" href="{{ cdn(elixir('assets/css/api.css')) }}">

    <script>
        Config = {
            'cdnDomain': '{{ get_cdn_domain() }}',
            'token': '{{ csrf_token() }}',
            'environment': '{{ app()->environment() }}'
        };
    </script>

    @yield('styles')

</head>
<body>

    @yield('content')

    <script src="{{ cdn(elixir('assets/js/api.js')) }}"></script>

    @yield('scripts')
</body>
</html>