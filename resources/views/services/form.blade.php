<input id='id' type='hidden' value="{{ old('id', optional($service)->id) }}">
<div class="d-inline-flex align-items-center p-2 {{ $errors->has('name') ? 'has-error' : '' }}">
    <label for="name" class="col-md-2 control-label">Name</label>
    <div class="col-md-10">
        <input class="form-control" name="name" type="text" id="name" value="{{ old('name', optional($service)->name) }}" maxlength="100" placeholder="Enter name here...">
        {!! $errors->first('name', '<p class="help-block">:message</p>') !!}
    </div>
</div>

<div class="d-inline-flex align-items-center p-2 {{ $errors->has('phone') ? 'has-error' : '' }}">
    <label for="phone" class="col-md-2 control-label">Phone</label>
    <div class="col-md-10">
        <input class="form-control" name="phone" type="text" id="phone" value="{{ old('phone', optional($service)->phone) }}" maxlength="13" placeholder="Enter phone here...">
        {!! $errors->first('phone', '<p class="help-block">:message</p>') !!}
    </div>
</div>

<div class="d-inline-flex align-items-center p-2 {{ $errors->has('email') ? 'has-error' : '' }}">
    <label for="email" class="col-md-2 control-label">Email</label>
    <div class="col-md-10">
        <input class="form-control" name="email" type="email" id="email" value="{{ old('email', optional($service)->email) }}" maxlength="75" placeholder="Enter email here...">
        {!! $errors->first('email', '<p class="help-block">:message</p>') !!}
    </div>
</div>

<div class="d-inline-flex align-items-center p-2 {{ $errors->has('description') ? 'has-error' : '' }}">
    <label for="description" class="col-md-2 control-label">Description</label>
    <div class="col-md-10">
        <textarea class="form-control" name="description" cols="50" rows="10" id="description" minlength="1" maxlength="1000">{{ old('description', optional($service)->description) }}</textarea>
        {!! $errors->first('description', '<p class="help-block">:message</p>') !!}
    </div>
</div>

<div class="d-inline-flex align-items-center p-2 {{ $errors->has('color') ? 'has-error' : '' }}">
    <label for="color" class="col-md-2 control-label">Color</label>
    <div class="col-md-1">
        <input class="form-control" name="color" type="color" id="color" value="{{ old('color', optional($service)->color) }}" minlength="1" maxlength="255" placeholder="Choose a color here...">
        {!! $errors->first('color', '<p class="help-block">:message</p>') !!}
    </div>
</div>

<div class="d-inline-flex align-items-center p-2 {{ $errors->has('duration') ? 'has-error' : '' }}">
    <label for="duration" class="col-md-2 control-label">Duration</label>
    <div class="col-md-3">
        <input class="form-control" name="duration" type="number" id="duration" value="{{ old('duration', optional($service)->duration) }}" max="480" min="10" placeholder="Enter duration here..." required>
        {!! $errors->first('duration', '<p class="help-block">:message</p>') !!}
    </div>
    <label for="duration" class="col-md-2">minutes</label>
</div>

<div class="d-none {{ $errors->has('listed_duration') ? 'has-error' : '' }} ">
    <label for="listed_duration" class="col-md-2 control-label"> Listed Duration</label>
    <div class="col-md-10">
        <input class="form-control" name="listed_duration" type="text" id="listed_duration" value="0" minlength="1" placeholder="Enter listed duration here..." required>
        {!! $errors->first('listed_duration', '<p class="help-block">:message</p>') !!}
    </div>
</div>

<div class="d-inline-flex align-items-center p-2 {{ $errors->has('interpreter_duration') ? 'has-error' : '' }}">
    <label for="interpreter_duration" class="col-md-2 control-label">Interpreter Duration</label>
    <div class="col-md-3">
        <input class="form-control" name="interpreter_duration" type="number" id="interpreter_duration" value="{{ old('interpreter_duration', optional($service)->interpreter_duration) }}" max="480" min="10" placeholder="Enter duration here..." required>
        {!! $errors->first('interpreter_duration', '<p class="help-block">:message</p>') !!}
    </div>
    <label for="duration" class="col-md-2">minutes</label>
</div>

<div class="d-none p-2  {{ $errors->has('listed_interpreter_duration') ? 'has-error' : '' }} hidden">
    <label for="listed_interpreter_duration" class="col-md-2 control-label">Interpreter Listed Duration</label>
    <div class="col-md-10">
        <input class="form-control" name="listed_interpreter_duration" type="text" id="listed_interpreter_duration" value="0" minlength="1" placeholder="Enter listed duration here..." required>
        {!! $errors->first('listed_interpreter_duration', '<p class="help-block">:message</p>') !!}
    </div>
</div>

<div class="d-none p-2 {{ $errors->has('spaces') ? 'has-error' : '' }} hidden">
    <label for="spaces" class="col-md-2 control-label">Spaces</label>
    <div class="col-md-10">
        <input class="form-control" name="spaces" type="text" id="spaces" value="1" minlength="1" placeholder="Enter spaces here...">
        {!! $errors->first('spaces', '<p class="help-block">:message</p>') !!}
    </div>
</div>

<div class="d-inline-flex align-items-center p-2 {{ $errors->has('service_provider_id') ? 'has-error' : '' }} {{ !auth()->user()->isAdmin() ? 'hidden' : '' }}">
    <label for="service_provider_id" class="col-md-2 control-label">Service Provider</label>
    <div class="col-md-10">
        <select class="form-control" id="service_provider_id" name="service_provider_id">
        	    <option value="" style="display: none;" {{ old('service_provider_id', optional($service)->service_provider_id ?: '') == '' ? 'selected' : '' }} disabled selected>Select service provider</option>
        	@foreach ($serviceProviders as $key => $serviceProvider)
			    <option value="{{ $key }}" {{ (old('service_provider_id', optional($service)->service_provider_id) == $key || auth()->user()->service_provider_id == $key) ? 'selected' : '' }}>
			    	{{ $serviceProvider }}
			    </option>
			@endforeach
        </select>

        {!! $errors->first('service_provider_id', '<p class="help-block">:message</p>') !!}
    </div>
</div>

<div class="d-inline-flex align-items-center p-2 {{ $errors->has('resources') ? 'has-error' : '' }}" id="resources-select">
    <label for="resources" class="col-md-2 control-label">Resources</label>
    <div class="col-md-10">
        <multiselect    v-model="selected"
                        label="name"
                        key = "id"
                        track-by="name"
                        placeholder="Type to search resources"
                        open-direction="bottom"
                        :options="options"
                        :multiple="true"
                        :searchable="true"
                        :clear-on-select="true"
                        :close-on-select="true"
                        :show-no-results="false"
                        :hide-selected="true">
        </multiselect>
        <input type="hidden" name="resources[]" v-for="value in selected" :value= "value.id" id="resources">
        {!! $errors->first('resources', '<p class="help-block">:message</p>') !!}
    </div>
</div>
@section('scripts')
    <script src="{{ asset('js/service.js')}}" ></script>
@endsection


