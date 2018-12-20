@extends('layouts.booking_engine.master')

@section('sub_title')
    {{ isset($user->name) ? $user->name : 'User' }}
@endsection

@section('buttons')
    <form method="POST" action="{!! route('users.destroy', $user->id) !!}" accept-charset="UTF-8">
        <input name="_method" value="DELETE" type="hidden">
        {{ csrf_field() }}
        <div class="btn-group btn-group-sm" role="group">
            <a href="{{ route('users.index') }}" class="btn btn-primary" title="Show All User">
                <i class="fa fa-list-ul"></i>
            </a>

            <a href="{{ route('users.create') }}" class="btn btn-success" title="Create New User">
                <i class="fa fa-plus"></i>
            </a>

            <a href="{{ route('users.edit', $user->id ) }}" class="btn btn-primary" title="Edit User">
                <i class="fa fa-pencil-alt"></i>
            </a>

            <button type="submit" class="btn btn-danger" title="Delete User" onclick="return confirm(&quot;Delete User??&quot;)">
                <i class="fa fa-trash-alt"></i>
            </button>
        </div>
    </form>
@endsection

@section('content')

    <div class="col-12 pb-4">
        <h5>Name</h5>
        <span class="col-12">{{ $user->name }}</span>
    </div>

    <div class="col-12 pb-4">
        <h5>Email</h5>
        <span class="col-12">{{ $user->email }}</span>
    </div>

    <div class="col-12 pb-4">
        <h5>Role</h5>
        <span class="col-12">
            @if(!empty($user->getRoleNames()))
                @foreach($user->getRoleNames() as $role_name)
                    <label class="badge badge-success">{{ $role_name }}</label>
                @endforeach
            @endif
            </span>
    </div>

    <div class="col-12">
        <h5>Service Provider</h5>
        <span class="col-12">{{ $user->serviceProvider ? $user->serviceProvider->name : 'N/P'}}</span>
    </div>

@endsection