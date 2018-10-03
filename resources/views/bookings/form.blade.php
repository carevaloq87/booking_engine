<div id='booking-create'>

    <div class="form-group {{ $errors->has('service') ? 'has-error' : '' }}">
        <label for="service-select" class="col-md-2 control-label">Service</label>
        <div class="col-md-6">
            <multiselect
            v-model="service_selected"
            label="name"
            key = "id"
            id="service-select"
            track-by="name"
            placeholder="Select Service..."
            open-direction="bottom"
            :options="service_options"
            :multiple="false"
            :searchable="true"
            :close-on-select="true"
            :show-no-results="false"
            :show-labels="false"
            @input="getAvailability">
            </multiselect>
            <input v-if="service_selected" type="hidden" name="service" :value="service_selected.id" id="service">
            {!! $errors->first('service', '<p class="help-block">:message</p>') !!}
        </div>
    </div>
    <div class="form-group {{ $errors->has('interpreter') ? 'has-error' : '' }}">
        <label for="interpreter" class="col-md-2 control-label">Interpreter required?</label>
        <div class="col-md-6 mt-radio-inline" v-on:change="onChangeInterpreter">
                <label class="mt-radio mt-radio-outline">
                    <input type="radio" name="intepreter" id="is_interpreter" value="1">Yes<span></span>
                </label>
                <label class="mt-radio mt-radio-outline">
                    <input type="radio" name="intepreter" id="is_interpreter" checked value="0">No<span></span>
                </label>
        </div>
    </div>
    <booking-date-picker></booking-date-picker>



</div>
@section('scripts')
    <script src="{{ asset('js/booking.js')}}" />
@endsection



