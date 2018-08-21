@extends('layouts.app')

@section('content')

    @if(Session::has('success_message'))
        <div class="alert alert-success">
            <span class="glyphicon glyphicon-ok"></span>
            {!! session('success_message') !!}

            <button type="button" class="close" data-dismiss="alert" aria-label="close">
                <span aria-hidden="true">&times;</span>
            </button>

        </div>
    @endif

    <div class="panel panel-default">

        <div class="panel-heading clearfix">

            <div class="pull-left">
                <h4 class="mt-5 mb-5">Service Providers</h4>
            </div>

            <div class="btn-group btn-group-sm pull-right" role="group">
                <a href="{{ route('service_providers.service_provider.create') }}" class="btn btn-success" title="Create New Service Provider">
                    <span class="glyphicon glyphicon-plus" aria-hidden="true"></span>
                </a>
            </div>

        </div>
        
        @if(count($serviceProviders) == 0)
            <div class="panel-body text-center">
                <h4>No Service Providers Available!</h4>
            </div>
        @else
        <div class="panel-body panel-body-with-table">
            <div class="table-responsive">

                <table class="table table-striped ">
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Contact Name</th>
                            <th>Phone</th>
                            <th>Email</th>

                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                    @foreach($serviceProviders as $serviceProvider)
                        <tr>
                            <td>{{ $serviceProvider->name }}</td>
                            <td>{{ $serviceProvider->contact_name }}</td>
                            <td>{{ $serviceProvider->phone }}</td>
                            <td>{{ $serviceProvider->email }}</td>

                            <td>

                                <form method="POST" action="{!! route('service_providers.service_provider.destroy', $serviceProvider->id) !!}" accept-charset="UTF-8">
                                <input name="_method" value="DELETE" type="hidden">
                                {{ csrf_field() }}

                                    <div class="btn-group btn-group-xs pull-right" role="group">
                                        <a href="{{ route('service_providers.service_provider.show', $serviceProvider->id ) }}" class="btn btn-info" title="Show Service Provider">
                                            <span class="glyphicon glyphicon-open" aria-hidden="true"></span>
                                        </a>
                                        <a href="{{ route('service_providers.service_provider.edit', $serviceProvider->id ) }}" class="btn btn-primary" title="Edit Service Provider">
                                            <span class="glyphicon glyphicon-pencil" aria-hidden="true"></span>
                                        </a>

                                        <button type="submit" class="btn btn-danger" title="Delete Service Provider" onclick="return confirm(&quot;Delete Service Provider?&quot;)">
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
            {!! $serviceProviders->render() !!}
        </div>
        
        @endif
    
    </div>
@endsection