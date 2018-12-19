@extends('layouts.booking_engine.master')

@section('sub_title')
    Create New Booking
@endsection

@section('content')

    <div class="panel panel-default">

        <div class="panel-body">

            <form method="POST" action="{{ route('bookings.booking.store') }}" accept-charset="UTF-8" id="create_booking_form" name="create_booking_form" class="form-horizontal">
            {{ csrf_field() }}

            @include ('bookings.form', [
                                        'bookingStatus' => null,
                                        ])

                <div class="form-group">
                    <div class="col-md-offset-2 col-md-10">
                        <input class="btn btn-primary" type="submit" value="Make Booking">
                    </div>
                </div>

            </form>

        </div>
    </div>

@endsection


