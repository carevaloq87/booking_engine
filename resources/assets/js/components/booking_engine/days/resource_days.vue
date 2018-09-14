<template>

    <div class="resource_days">

        <div class="btn-group btn-group-xs" data-toggle="buttons">
            <button type="button" class="btn btn-primary" v-on:click="makeActive('currentActive')"> Current Year </button>
            <button type="button" class="btn btn-primary"  v-on:click="makeActive('nextActive')"> Next Year </button>
        </div>

        <div id="current" v-show="isActiveTab('currentActive')">

            <div class="tab-content">
                <div id="current_regular" class="tab-pane fade in active">
                    <calendar-container v-bind:currentCalendar="calendars.current_year" tableClass="current"> </calendar-container>
                </div>
            </div>

        </div>

        <div id="next" v-show="isActiveTab('nextActive')">

            <div class="tab-content">
                <div id="next_regular" class="tab-pane fade in active">
                    <calendar-container v-bind:currentCalendar="calendars.next_year" tableClass="next"> </calendar-container>
                </div>
            </div>

        </div>

        <div class="col-sm-12 margin-top-10">
            <button class="btn green pull-right" id="" v-on:click="submitInfo">Submit</button>
        </div>

    </div>

</template>

<script>
    import SelectableDS from '../selectableDS';
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
                $("#contentLoading").modal("show");

                var self = this;
                let url = '/calendar/resource/days/' + rs_id;

                axios['get'](url, {})
                    .then(response => {
                        self.calendars = response.data;
                    })
                    .then(() => {
                        self.initDragSelect();
                        $("#contentLoading").modal("hide");
                    })
                    .catch(error => {
                        $("#contentLoading").modal("hide");
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

                $("#contentLoading").modal("show");
                axios['post'](url, { id: self.resource, dates: selections })
                    .then(response => {
                        console.log(response);
                    })
                    .then(() => {
                        $("#contentLoading").modal("hide");
                    })
                    .catch(error => {
                        $("#contentLoading").modal("hide");
                        console.log(error);
                    });
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