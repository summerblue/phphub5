<div class="banner-container rbs row">
    @if(isset($banners['website_top']))
        @foreach($banners['website_top'] as $banner)
    <div class="col-xs-6 col-sm-4 col-md-3 col-lg-2">
        <div class="item">
            <a href="@if($banner->link){{$banner->link}}@else javascript:; @endif" class="no-pjax">
                <p class="img"><span style="background-image:url({{$banner->image_url}}?imageView2/1/w/424/h/212)"></span></p>
                <p class="caption">{{$banner->title}}</p>
            </a>
        </div>
    </div>
        @endforeach
    @endif
</div>
