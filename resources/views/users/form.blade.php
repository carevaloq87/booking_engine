
<div class="form-group {{ $errors->has('name') ? 'has-error' : '' }}">
    <label for="name" class="col-md-2 control-label">Name</label>
    <div class="col-md-10">
        <input class="form-control" name="name" type="text" id="name" value="{{ old('name', optional($user)->name) }}" maxlength="255" required="true" placeholder="Enter name here...">
        {!! $errors->first('name', '<p class="help-block">:message</p>') !!}
    </div>
</div>

<div class="form-group {{ $errors->has('email') ? 'has-error' : '' }}">
    <label for="email" class="col-md-2 control-label">Email</label>
    <div class="col-md-10">
        <input class="form-control" name="email" type="email" id="email" value="{{ old('email', optional($user)->email) }}" required="true" placeholder="Enter email here...">
        {!! $errors->first('email', '<p class="help-block">:message</p>') !!}
    </div>
</div>

<div class="form-group {{ $errors->has('password') ? 'has-error' : '' }}">
    <label for="password" class="col-md-2 control-label">Password</label>
    <div class="col-md-10">
        <input class="form-control" name="password" type="password" id="password" value="{{ old('password', optional($user)->password) }}" required="true" placeholder="Enter password here...">
        {!! $errors->first('password', '<p class="help-block">:message</p>') !!}
    </div>
</div>

<div class="form-group {{ $errors->has('role') ? 'has-error' : '' }}">
    <label for="role" class="col-md-2 control-label">Role</label>
    <div class="col-md-10">
        <select class="form-control" id="role" name="role" required="true">
        	    <option value="" style="display: none;" {{ old('role', optional($user)->roles() ?: '') == '' ? 'selected' : '' }} disabled selected>Enter role here...</option>
        	@foreach ($roles as $role)
            <option value="{{ $role['id'] }}" {{ isset($user) && old('role', optional($user) && $user->roles()->first()->id) == $role['id'] ? 'selected' : '' }}>
                {{ $role['name'] }}
            </option>
			@endforeach
        </select>
        
        {!! $errors->first('roles', '<p class="help-block">:message</p>') !!}
    </div>
</div>



