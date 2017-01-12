@extends('layout')

@section('header')
    <div class="page-header">
        <h1><i class="glyphicon glyphicon-plus"></i> Blogs / Create </h1>
    </div>
@endsection

@section('content')
    @include('error')

    <div class="row">
        <div class="col-md-12">

            <form action="{{ route('blogs.store') }}" method="POST">
                <input type="hidden" name="_token" value="{{ csrf_token() }}">

                <div class="form-group @if($errors->has('name')) has-error @endif">
                       <label for="name-field">Name</label>
                    <input type="text" id="name-field" name="name" class="form-control" value="{{ old("name") }}"/>
                       @if($errors->has("name"))
                        <span class="help-block">{{ $errors->first("name") }}</span>
                       @endif
                    </div>
                    <div class="form-group @if($errors->has('slug')) has-error @endif">
                       <label for="slug-field">Slug</label>
                    <input type="text" id="slug-field" name="slug" class="form-control" value="{{ old("slug") }}"/>
                       @if($errors->has("slug"))
                        <span class="help-block">{{ $errors->first("slug") }}</span>
                       @endif
                    </div>
                    <div class="form-group @if($errors->has('description')) has-error @endif">
                       <label for="description-field">Description</label>
                    <textarea class="form-control" id="description-field" rows="3" name="description">{{ old("description") }}</textarea>
                       @if($errors->has("description"))
                        <span class="help-block">{{ $errors->first("description") }}</span>
                       @endif
                    </div>
                    <div class="form-group @if($errors->has('cover')) has-error @endif">
                       <label for="cover-field">Cover</label>
                    <input type="text" id="cover-field" name="cover" class="form-control" value="{{ old("cover") }}"/>
                       @if($errors->has("cover"))
                        <span class="help-block">{{ $errors->first("cover") }}</span>
                       @endif
                    </div>
                    <div class="form-group @if($errors->has('user_id')) has-error @endif">
                       <label for="user_id-field">User_id</label>
                    <input type="text" id="user_id-field" name="user_id" class="form-control" value="{{ old("user_id") }}"/>
                       @if($errors->has("user_id"))
                        <span class="help-block">{{ $errors->first("user_id") }}</span>
                       @endif
                    </div>
                    <div class="form-group @if($errors->has('article_count')) has-error @endif">
                       <label for="article_count-field">Article_count</label>
                    <input type="text" id="article_count-field" name="article_count" class="form-control" value="{{ old("article_count") }}"/>
                       @if($errors->has("article_count"))
                        <span class="help-block">{{ $errors->first("article_count") }}</span>
                       @endif
                    </div>
                    <div class="form-group @if($errors->has('subscriber_count')) has-error @endif">
                       <label for="subscriber_count-field">Subscriber_count</label>
                    <input type="text" id="subscriber_count-field" name="subscriber_count" class="form-control" value="{{ old("subscriber_count") }}"/>
                       @if($errors->has("subscriber_count"))
                        <span class="help-block">{{ $errors->first("subscriber_count") }}</span>
                       @endif
                    </div>
                    <div class="form-group @if($errors->has('is_recommended')) has-error @endif">
                       <label for="is_recommended-field">Is_recommended</label>
                    <input type="text" id="is_recommended-field" name="is_recommended" class="form-control" value="{{ old("is_recommended") }}"/>
                       @if($errors->has("is_recommended"))
                        <span class="help-block">{{ $errors->first("is_recommended") }}</span>
                       @endif
                    </div>
                    <div class="form-group @if($errors->has('is_blocked')) has-error @endif">
                       <label for="is_blocked-field">Is_blocked</label>
                    <input type="text" id="is_blocked-field" name="is_blocked" class="form-control" value="{{ old("is_blocked") }}"/>
                       @if($errors->has("is_blocked"))
                        <span class="help-block">{{ $errors->first("is_blocked") }}</span>
                       @endif
                    </div>
                <div class="well well-sm">
                    <button type="submit" class="btn btn-primary">Create</button>
                    <a class="btn btn-link pull-right" href="{{ route('blogs.index') }}"><i class="glyphicon glyphicon-backward"></i> Back</a>
                </div>
            </form>

        </div>
    </div>
@endsection