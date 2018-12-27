<input id='id' type='hidden' value="{{ old('id', optional($holiday)->id) }}">
<div class="form-group {{ $errors->has('date') ? 'has-error' : '' }}">
    <label for="date" class="col-md-2 control-label">Date</label>
    <div class="col-md-10">
        <datepicker
        id="date"
        v-model="date"
        name="date"
        value="{{ old('date', optional($holiday)->date) }}"
        :format="'yyyy-MM-dd'"
        :clear-button-icon="'fa fa-calendar-alt'"
        :calendar-button="true"
        :calendar-button-icon="'fa fa-calendar-alt'"
        :bootstrap-styling="true"
        required>
        </datepicker>
        {!! $errors->first('date', '<p class="help-block">:message</p>') !!}
    </div>
</div>

<div class="form-group {{ $errors->has('description') ? 'has-error' : '' }}">
    <label for="description" class="col-md-2 control-label">Description</label>
    <div class="col-md-10">
        <textarea class="form-control" name="description" cols="50" rows="10" id="description">{{ old('description', optional($holiday)->description) }}</textarea>
        {!! $errors->first('description', '<p class="help-block">:message</p>') !!}
    </div>
</div>
@section('scripts')
    <script src="{{ asset('js/holiday.js')}}" ></script>
@endsection
