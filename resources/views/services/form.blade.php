
<div class="form-group {{ $errors->has('name') ? 'has-error' : '' }}">
    <label for="name" class="col-md-2 control-label">Name</label>
    <div class="col-md-10">
        <input class="form-control" name="name" type="text" id="name" value="{{ old('name', optional($service)->name) }}" minlength="1" maxlength="255" placeholder="Enter name here...">
        {!! $errors->first('name', '<p class="help-block">:message</p>') !!}
    </div>
</div>

<div class="form-group {{ $errors->has('phone') ? 'has-error' : '' }}">
    <label for="phone" class="col-md-2 control-label">Phone</label>
    <div class="col-md-10">
        <input class="form-control" name="phone" type="text" id="phone" value="{{ old('phone', optional($service)->phone) }}" minlength="1" placeholder="Enter phone here...">
        {!! $errors->first('phone', '<p class="help-block">:message</p>') !!}
    </div>
</div>

<div class="form-group {{ $errors->has('email') ? 'has-error' : '' }}">
    <label for="email" class="col-md-2 control-label">Email</label>
    <div class="col-md-10">
        <input class="form-control" name="email" type="email" id="email" value="{{ old('email', optional($service)->email) }}" placeholder="Enter email here...">
        {!! $errors->first('email', '<p class="help-block">:message</p>') !!}
    </div>
</div>

<div class="form-group {{ $errors->has('description') ? 'has-error' : '' }}">
    <label for="description" class="col-md-2 control-label">Description</label>
    <div class="col-md-10">
        <textarea class="form-control" name="description" cols="50" rows="10" id="description" minlength="1" maxlength="1000">{{ old('description', optional($service)->description) }}</textarea>
        {!! $errors->first('description', '<p class="help-block">:message</p>') !!}
    </div>
</div>

<div class="form-group {{ $errors->has('duration') ? 'has-error' : '' }}">
    <label for="duration" class="col-md-2 control-label">Duration</label>
    <div class="col-md-10">
        <input class="form-control" name="duration" type="text" id="duration" value="{{ old('duration', optional($service)->duration) }}" minlength="1" placeholder="Enter duration here...">
        {!! $errors->first('duration', '<p class="help-block">:message</p>') !!}
    </div>
</div>

<div class="form-group {{ $errors->has('listed_duration') ? 'has-error' : '' }}">
    <label for="listed_duration" class="col-md-2 control-label">Listed Duration</label>
    <div class="col-md-10">
        <input class="form-control" name="listed_duration" type="text" id="listed_duration" value="{{ old('listed_duration', optional($service)->listed_duration) }}" minlength="1" placeholder="Enter listed duration here...">
        {!! $errors->first('listed_duration', '<p class="help-block">:message</p>') !!}
    </div>
</div>

<div class="form-group {{ $errors->has('spaces') ? 'has-error' : '' }}">
    <label for="spaces" class="col-md-2 control-label">Spaces</label>
    <div class="col-md-10">
        <input class="form-control" name="spaces" type="text" id="spaces" value="{{ old('spaces', optional($service)->spaces) }}" minlength="1" placeholder="Enter spaces here...">
        {!! $errors->first('spaces', '<p class="help-block">:message</p>') !!}
    </div>
</div>

<div class="form-group {{ $errors->has('service_provider_id') ? 'has-error' : '' }}">
    <label for="service_provider_id" class="col-md-2 control-label">Service Provider</label>
    <div class="col-md-10">
        <select class="form-control" id="service_provider_id" name="service_provider_id">
        	    <option value="" style="display: none;" {{ old('service_provider_id', optional($service)->service_provider_id ?: '') == '' ? 'selected' : '' }} disabled selected>Select service provider</option>
        	@foreach ($serviceProviders as $key => $serviceProvider)
			    <option value="{{ $key }}" {{ old('service_provider_id', optional($service)->service_provider_id) == $key ? 'selected' : '' }}>
			    	{{ $serviceProvider }}
			    </option>
			@endforeach
        </select>
        
        {!! $errors->first('service_provider_id', '<p class="help-block">:message</p>') !!}
    </div>
</div>

<div class="form-group {{ $errors->has('resources') ? 'has-error' : '' }}">
    <label for="resources" class="col-md-2 control-label">Resources</label>
    <div class="col-md-10">
        <select class="form-control" id="resources" name="resources[]" multiple searchable="Search here..">
        	    <option value="" style="display: none;" disabled selected>Select resource</option>
        	@foreach ($resources as $key => $resource)
			    <option value="{{ $resource->id }}" {{ old('resource', optional($resource)->id) == $key ? 'selected' : '' }}>
			    	{{ $resource->name }}
			    </option>
			@endforeach
        </select>
        
        {!! $errors->first('resources', '<p class="help-block">:message</p>') !!}
    </div>
</div>

