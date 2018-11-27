@extends('layouts.app')

@section('content')
    <div id="booking_engine">
        <div class="panel-group">
            <div class="panel panel-default">
                <div class="panel-heading clearfix">

                    <span class="pull-left">
                        <h4 class="mt-5 mb-5">{{ isset($service->name) ? $service->name : 'Service' }}</h4>
                    </span>

                    <div class="pull-right">

                        <form method="POST" action="{!! route('services.service.destroy', $service->id) !!}" accept-charset="UTF-8">
                        <input name="_method" value="DELETE" type="hidden">
                        {{ csrf_field() }}
                            <div class="btn-group btn-group-sm" role="group">
                                <a href="{{ route('services.service.index') }}" class="btn btn-primary" title="Show All Service">
                                    <span class="glyphicon glyphicon-th-list" aria-hidden="true"></span>
                                </a>

                                <a href="{{ route('services.service.create') }}" class="btn btn-success" title="Create New Service">
                                    <span class="glyphicon glyphicon-plus" aria-hidden="true"></span>
                                </a>

                                <a href="{{ route('services.service.edit', $service->id ) }}" class="btn btn-primary" title="Edit Service">
                                    <span class="glyphicon glyphicon-pencil" aria-hidden="true"></span>
                                </a>

                                <button type="submit" class="btn btn-danger" title="Delete Service" onclick="return confirm(&quot;Delete Service??&quot;)">
                                    <span class="glyphicon glyphicon-trash" aria-hidden="true"></span>
                                </button>
                            </div>
                        </form>

                    </div>

                </div>

                <div class="panel-body">
                    <dl class="dl-horizontal">
                        <dt>Name</dt>
                        <dd>{{ $service->name }}</dd>
                        <dt>Phone</dt>
                        <dd>{{ $service->phone }}</dd>
                        <dt>Email</dt>
                        <dd>{{ $service->email }}</dd>
                        <dt>Description</dt>
                        <dd>{{ $service->description }}</dd>
                        <dt>Duration</dt>
                        <dd>{{ $service->duration }}</dd>
                        <dt>Interpreter Duration</dt>
                        <dd>{{ $service->interpreter_duration }}</dd>
                        <dt>Service Provider</dt>
                        <dd>{{ optional($service->serviceProvider)->name }}</dd>

                    </dl>

                </div>
            </div>

            <div class="panel panel-default">
                <div class="panel-heading">
                    <h4 class="mt-5 mb-5">Booking Settings</h4>
                </div>
                <div class="panel-body">
                    <div class="col-xs-4">
                        <a v-on:click="openCalendar({{ $service->id }})" href="#"><small><i class="fa fa-calendar"></i> Days</small></a>
                    </div>
                    <div class="col-xs-4 border-left">
                        <a v-on:click="openSchedule({{ $service->id }})" href="#"><small><i class="fa fa-clock-o"></i> Hours</small></a>
                    </div>
                    <div class="col-xs-4 border-left">
                        <a v-on:click="openAdhoc({{ $service->id }})" href="#"><small><i class="fa fa-cog"></i> Ad hoc</small></a>
                    </div>
                </div>
            </div>

            <div class="panel panel-default">
                <div class="panel-heading">
                    <h4 class="mt-5 mb-5">Future Adhocs</h4>
                </div>
                <div class="panel-body">
                    <div class="col-xs-12">
                        <selected-adhoc :service="{{ $service->id }}"></selected-adhoc>
                    </div>
                </div>
            </div>

            <div class="panel panel-default">
                <div class="panel-heading">
                    <h4 class="mt-5 mb-5">Available Slots</h4>
                </div>
                <div class="panel-body">
                    <div class="col-xs-12">
                        <div id="calendar">
                            <Calendar :sv_id="{{ $service->id }}"></Calendar>
                        </div>
                    </div>
                </div>
            </div>

        </div>

        @include("services.modal.days")
        @include("services.modal.hours")
        @include("services.modal.adhoc")
    </div>
@endsection

@section('scripts')
    <script src="/js/booking_engine.js?id={{ str_random(6) }}"></script>
@endsection