
<div class="form-group {{ $errors->has('name') ? 'has-error' : '' }}">
    <label for="name" class="col-md-2 control-label">Name</label>
    <div class="col-md-10">
        <input class="form-control" name="name" type="text" id="name" value="{{ old('name', optional($resource)->name) }}" minlength="1" required="true" placeholder="Enter name here...">
        {!! $errors->first('name', '<p class="help-block">:message</p>') !!}
    </div>
</div>

<div class="form-group {{ $errors->has('phone') ? 'has-error' : '' }}">
    <label for="phone" class="col-md-2 control-label">Phone</label>
    <div class="col-md-10">
        <input class="form-control" name="phone" type="text" id="phone" value="{{ old('phone', optional($resource)->phone) }}" minlength="10" placeholder="Enter phone here...">
        {!! $errors->first('phone', '<p class="help-block">:message</p>') !!}
    </div>
</div>

<div class="form-group {{ $errors->has('email') ? 'has-error' : '' }}">
    <label for="email" class="col-md-2 control-label">Email</label>
    <div class="col-md-10">
        <input class="form-control" name="email" type="email" id="email" value="{{ old('email', optional($resource)->email) }}" placeholder="Enter email here...">
        {!! $errors->first('email', '<p class="help-block">:message</p>') !!}
    </div>
</div>

<div class="form-group {{ $errors->has('service_provider_id') ? 'has-error' : '' }} {{ !auth()->user()->isAdmin() ? 'hidden' : '' }}">
    <label for="service_provider_id" class="col-md-2 control-label">Service Provider</label>
    <div class="col-md-10">
        <select class="form-control" id="service_provider_id" name="service_provider_id">
        	    <option value="" style="display: none;" {{ old('service_provider_id', optional($resource)->service_provider_id ?: '') == '' ? 'selected' : '' }} disabled selected>Select service provider</option>
        	@foreach ($serviceProviders as $key => $serviceProvider)
			    <option value="{{ $key }}" {{ (old('service_provider_id', optional($resource)->service_provider_id) == $key || auth()->user()->service_provider_id == $key) ? 'selected' : '' }}>
			    	{{ $serviceProvider }}
			    </option>
			@endforeach
        </select>

        {!! $errors->first('service_provider_id', '<p class="help-block">:message</p>') !!}
    </div>
</div>

<div class="form-group {{ $errors->has('services') ? 'has-error' : '' }}">
    <label for="services" class="col-md-2 control-label">Resources</label>
    <div class="col-md-10">
        <select class="form-control" id="services" name="services[]" multiple searchable="Search here..">
        	    <option value="" style="display: none;" disabled selected>Select service</option>
        	@foreach ($services as $key => $service)
			    <option value="{{ $service->id }}" {{ (old('resource', optional($service)->id) == $key || (isset($selected_services) && $selected_services->contains($service->id))) ? 'selected' : '' }}>
			    	{{ $service->name }}
			    </option>
			@endforeach
        </select>

        {!! $errors->first('services', '<p class="help-block">:message</p>') !!}
    </div>
</div>

