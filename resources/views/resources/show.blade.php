@extends('layouts.app')

@section('content')

<div class="panel panel-default">
    <div class="panel-heading clearfix">

        <span class="pull-left">
            <h4 class="mt-5 mb-5">{{ isset($resource->name) ? $resource->name : 'Resource' }}</h4>
        </span>

        <div class="pull-right">

            <form method="POST" action="{!! route('resources.resource.destroy', $resource->id) !!}" accept-charset="UTF-8">
            <input name="_method" value="DELETE" type="hidden">
            {{ csrf_field() }}
                <div class="btn-group btn-group-sm" role="group">
                    <a href="{{ route('resources.resource.index') }}" class="btn btn-primary" title="Show All Resource">
                        <span class="glyphicon glyphicon-th-list" aria-hidden="true"></span>
                    </a>

                    <a href="{{ route('resources.resource.create') }}" class="btn btn-success" title="Create New Resource">
                        <span class="glyphicon glyphicon-plus" aria-hidden="true"></span>
                    </a>
                    
                    <a href="{{ route('resources.resource.edit', $resource->id ) }}" class="btn btn-primary" title="Edit Resource">
                        <span class="glyphicon glyphicon-pencil" aria-hidden="true"></span>
                    </a>

                    <button type="submit" class="btn btn-danger" title="Delete Resource" onclick="return confirm(&quot;Delete Resource??&quot;)">
                        <span class="glyphicon glyphicon-trash" aria-hidden="true"></span>
                    </button>
                </div>
            </form>

        </div>

    </div>

    <div class="panel-body">
        <dl class="dl-horizontal">
            <dt>Name</dt>
            <dd>{{ $resource->name }}</dd>
            <dt>Phone</dt>
            <dd>{{ $resource->phone }}</dd>
            <dt>Email</dt>
            <dd>{{ $resource->email }}</dd>
            <dt>Service Provider</dt>
            <dd>{{ optional($resource->serviceProvider)->name }}</dd>

        </dl>

    </div>
</div>

@endsection