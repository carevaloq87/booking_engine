<template>

    <div class="resource_days">

        <div class="btn-group btn-group-sm mb-3 mt-2" role="group" aria-label="Year selection group">
            <button type="button" class="btn btn-primary btn-sm" :class="choice == 'currentActive' ? 'active' : ''" v-on:click="makeActive('currentActive')"> Current Year </button>
            <button type="button" class="btn btn-primary btn-sm" :class="choice == 'nextActive' ? 'active' : ''" v-on:click="makeActive('nextActive')"> Next Year </button>
        </div>

        <div id="current" v-show="isActiveTab('currentActive')">

            <div class="tab-content">
                <div id="current_regular" class="tab-pane fade show active">
                    <calendar-container v-bind:currentCalendar="calendars.current_year" tableClass="current" v-bind:copyField="false"> </calendar-container>
                </div>
            </div>

        </div>

        <div id="next" v-show="isActiveTab('nextActive')">

            <div class="tab-content">
                <div id="next_regular" class="tab-pane fade show active">
                    <calendar-container v-bind:currentCalendar="calendars.next_year" tableClass="next" v-bind:copyField="false"> </calendar-container>
                </div>
            </div>

        </div>

        <div class="form-group col-sm mt-2">
            <button type="button" class="btn btn-sm btn-secondary" data-dismiss="modal">Close</button>
            <button class="btn btn-sm" id="" v-on:click="submitInfo">Submit</button>
            <label id="holiday_label" class="control-label">Holiday</label>
            <span class="option_day holiday_conv"></span>
            <label id="selected_label" class="control-label">Date Selected &nbsp;</label>
            <span class="option_day selected_conv"></span>
        </div>

    </div>

</template>

<script>
    import SelectableDS from '../selectableDS';
    import EventBus from '../../../utils/event-bus';
    Vue.component('calendar-container', require('./calendar_container.vue'));
    export default {
        props:['resource'],
        data() {
            return {
                calendars: {},
                choice: 'currentActive',
                ds_current: {},
                ds_next: {},
                rs_id: this.$root.rs_id,
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
            //Get Calendar by resource ID
            getCalendar(rs_id) {

                var self = this;
                let url = '/calendar/resource/days/' + rs_id;

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
                        console.log('Error', error.message);
                    });
            },
            //Initialize Drage and select object for regular and interpreter elements
            initDragSelect() {
                var self = this;
                //Initialize Drag Select in for calendars
                self.ds_current = new SelectableDS('.current_option_day');
                self.ds_next = new SelectableDS('.next_option_day');

                //Set previous selections
                self.ds_current.setInitialSelections('.current ', self.calendars.selected_current); // Pre select values for an specific resource
                self.ds_next.setInitialSelections('.next ', self.calendars.selected_next); // Pre select values for an specific resource
            },
            //Submit information to webservice
            submitInfo() {
                let self = this;
                let selections = {};
                selections.current = self.ds_current.getSelectedValues();
                selections.next = self.ds_next.getSelectedValues();

                let url = '/calendar/resource/days';

                self.showLoader();
                axios['post'](url, { id: self.resource, dates: selections })
                    .then(response => {
                        $("#set_days").modal("hide");
                        self.hideLoader();
                    })
                    .catch(error => {
                        self.hideLoader();
                        console.log(error);
                    });
            },
            showLoader() {
                EventBus.$emit('SHOW_LOADER', 'resource_days');
            },
            hideLoader() {
                EventBus.$emit('HIDE_LOADER', 'resource_days');
            }
        },
        watch: {
            //Watch change of resource
            resource: function() {
                let self = this;
                if (typeof this.ds_current.clear === "function") {
                    self.ds_current.clear();
                    self.ds_current = {};
                    self.ds_next.clear();
                    self.ds_next = {};
                }
                this.getCalendar(this.resource);
            }
        }
    }
</script>