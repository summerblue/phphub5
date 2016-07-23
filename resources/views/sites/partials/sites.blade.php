
@if(count($filterd_sites))
<div class="panel panel-default">

    <div class="panel-heading">
        {!! $heading_title !!}
    </div>

    <div class="panel-body row">
        @foreach($filterd_sites as $site)
            <div class="col-md-2 site">
              <img class="favicon" style="width: 16px; height: 16px;" src="{{ $site->favicon }}">
              <a class="popover-with-html" target="_blank" href="{{ $site->link }}" data-content="{{ $site->description }}">{{ $site->title }}</a>
            </div>
        @endforeach
    </div>

</div>
@endif