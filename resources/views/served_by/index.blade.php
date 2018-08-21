@extends('layouts.app')

@section('content')

    @if(Session::has('success_message'))
        <div class="alert alert-success">
            <span class="glyphicon glyphicon-ok"></span>
            {!! session('success_message') !!}

            <button type="button" class="close" data-dismiss="alert" aria-label="close">
                <span aria-hidden="true">&times;</span>
            </button>

        </div>
    @endif

    <div class="panel panel-default">

        <div class="panel-heading clearfix">

            <div class="pull-left">
                <h4 class="mt-5 mb-5">Allocate Resources to Services</h4>
            </div>

            <div class="btn-group btn-group-sm pull-right" role="group">
                <a href="{{ route('served_by.served_by.create') }}" class="btn btn-success" title="Create New Served By">
                    <span class="glyphicon glyphicon-plus" aria-hidden="true"></span>
                </a>
            </div>

        </div>

        @if(count($servedBy) == 0)
            <div class="panel-body text-center">
                <h4>No Resources Allocated to Services Available!</h4>
            </div>
        @else
        <div class="panel-body panel-body-with-table">
            <div class="table-responsive">

                <table class="table table-striped ">
                    <thead>
                        <tr>
                            <th>Service</th>
                            <th>Resource</th>

                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                    @foreach($servedBy as $servedBy)
                        <tr>
                            <td>{{ optional($servedBy->service)->name }}</td>
                            <td>{{ optional($servedBy->resource)->name }}</td>

                            <td>

                                <form method="POST" action="{!! route('served_by.served_by.destroy', $servedBy->id) !!}" accept-charset="UTF-8">
                                <input name="_method" value="DELETE" type="hidden">
                                {{ csrf_field() }}

                                    <div class="btn-group btn-group-xs pull-right" role="group">
                                        <a href="{{ route('served_by.served_by.show', $servedBy->id ) }}" class="btn btn-info" title="Show Served By">
                                            <span class="glyphicon glyphicon-open" aria-hidden="true"></span>
                                        </a>
                                        <a href="{{ route('served_by.served_by.edit', $servedBy->id ) }}" class="btn btn-primary" title="Edit Served By">
                                            <span class="glyphicon glyphicon-pencil" aria-hidden="true"></span>
                                        </a>

                                        <button type="submit" class="btn btn-danger" title="Delete Served By" onclick="return confirm(&quot;Delete Served By?&quot;)">
                                            <span class="glyphicon glyphicon-trash" aria-hidden="true"></span>
                                        </button>
                                    </div>

                                </form>

                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>

            </div>
        </div>

        <div class="panel-footer">
            
        </div>

        @endif

    </div>
@endsection