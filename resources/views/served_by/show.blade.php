@extends('layouts.app')

@section('content')

<div class="panel panel-default">
    <div class="panel-heading clearfix">

        <span class="pull-left">
            <h4 class="mt-5 mb-5">{{ isset($title) ? $title : 'Served By' }}</h4>
        </span>

        <div class="pull-right">

            <form method="POST" action="{!! route('served_by.served_by.destroy', $servedBy->id) !!}" accept-charset="UTF-8">
            <input name="_method" value="DELETE" type="hidden">
            {{ csrf_field() }}
                <div class="btn-group btn-group-sm" role="group">
                    <a href="{{ route('served_by.served_by.index') }}" class="btn btn-primary" title="Show All Served By">
                        <span class="glyphicon glyphicon-th-list" aria-hidden="true"></span>
                    </a>

                    <a href="{{ route('served_by.served_by.create') }}" class="btn btn-success" title="Create New Served By">
                        <span class="glyphicon glyphicon-plus" aria-hidden="true"></span>
                    </a>

                    <a href="{{ route('served_by.served_by.edit', $servedBy->id ) }}" class="btn btn-primary" title="Edit Served By">
                        <span class="glyphicon glyphicon-pencil" aria-hidden="true"></span>
                    </a>

                    <button type="submit" class="btn btn-danger" title="Delete Served By" onclick="return confirm(&quot;Delete Served By??&quot;)">
                        <span class="glyphicon glyphicon-trash" aria-hidden="true"></span>
                    </button>
                </div>
            </form>

        </div>

    </div>

    <div class="panel-body">
        <dl class="dl-horizontal">
            <dt>Service</dt>
            <dd>{{ optional($servedBy->service)->name }}</dd>
            <dt>Resource</dt>
            <dd>{{ optional($servedBy->resource)->name }}</dd>

        </dl>

    </div>
</div>

@endsection