@extends('layouts.booking_engine.master')

@section('sub_title')
    {{ isset($service->name) ? $service->name : 'Service' }}
@endsection

@section('buttons')
    <form method="POST" action="{!! route('services.service.destroy', $service->id) !!}" accept-charset="UTF-8">
        <input name="_method" value="DELETE" type="hidden">
        {{ csrf_field() }}
        <div class="btn-group btn-group-sm" role="group">
            <a href="{{ route('office.index') }}" class="btn btn-primary" title="Show All Service">
                <i class="fa fa-list-ul"></i>
            </a>

            <a href="{{ route('services.service.create') }}" class="btn btn-success" title="Create New Service">
                <i class="fa fa-plus"></i>
            </a>

            <button type="submit" class="btn btn-danger" title="Delete Service" onclick="return confirm(&quot;Delete Service??&quot;)">
                <i class="fa fa-trash-alt"></i>
            </button>
        </div>
    </form>
@endsection

@section('content')
    <div class="container">

        <div class="m-accordion m-accordion--default m-accordion--solid m-accordion--toggle-arrow" id="accordion_service" role="tablist">
            <!--begin::Item-->
            <div class="m-accordion__item">
                <div class="m-accordion__item-head" role="tab" id="accordion_service_details" data-toggle="collapse" href="#accordion_service_details_body" aria-expanded="false">
                    <span class="m-accordion__item-icon"><i class="fa flaticon-user-ok"></i></span>
                    <span class="m-accordion__item-title">Service Details</span>

                    <span class="m-accordion__item-mode"></span>
                </div>

                <div class="m-accordion__item-body collapse show" id="accordion_service_details_body" role="tabpanel" aria-labelledby="accordion_service_details" data-parent="#accordion_service" style="">
                    <div class="m-accordion__item-content">
                        <form method="POST" action="{{ route('services.service.update', $service->id) }}" id="edit_service_form" name="edit_service_form" accept-charset="UTF-8" class="form-horizontal">
                            {{ csrf_field() }}
                            <input name="_method" type="hidden" value="PUT">
                            <div class="d-flex flex-column">
                                @include ('services.form', [
                                                            'service' => $service,
                                                            ])
                            </div>
                            <div class="form-group">
                                <div class="col-md-offset-2 col-md-10">
                                    <input class="btn btn-primary" type="submit" value="Update">
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <!--end::Item-->

            <!--begin::Item-->
            <div id="booking_engine">
                <div class="m-accordion__item">
                    <div class="m-accordion__item-head collapsed" role="tab" id="accordion_service_availability" data-toggle="collapse" href="#accordion_service_availability_body" aria-expanded="false">
                        <span class="m-accordion__item-icon"><i class="fa  flaticon-alert-2"></i></span>
                        <span class="m-accordion__item-title">Service Availability</span>

                        <span class="m-accordion__item-mode"></span>
                    </div>

                    <div class="m-accordion__item-body collapse" id="accordion_service_availability_body" role="tabpanel" aria-labelledby="accordion_service_availability" data-parent="#accordion_service">
                        <div class="m-accordion__item-content">

                            <div class="row border-bottom mb-4 pb-4">
                                <div class="col-12">
                                    <h5>Set Service Availability</h5>
                                </div>
                                <div class="col-sm-4">
                                    <div class="col-sm-12 text-center">
                                        <a v-on:click="openCalendar({{ $service->id }})" href="#">
                                            <img src="/img/icons/service/service_days.png" alt="" width="160">
                                        </a>
                                    </div>
                                </div>

                                <div class="col-sm-4">
                                    <div class="col-sm-12 text-center">
                                        <a v-on:click="openSchedule({{ $service->id }})" href="#">
                                            <img src="/img/icons/service/service_hours.png" alt="" width="160">
                                        </a>
                                    </div>
                                </div>

                                <div class="col-sm-4">
                                    <div class="col-sm-12 text-center">
                                        <a v-on:click="openAdhoc({{ $service->id }},{{ $service->duration }},{{ $service->interpreter_duration }})" href="#">
                                            <img src="/img/icons/service/exceptions_adhocs.png" alt="" width="160">
                                        </a>
                                    </div>
                                </div>
                            </div>
                            <div class="row border-bottom mb-4 pb-4">
                                <div class="col-sm-12 adhoc-appointments">
                                    <h5>Future Adhocs</h5>
                                </div>

                                <div class="col-sm">
                                    <selected-adhoc :service="{{ $service->id }}"></selected-adhoc>
                                </div>
                            </div>


                        </div>
                    </div>
                </div>
                <!--end::Item-->

                <div class="row">
                    <div class="col-sm">
                        <div id="calendar">
                            <Calendar :sv_id="{{ $service->id }}"></Calendar>
                        </div>
                    </div>
                </div>

                @include("services.modal.days")
                @include("services.modal.hours")
                @include("services.modal.adhoc")
                <loading-modal></loading-modal>
            </div>
        </div>
    </div>
@endsection

@section('extra-scripts')
    <script src="{{ asset('js/booking_engine.js?id=') . str_random(6) }}"></script>
@endsection