<div class="banner-container rbs row">
    @if(isset($banners['website_bottom']))
        @foreach($banners['website_bottom'] as $banner)
        <div class="col-xs-6 col-sm-4 col-md-3 col-lg-2">
            <div class="item">
                <a href="@if($banner->link){{$banner->link}}@else javascript:; @endif" target="{{$banner->target}}">
                    <p class="img"><span style="background-image:url({{$banner->image_url}})"></span></p>
                    <p class="caption">{{$banner->title}}</p>
                </a>
            </div>
        </div>
        @endforeach
    @endif
</div>
