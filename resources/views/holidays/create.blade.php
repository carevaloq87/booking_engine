@extends('layouts.booking_engine.master')

@section('sub_title')
    Create New Holiday
@endsection

@section('buttons')
    <div class="btn-group btn-group-sm" role="group">
        <a href="{{ route('holidays.holiday.index') }}" class="btn btn-primary" title="Show All Holiday">
            <i class="fa fa-list-ul"></i>
        </a>
    </div>
@endsection

@section('content')

    <div class="panel panel-default">

        <div class="panel-body">

            <form method="POST" action="{{ route('holidays.holiday.store') }}" accept-charset="UTF-8" id="holiday_form" name="create_holiday_form" class="form-horizontal">
            {{ csrf_field() }}
            @include ('holidays.form', [
                                        'holiday' => null,
                                    ])

                <div class="form-group">
                    <div class="col-md-offset-2 col-md-10">
                        <input class="btn btn-primary" type="submit" value="Add">
                    </div>
                </div>

            </form>

        </div>
    </div>

@endsection


