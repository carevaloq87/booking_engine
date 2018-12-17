@extends('layouts.booking_engine.master')

@section('sub_title')
    {{ !empty($serviceProvider->name) ? $serviceProvider->name : 'Service Provider' }}
@endsection

@section('buttons')
    <div class="btn-group btn-group-sm" role="group">
        <a href="{{ route('service_providers.service_provider.index') }}" class="btn btn-primary" title="Show All Service Provider">
            <i class="fa fa-list-ul"></i>
        </a>

        <a href="{{ route('service_providers.service_provider.create') }}" class="btn btn-success" title="Create New Service Provider">
            <i class="fa fa-plus"></i>
        </a>
    </div>
@endsection

@section('content')

    <form method="POST" action="{{ route('service_providers.service_provider.update', $serviceProvider->id) }}" id="edit_service_provider_form" name="edit_service_provider_form" accept-charset="UTF-8" class="form-horizontal">
    {{ csrf_field() }}
    <input name="_method" type="hidden" value="PUT">
    @include ('service_providers.form', [ 'serviceProvider' => $serviceProvider ])

        <div class="form-group">
            <div class="col-md-offset-2 col-md-10">
                <input class="btn btn-primary" type="submit" value="Update">
            </div>
        </div>
    </form>

@endsection