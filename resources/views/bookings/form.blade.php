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
    <div class="form-group ">
        <label for="date" class="col-md-2 control-label">Date</label>
        <div class="col-md-6">
            <booking-date-picker></booking-date-picker>
        </div>
    </div>

</div>
    @section('scripts')
    <script src="{{ asset('js/booking.js')}}" />
@endsection



