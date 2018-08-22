@extends('layouts.app')

@section('content')

<div class="panel panel-default">
    <div class="panel-heading clearfix">

        <span class="pull-left">
            <h4 class="mt-5 mb-5">{{ isset($bookingStatus->name) ? $bookingStatus->name : 'Booking Status' }}</h4>
        </span>

        <div class="pull-right">

            <form method="POST" action="{!! route('booking_status.booking_status.destroy', $bookingStatus->id) !!}" accept-charset="UTF-8">
            <input name="_method" value="DELETE" type="hidden">
            {{ csrf_field() }}
                <div class="btn-group btn-group-sm" role="group">
                    <a href="{{ route('booking_status.booking_status.index') }}" class="btn btn-primary" title="Show All Booking Status">
                        <span class="glyphicon glyphicon-th-list" aria-hidden="true"></span>
                    </a>

                    <a href="{{ route('booking_status.booking_status.create') }}" class="btn btn-success" title="Create New Booking Status">
                        <span class="glyphicon glyphicon-plus" aria-hidden="true"></span>
                    </a>
                    
                    <a href="{{ route('booking_status.booking_status.edit', $bookingStatus->id ) }}" class="btn btn-primary" title="Edit Booking Status">
                        <span class="glyphicon glyphicon-pencil" aria-hidden="true"></span>
                    </a>

                    <button type="submit" class="btn btn-danger" title="Delete Booking Status" onclick="return confirm(&quot;Delete Booking Status??&quot;)">
                        <span class="glyphicon glyphicon-trash" aria-hidden="true"></span>
                    </button>
                </div>
            </form>

        </div>

    </div>

    <div class="panel-body">
        <dl class="dl-horizontal">
            <dt>Name</dt>
            <dd>{{ $bookingStatus->name }}</dd>
            <dt>Description</dt>
            <dd>{{ $bookingStatus->description }}</dd>

        </dl>

    </div>
</div>

@endsection