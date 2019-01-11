<template>

    <div class="service_days">

        <div class="btn-group btn-group-sm mb-3 mt-2" role="group" aria-label="Year selection group">
            <button type="button" class="btn btn-primary btn-sm" :class="choice == 'currentActive' ? 'active' : ''" v-on:click="makeActive('currentActive')"> Current Year </button>
            <button type="button" class="btn btn-primary btn-sm" :class="choice == 'nextActive' ? 'active' : ''" v-on:click="makeActive('nextActive')"> Next Year </button>
        </div>

        <div id="current" v-show="isActiveTab('currentActive')">

            <ul class="nav nav-tabs" role="tablist">
                <li class="nav-item"><a class="nav-link active" data-toggle="tab" role="tab" aria-controls="current regular" aria-selected="true" href="#current_regular">Regular</a></li>
                <li class="nav-item"><a class="nav-link" data-toggle="tab" role="tab" aria-controls="current interpreter" aria-selected="false" href="#current_interpreter">Interpreter</a></li>
            </ul>

            <div class="tab-content">
                <div id="current_regular" class="tab-pane fade show active" role="tabpanel" aria-labelledby="current-regular-tab">
                    <calendar-container v-bind:currentCalendar="calendars.current_year" tableClass="current" v-bind:copyField="true"> </calendar-container>
                </div>

                <div id="current_interpreter" class="tab-pane fade" role="tabpanel" aria-labelledby="current-interpreter-tab">
                    <calendar-container v-bind:currentCalendar="calendars.current_year" tableClass="current_interpreter" v-bind:copyField="false"> </calendar-container>
                </div>
            </div>

        </div>

        <div id="next" v-show="isActiveTab('nextActive')">

            <ul class="nav nav-tabs" role="tablist">
                <li class="nav-item"><a class="nav-link active" data-toggle="tab" role="tab" aria-controls="next year regular" aria-selected="true" href="#next_regular">Regular</a></li>
                <li class="nav-item"><a class="nav-link" data-toggle="tab" role="tab" aria-controls="next year interpreter" aria-selected="false" href="#next_interpreter">Interpreter</a></li>
            </ul>

            <div class="tab-content">
                <div id="next_regular" class="tab-pane fade show active" role="tabpanel" aria-labelledby="next-year-regular-tab">
                    <calendar-container v-bind:currentCalendar="calendars.next_year" tableClass="next" v-bind:copyField="true"> </calendar-container>
                </div>

                <div id="next_interpreter" class="tab-pane fade" role="tabpanel" aria-labelledby="next-year-interpreter-tab">
                    <calendar-container v-bind:currentCalendar="calendars.next_year" tableClass="next_interpreter" v-bind:copyField="false"> </calendar-container>
                </div>
            </div>

        </div>

        <div class="form-group col-sm mt-2">
            <button class="btn btn-sm btn-green" id="" v-on:click="submitInfo">Submit</button>
            <button type="button" class="btn btn-sm btn-secondary" data-dismiss="modal">Cancel</button>
            <label id="holiday_label" class="control-label ml-1">Holiday</label>
            <span class="option_day holiday_conv"></span>
            <label id="selected_label" class="control-label ml-1 mr-3">Date Selected</label>
            <span class="option_day selected_conv"></span>
        </div>

    </div>

</template>

<script>
    import SelectableDS from '../selectableDS';
    import EventBus from '../../../utils/event-bus';

    Vue.component('calendar-container', require('./calendar_container.vue'));
    export default {
        props:['service'],
        data() {
            return {
                calendars: {},
                choice: 'currentActive',
                ds_current: {},
                ds_current_interpreter: {},
                ds_next: {},
                ds_next_interpreter: {},
                sv_id: this.$root.sv_id,
            }
        },
        methods: {
            //Make tab/container visible or hidden
            makeActive: function(val) {
                this.choice = val;
            },
            //Check if a tab/container should be visible
            isActiveTab: function(val) {
                return this.choice === val;
            },
            //Get Calendar by service ID
            getCalendar(sv_id) {
                if( sv_id > 0) {
                    var self = this;
                    let url = '/calendar/service/days/' + sv_id;
                    self.showLoader();

                    axios['get'](url, {})
                        .then(response => {
                            self.calendars = response.data;
                        })
                        .then(() => {
                            self.initDragSelect();
                            self.hideLoader();
                        })
                        .catch(error => {
                            self.hideLoader();
                            if (error.response) {
                                // The request was made and the server responded with a status code
                                // that falls out of the range of 2xx
                                console.log(error.response.data);
                                console.log(error.response.status);
                                console.log(error.response.headers);
                            } else if (error.request) {
                                // The request was made but no response was received
                                // `error.request` is an instance of XMLHttpRequest in the browser and an instance of
                                // http.ClientRequest in node.js
                                console.log(error.request);
                            } else {
                                // Something happened in setting up the request that triggered an Error
                                console.log('Error', error.message);
                            }
                            console.log(error.config);
                            self.getCalendar(self.service);
                        });
                }
            },
            //Initialize Drage and select object for regular and interpreter elements
            initDragSelect() {
                var self = this;
                //Initialize Drag Select in for calendars
                self.ds_current = new SelectableDS('.current_option_day');
                self.ds_current_interpreter = new SelectableDS('.current_interpreter_option_day');
                self.ds_next = new SelectableDS('.next_option_day');
                self.ds_next_interpreter = new SelectableDS('.next_interpreter_option_day');

                //Set previous selections
                self.ds_current.setInitialSelections('.current ', self.calendars.selected_current); // Pre select values for an specific service
                self.ds_current_interpreter.setInitialSelections('.current_interpreter ', self.calendars.selected_current_interpreter); // Pre select values for an specific service
                self.ds_next.setInitialSelections('.next ', self.calendars.selected_next); // Pre select values for an specific service
                self.ds_next_interpreter.setInitialSelections('.next_interpreter ', self.calendars.selected_next_interpreter); // Pre select values for an specific service
            },
            //Submit information to webservice
            submitInfo() {
                let self = this;
                let selections = {};
                selections.current = self.ds_current.getSelectedValuesByContext('#current_regular .ds-selected');
                selections.current_interpreter = self.ds_current_interpreter.getSelectedValuesByContext('#current_interpreter .ds-selected');
                selections.next = self.ds_next.getSelectedValuesByContext('#next_regular .ds-selected');
                selections.next_interpreter = self.ds_next_interpreter.getSelectedValuesByContext('#next_interpreter .ds-selected');

                let url = '/calendar/service/days';

                self.showLoader();
                axios['post'](url, { id: self.service, dates: selections })
                    .then(response => {
                        $("#set_days").modal("hide");
                        EventBus.$emit('calendar', response.data);
                    })
                    .then(() => {
                        self.hideLoader();
                    })
                    .catch(error => {
                        self.hideLoader();
                        console.log(error);
                    });
            },
            clearDays() {
                let self = this;
                if (typeof self.ds_current.clear === "function") {
                    self.ds_current.clear();
                    self.ds_current = {};
                    self.ds_current_interpreter.clear();
                    self.ds_current_interpreter = {};
                    self.ds_next.clear();
                    self.ds_next = {};
                    self.ds_next_interpreter.clear();
                    self.ds_next_interpreter = {};

                    let selected  = document.querySelectorAll('.service_days .ds-selected');
                    [].forEach.call(selected, function(el) {
                        el.className = el.className.replace(/\bds-hover\b/, "");
                        el.className = el.className.replace(/\bds-selected\b/, "");
                    });
                }
            },
            reloadDays() {
                let self = this;
                self.clearDays();
                self.getCalendar(self.service);
            },
            eventFetchDays() {
                let self = this;
                EventBus.$on('FETCH_DAYS', function () {
                    self.reloadDays();
                    self.choice ='currentActive';
                });
            },
            showLoader() {
                EventBus.$emit('SHOW_LOADER', 'service_days');
            },
            hideLoader() {
                EventBus.$emit('HIDE_LOADER', 'service_days');
            }
        },
        watch: {
            //Watch change of service
            service: function() {
                this.reloadDays();
            }
        },
        mounted() {
            this.eventFetchDays();
        },
    }
</script>