@extends('layouts.booking_engine.master')

@section('sub_title')
    Services
@endsection

@section('buttons')
    <a href="{{ route('services.service.create') }}" class="btn btn-success btn-sm" title="Create New Service" role="button">
        Add Service
    </a>
@endsection

@section('content')

    <div class="panel panel-default">

        @if(count($services) == 0)
            <div class="panel-body text-center">
                <h4>No Services Available!</h4>
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
                            <th>Duration</th>
                            <th>Interpreter Duration</th>
                            <th>Service Provider</th>

                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                    @foreach($services as $service)
                        <tr>
                            <td>{{ $service->name }}</td>
                            <td>{{ $service->phone }}</td>
                            <td>{{ $service->email }}</td>
                            <td>{{ $service->duration }}</td>
                            <td>{{ $service->interpreter_duration }}</td>
                            <td>{{ optional($service->serviceProvider)->name }}</td>

                            <td>

                                <form method="POST" action="{!! route('services.service.destroy', $service->id) !!}" accept-charset="UTF-8">
                                <input name="_method" value="DELETE" type="hidden">
                                {{ csrf_field() }}

                                    <div class="btn-group btn-group-xs pull-right" role="group">
                                        <a href="{{ route('services.service.show', $service->id ) }}" class="btn btn-info" title="Show Service">
                                            <span class="glyphicon glyphicon-open" aria-hidden="true"></span>
                                        </a>
                                        <a href="{{ route('services.service.edit', $service->id ) }}" class="btn btn-primary" title="Edit Service">
                                            <span class="glyphicon glyphicon-pencil" aria-hidden="true"></span>
                                        </a>

                                        <button type="submit" class="btn btn-danger" title="Delete Service" onclick="return confirm(&quot;Delete Service?&quot;)">
                                            <span class="glyphicon glyphicon-trash" aria-hidden="true"></span>
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
            {!! $services->render() !!}
        </div>

        @endif

    </div>
@endsection