
@if(isset($banners['sidebar-resources']))
<div class="panel panel-default corner-radius sidebar-resources">
  <div class="panel-heading text-center">
    <h3 class="panel-title">推荐资源</h3>
  </div>
  <div class="panel-body">
    <ul class="list list-group ">
        @foreach($banners['sidebar-resources'] as $banner)
            <li class="list-group-item ">
                <a href="{{ $banner->link }}" class="no-pjax" title="{{{ $banner->title }}}">
                    <img class="media-object inline-block " src="{{ $banner->image_url }}">
                    {{{ $banner->title }}}
                </a>
            </li>
        @endforeach
    </ul>
  </div>
</div>
@endif