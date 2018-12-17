@extends('layouts.booking_engine.master')

@section('sub_title')
    {{ !empty($resource->name) ? $resource->name : 'Resource' }}
@endsection

@section('buttons')
    <div class="btn-group btn-group-sm" role="group">
        <a href="{{ route('resources.resource.index') }}" class="btn btn-primary" title="Show All Service">
            <i class="fa fa-list-ul"></i>
        </a>
        <a href="{{ route('resources.resource.create') }}" class="btn btn-success" title="Create New Service">
            <i class="fa fa-plus"></i>
        </a>
    </div>
@endsection

@section('content')

    <div class="panel panel-default">

        <div class="panel-body">

            @if ($errors->any())
                <ul class="alert alert-danger">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            @endif

            <form method="POST" action="{{ route('resources.resource.update', $resource->id) }}" id="edit_resource_form" name="edit_resource_form" accept-charset="UTF-8" class="form-horizontal">
            {{ csrf_field() }}
            <input name="_method" type="hidden" value="PUT">
            <div class="d-flex flex-column">
            @include ('resources.form', [
                                        'resource' => $resource,
                                        ])
            </div>
                <div class="form-group">
                    <div class="col-md-offset-2 col-md-10">
                        <input class="btn btn-primary" type="submit" value="Update">
                    </div>
                </div>
            </form>

        </div>
    </div>

@endsection