@extends('layouts.booking_engine.master')

@section('sub_title')
    Services
@endsection

@section('buttons')
    <a href="{{ route('services.service.create') }}" class="btn btn-success btn-sm" title="Create New Service" role="button">
        Add Service
    </a>
@endsection

@section('content')

    <div class="panel panel-default">

        @if(count($services) == 0)
            <div class="panel-body text-center">
                <h4>No Services Available!</h4>
            </div>
        @else
        <div class="panel-body panel-body-with-table">
            <div class="table-responsive">
                <div id="dTables">
                    <data-table
                        fetch-url="{{ route('services.list') }}"
                        show-url="{{ route('services.service.show', '' ) }}"
                        edit-url="{{ route('services.service.edit', '' ) }}"
                        delete-url="{!! route('services.service.destroy', '') !!}"
                        :columns="['name', 'duration', 'interpreter_duration' , 'service_provider','created_at']"
                    ></data-table>
                </div>
            </div>
        </div>

        <div class="panel-footer">
            {!! $services->render() !!}
        </div>

        @endif

    </div>
@endsection

@section('scripts')
    <script src="{{ asset('js/tables.js') }}"></script>
@endsection
