<div id="set_sp" class="modal fade bs-modal-lg" tabindex="-1" aria-hidden="true" role="dialog" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">

            <div class="modal-header">
                <span class="modal-title">Set up profile</span>
            </div>

            <div class="modal-body" style="display: grid;">

                <div class="form-group margin-top-10 noSelect {{ $errors->has('service_provider_id') ? 'has-error' : '' }}">
                    <label for="service_provider_id" class="col-md-12 control-label">Choose your office/program area from the list to get the correct access:</label>
                    <div class="col-md-12">
                        @if(isset($service_providers))
                        <select class="form-control" id="service_provider_id" v-model="service_provider_id" name="service_provider_id" required="true">
                            @foreach ($service_providers as $sp_id => $service_provider)
                            <option value="{{ $sp_id }}">
                                {{ $service_provider }}
                            </option>
                            @endforeach
                        </select>
                        @else
                        <label class="col-md-12">No Service Provider Available</label>
                        @endif
                        {!! $errors->first('service_provider_id', '<p class="help-block">:message</p>') !!}
                    </div>
                    <label for="full_name" class="col-md-12 control-label">Name: <small> optional </small> </label>
                    <div class="col-md-12">
                        @if(isset($service_providers))
                        <input class="form-control" type="text" v-model="name" autocomplete="off" id="full_name" name="full_name"/>
                        @endif
                        {!! $errors->first('full_name', '<p class="help-block">:message</p>') !!}
                    </div>
                    <br>
                    <br>
                    <br>
                    <br>
                    <br>
                    <br>
                    <br>
                    <div class="col-md-12">
                        <button type="button" @click="setServiceProvider" class="btn btn-primary" id="sp_send">Update profile</button><br>
                    </div>
                </div>


            </div>

        </div>
    </div>
</div>