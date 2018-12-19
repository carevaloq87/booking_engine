<template>
    <div>
        <div class="card mx-2 col-sm-11 mx-auto">
            <div class="card-body">
                <span>This will set an adhoc day only, you should provide a range of hours and duration for those appointments. Please include regular information when relevant.</span>
            </div>
        </div>
        <div class="col-11 mx-auto p-0">
            <div class="col-12 mt-4 mb-2 p-0"><h6>Choose Day</h6></div>
            <div class="col-sm-6 mb-4 p-0">
                <datepicker
                v-model="adhoc_object.date"
                name="adhoc_date"
                :format="'dd/MM/yyyy'"
                :disabledDates="datepicker_state.disabledDates"
                :clear-button-icon="'fa fa-calendar-alt'"
                :calendar-button="true"
                :calendar-button-icon="'fa fa-calendar-alt'"
                :bootstrap-styling="true">
                </datepicker>

            </div>
        </div>

        <div class="col-11 mb-3 mx-auto p-0" >
            <div id="regular_journey" class="adhoc_hours">
                <journey-container v-bind:currentJourney="journey.regular"  tableClass="regular" v-on:reload-ds="updateDragSelect"> </journey-container>
            </div>
        </div>

        <div class="col- mb-2 p-0">
            <div class="col-12 mt-3 mb-2"><h6>Details</h6></div>
            <div class="col-sm-12">
                <textarea class="form-control" name="details" id="details" rows="4" cols="50"  placeholder="Enter details here..."></textarea>
            </div>
        </div>
        <div class="col-12 mx-auto">
                <button class="btn h-25 btn-sm" v-on:click="submitInfo">Submit</button>
        </div>
    </div>
</template>

<script>
    import SelectableDS from '../selectableDS';
    import moment from 'moment';
    import Datepicker from 'vuejs-datepicker';
    import { data_bus } from '../../../booking_engine_resource';

    Vue.component('journey-container', require('./journey_container.vue'));
    export default {
        props:['resource'],
        components: {
            Datepicker
        },
        data() {
            return {
                adhoc_object: {
                    date: null,
                    regular: {},
                },
                adhoc_date: null,
                choice: 'currentActive',
                duration: 0,
                ds_regular: {},
                journey: {},
                datepicker_state: {
                    disabledDates: {
                        to: new Date(),
                        days: [6, 0], // Disable Saturday's and Sunday's
                    }
                },
                rs_id: this.$root.rs_id,
                regular_selector: '#regular_journey .ds-button'
            }
        },
        methods: {
            //Initialize Drage and select object for regular and interpreter elements
            initDragSelect() {
                var self = this;
                //Initialize Drag Select in for calendars
                self.ds_regular = new SelectableDS(self.regular_selector);
            },
            //Keep selected values on interface update
            updateDragSelect() {
                var self = this;
                let selected_regular = self.ds_regular.getSelectedValues();

                //Re-Initialize Drag Select
                self.initDragSelect();

                //Set previous selections
                if(selected_regular.length > 0) {
                    self.ds_regular.setInitialSelections(self.regular_selector, selected_regular); // Pre select values for an specific service
                }
            },
            updateAdhocObj() {
                let self = this;
                self.adhoc_object.regular = {
                                    time_name: document.querySelector("#regular_journey button.active").id,
                                    hours: self.ds_regular.getSelectedValues(),
                                    duration: 0,//  Duration in adhoc resource is not necessary. document.querySelector("#regular_duration").value
                                    details:document.querySelector("#details").value
                                };
            },
            validateAdhocForm() {
                let self = this;
                let response = {
                    can_submit: true,
                    message: []
                };
                self.updateAdhocObj();

                if(self.adhoc_object.date === null || self.adhoc_object.date === '') { //No date selected
                    response.can_submit = false;
                    response.message.push('date');
                }
                if( self.adhoc_object.regular.duration === '' ) { // No Duration
                    response.can_submit = false;
                    response.message.push('duration');
                } else if (self.adhoc_object.regular.duration !== '' && self.adhoc_object.regular.hours.length === 0 ) { //Duration but not selected hours
                    response.can_submit = false;
                    response.message.push('regular hours');
                }
                if (self.adhoc_object.regular.duration === '' && self.adhoc_object.regular.hours.length > 0 ) { //Selected Hours but not duration
                    response.can_submit = false;
                    response.message.push('regular duration');
                }
                return response;
            },
            //Submit information to webservice
            submitInfo() {
                let self = this;
                let url = '/calendar/resource/adhoc';
                let form_validation = self.validateAdhocForm();
                if(form_validation.can_submit) {
                    $("#contentLoading").modal("show");
                    axios['post'](url, { id: self.resource, hours: self.adhoc_object })
                        .then(response => {
                            data_bus.$emit('adhoc', response.data);
                            $("#set_adhoc_booking").modal("hide");
                            //console.log(response);
                        })
                        .then(() => {
                            $("#contentLoading").modal("hide");
                            self.clearDataAdhoc();
                        })
                        .catch(error => {
                            $("#contentLoading").modal("hide");
                        });
                } else {
                    console.log(form_validation.message);
                    let message = 'Please set ' + form_validation.message.join(', ');
                    alert(message);
                }
            },
            clearDataAdhoc() {
                var self = this;
                self.adhoc_object.date = '';
                document.querySelector("#details").value = '';
                self.ds_regular.clear();

            }
        },
        watch: {
            //Watch change of service
            resource: function() {
                $("#contentLoading").modal("show");
                if (typeof this.ds_regular.clear === "function" ) {
                    this.ds_regular.clear();
                }
            }
        },
        mounted() {
            this.initDragSelect();
            $('#contentLoading').modal('hide');
        }
    }
</script>