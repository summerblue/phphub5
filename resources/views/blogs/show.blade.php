@extends('layout')
@section('header')
<div class="page-header">
        <h1>Blogs / Show #{{$blog->id}}</h1>
        <form action="{{ route('blogs.destroy', $blog->id) }}" method="POST" style="display: inline;" onsubmit="if(confirm('Delete? Are you sure?')) { return true } else {return false };">
            <input type="hidden" name="_method" value="DELETE">
            <input type="hidden" name="_token" value="{{ csrf_token() }}">
            <div class="btn-group pull-right" role="group" aria-label="...">
                <a class="btn btn-warning btn-group" role="group" href="{{ route('blogs.edit', $blog->id) }}"><i class="glyphicon glyphicon-edit"></i> Edit</a>
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
                     <label for="name">NAME</label>
                     <p class="form-control-static">{{$blog->name}}</p>
                </div>
                    <div class="form-group">
                     <label for="slug">SLUG</label>
                     <p class="form-control-static">{{$blog->slug}}</p>
                </div>
                    <div class="form-group">
                     <label for="description">DESCRIPTION</label>
                     <p class="form-control-static">{{$blog->description}}</p>
                </div>
                    <div class="form-group">
                     <label for="cover">COVER</label>
                     <p class="form-control-static">{{$blog->cover}}</p>
                </div>
                    <div class="form-group">
                     <label for="user_id">USER_ID</label>
                     <p class="form-control-static">{{$blog->user_id}}</p>
                </div>
                    <div class="form-group">
                     <label for="article_count">ARTICLE_COUNT</label>
                     <p class="form-control-static">{{$blog->article_count}}</p>
                </div>
                    <div class="form-group">
                     <label for="subscriber_count">SUBSCRIBER_COUNT</label>
                     <p class="form-control-static">{{$blog->subscriber_count}}</p>
                </div>
                    <div class="form-group">
                     <label for="is_recommended">IS_RECOMMENDED</label>
                     <p class="form-control-static">{{$blog->is_recommended}}</p>
                </div>
                    <div class="form-group">
                     <label for="is_blocked">IS_BLOCKED</label>
                     <p class="form-control-static">{{$blog->is_blocked}}</p>
                </div>
            </form>

            <a class="btn btn-link" href="{{ route('blogs.index') }}"><i class="glyphicon glyphicon-backward"></i>  Back</a>

        </div>
    </div>

@endsection