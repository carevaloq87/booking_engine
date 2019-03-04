@extends('layouts.booking_engine.master')

@section('sub_title')
    Resources
@endsection

@section('buttons')
    <a href="{{ route('resources.resource.create') }}" class="btn btn-success btn-sm" title="Create New Resource">
        Add Resource
    </a>
@endsection


@section('content')

    <div class="panel panel-default">

        @if(count($resources) == 0)
            <div class="panel-body text-center">
                <h4>No Resources Available!</h4>
            </div>
        @else
        <div class="panel-body panel-body-with-table">
            <div class="table-responsive">
                <div id="dTables">
                    <data-table
                        fetch-url="{{ route('resources.list') }}"
                        show-url="{{ route('resources.resource.show', '' ) }}"
                        edit-url="{{ route('resources.resource.edit', '' ) }}"
                        delete-url="{!! route('resources.resource.destroy', '') !!}"
                        :columns="['id','name', 'phone', 'email' , 'service_provider']"
                    ></data-table>
                </div>
            </div>
        </div>

        @endif

    </div>
@endsection

@section('scripts')
    <script src="{{ asset('js/tables.js') }}"></script>
@endsection