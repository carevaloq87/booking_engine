@extends('layouts.booking_engine.master')

@section('sub_title')
    Create New User
@endsection

@section('buttons')
    <a href="{{ route('users.index') }}" class="btn btn-sm btn-primary" title="Show All User">
        <i class="fa fa-list"></i>
    </a>
@endsection

@section('content')

    <div class="panel panel-default">

        <div class="panel-body">

            <form method="POST" action="{{ route('users.store') }}" accept-charset="UTF-8" id="create_user_form" name="create_user_form" class="form-horizontal">
            {{ csrf_field() }}
            @include ('users.form', [ 'user' => null ])

                <div class="form-group">
                    <div class="col-md-offset-2 col-md-10">
                        <input class="btn btn-primary" type="submit" value="Add">
                    </div>
                </div>

            </form>

        </div>
    </div>

@endsection


