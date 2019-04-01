@extends('layouts.booking_engine.master')

@section('sub_title')
    {{ isset($resource->name) ? $resource->name : 'Resource' }}
@endsection

@section('buttons')
    <form method="POST" action="{!! route('resources.resource.destroy', $resource->id) !!}" accept-charset="UTF-8">
        <input name="_method" value="DELETE" type="hidden">
        {{ csrf_field() }}
        <div class="btn-group btn-group-sm" role="group">
            <a href="{{ route('office.index') }}" class="btn btn-primary" title="Show All Resource">
                <i class="fa fa-list-ul"></i>
            </a>

            <a href="{{ route('resources.resource.create') }}" class="btn btn-success" title="Create New Resource">
                <i class="fa fa-plus"></i>
            </a>

            <button type="submit" class="btn btn-danger" title="Delete Resource" onclick="return confirm(&quot;Delete Resource??&quot;)">
                <i class="fa fa-trash-alt"></i>
            </button>
        </div>
    </form>
@endsection

@section('content')
    <div class="container resource_page">

        <div class="m-accordion m-accordion--default m-accordion--solid m-accordion--toggle-arrow" id="accordion_resource" role="tablist">
            <!--begin::Item-->
            <div class="m-accordion__item">
                <div class="m-accordion__item-head" role="tab" id="accordion_resource_details" data-toggle="collapse" href="#accordion_resource_details_body" aria-expanded="false" data-tooltip="true" data-placement="top" title="Click to open or close Resource Details">
                    <span class="m-accordion__item-icon"><i class="fa flaticon-user-ok"></i></span>
                    <span class="m-accordion__item-title">Resource Details</span>

                    <span class="m-accordion__item-mode"></span>
                </div>

                <div class="m-accordion__item-body collapse show" id="accordion_resource_details_body" role="tabpanel" aria-labelledby="accordion_resource_details" data-parent="#accordion_resource" style="">
                    <div class="m-accordion__item-content">
                        <form method="POST" action="{{ route('resources.resource.update', $resource->id) }}" id="edit_resource_form" name="edit_resource_form" accept-charset="UTF-8" class="form-horizontal">
                            {{ csrf_field() }}
                            <input name="_method" type="hidden" value="PUT">
                            <div class="d-flex flex-column">
                                @include ('resources.form', [
                                                            'resource' => $resource,
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
            <div id="booking_engine_resource" class="resource">
                <div class="m-accordion__item">
                    <div class="m-accordion__item-head collapsed" role="tab" id="accordion_resource_unavailability" data-toggle="collapse" href="#accordion_resource_unavailability_body" aria-expanded="false" data-tooltip="true" data-placement="top" title="Click to open or close Resource Unavailability">
                        <span class="m-accordion__item-icon"><i class="fa  flaticon-alert-2"></i></span>
                        <span class="m-accordion__item-title">Resource Unavailability</span>

                        <span class="m-accordion__item-mode"></span>
                    </div>

                    <div class="m-accordion__item-body collapse" id="accordion_resource_unavailability_body" role="tabpanel" aria-labelledby="accordion_resource_unavailability" data-parent="#accordion_resource">
                        <div class="m-accordion__item-content">

                            <div class="row border-bottom mb-4 pb-4">
                                <div class="col-12">
                                    <h5>Set Resource Unavailability</h5>
                                </div>
                                <div class="col-sm-4">
                                    <div class="col-sm-12 text-center">
                                        <a v-on:click="openCalendar({{ $resource->id }})" href="#">
                                            <img src="/img/icons/resource/days.png" alt="" width="160">
                                        </a>
                                    </div>
                                </div>

                                <div class="col-sm-4">
                                    <div class="col-sm-12 text-center">
                                        <a v-on:click="openSchedule({{ $resource->id }})" href="#">
                                            <img src="/img/icons/resource/hours.png" alt="" width="160">
                                        </a>
                                    </div>
                                </div>

                                <div class="col-sm-4">
                                    <div class="col-sm-12 text-center">
                                        <a v-on:click="openAdhoc({{ $resource->id }})" href="#">
                                            <img src="/img/icons/resource/exceptions_adhocs.png" alt="" width="160">
                                        </a>
                                    </div>
                                </div>
                            </div>
                            <div class="row border-bottom mb-4 pb-4">
                                <div class="col-sm-12 adhoc-appointments">
                                    <h5>Future Adhocs</h5>
                                </div>

                                <div class="col-sm">
                                    <selected-adhoc :resource="{{ $resource->id }}"></selected-adhoc>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
                <!--end::Item-->


                @include("resources.modal.days")
                @include("resources.modal.hours")
                @include("resources.modal.adhoc")
                <loading-modal></loading-modal>
            </div>
        </div>
    </div>
@endsection

@section('extra-scripts')
    <script src="{{ asset('js/booking_engine_resource.js?id=') . str_random(6)}}" ></script>
@endsection