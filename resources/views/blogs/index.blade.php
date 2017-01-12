@extends('layout')

@section('header')
    <div class="page-header clearfix">
        <h1>
            <i class="glyphicon glyphicon-align-justify"></i> Blogs
            <a class="btn btn-success pull-right" href="{{ route('blogs.create') }}"><i class="glyphicon glyphicon-plus"></i> Create</a>
        </h1>

    </div>
@endsection

@section('content')
    <div class="row">
        <div class="col-md-12">
            @if($blogs->count())
                <table class="table table-condensed table-striped">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>NAME</th>
                        <th>SLUG</th>
                        <th>DESCRIPTION</th>
                        <th>COVER</th>
                        <th>USER_ID</th>
                        <th>ARTICLE_COUNT</th>
                        <th>SUBSCRIBER_COUNT</th>
                        <th>IS_RECOMMENDED</th>
                        <th>IS_BLOCKED</th>
                            <th class="text-right">OPTIONS</th>
                        </tr>
                    </thead>

                    <tbody>
                        @foreach($blogs as $blog)
                            <tr>
                                <td>{{$blog->id}}</td>
                                <td>{{$blog->name}}</td>
                    <td>{{$blog->slug}}</td>
                    <td>{{$blog->description}}</td>
                    <td>{{$blog->cover}}</td>
                    <td>{{$blog->user_id}}</td>
                    <td>{{$blog->article_count}}</td>
                    <td>{{$blog->subscriber_count}}</td>
                    <td>{{$blog->is_recommended}}</td>
                    <td>{{$blog->is_blocked}}</td>
                                <td class="text-right">
                                    <a class="btn btn-xs btn-primary" href="{{ route('blogs.show', $blog->id) }}"><i class="glyphicon glyphicon-eye-open"></i> View</a>
                                    <a class="btn btn-xs btn-warning" href="{{ route('blogs.edit', $blog->id) }}"><i class="glyphicon glyphicon-edit"></i> Edit</a>
                                    <form action="{{ route('blogs.destroy', $blog->id) }}" method="POST" style="display: inline;" onsubmit="if(confirm('Delete? Are you sure?')) { return true } else {return false };">
                                        <input type="hidden" name="_method" value="DELETE">
                                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                        <button type="submit" class="btn btn-xs btn-danger"><i class="glyphicon glyphicon-trash"></i> Delete</button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
                {!! $blogs->render() !!}
            @else
                <h3 class="text-center alert alert-info">Empty!</h3>
            @endif

        </div>
    </div>

@endsection