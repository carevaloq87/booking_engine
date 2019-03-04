@extends('layouts.booking_engine.master')

@section('sub_title')
    {{ !empty($user->name) ? $user->name : 'User' }}
@endsection

@section('buttons')
    <a href="{{ route('users.index') }}" class="btn btn-sm btn-primary" title="Show All User">
        <i class="fa fa-list"></i>
    </a>

    <a href="{{ route('users.create') }}" class="btn btn-sm btn-success" title="Create New User">
        <i class="fa fa-plus"></i>
    </a>
@endsection

@section('content')

    <div class="panel panel-default">

        <div class="panel-body">

            <form method="POST" action="{{ route('users.update', $user->id) }}" id="edit_user_form" name="edit_user_form" accept-charset="UTF-8" class="form-horizontal">
            {{ csrf_field() }}
            <input name="_method" type="hidden" value="PUT">
            @include ('users.form', [
                                        'user' => $user,
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