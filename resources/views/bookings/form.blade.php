<div id='booking-create' class="d-flex flex-column" >

    <div class="d-inline-flex align-items-center p-2 {{ $errors->has('service') ? 'has-error' : '' }}">
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
    <div class="d-inline-flex align-items-center p-2 {{ $errors->has('interpreter') ? 'has-error' : '' }}">
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
    <div class="d-inline-flex align-items-center p-2 {{ $errors->has('int_language') ? 'has-error' : '' }}" v-if="is_interpreter">
        <label for="int_language" class="col-md-2 control-label">Language</label>
        <div class="col-md-6 ">
            <select class="form-control" id="int_language" name="int_language">
                @include( 'bookings.language' )
            </select>
        </div>
    </div>
    <booking-date-picker></booking-date-picker>
    <hr>
    <h3 id="detailsh3">Client Details</h3>
    <div class="d-inline-flex align-items-center p-2 {{ $errors->has('client') ? 'has-error' : '' }}">
        <label for="first_name" class="col-md-2 control-label">First Name</label>
        <div class="col-md-3">
            <input class="form-control" name="first_name" type="text" id="first_name" placeholder="Client first name..." required>
            {!! $errors->first('first_name', '<p class="help-block">:message</p>') !!}
        </div>
        <label for="last_name" class="col-md-2 control-label">Last Name</label>
        <div class="col-md-3">
                <input class="form-control" name="last_name" type="text" id="last_name" placeholder="Client last name..." required>
                {!! $errors->first('last_name', '<p class="help-block">:message</p>') !!}
        </div>
    </div>
    <div class="d-inline-flex align-items-center p-2 {{ $errors->has('contact') ? 'has-error' : '' }}">
        <label for="contact" class="col-md-2 control-label">Contact Detail </label>
        <div class="col-md-6">
            <input class="form-control" name="contact" type="text" id="contact" placeholder="Client email or phone number...">
            {!! $errors->first('contact', '<p class="help-block">:message</p>') !!}
        </div>
    </div>
    <hr>
    <div class="d-inline-flex align-items-center p-2 {{ $errors->has('comment') ? 'has-error' : '' }}">
        <label for="comment" class="col-md-2 control-label">Description</label>
        <div class="col-md-8">
            <vue-mce
            id="booking_description"
            class="form-control col-sm-12"
            v-model="comment_value"
            :config="config"
            name="comment"
            :value='comment_value'>
            {!! $errors->first('comment', '<p class="help-block">:message</p>') !!}
        </div>
    </div>

</div>
@section('scripts')
    <script src="https://cloud.tinymce.com/dev/tinymce.min.js?apiKey=v3tjlgkjdlr8xiq21qsdopbjfkuk5ibmdhgb5yznjfpyb1lj" ></script>
    <script src="{{ asset('js/booking.js')}}" ></script>
@endsection



