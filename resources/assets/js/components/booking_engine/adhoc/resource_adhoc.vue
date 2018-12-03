<template>
    <div>
        <div class="well">
            <span>This will set an adhoc day only, you should provide a range of hours and duration for those appointments. Please include regular information when relevant.</span>
        </div>
        <div class="form-group col-xs-12">
            <label for="duration" class="col-md-2 control-label">Choose Day</label>
            <div class="col-sm-6 col-md-4">
                <dropdown class="form-group">
                    <div class="input-group">
                        <input class="form-control" type="text" v-model="adhoc_object.date" name="adhoc_date">
                        <div class="input-group-btn">
                        <btn class="dropdown-toggle"><i class="glyphicon glyphicon-calendar"></i></btn>
                        </div>
                    </div>
                    <template slot="dropdown">
                        <li>
                        <date-picker v-model="adhoc_object.date" :width="200" :today-btn="false" :clear-btn="false" :limit-from="limit_from"/>
                        </li>
                    </template>
                </dropdown>
            </div>
        </div>
        <div class="form-group col-xs-12">
            <label for="details" class="col-md-2 control-label">Details</label>
            <div class="col-sm-6 col-md-4">
                <textarea class="form-control" name="details" id="details" rows="4" cols="50"  placeholder="Enter details here..."></textarea>
            </div>
        </div>
        <div class="form-group col-sm-12" >
            <div id="regular_journey" class="tab-pane fade in active">
                <journey-container v-bind:currentJourney="journey.regular"  tableClass="regular" v-on:reload-ds="updateDragSelect"> </journey-container>
            </div>
        </div>

        <div class="form-group col-sm-12">
                <button class="btn green pull-left" v-on:click="submitInfo">Submit</button>
        </div>
    </div>
</template>

<script>
    import SelectableDS from '../selectableDS';
    import moment from 'moment';
    import { data_bus } from '../../../booking_engine_resource';

    Vue.component('journey-container', require('./journey_container.vue'));
    export default {
        props:['resource'],
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
                limit_from: new Date().toISOString().split('T')[0],
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
        }
    }
</script>