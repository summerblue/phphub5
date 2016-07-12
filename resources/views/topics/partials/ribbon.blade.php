
@if ($topic->is_excellent == 'yes')
  <div class="ribbon">
    @if ($topic->is_excellent == 'yes')
      <div class="ribbon-excellent">
          <i class="fa fa-trophy"></i> {{ lang('This topic has been mark as Excenllent Topic.') }}
      </div>
    @endif

  </div>
@endif
