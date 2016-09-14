
@if ($topic->is_excellent == 'yes')
  <div class="ribbon">
      <div class="ribbon-excellent">
          <i class="fa fa-trophy"></i> {{ lang('This topic has been mark as Excenllent Topic.') }}
      </div>
  </div>
@endif

@if ($topic->order == -1)
  <div class="ribbon">
      <div class="ribbon-anchored">
          <i class="fa fa-anchor"></i> 此贴已被下沉，请查看 <a href="https://laravel-china.org/topics/2802">下沉说明</a> 进行修改。
      </div>
  </div>
@endif
