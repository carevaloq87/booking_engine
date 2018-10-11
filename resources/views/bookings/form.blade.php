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
            @input="getAvailability"
            >
            </multiselect>
            <input v-if="service_selected" type="hidden" name="service_id" :value="service_selected.id" id="service_id">
            {!! $errors->first('service', '<p class="help-block">:message</p>') !!}
        </div>
    </div>
    <div class="form-group {{ $errors->has('interpreter') ? 'has-error' : '' }}">
        <label for="interpreter" class="col-md-2 control-label">Interpreter required?</label>
        <div class="col-md-6 mt-radio-inline" v-on:change="onChangeInterpreter">
                <label class="mt-radio mt-radio-outline">
                    <input type="radio" name="is_interpreter" id="is_interpreter" value="1">Yes<span></span>
                </label>
                <label class="mt-radio mt-radio-outline">
                    <input type="radio" name="is_interpreter" id="is_interpreter" checked value="0">No<span></span>
                </label>
        </div>
    </div>
    <div class="form-group {{ $errors->has('int_language') ? 'has-error' : '' }}" v-if="is_interpreter">
        <label for="int_language" class="col-md-2 control-label">Language</label>
        <div class="col-md-6 ">
            <select class="form-control" id="int_language" name="int_language">
                @include( 'bookings.language' )
            </select>
        </div>
    </div>
    <booking-date-picker></booking-date-picker>
    <div class="form-group {{ $errors->has('comment') ? 'has-error' : '' }}">
        <label for="comment" class="col-md-2 control-label">Description</label>
        <div class="col-md-6">
            <textarea class="form-control" name="comment" cols="50" rows="10" id="comment" minlength="0"></textarea>
            {!! $errors->first('comment', '<p class="help-block">:message</p>') !!}
        </div>
    </div>

</div>
@section('scripts')
    <script src="{{ asset('js/booking.js')}}" />
@endsection



