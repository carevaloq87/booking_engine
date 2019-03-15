<template>
    <div>
        <div class="card mx-2 col-sm-11 mx-auto">
            <div class="card-body">
                <span>This will set an adhoc day only, you should provide a range of hours and duration for those appointments. Please include interpreter and regular information when relevant.</span>
                <br>
                <br>
                <span>Any existing appointments will <strong>not</strong> be deleted.</span>
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
                :highlighted="datepicker_state.highlighted"
                :clear-button-icon="'fa fa-calendar-alt'"
                :calendar-button="true"
                :calendar-button-icon="'fa fa-calendar-alt'"
                :bootstrap-styling="true">
                </datepicker>

            </div>
        </div>

        <div class="col-11 mx-auto p-0">
            <ul class="nav nav-tabs" role="tablist">
                <li class="nav-item"><a class="nav-link active" data-toggle="tab" role="tab" aria-controls="regular journey" aria-selected="true" href="#regular_journey">Regular</a></li>
                <li class="nav-item"><a class="nav-link" data-toggle="tab" role="tab" aria-controls="interpreter journey" aria-selected="false" href="#interpreter_journey">Interpreter</a></li>
            </ul>

            <div class="tab-content col-sm adhoc_hours_selection">
                <div id="regular_journey"  class="tab-pane fade show active" role="tabpanel" aria-labelledby="regular-journey-tab">
                    <journey-container v-bind:currentJourney="journey.regular" :duration="regular_duration" tableClass="regular" v-on:reload-ds="updateDragSelect" is_service> </journey-container>
                </div>

                <div id="interpreter_journey" class="tab-pane fade" role="tabpanel" aria-labelledby="interpreter-journey-tab">
                    <journey-container v-bind:currentJourney="journey.interpreter" :duration="interpreter_duration" tableClass="interpreter" v-on:reload-ds="updateDragSelect" is_service> </journey-container>
                </div>
            </div>
        </div>

        <div class="col-12 mx-auto mt-4 mt-md-0">
                <button class="btn h-25 btn-sm btn-green" v-on:click="submitInfo">Submit</button>
                <button type="button" class="btn btn-sm btn-secondary" data-dismiss="modal">Cancel</button>
        </div>
    </div>
</template>

<script>
    import SelectableDS from '../selectableDS';
    import moment from 'moment';
    import Datepicker from 'vuejs-datepicker';
    import EventBus from '../../../utils/event-bus';

    Vue.component('journey-container', require('./journey_container.vue'));

    export default {
        props:['service', 'regular_duration', 'interpreter_duration'],
        components: {
            Datepicker
        },
        data() {
            let self =this;
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
                holidays_date: [],
                datepicker_state: {
                    disabledDates: {
                        to: new Date(),
                        days: [6, 0], // Disable Saturday's and Sunday's
                        customPredictor: function (date) {
                            return self.getHolidays(date);
                        },
                    },
                    highlighted: {
                        dates: [
                            new Date(2019, 0, 1)
                        ],
                        customPredictor: function (date) {
                            return self.getHolidays(date);
                        },
                        includeDisabled: true
                    }
                },
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
                let selected_regular = self.ds_regular.getSelectedValuesByContext("#regular_journey .ds-button.ds-selected");
                let selected_interpreter = self.ds_interpreter.getSelectedValuesByContext("#interpreter_journey .ds-button.ds-selected");

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
                                    hours: self.ds_regular.getSelectedValuesByContext("#regular_journey .ds-button.ds-selected"),
                                    duration: document.querySelector("#regular_duration").value
                                };
                self.adhoc_object.interpreter = {
                                        time_name: document.querySelector("#interpreter_journey button.active").id,
                                        hours: self.ds_interpreter.getSelectedValuesByContext("#interpreter_journey .ds-button.ds-selected"),
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
                    self.adhoc_object.date = moment(self.adhoc_object.date).format('YYYY-MM-DD');
                    self.showLoader();
                    axios['post'](url, { id: self.service, hours: self.adhoc_object })
                        .then(response => {
                            EventBus.$emit('calendar', response.data);
                            $("#set_adhoc_booking").modal("hide");
                            self.clearDataAdhoc();
                            self.hideLoader();
                        })
                        .catch(error => {
                            self.hideLoader();
                        });
                } else {
                    let message = 'Please set ' + form_validation.message.join(', ');
                    alert(message);
                }
            },
            clearDataAdhoc() {
                var self = this;
                self.adhoc_object.date = '';
                if (typeof this.ds_regular.clear === "function" && typeof this.ds_interpreter.clear === "function") {
                    this.ds_regular.clear();
                    this.ds_interpreter.clear();
                    document.querySelector("#interpreter_duration").value='';
                    document.querySelector("#regular_duration").value = '';
                }

            },
            initHolidays () {
                var self = this;
                let url = '/holidays/getTwoYearDates';
                self.showLoader();
                axios.get(url)
                    .then(function (response) {
                        let holidays = response.data.data;
                        holidays.forEach(function(holiday) {
                            self.holidays_date.push(moment(holiday.date).format('YYYY-MM-DD'));
                        });
                        self.hideLoader();
                    })
                    .catch(function (error) {
                        self.hideLoader();
                    });
            },
            getHolidays (date) {
                var self = this;
                let date_formated = moment(date).format('YYYY-MM-DD');
                if( self.holidays_date ) {
                    return  self.holidays_date.includes(date_formated) ? true:false;
                }
            },
            showLoader() {
                EventBus.$emit('SHOW_LOADER', 'service_adhoc');
            },
            hideLoader() {
                EventBus.$emit('HIDE_LOADER', 'service_adhoc');
            }
        },
        watch: {
            //Watch change of service
            service: function() {
                if (typeof this.ds_regular.clear === "function" && typeof this.ds_interpreter.clear === "function") {
                    this.showLoader();
                    this.ds_regular.clear();
                    this.ds_interpreter.clear();
                    this.hideLoader();
                }
            }
        },
        mounted() {
            this.initDragSelect();
            this.initHolidays();
        }
    }
</script>