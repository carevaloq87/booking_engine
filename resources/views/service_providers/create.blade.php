@extends('layouts.booking_engine.master')

@section('sub_title')
    Create New Service Provider
@endsection

@section('buttons')
    <div class="btn-group btn-group-sm" role="group">
        <a href="{{ route('service_providers.service_provider.index') }}" class="btn btn-primary" title="Show All Service Provider">
            <i class="fa fa-list-ul"></i>
        </a>
    </div>
@endsection

@section('content')

    <form method="POST" action="{{ route('service_providers.service_provider.store') }}" accept-charset="UTF-8" id="create_service_provider_form" name="create_service_provider_form" class="form-horizontal">
    {{ csrf_field() }}
    @include ('service_providers.form', [ 'serviceProvider' => null ])

        <div class="form-group">
            <div class="col-md-offset-2 col-md-10">
                <input class="btn btn-primary" type="submit" value="Add">
            </div>
        </div>

    </form>

@endsection


