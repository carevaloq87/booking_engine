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

<dl class="dl-horizontal">
    <dt>Name</dt>
    <dd>{{ $serviceProvider->name }}</dd>
    <dt>Contact Name</dt>
    <dd>{{ $serviceProvider->contact_name }}</dd>
    <dt>Phone</dt>
    <dd>{{ $serviceProvider->phone }}</dd>
    <dt>Email</dt>
    <dd>{{ $serviceProvider->email }}</dd>

</dl>

@endsection