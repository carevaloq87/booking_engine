@extends('layouts.app')

@section('content')

<div class="panel panel-default">
    <div class="panel-heading clearfix">

        <span class="pull-left">
            <h4 class="mt-5 mb-5">{{ isset($user->name) ? $user->name : 'User' }}</h4>
        </span>

        <div class="pull-right">

            <form method="POST" action="{!! route('users.destroy', $user->id) !!}" accept-charset="UTF-8">
            <input name="_method" value="DELETE" type="hidden">
            {{ csrf_field() }}
                <div class="btn-group btn-group-sm" role="group">
                    <a href="{{ route('users.index') }}" class="btn btn-primary" title="Show All User">
                        <span class="glyphicon glyphicon-th-list" aria-hidden="true"></span>
                    </a>

                    <a href="{{ route('users.create') }}" class="btn btn-success" title="Create New User">
                        <span class="glyphicon glyphicon-plus" aria-hidden="true"></span>
                    </a>

                    <a href="{{ route('users.edit', $user->id ) }}" class="btn btn-primary" title="Edit User">
                        <span class="glyphicon glyphicon-pencil" aria-hidden="true"></span>
                    </a>

                    <button type="submit" class="btn btn-danger" title="Delete User" onclick="return confirm(&quot;Delete User??&quot;)">
                        <span class="glyphicon glyphicon-trash" aria-hidden="true"></span>
                    </button>
                </div>
            </form>

        </div>

    </div>

    <div class="panel-body">
        <dl class="dl-horizontal">
            <dt>Name</dt>
            <dd>{{ $user->name }}</dd>
            <dt>Email</dt>
            <dd>{{ $user->email }}</dd>
            <dt>Role</dt>
            <dd>
            @if(!empty($user->getRoleNames()))
                @foreach($user->getRoleNames() as $role_name)
                    <label class="badge badge-success">{{ $role_name }}</label>
                @endforeach
            @endif
            </dd>
            <dt>Created At</dt>
            <dd>{{ $user->created_at }}</dd>
            <dt>Updated At</dt>
            <dd>{{ $user->updated_at }}</dd>

        </dl>

    </div>
</div>

@endsection