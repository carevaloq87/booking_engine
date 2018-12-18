@extends('layouts.booking_engine.master')

@section('sub_title')
    {{ isset($resource->name) ? $resource->name : 'Resource' }}
@endsection

@section('buttons')
    <form method="POST" action="{!! route('resources.resource.destroy', $resource->id) !!}" accept-charset="UTF-8">
        <input name="_method" value="DELETE" type="hidden">
        {{ csrf_field() }}
        <div class="btn-group btn-group-sm" role="group">
            <a href="{{ route('resources.resource.index') }}" class="btn btn-primary" title="Show All Resource">
                <i class="fa fa-list-ul"></i>
            </a>

            <a href="{{ route('resources.resource.create') }}" class="btn btn-success" title="Create New Resource">
                <i class="fa fa-plus"></i>
            </a>

            <a href="{{ route('resources.resource.edit', $resource->id ) }}" class="btn btn-primary" title="Edit Resource">
                <i class="fa fa-pencil-alt"></i>
            </a>

            <button type="submit" class="btn btn-danger" title="Delete Resource" onclick="return confirm(&quot;Delete Resource??&quot;)">
                <i class="fa fa-trash-alt"></i>
            </button>
        </div>
    </form>
@endsection

@section('content')
    <div class="container" id="booking_engine_resource">
        <div class="row border-bottom border-top pt-4 mb-4 pb-4">
            <div class="col-sm-3">
                <h5>Resource Setting</h5>
            </div>
            <div class="col-sm-2">
                <div class="col-sm-12 text-center">
                    <a v-on:click="openCalendar({{ $resource->id }})" href="#"><i class="fa fa-calendar"></i></a>
                </div>
                <div class="col-sm-12 text-center">
                    <a v-on:click="openCalendar({{ $resource->id }})" href="#">Days</a>
                </div>
            </div>

            <div class="col-sm-2">
                <div class="col-sm-12 text-center">
                    <a v-on:click="openSchedule({{ $resource->id }})" href="#"><i class="fa fa-clock"></i></a>
                </div>
                <div class="col-sm-12 text-center">
                    <a v-on:click="openSchedule({{ $resource->id }})" href="#">Hours</a>
                </div>
            </div>

            <div class="col-sm-2">
                <div class="col-sm-12 text-center">
                    <a v-on:click="openAdhoc({{ $resource->id }})" href="#"><i class="fa fa-cogs"></i></a>
                </div>
                <div class="col-sm-12 text-center">
                    <a v-on:click="openAdhoc({{ $resource->id }})" href="#">Ad hoc</a>
                </div>
            </div>
        </div>
        <div class="row border-bottom mb-4 pb-4">
            <div class="col-sm-12 adhoc-appointments">
                <h5>Future Adhocs</h5>
            </div>

            <div class="col-sm">
                <selected-adhoc :resource="{{ $resource->id }}"></selected-adhoc>
            </div>
        </div>

        @include("resources.modal.days")
        @include("resources.modal.hours")
        @include("resources.modal.adhoc")
    </div>
@endsection

@section('scripts')
    <script src="/js/booking_engine_resource.js"></script>
@endsection