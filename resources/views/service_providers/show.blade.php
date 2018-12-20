@extends('layouts.booking_engine.master')

@section('sub_title')
    {{ isset($serviceProvider->name) ? $serviceProvider->name : 'Service Provider' }}
@endsection

@section('buttons')
    <div class="btn-group btn-group-sm" role="group">
        <form method="POST" action="{!! route('service_providers.service_provider.destroy', $serviceProvider->id) !!}" accept-charset="UTF-8">
            <input name="_method" value="DELETE" type="hidden">
            {{ csrf_field() }}
            <div class="btn-group btn-group-sm" role="group">
                <a href="{{ route('service_providers.service_provider.index') }}" class="btn btn-primary" title="Show All Service Provider">
                    <i class="fa fa-list-ul"></i>
                </a>

                <a href="{{ route('service_providers.service_provider.create') }}" class="btn btn-success" title="Create New Service Provider">
                    <i class="fa fa-plus"></i>
                </a>

                <a href="{{ route('service_providers.service_provider.edit', $serviceProvider->id ) }}" class="btn btn-primary" title="Edit Service Provider">
                    <i class="fa fa-pencil-alt"></i>
                </a>

                <button type="submit" class="btn btn-danger" title="Delete Service Provider" onclick="return confirm(&quot;Delete Service Provider??&quot;)">
                    <i class="fa fa-trash-alt"></i>
                </button>
            </div>
        </form>
    </div>
@endsection

@section('content')

    <div class="col-12 pb-4">
        <h5>Name</h5>
        <span class="col-12">{{ $serviceProvider->name }}</span>
    </div>
    <div class="col-12 pb-4">
        <h5>Contact Name</h5>
        <span class="col-12">{{ $serviceProvider->contact_name }}</span>
    </div>
    <div class="col-12 pb-4">
        <h5>Phone</h5>
        <span class="col-12">{{ $serviceProvider->phone }}</span>
    </div>
    <div class="col-12 pb-4">
        <h5>Email</h5>
        <span class="col-12">{{ $serviceProvider->email }}</span>
    </div>

@endsection