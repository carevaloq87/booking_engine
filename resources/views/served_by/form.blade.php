
<div class="form-group {{ $errors->has('service_id') ? 'has-error' : '' }}">
    <label for="service_id" class="col-md-2 control-label">Service</label>
    <div class="col-md-10">
        <select class="form-control" id="service_id" name="service_id" required="true">
        	    <option value="" style="display: none;" {{ old('service_id', optional($servedBy)->service_id ?: '') == '' ? 'selected' : '' }} disabled selected>Select service</option>
        	@foreach ($services as $key => $service)
			    <option value="{{ $key }}" {{ old('service_id', optional($servedBy)->service_id) == $key ? 'selected' : '' }}>
			    	{{ $service }}
			    </option>
			@endforeach
        </select>
        
        {!! $errors->first('service_id', '<p class="help-block">:message</p>') !!}
    </div>
</div>

<div class="form-group {{ $errors->has('resource_id') ? 'has-error' : '' }}">
    <label for="resource_id" class="col-md-2 control-label">Resource</label>
    <div class="col-md-10">
        <select class="form-control" id="resource_id" name="resource_id" required="true">
        	    <option value="" style="display: none;" {{ old('resource_id', optional($servedBy)->resource_id ?: '') == '' ? 'selected' : '' }} disabled selected>Select resource</option>
        	@foreach ($resources as $key => $resource)
			    <option value="{{ $key }}" {{ old('resource_id', optional($servedBy)->resource_id) == $key ? 'selected' : '' }}>
			    	{{ $resource }}
			    </option>
			@endforeach
        </select>
        
        {!! $errors->first('resource_id', '<p class="help-block">:message</p>') !!}
    </div>
</div>

