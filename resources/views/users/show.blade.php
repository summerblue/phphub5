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

        @if($user->github_name)
            <iframe src="{{ route('users.github-card') }}?user={{ $user->github_name }}&client_id={{ getenv('github_card_client_id') }}&client_secret={{ getenv('github_card_client_secret') }}&target=blank" frameborder="0" scrolling="0" width="100%" height="146px" allowtransparency></iframe>
        @else
            <div class="empty-block">{{ lang('Dont have any data Yet') }}~~</div>
        @endif
    </div>


    <div class="panel panel-default">
        <div class="panel-heading">
          {{ lang('Recent Topics') }}
        </div>

        <div class="panel-body">
            @if (count($topics))
              @include('users.partials.topics')

                <div class="add-padding-vertically">
          	        {!! $topics->render() !!}
          	    </div>
            @else
              <div class="empty-block">{{ lang('Dont have any data Yet') }}~~</div>
            @endif
        </div>
    </div>


    <div class="panel panel-default">
        <div class="panel-heading">
          {{ lang('Recent Replies') }}
        </div>

        <div class="panel-body">
            @if (count($replies))
              @include('users.partials.replies')
              <div class="add-padding-vertically">
                  {!! $replies->render() !!}
              </div>
            @else
              <div class="empty-block">{{ lang('Dont have any comment yet') }}~~</div>
            @endif
        </div>
    </div>

  </div>


</div>




@stop
