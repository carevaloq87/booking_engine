<template>
    <div class="col-sm p-2">
        <div class="tab-content col-sm">
            <div id="regular" class="tab-pane fade show active" role="tabpanel" aria-labelledby="regular-tab">
                <hours-container v-bind:currentSchedule="schedule.regular" tableClass="current" v-on:reload-ds="updateDragSelect"> </hours-container>
            </div>
        </div>

        <div class="form-group mt-1">
            <div class="col-sm mt-1">
                <button class="btn btn-sm btn-success" v-on:click="submitInfo">Submit</button>
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
        props:['resource'],
        data() {
            return {
                ds_regular: {},
                schedule: {},
                rs_id: this.$root.rs_id,
                regular_selector: '#regular .ds-button',
            }
        },
        methods: {
            //Get schedules by resource ID
            getSchedule(rs_id) {
                var self = this;
                let url = '/calendar/resource/hours/' + rs_id;

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
                        self.getSchedule(self.resource);
                    });
            },
            //Initialize Drage and select object for regular and interpreter elements
            initDragSelect() {
                var self = this;
                //Initialize Drag Select in for calendars
                self.ds_regular = new SelectableDS(self.regular_selector);
            },
            //Select initial values
            loadInitialSelections() {
                var self = this;
                //Set previous selections
                if(self.schedule.hasOwnProperty('regular') && self.schedule.regular.hasOwnProperty('days')){
                    self.ds_regular.setInitialSelections(self.regular_selector, self.schedule.regular.days); // Pre select values for an specific resource
                }
            },
            //Keep selected values on interface update
            updateDragSelect() {
                var self = this;
                let selected_regular = self.ds_regular.getSelectedValues();

                //Re-Initialize Drag Select
                self.initDragSelect();

                //Set previous selections
                if(selected_regular.length > 0) {
                    self.ds_regular.setInitialSelections(self.regular_selector, selected_regular); // Pre select values for an specific resource
                }
            },
            // Submit information to webservice
            submitInfo() {
                let self = this;
                let hours = {
                                regular: {
                                    time_name: document.querySelector("#regular button.active").id,
                                    days: self.ds_regular.getSelectedValues()
                                }
                            };

                let url = '/calendar/resource/hours';

                self.showLoader();
                axios['post'](url, { id: self.resource, hours: hours })
                    .then(response => {
                        console.log(response);
                    })
                    .then(() => {
                        self.hideLoader();
                        $("#set_hours").modal("hide");
                    })
                    .catch(error => {
                        self.hideLoader();
                    });
            },
            showLoader() {
                EventBus.$emit('SHOW_LOADER', 'resource_hours');
            },
            hideLoader() {
                EventBus.$emit('HIDE_LOADER', 'resource_hours');
            }
        },
        watch: {
            //Watch change of resource
            resource: function() {
                if (typeof this.ds_regular.clear === "function") {
                    this.ds_regular.clear();
                }
                this.getSchedule(this.resource);
            }
        }
    }
</script>