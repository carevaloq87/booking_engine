<div class="form-group m-form__group row {{ $errors->has('name') ? 'has-error' : '' }}">
    <label for="name" class="col-2 col-form-label">Name</label>
    <div class="col-6">
        <input class="form-control" name="name" type="text" id="name" value="{{ old('name', optional($user)->name) }}" maxlength="255" required="true" placeholder="Enter name here...">
        {!! $errors->first('name', '<p class="help-block">:message</p>') !!}
    </div>
</div>

<div class="form-group m-form__group row {{ $errors->has('email') ? 'has-error' : '' }}">
    <label for="email" class="col-2 col-form-label">Email</label>
    <div class="col-6">
        <input class="form-control" name="email" type="email" id="email" value="{{ old('email', optional($user)->email) }}" required="true" placeholder="Enter email here...">
        {!! $errors->first('email', '<p class="help-block">:message</p>') !!}
    </div>
</div>
@if(!isset($user))
<div class="form-group m-form__group row {{ $errors->has('password') ? 'has-error' : '' }}">
    <label for="password" class="col-2 col-form-label">Password</label>
    <div class="col-6">
        <input class="form-control" name="password" type="password" id="password"  required="true" placeholder="Enter password here...">
        {!! $errors->first('password', '<p class="help-block">:message</p>') !!}
    </div>
</div>

<div class="form-group m-form__group row {{ $errors->has('password_confirmation') ? 'has-error' : '' }}">
    <label for="password_confirmation" class="col-2 col-form-label">Password Confirmation</label>
    <div class="col-6">
        <input class="form-control" name="password_confirmation" type="password" id="password_confirmation"  required="true" placeholder="Enter password confirmation here...">
        {!! $errors->first('password-confirmation', '<p class="help-block">:message</p>') !!}
    </div>
</div>
@endif

<div class="form-group m-form__group row {{ $errors->has('role') ? 'has-error' : '' }}">
    <label for="role" class="col-2 col-form-label">Role</label>
    <div class="col-6">
        <select class="form-control" id="roles" name="roles" required="true">
            <option value="" style="display: none;" {{ old('role', optional($user)->roles() ?: '') == '' ? 'selected' : '' }} disabled selected>Enter role here...</option>
            @foreach ($roles as $role)
            <option value="{{ $role['id'] }}" {{ ( isset($user) && $user->roles->pluck('id')->isNotEmpty() && $user->roles->pluck('id')[0] == $role['id'] ? 'selected' : '' ) }}>
                {{ $role['name'] }}
            </option>
			@endforeach
        </select>
        {!! $errors->first('roles', '<p class="help-block">:message</p>') !!}
    </div>
</div>

<div class="form-group m-form__group row {{ $errors->has('service_provider_id') ? 'has-error' : '' }}">
    <label for="service_provider_id" class="col-2 col-form-label">Service Provider</label>
    <div class="col-6">
        <select class="form-control" id="service_provider_id" name="service_provider_id" required="true">
            <option value="" style="display: none;" {{ old('service_provider_id', optional($user)->serviceProvider() ?: '') == '' ? 'selected' : '' }} disabled selected>Enter Service Provider here...</option>
            @foreach ($serviceProviders as $sp_id => $service_provider)
            <option value="{{ $sp_id }}" {{ ( isset($user) && isset($user->service_provider_id) && $user->service_provider_id == $sp_id ? 'selected' : '' ) }}>
                {{ $service_provider }}
            </option>
			@endforeach
        </select>
        {!! $errors->first('service_provider_id', '<p class="help-block">:message</p>') !!}
    </div>
</div>



