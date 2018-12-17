@extends('layouts.booking_engine.master')

@section('sub_title')
    {{ !empty($role->name) ? $role->name : 'Role' }}
@endsection

@section('buttons')
    <a href="{{ route('roles.index') }}" class="btn btn-primary btn-sm" title="Show all roles" role="button">
        <i class="fa fa-list-ul"></i>
    </a>
    <a href="{{ route('roles.create') }}" class="btn btn-success btn-sm" title="Create New Role" role="button">
        <i class="fa fa-plus"></i>
    </a>
@endsection

@section('content')

    {!! Form::model($role, ['method' => 'PUT','route' => ['roles.update', $role->id]]) !!}
    <div class="row">
        <div class="col-sm-12">
            <div class="form-group">
                <strong>Name:</strong>
                {!! Form::text('name', null, array('placeholder' => 'Name','class' => 'form-control')) !!}
            </div>
        </div>
        <div class="col-sm-12">
            <div class="form-group">
                <strong>Permission:</strong>
                <br/>
                @foreach($permission as $value)
                    <label>{{ Form::checkbox('permission[]', $value->id, in_array($value->id, $rolePermissions) ? true : false, array('class' => 'name')) }}
                    {{ $value->name }}</label>
                <br/>
                @endforeach
            </div>
        </div>
        <div class="col-sm-12">
            <button type="submit" class="btn btn-primary">Submit</button>
        </div>
    </div>
    {!! Form::close() !!}

@endsection