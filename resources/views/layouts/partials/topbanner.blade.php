
<div class="row grid topbanner">
    @if(isset($banners['website_top']))
        @foreach($banners['website_top'] as $banner)
            <div class="col-md-3 col-sm-6 col-xs-6 projects-card grid-item">
              <div class="thumbnail">
                  <a href="@if($banner->link){{$banner->link}}@else javascript:; @endif" class="no-pjax">
                      <img src="{{$banner->image_url}}?imageView2/1/w/424/h/212">
                  </a>
                <div class="caption">
                  <h3 style="font-size:1.1em;font-weight:bord" class="card-title"><a href="@if($banner->link){{$banner->link}}@else javascript:; @endif" class="no-pjax">{{$banner->title}}</a></h3>
                  <p class="card-description hidden-xs">{{$banner->description}}</p>
                </div>
              </div>
            </div>
        @endforeach
    @endif
</div>
