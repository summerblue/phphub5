@extends('layout')
@section('header')
<div class="page-header">
        <h1>Activities / Show #{{$activity->id}}</h1>
        <form action="{{ route('activities.destroy', $activity->id) }}" method="POST" style="display: inline;" onsubmit="if(confirm('Delete? Are you sure?')) { return true } else {return false };">
            <input type="hidden" name="_method" value="DELETE">
            <input type="hidden" name="_token" value="{{ csrf_token() }}">
            <div class="btn-group pull-right" role="group" aria-label="...">
                <a class="btn btn-warning btn-group" role="group" href="{{ route('activities.edit', $activity->id) }}"><i class="glyphicon glyphicon-edit"></i> Edit</a>
                <button type="submit" class="btn btn-danger">Delete <i class="glyphicon glyphicon-trash"></i></button>
            </div>
        </form>
    </div>
@endsection

@section('content')
    <div class="row">
        <div class="col-md-12">

            <form action="#">
                <div class="form-group">
                    <label for="nome">ID</label>
                    <p class="form-control-static"></p>
                </div>
                <div class="form-group">
                     <label for="causer">CAUSER</label>
                     <p class="form-control-static">{{$activity->causer}}</p>
                </div>
                    <div class="form-group">
                     <label for="type">TYPE</label>
                     <p class="form-control-static">{{$activity->type}}</p>
                </div>
                    <div class="form-group">
                     <label for="indentifier">INDENTIFIER</label>
                     <p class="form-control-static">{{$activity->indentifier}}</p>
                </div>
                    <div class="form-group">
                     <label for="user_id">USER_ID</label>
                     <p class="form-control-static">{{$activity->user_id}}</p>
                </div>
                    <div class="form-group">
                     <label for="data">DATA</label>
                     <p class="form-control-static">{{$activity->data}}</p>
                </div>
            </form>

            <a class="btn btn-link" href="{{ route('activities.index') }}"><i class="glyphicon glyphicon-backward"></i>  Back</a>

        </div>
    </div>

@endsection