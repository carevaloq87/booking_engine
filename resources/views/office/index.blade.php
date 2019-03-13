@extends('layouts.booking_engine.master')

@section('sub_title')
    {{ auth()->user()->serviceProvider->name }} Home
@endsection

@section('buttons')
    <a href="{{ route('services.service.create') }}" class="btn btn-success btn-sm" title="Create New Service" role="button">
        Add Service
    </a>
    <a href="{{ route('resources.resource.create') }}" class="btn btn-success btn-sm" title="Create New Resource">
        Add Resource
    </a>
@endsection

@section('content')

    <div class="panel panel-default">
        <div class="panel-body panel-body-with-table">
            <div class="table-responsive">
                <div id="dTables">
                    <data-table
                        fetch-url="{{ route('services.list') }}"
                        show-url="{{ route('services.service.show', '' ) }}"
                        edit-url="{{ route('services.service.edit', '' ) }}"
                        delete-url="{!! route('services.service.destroy', '') !!}"
                        title="Services"
                        per-page="7"
                        :columns="[
                                    'id',
                                    'name',
                                    'duration',
                                    'interpreter_duration',
                                    @if(auth()->user()->isAdmin())
                                    'service_provider'
                                    @endif
                                ]"
                    ></data-table>
                    <hr>
                    <data-table
                        fetch-url="{{ route('resources.list') }}"
                        show-url="{{ route('resources.resource.show', '' ) }}"
                        edit-url=""
                        delete-url="{!! route('resources.resource.destroy', '') !!}"
                        title="Resources"
                        per-page="5"
                        :columns="[
                                    'id',
                                    'name',
                                    'phone',
                                    'email',
                                    @if(auth()->user()->isAdmin())
                                    'service_provider'
                                    @endif
                                ]"
                    ></data-table>
                </div>
            </div>
        </div>
    </div>

@endsection

@section('scripts')
    <script src="{{ asset('js/tables.js') }}"></script>
@endsection
