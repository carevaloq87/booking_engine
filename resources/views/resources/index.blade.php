@extends('layouts.booking_engine.master')

@section('sub_title')
    Resources
@endsection

@section('buttons')
    <a href="{{ route('resources.resource.create') }}" class="btn btn-success btn-sm" title="Create New Resource">
        Add Resource
    </a>
@endsection


@section('content')

    <div class="panel panel-default">

        @if(count($resources) == 0)
            <div class="panel-body text-center">
                <h4>No Resources Available!</h4>
            </div>
        @else
        <div class="panel-body panel-body-with-table">
            <div class="table-responsive">

                <table class="table table-striped ">
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Phone</th>
                            <th>Email</th>
                            <th>Service Provider</th>

                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                    @foreach($resources as $resource)
                        <tr>
                            <td>{{ $resource->name }}</td>
                            <td>{{ $resource->phone }}</td>
                            <td>{{ $resource->email }}</td>
                            <td>{{ optional($resource->serviceProvider)->name }}</td>

                            <td>

                                <form method="POST" action="{!! route('resources.resource.destroy', $resource->id) !!}" accept-charset="UTF-8">
                                <input name="_method" value="DELETE" type="hidden">
                                {{ csrf_field() }}

                                    <div class="btn-group btn-group-sm pull-right list-table" role="group">
                                        <a href="{{ route('resources.resource.show', $resource->id ) }}" class="btn btn-sm btn-info" title="Show Resource">
                                            <i class="fa fa-user-clock"></i>
                                        </a>
                                        <a href="{{ route('resources.resource.edit', $resource->id ) }}" class="btn btn-sm btn-primary" title="Edit Resource">
                                            <i class="fa fa-pencil-alt"></i>
                                        </a>

                                        <button type="submit" dusk="delete-resource-{{$resource->id}}" class="btn btn-sm btn-danger" title="Delete Resource" onclick="return confirm(&quot;Delete Resource?&quot;)">
                                            <i class="fa fa-trash-alt"></i>
                                        </button>
                                    </div>

                                </form>

                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>

            </div>
        </div>

        <div class="panel-footer">
            {!! $resources->render() !!}
        </div>
        
        @endif
    
    </div>
@endsection