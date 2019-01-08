<template>
    <div class="col-sm p-2">
        <ul class="nav nav-tabs" role="tablist">
            <li class="nav-item"><a class="nav-link active" data-toggle="tab" role="tab" aria-controls="regular" aria-selected="true" href="#regular">Regular</a></li>
            <li class="nav-item"><a class="nav-link" data-toggle="tab" role="tab" aria-controls="interpreter" aria-selected="false" href="#interpreter">Interpreter</a></li>
        </ul>

        <div class="tab-content col-sm">
            <div id="regular" class="tab-pane fade show active" role="tabpanel" aria-labelledby="regular-tab">
                <hours-container v-bind:currentSchedule="schedule.regular" tableClass="current" v-on:reload-ds="updateDragSelect"> </hours-container>
            </div>

            <div id="interpreter" class="tab-pane fade" role="tabpanel" aria-labelledby="interpreter-tab">
                <hours-container v-bind:currentSchedule="schedule.interpreter" tableClass="current_interpreter" v-on:reload-ds="updateDragSelect"> </hours-container>
            </div>
        </div>

        <div class="form-group mt-1">
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
                        $("#set_hours").modal("hide");
                        self.hideLoader();
                    })
                    .catch(error => {
                        self.hideLoader();
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
                if (typeof this.ds_regular.clear === "function" && typeof this.ds_interpreter.clear === "function") {
                    this.ds_regular.clear();
                    this.ds_interpreter.clear();
                }
                this.getSchedule(this.service);
            }
        }
    }
</script>