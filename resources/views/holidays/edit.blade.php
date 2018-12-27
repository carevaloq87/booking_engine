@extends('layouts.booking_engine.master')

@section('sub_title')
    {{ !empty($holiday->description) ? $holiday->description : 'Holiday' }}
@endsection

@section('buttons')
    <div class="btn-group btn-group-sm" role="group">
        <a href="{{ route('holidays.holiday.index') }}" class="btn btn-primary" title="Show All Holiday">
            <i class="fa fa-list-ul"></i>
        </a>

        <a href="{{ route('holidays.holiday.create') }}" class="btn btn-success" title="Create New Holiday">
            <i class="fa fa-plus"></i>
        </a>
    </div>
@endsection

@section('content')

    <div class="panel panel-default">

        <div class="panel-body">

            <form method="POST" action="{{ route('holidays.holiday.update', $holiday->id) }}" id="holiday_form" name="edit_holiday_form" accept-charset="UTF-8" class="form-horizontal">
            {{ csrf_field() }}
            <input name="_method" type="hidden" value="PUT">
            @include ('holidays.form', [
                                        'holiday' => $holiday,
                                    ])

                <div class="form-group">
                    <div class="col-md-offset-2 col-md-10">
                        <input class="btn btn-primary" type="submit" value="Update">
                    </div>
                </div>
            </form>

        </div>
    </div>

@endsection