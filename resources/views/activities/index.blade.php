@extends('layout')

@section('header')
    <div class="page-header clearfix">
        <h1>
            <i class="glyphicon glyphicon-align-justify"></i> Activities
            <a class="btn btn-success pull-right" href="{{ route('activities.create') }}"><i class="glyphicon glyphicon-plus"></i> Create</a>
        </h1>

    </div>
@endsection

@section('content')
    <div class="row">
        <div class="col-md-12">
            @if($activities->count())
                <table class="table table-condensed table-striped">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>CAUSER</th>
                        <th>TYPE</th>
                        <th>INDENTIFIER</th>
                        <th>USER_ID</th>
                        <th>DATA</th>
                            <th class="text-right">OPTIONS</th>
                        </tr>
                    </thead>

                    <tbody>
                        @foreach($activities as $activity)
                            <tr>
                                <td>{{$activity->id}}</td>
                                <td>{{$activity->causer}}</td>
                    <td>{{$activity->type}}</td>
                    <td>{{$activity->indentifier}}</td>
                    <td>{{$activity->user_id}}</td>
                    <td>{{$activity->data}}</td>
                                <td class="text-right">
                                    <a class="btn btn-xs btn-primary" href="{{ route('activities.show', $activity->id) }}"><i class="glyphicon glyphicon-eye-open"></i> View</a>
                                    <a class="btn btn-xs btn-warning" href="{{ route('activities.edit', $activity->id) }}"><i class="glyphicon glyphicon-edit"></i> Edit</a>
                                    <form action="{{ route('activities.destroy', $activity->id) }}" method="POST" style="display: inline;" onsubmit="if(confirm('Delete? Are you sure?')) { return true } else {return false };">
                                        <input type="hidden" name="_method" value="DELETE">
                                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                        <button type="submit" class="btn btn-xs btn-danger"><i class="glyphicon glyphicon-trash"></i> Delete</button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
                {!! $activities->render() !!}
            @else
                <h3 class="text-center alert alert-info">Empty!</h3>
            @endif

        </div>
    </div>

@endsection