<template>
    <div class="col-sm p-2">
        <ul class="nav nav-tabs" role="tablist">
            <li class="nav-item"><a class="nav-link active" data-toggle="tab" role="tab" aria-controls="regular" aria-selected="true" href="#regular">Regular</a></li>
            <li class="nav-item"><a class="nav-link" data-toggle="tab" role="tab" aria-controls="interpreter" aria-selected="false" href="#interpreter">Interpreter</a></li>
        </ul>

        <div class="tab-content col-sm pb-0">
            <div id="regular" class="tab-pane fade show active" role="tabpanel" aria-labelledby="regular-tab">
                <hours-container v-bind:currentSchedule="schedule.regular" tableClass="current" v-on:reload-ds="updateDragSelect" v-bind:copyField="true"> </hours-container>
            </div>

            <div id="interpreter" class="tab-pane fade" role="tabpanel" aria-labelledby="interpreter-tab">
                <hours-container v-bind:currentSchedule="schedule.interpreter" tableClass="current_interpreter" v-on:reload-ds="updateDragSelect" v-bind:copyField="false"> </hours-container>
            </div>
        </div>

        <div class="form-group">
            <div class="col-sm mt-1">
                <button class="btn btn-sm btn-green" v-on:click="submitInfo">Submit</button>
                <button type="button" class="btn btn-sm btn-secondary" data-dismiss="modal">Cancel</button>
            </div>
        </div>
    </div>
</template>

<script>
    import SelectableDS from '../selectableDS';
    import EventBus from '../../../utils/event-bus';
    Vue.component('hours-container', require('./hours_container.vue'));
    export default {
        props:['service'],
        data() {
            return {
                ds_interpreter: {},
                ds_regular: {},
                schedule: {},
                sv_id: this.$root.sv_id,
                regular_selector: '#regular .ds-button',
                interpreter_selector: '#interpreter .ds-button',
            }
        },
        methods: {
            //Get schedules by service ID
            getSchedule(sv_id) {
                if(sv_id){
                    var self = this;
                    let url = '/calendar/service/hours/' + sv_id;
                    self.showLoader();

                    axios['get'](url, {})
                        .then(response => {
                            self.schedule = response.data;
                        })
                        .then(() => {
                            self.initDragSelect();
                        })
                        .then(() => {
                            self.loadInitialSelections();
                            self.hideLoader();
                        })
                        .catch(error => {
                            console.log(error);
                            self.hideLoader();
                            self.getSchedule(self.service);
                        });
                }
            },
            //Initialize Drage and select object for regular and interpreter elements
            initDragSelect() {
                var self = this;
                //Initialize Drag Select in for calendars
                self.ds_regular = new SelectableDS(self.regular_selector);
                self.ds_interpreter = new SelectableDS(self.interpreter_selector);
            },
            //Select initial values
            loadInitialSelections() {
                var self = this;
                //Set previous selections
                if(self.schedule.hasOwnProperty('regular') && self.schedule.regular.hasOwnProperty('days')){
                    self.ds_regular.setInitialSelections(self.regular_selector, self.schedule.regular.days); // Pre select values for an specific service
                }
                if(self.schedule.hasOwnProperty('interpreter') && self.schedule.interpreter.hasOwnProperty('days')){
                    self.ds_interpreter.setInitialSelections(self.interpreter_selector, self.schedule.interpreter.days); // Pre select values for an specific service
                }
            },
            //Keep selected values on interface update
            updateDragSelect(selected_options) {
                var self = this;
                let selected_regular = selected_options.regular;
                let selected_interpreter = selected_options.interpreter;

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
            // Submit information to webservice
            submitInfo() {
                let self = this;
                let hours = {
                                regular: {
                                    time_name: document.querySelector("#regular button.active").id,
                                    days: self.ds_regular.getSelectedValuesByContext("#regular .ds-button.ds-selected")
                                },
                                interpreter: {
                                    time_name: document.querySelector("#interpreter button.active").id,
                                    days: self.ds_interpreter.getSelectedValuesByContext("#interpreter .ds-button.ds-selected")
                                }
                            };

                let url = '/calendar/service/hours';

                self.showLoader();
                axios['post'](url, { id: self.service, hours: hours })
                    .then(response => {
                        EventBus.$emit('calendar', response.data);
                        // Hide the warning message
                        let message_hour = document.getElementById('alert_hour');
                        let message_resource = document.getElementById('alert_resource');
                        let message = document.querySelector('.alert-warning');
                        if ((message_hour && message_resource) &&
                            (response.data.hours.interpreter.days.length > 0 || response.data.hours.regular.days.length > 0)) {
                            message_hour.style.display = 'none';
                        } else if ((message_hour && !message_resource) &&
                            (response.data.hours.interpreter.days.length > 0 || response.data.hours.regular.days.length > 0)) {
                            message.style.display = 'none';
                        }
                        $("#set_hours").modal("hide");
                        self.hideLoader();
                    })
                    .catch(error => {
                        self.hideLoader();
                        self.$swal('Error','Please save it again.','error');
                    });
            },
            clearHours() {
                let self = this;
                if (typeof self.ds_regular.clear === "function" && typeof self.ds_interpreter.clear === "function") {
                    self.ds_regular.clear();
                    self.ds_regular = {};
                    self.ds_interpreter.clear();
                    self.ds_interpreter = {};

                    let selected  = document.querySelectorAll('#set_hours .ds-selected');
                    [].forEach.call(selected, function(el) {
                        el.className = el.className.replace(/\bds-hover\b/, "");
                        el.className = el.className.replace(/\bds-selected\b/, "");
                    });
                }
            },
            reloadHours() {
                let self = this;
                self.clearHours();
                self.getSchedule(self.service);
            },
            eventFetchHours() {
                let self = this;
                EventBus.$on('FETCH_HOURS', function () {
                    self.reloadHours();
                });
            },
            showLoader() {
                EventBus.$emit('SHOW_LOADER', 'service_hours');
            },
            hideLoader() {
                EventBus.$emit('HIDE_LOADER', 'service_hours');
            }
        },
        watch: {
            //Watch change of service
            service: function() {
                this.reloadHours();
            }
        },
        mounted() {
            this.eventFetchHours();
        },
    }
</script>