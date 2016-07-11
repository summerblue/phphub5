@extends('layouts.default')

@section('title')
{{ lang('Newly Registered User List') }}_@parent
@stop

@section('content')

<div class="panel panel-default users-index">

    <div class="panel-heading text-center">
        {{ lang('Newly Registered User List') }} ( {{ lang('Total User') }} {{ $siteStat->user_count }} )
    </div>

    <div class="panel-body">
    @foreach ($users as $user)
        <div class="col-md-1 remove-padding-right">
            <div class="avatar">
              <a href="{{ route('users.show', $user->id) }}" class="users-index-{{ $user->id }}">
                <img src="{{ $user->present()->gravatar }}" class="img-thumbnail avatar"  style="width:48px;height:48px;margin-bottom: 20px;"/>
              </a>
            </div>
        </div>
    @endforeach
    </div>

</div>
@stop
