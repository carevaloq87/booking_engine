<div id="set_hours" class="modal fade in modal-scroll" tabindex="-1" data-replace="true" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">

            <div class="modal-body p-3">

                <div class="row mx-4 mt-4 mb-2 pb-2 border-bottom">
                    <div class="col-11 p-0">
                        <h5>Set Unavailable Hours</h5>
                    </div>
                    <div class="col-1">
                        <button class="close" aria-label="Close" data-dismiss="modal" type="button"><span aria-hidden="true">×</span></button>
                    </div>
                </div>

                <resource-hours :resource="rs_id"></resource-hours>

            </div>

        </div>
    </div>
</div>