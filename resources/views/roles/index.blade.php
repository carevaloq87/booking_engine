@extends('layouts.booking_engine.master')

@section('sub_title')
    Roles
@endsection

@section('buttons')
    <a href="{{ route('roles.create') }}" class="btn btn-success btn-sm" title="Create New Role" role="button">
        Add Role
    </a>
@endsection

@section('content')

    <div class="panel panel-default">

        @if(count($roles) == 0)
            <div class="panel-body text-center">
                <h4>No Roles Available!</h4>
            </div>
        @else
        <div class="panel-body panel-body-with-table">
            <div class="table-responsive">

                <table class="table table-striped ">
                    <thead>
                        <tr>
                            <th>Name</th>

                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                    @foreach($roles as $role)
                        <tr>
                            <td>{{ $role->name }}</td>

                            <td>

                                <form method="POST" action="{!! route('roles.destroy', $role->id) !!}" accept-charset="UTF-8">
                                <input name="_method" value="DELETE" type="hidden">
                                {{ csrf_field() }}

                                    <div class="btn-group btn-group-xs pull-right list-table" role="group">
                                        <a href="{{ route('roles.show', $role->id ) }}" class="btn btn-sm btn-info" title="Show Role">
                                            <i class="fa fa-list"></i>
                                        </a>
                                        <a href="{{ route('roles.edit', $role->id ) }}" class="btn btn-sm btn-primary" title="Edit Role">
                                            <i class="fa fa-pencil-alt"></i>
                                        </a>

                                        <button type="submit" dusk="delete-role-{{$role->id}}" class="btn btn-sm btn-danger" title="Delete Role" onclick="return confirm(&quot;Delete Role?&quot;)">
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
            {!! $roles->render() !!}
        </div>

        @endif

    </div>
@endsection