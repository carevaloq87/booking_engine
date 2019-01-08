<div id="set_days" class="modal fade modal-scroll" tabindex="-1" data-replace="true" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">

            <div class="modal-body p-0">

                <div class="row mx-4 mt-4 mb-2 pb-2 border-bottom">
                    <div class="col-11 p-0">
                        <h5>Set Days</h5>
                    </div>
                    <div class="col-1">
                        <button class="close" aria-label="Close" data-dismiss="modal" type="button"><span aria-hidden="true">×</span></button>
                    </div>
                </div>

                <div class="form-group calendars noSelect">
                    <div class="col-sm">

                        <service-days :service="sv_id"></service-days>

                    </div>
                </div>

            </div>

        </div>
    </div>
</div>