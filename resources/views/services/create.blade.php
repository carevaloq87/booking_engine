@extends('layouts.booking_engine.master')

@section('sub_title')
    Create New Service
@endsection

@section('buttons')
    <div class="btn-group btn-group-sm" role="group">
        <a href="{{ route('office.index') }}" class="btn btn-primary" title="Show All Service">
            <i class="fa fa-list-ul"></i>
        </a>
    </div>
@endsection

@section('content')

    <div class="panel panel-default">

        <div class="panel-body">
        
            <form method="POST" action="{{ route('services.service.store') }}" accept-charset="UTF-8" id="create_service_form" name="create_service_form" class="form-horizontal">
            {{ csrf_field() }}
                <div class="d-flex flex-column">
                @include ('services.form', [
                                            'service' => null,
                                            ])
                </div>
                <div class="form-group">
                    <div class="col-md-offset-2 col-md-10">
                        <input class="btn btn-primary" type="submit" value="Add">
                    </div>
                </div>

            </form>

        </div>
    </div>

@endsection


