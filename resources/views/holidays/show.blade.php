@extends('layouts.app')

@section('content')

<div class="panel panel-default">
    <div class="panel-heading clearfix">

        <span class="pull-left">
            <h4 class="mt-5 mb-5">{{ isset($title) ? $title : 'Holiday' }}</h4>
        </span>

        <div class="pull-right">

            <form method="POST" action="{!! route('holidays.holiday.destroy', $holiday->id) !!}" accept-charset="UTF-8">
            <input name="_method" value="DELETE" type="hidden">
            {{ csrf_field() }}
                <div class="btn-group btn-group-sm" role="group">
                    <a href="{{ route('holidays.holiday.index') }}" class="btn btn-primary" title="Show All Holiday">
                        <span class="glyphicon glyphicon-th-list" aria-hidden="true"></span>
                    </a>

                    <a href="{{ route('holidays.holiday.create') }}" class="btn btn-success" title="Create New Holiday">
                        <span class="glyphicon glyphicon-plus" aria-hidden="true"></span>
                    </a>
                    
                    <a href="{{ route('holidays.holiday.edit', $holiday->id ) }}" class="btn btn-primary" title="Edit Holiday">
                        <span class="glyphicon glyphicon-pencil" aria-hidden="true"></span>
                    </a>

                    <button type="submit" class="btn btn-danger" title="Delete Holiday" onclick="return confirm(&quot;Delete Holiday??&quot;)">
                        <span class="glyphicon glyphicon-trash" aria-hidden="true"></span>
                    </button>
                </div>
            </form>

        </div>

    </div>

    <div class="panel-body">
        <dl class="dl-horizontal">
            <dt>Date</dt>
            <dd>{{ $holiday->date }}</dd>
            <dt>Description</dt>
            <dd>{{ $holiday->description }}</dd>

        </dl>

    </div>
</div>

@endsection