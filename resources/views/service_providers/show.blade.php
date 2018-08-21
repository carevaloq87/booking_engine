@extends('layouts.app')

@section('content')

<div class="panel panel-default">
    <div class="panel-heading clearfix">

        <span class="pull-left">
            <h4 class="mt-5 mb-5">{{ isset($serviceProvider->name) ? $serviceProvider->name : 'Service Provider' }}</h4>
        </span>

        <div class="pull-right">

            <form method="POST" action="{!! route('service_providers.service_provider.destroy', $serviceProvider->id) !!}" accept-charset="UTF-8">
            <input name="_method" value="DELETE" type="hidden">
            {{ csrf_field() }}
                <div class="btn-group btn-group-sm" role="group">
                    <a href="{{ route('service_providers.service_provider.index') }}" class="btn btn-primary" title="Show All Service Provider">
                        <span class="glyphicon glyphicon-th-list" aria-hidden="true"></span>
                    </a>

                    <a href="{{ route('service_providers.service_provider.create') }}" class="btn btn-success" title="Create New Service Provider">
                        <span class="glyphicon glyphicon-plus" aria-hidden="true"></span>
                    </a>
                    
                    <a href="{{ route('service_providers.service_provider.edit', $serviceProvider->id ) }}" class="btn btn-primary" title="Edit Service Provider">
                        <span class="glyphicon glyphicon-pencil" aria-hidden="true"></span>
                    </a>

                    <button type="submit" class="btn btn-danger" title="Delete Service Provider" onclick="return confirm(&quot;Delete Service Provider??&quot;)">
                        <span class="glyphicon glyphicon-trash" aria-hidden="true"></span>
                    </button>
                </div>
            </form>

        </div>

    </div>

    <div class="panel-body">
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

    </div>
</div>

@endsection