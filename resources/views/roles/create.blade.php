@extends('layouts.booking_engine.master')

@section('sub_title')
    Create New Role
@endsection

@section('buttons')
    <a href="{{ route('roles.index') }}" class="btn btn-success btn-sm" title="Show Roles" role="button">
        <i class="fa fa-list"></i>
    </a>
@endsection

@section('content')

    @include ('roles.form')

@endsection


