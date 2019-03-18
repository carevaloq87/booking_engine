@extends('layouts.booking_engine.master')

@section('sub_title')
    Holidays 
    <br>
    <small> Click <a href="https://www.business.vic.gov.au/victorian-public-holidays-and-daylight-saving/victorian-public-holidays" target="_blank"> here</a> to consult the official dates</small>
@endsection

@section('buttons')
    <a href="{{ route('holidays.holiday.create') }}" class="btn btn-success btn-sm" title="Create New Holiday" role="button">
        Add Holiday
    </a>
@endsection

@section('content')

    <div class="panel panel-default">

        @if(count($holidays) == 0)
            <div class="panel-body text-center">
                <h4>No Holidays Available!</h4>
            </div>
        @else
        <div class="panel-body panel-body-with-table">
            <div class="table-responsive">

                <table class="table table-striped ">
                    <thead>
                        <tr>
                            <th>Date</th>
                            <th>Description</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                    @foreach($holidays as $holiday)
                        <tr>
                            <td>{{ date('Y-m-d',strtotime($holiday->date)) }}</td>
                            <td>{{ $holiday->description }}</td>

                            <td>

                                <form method="POST" action="{!! route('holidays.holiday.destroy', $holiday->id) !!}" accept-charset="UTF-8">
                                <input name="_method" value="DELETE" type="hidden">
                                {{ csrf_field() }}

                                    <div class="btn-group btn-group-xs pull-right" role="group">
                                        <a href="{{ route('holidays.holiday.show', $holiday->id ) }}" class="btn btn-sm btn-info" title="Show Holiday">
                                            <i class="fa fa-calendar-alt"></i>
                                        </a>
                                        <a href="{{ route('holidays.holiday.edit', $holiday->id ) }}" class="btn btn-sm btn-primary" title="Edit Holiday">
                                            <i class="fa fa-pencil-alt"></i>
                                        </a>

                                        <button type="submit" class="btn btn-sm btn-danger" title="Delete Holiday" onclick="return confirm(&quot;Delete Holiday?&quot;)">
                                            <i class="fa fa-trash-alt"></i>
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
            {!! $holidays->render() !!}
        </div>

        @endif

    </div>
@endsection