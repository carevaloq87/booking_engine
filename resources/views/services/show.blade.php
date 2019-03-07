@extends('layouts.booking_engine.master')

@section('sub_title')
    {{ isset($service->name) ? $service->name : 'Service' }}
@endsection

@section('buttons')
    <form method="POST" action="{!! route('services.service.destroy', $service->id) !!}" accept-charset="UTF-8">
        <input name="_method" value="DELETE" type="hidden">
        {{ csrf_field() }}
        <div class="btn-group btn-group-sm" role="group">
            <a href="{{ route('office.index') }}" class="btn btn-primary" title="Show All Service">
                <i class="fa fa-list-ul"></i>
            </a>

            <a href="{{ route('services.service.create') }}" class="btn btn-success" title="Create New Service">
                <i class="fa fa-plus"></i>
            </a>

            <a href="{{ route('services.service.edit', $service->id ) }}" class="btn btn-primary" title="Edit Service">
                <i class="fa fa-pencil-alt"></i>
            </a>

            <button type="submit" class="btn btn-danger" title="Delete Service" onclick="return confirm(&quot;Delete Service??&quot;)">
                <i class="fa fa-trash-alt"></i>
            </button>
        </div>
    </form>
@endsection

@section('content')
    <div class="container" id="booking_engine">

        <div class="row border-bottom border-top pt-4 mb-4 pb-4">
            <div class="col-sm-3">
                <h5>Booking Setting</h5>
            </div>
            <div class="col-sm-2">
                <div class="col-sm-12 text-center">
                    <a v-on:click="openCalendar({{ $service->id }})" href="#"><i class="fa fa-calendar-alt"></i></a>
                </div>
                <div class="col-sm-12 text-center">
                    <a v-on:click="openCalendar({{ $service->id }})" href="#">Days</a>
                </div>
            </div>

            <div class="col-sm-2">
                <div class="col-sm-12 text-center">
                    <a v-on:click="openSchedule({{ $service->id }})" href="#"><i class="fa fa-clock"></i></a>
                </div>
                <div class="col-sm-12 text-center">
                    <a v-on:click="openSchedule({{ $service->id }})" href="#">Hours</a>
                </div>
            </div>

            <div class="col-sm-2">
                <div class="col-sm-12 text-center">
                    <a v-on:click="openAdhoc({{ $service->id }},{{ $service->duration }},{{ $service->interpreter_duration }})" href="#"><i class="fa fa-cogs"></i> </a>
                </div>
                <div class="col-sm-12 text-center">
                    <a v-on:click="openAdhoc({{ $service->id }},{{ $service->duration }},{{ $service->interpreter_duration }})" href="#">Ad hoc</a>
                </div>
            </div>
        </div>

        <div class="row border-bottom mb-4 pb-4">
            <div class="col-sm-12 adhoc-appointments">
                <h5>Future Adhocs</h5>
            </div>

            <div class="col-sm">
                <selected-adhoc :service="{{ $service->id }}"></selected-adhoc>
            </div>
        </div>
        <div class="row">
            <div class="col-sm">
                <div id="calendar">
                    <Calendar :sv_id="{{ $service->id }}"></Calendar>
                </div>
            </div>
        </div>

        @include("services.modal.days")
        @include("services.modal.hours")
        @include("services.modal.adhoc")
        <loading-modal></loading-modal>
    </div>
@endsection

@section('scripts')
    <script src="/js/booking_engine.js?id={{ str_random(6) }}"></script>
@endsection