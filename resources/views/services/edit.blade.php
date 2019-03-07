@extends('layouts.booking_engine.master')

@section('sub_title')
    {{ !empty($service->name) ? $service->name : 'Service' }}
@endsection

@section('buttons')
    <div class="btn-group btn-group-sm" role="group">
        <a href="{{ route('office.index') }}" class="btn btn-primary" title="Show All Service">
            <i class="fa fa-list-ul"></i>
        </a>
        <a href="{{ route('services.service.create') }}" class="btn btn-success" title="Create New Service">
            <i class="fa fa-plus"></i>
        </a>
    </div>
@endsection

@section('content')

    <div class="panel panel-default">

        <div class="panel-body">

            <form method="POST" action="{{ route('services.service.update', $service->id) }}" id="edit_service_form" name="edit_service_form" accept-charset="UTF-8" class="form-horizontal">
            {{ csrf_field() }}
            <input name="_method" type="hidden" value="PUT">
            <div class="d-flex flex-column">
                @include ('services.form', [
                                            'service' => $service,
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