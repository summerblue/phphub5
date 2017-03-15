@extends('layout')

@section('header')
    <div class="page-header">
        <h1><i class="glyphicon glyphicon-plus"></i> Activities / Create </h1>
    </div>
@endsection

@section('content')
    @include('error')

    <div class="row">
        <div class="col-md-12">

            <form action="{{ route('activities.store') }}" method="POST">
                <input type="hidden" name="_token" value="{{ csrf_token() }}">

                <div class="form-group @if($errors->has('causer')) has-error @endif">
                       <label for="causer-field">Causer</label>
                    <input type="text" id="causer-field" name="causer" class="form-control" value="{{ old("causer") }}"/>
                       @if($errors->has("causer"))
                        <span class="help-block">{{ $errors->first("causer") }}</span>
                       @endif
                    </div>
                    <div class="form-group @if($errors->has('type')) has-error @endif">
                       <label for="type-field">Type</label>
                    <input type="text" id="type-field" name="type" class="form-control" value="{{ old("type") }}"/>
                       @if($errors->has("type"))
                        <span class="help-block">{{ $errors->first("type") }}</span>
                       @endif
                    </div>
                    <div class="form-group @if($errors->has('indentifier')) has-error @endif">
                       <label for="indentifier-field">Indentifier</label>
                    <input type="text" id="indentifier-field" name="indentifier" class="form-control" value="{{ old("indentifier") }}"/>
                       @if($errors->has("indentifier"))
                        <span class="help-block">{{ $errors->first("indentifier") }}</span>
                       @endif
                    </div>
                    <div class="form-group @if($errors->has('user_id')) has-error @endif">
                       <label for="user_id-field">User_id</label>
                    <input type="text" id="user_id-field" name="user_id" class="form-control" value="{{ old("user_id") }}"/>
                       @if($errors->has("user_id"))
                        <span class="help-block">{{ $errors->first("user_id") }}</span>
                       @endif
                    </div>
                    <div class="form-group @if($errors->has('data')) has-error @endif">
                       <label for="data-field">Data</label>
                    <textarea class="form-control" id="data-field" rows="3" name="data">{{ old("data") }}</textarea>
                       @if($errors->has("data"))
                        <span class="help-block">{{ $errors->first("data") }}</span>
                       @endif
                    </div>
                <div class="well well-sm">
                    <button type="submit" class="btn btn-primary">Create</button>
                    <a class="btn btn-link pull-right" href="{{ route('activities.index') }}"><i class="glyphicon glyphicon-backward"></i> Back</a>
                </div>
            </form>

        </div>
    </div>
@endsection