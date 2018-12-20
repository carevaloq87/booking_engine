@extends('layouts.booking_engine.master')

@section('sub_title')
    {{ isset($role->name) ? $role->name : 'Role' }}
@endsection

@section('buttons')

    <form method="POST" action="{!! route('roles.destroy', $role->id) !!}" accept-charset="UTF-8">
        <input name="_method" value="DELETE" type="hidden">
        {{ csrf_field() }}
        <div class="btn-group btn-group-sm" role="group">
            <a href="{{ route('roles.index') }}" class="btn btn-primary" title="Show All Role">
                <i class="fa fa-list-ul"></i>
            </a>

            <a href="{{ route('roles.create') }}" class="btn btn-success" title="Create New Role">
                <i class="fa fa-plus"></i>
            </a>

            <a href="{{ route('roles.edit', $role->id ) }}" class="btn btn-primary" title="Edit Role">
                <i class="fa fa-pencil-alt"></i>
            </a>

            <button type="submit" class="btn btn-danger" title="Delete Role" onclick="return confirm(&quot;Delete Role??&quot;)">
                <i class="fa fa-trash-alt"></i>
            </button>
        </div>
    </form>
@endsection

@section('content')

    <div class="col-12 pb-4">
        <h5>Name</h5>
        <span class="col-12">{{ $role->name }}</span>
    </div>

    <div class="col-12">
        <h5>Permissions</h5>
        <div class="col-11 py-2">
            @if(!empty($rolePermissions))
                @foreach($rolePermissions as $permission)
                    <span class="m-badge m-badge--success m-badge--wide mb-2">{{ $permission->name }},</span>
                @endforeach
            @endif
        </div>
    </div>

@endsection