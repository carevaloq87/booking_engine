@extends('layouts.booking_engine.master')

@section('sub_title')
    Service Providers
@endsection

@section('buttons')
    <a href="{{ route('service_providers.service_provider.create') }}" class="btn btn-success btn-sm" title="Create New Service Provider" role="button">
        Add Service Providers
    </a>
@endsection

@section('content')

    <div class="panel panel-default">

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

                                    <div class="btn-group btn-group-xs pull-right list-table" role="group">
                                        <a href="{{ route('service_providers.service_provider.show', $serviceProvider->id ) }}" class="btn btn-sm btn-info" title="Show Service Provider">
                                            <i class="fa fa-list"></i>
                                        </a>
                                        <a href="{{ route('service_providers.service_provider.edit', $serviceProvider->id ) }}" class="btn btn-sm btn-primary" title="Edit Service Provider">
                                            <i class="fa fa-pencil-alt"></i>
                                        </a>

                                        <button type="submit"  dusk="delete-service-provider-{{$serviceProvider->id}}" class="btn btn-sm btn-danger" title="Delete Service Provider" onclick="return confirm(&quot;Delete Service Provider?&quot;)">
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
            {!! $serviceProviders->render() !!}
        </div>

        @endif

    </div>
@endsection