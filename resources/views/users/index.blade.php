@extends('layouts.booking_engine.master')

@section('sub_title')
    Users
@endsection

@section('buttons')
    <a href="{{ route('users.create') }}" class="btn btn-success btn-sm" title="Create New User" role="button">
        Add User
    </a>
@endsection


@section('content')

    <div class="panel panel-default">

        @if(count($users) == 0)
            <div class="panel-body text-center">
                <h4>No Users Available!</h4>
            </div>
        @else
        <div class="panel-body panel-body-with-table">
            <div class="table-responsive">

                <table class="table table-striped ">
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Role</th>
                            <th>Service Provider</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                    @foreach($users as $user)
                        <tr>
                            <td>{{ $user->name }}</td>
                            <td>{{ $user->email }}</td>
                            <td>
                            @if(!empty($user->getRoleNames()))
                                @foreach($user->getRoleNames() as $v)
                                <label class="badge badge-success">{{ $v }}</label>
                                @endforeach
                            @endif
                            </td>
                            <td>{{ $user->serviceProvider ? $user->serviceProvider->name : '' }}</td>
                            <td>

                                <form method="POST" action="{!! route('users.destroy', $user->id) !!}" accept-charset="UTF-8">
                                <input name="_method" value="DELETE" type="hidden">
                                {{ csrf_field() }}

                                    <div class="btn-group btn-group-xs pull-right list-table" role="group">
                                        <a href="{{ route('users.show', $user->id ) }}" class="btn btn-sm btn-info" title="Show User">
                                            <i class="fa fa-user"></i>
                                        </a>
                                        <a href="{{ route('users.edit', $user->id ) }}" class="btn btn-sm btn-primary" title="Edit User">
                                            <i class="fa fa-pencil-alt"></i>
                                        </a>

                                        <button type="submit" dusk="delete-user-{{$user->id}}" class="btn btn-sm btn-danger" title="Delete User" onclick="return confirm(&quot;Delete User?&quot;)">
                                            <i class="fa fa-trash-alt"></i>
                                        </button>
                                    </div>

                                </form>

                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>

            </div>
        </div>

        <div class="panel-footer">
            {!! $users->render() !!}
        </div>

        @endif

    </div>
@endsection