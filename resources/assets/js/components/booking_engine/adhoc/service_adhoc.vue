<template>
    <div>
        <div class="well">
            <span>This will set an adhoc day only, you should provide a range of hours and duration for those appointments. Please include interpreter and regular information when relevant.</span>
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

        <div class="form-group col-sm-12">
            <ul class="nav nav-tabs">
                <li class="active"><a data-toggle="tab" href="#regular_journey">Regular</a></li>
                <li><a data-toggle="tab" href="#interpreter_journey">Interpreter</a></li>
            </ul>

            <div class="tab-content adhoc_hours_selection col-xs-12">
                <div id="regular_journey" class="tab-pane fade in active">
                    <journey-container v-bind:currentJourney="journey.regular" tableClass="regular" v-on:reload-ds="updateDragSelect" is_service> </journey-container>
                </div>

                <div id="interpreter_journey" class="tab-pane fade">
                    <journey-container v-bind:currentJourney="journey.interpreter" tableClass="interpreter" v-on:reload-ds="updateDragSelect" is_service> </journey-container>
                </div>
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

    Vue.component('journey-container', require('./journey_container.vue'));
    export default {
        props:['service'],
        data() {
            return {
                adhoc_object: {
                    date: null,
                    regular: {},
                    interpreter: {}
                },
                adhoc_date: null,
                choice: 'currentActive',
                duration: 60,
                ds_interpreter: {},
                ds_regular: {},
                journey: {},
                limit_from: new Date().toISOString().split('T')[0],
                sv_id: this.$root.sv_id,
                regular_selector: '#regular_journey .ds-button',
                interpreter_selector: '#interpreter_journey .ds-button',
            }
        },
        methods: {
            //Initialize Drage and select object for regular and interpreter elements
            initDragSelect() {
                var self = this;
                //Initialize Drag Select in for calendars
                self.ds_regular = new SelectableDS(self.regular_selector);
                self.ds_interpreter = new SelectableDS(self.interpreter_selector);
            },
            //Keep selected values on interface update
            updateDragSelect() {
                var self = this;
                let selected_regular = self.ds_regular.getSelectedValues();
                let selected_interpreter = self.ds_interpreter.getSelectedValues();

                //Re-Initialize Drag Select
                self.initDragSelect();

                //Set previous selections
                if(selected_regular.length > 0) {
                    self.ds_regular.setInitialSelections(self.regular_selector, selected_regular); // Pre select values for an specific service
                }
                if(selected_interpreter.length > 0) {
                    self.ds_interpreter.setInitialSelections(self.interpreter_selector, selected_interpreter); // Pre select values for an specific service
                }
            },
            updateAdhocObj() {
                let self = this;
                self.adhoc_object.regular = {
                                    time_name: document.querySelector("#regular_journey button.active").id,
                                    hours: self.ds_regular.getSelectedValues(),
                                    duration: document.querySelector("#regular_duration").value
                                };
                self.adhoc_object.interpreter = {
                                        time_name: document.querySelector("#interpreter_journey button.active").id,
                                        hours: self.ds_interpreter.getSelectedValues(),
                                        duration: document.querySelector("#interpreter_duration").value
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
                if( self.adhoc_object.regular.duration === '' && self.adhoc_object.interpreter.duration === '' ) { // No Duration
                    response.can_submit = false;
                    response.message.push('duration');
                } else if (self.adhoc_object.regular.duration !== '' && self.adhoc_object.regular.hours.length === 0 ) { //Duration but not selected hours
                    response.can_submit = false;
                    response.message.push('regular hours');
                } else if(self.adhoc_object.interpreter.duration !== '' && self.adhoc_object.interpreter.hours.length === 0) { //Duration but not selected hours
                    response.can_submit = false;
                    response.message.push('interpreter hours');
                }

                if (self.adhoc_object.regular.duration === '' && self.adhoc_object.regular.hours.length > 0 ) { //Selected Hours but not duration
                    response.can_submit = false;
                    response.message.push('regular duration');
                }
                if(self.adhoc_object.interpreter.duration === '' && self.adhoc_object.interpreter.hours.length > 0) { //Selected Hours but not duration
                    response.can_submit = false;
                    response.message.push('interpreter duration');
                }
                return response;
            },
            //Submit information to webservice
            submitInfo() {
                let self = this;
                let url = '/calendar/service/adhoc';
                let form_validation = self.validateAdhocForm();
                if(form_validation.can_submit) {
                    $("#contentLoading").modal("show");
                    axios['post'](url, { id: self.service, hours: self.adhoc_object })
                        .then(response => {
                            console.log(response);
                        })
                        .then(() => {
                            $("#contentLoading").modal("hide");
                        })
                        .catch(error => {
                            $("#contentLoading").modal("hide");
                        });
                } else {
                    console.log(form_validation.message);
                    let message = 'Please set ' + form_validation.message.join(', ');
                    alert(message);
                }
            }
        },
        watch: {
            //Watch change of service
            service: function() {
                $("#contentLoading").modal("show");
                if (typeof this.ds_regular.clear === "function" && typeof this.ds_interpreter.clear === "function") {
                    this.ds_regular.clear();
                    this.ds_interpreter.clear();
                }
            }
        },
        mounted() {
            this.initDragSelect();
        }
    }
</script>