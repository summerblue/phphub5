@extends('layouts.default')

@section('title')
{{{ $user->name }}} {{ lang('Basic Info') }}_@parent
@stop

@section('content')

<div class="users-show">

  <div class="col-md-3 box" style="padding: 15px 15px;">
    @include('users.partials.basicinfo')
  </div>

  <div class="main-col col-md-9 left-col">

    @if ($user->introduction)
      <div class="box text-center">{{{ $user->introduction }}}</div>
    @endif

    @if ($user->is_banned == 'yes')
      <div class="text-center alert alert-info"><b>{{ lang('This user is banned!') }}</b></div>
    @endif

    <div class="panel panel-default">
      @include('users.partials.infonav', ['current' => 'basicinfo'])

      <iframe src="{{ route('users.github-card') }}?user={{ $user->github_name }}&client_id={{ getenv('github_card_client_id') }}&client_secret={{ getenv('github_card_client_secret') }}&target=blank" frameborder="0" scrolling="0" width="100%" height="146px" allowtransparency></iframe>
    </div>

    <div class="panel panel-default">

      <ul class="nav nav-tabs user-info-nav" role="tablist">
        <li class="active"><a href="#recent_replies" role="tab" data-toggle="tab">{{ lang('Recent Replies') }}</a></li>
        <li><a href="#recent_topics" role="tab" data-toggle="tab">{{ lang('Recent Topics') }}</a></li>
      </ul>

      <div class="panel-body remove-padding-vertically remove-padding-horizontal">
        <!-- Tab panes -->
        <div class="tab-content">

          <div class="tab-pane active" id="recent_replies">

            @if (count($replies))
              @include('users.partials.replies')
            @else
              <div class="empty-block">{{ lang('Dont have any comment yet') }}~~</div>
            @endif

          </div>

          <div class="tab-pane" id="recent_topics">
            @if (count($topics))
              @include('users.partials.topics')
            @else
              <div class="empty-block">{{ lang('Dont have any data Yet') }}~~</div>
            @endif
          </div>

        </div>
      </div>

    </div>
  </div>


</div>




@stop
