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

        <div class="form-group col-sm-12">

            <div class="tab-content adhoc_hours_selection col-xs-12">
                <div id="regular_journey" class="tab-pane fade in active">
                    <journey-container v-bind:currentJourney="journey.regular" tableClass="regular" v-on:reload-ds="initDragSelect"> </journey-container>
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
        props:['resource'],
        data() {
            return {
                adhoc_object: {
                    date: null,
                    regular: {}
                },
                adhoc_date: null,
                choice: 'currentActive',
                duration: 60,
                ds_regular: {},
                journey: {},
                limit_from: new Date().toISOString().split('T')[0],
                rs_id: this.$root.rs_id,
            }
        },
        methods: {
            //Initialize Drage and select object for regular and
            initDragSelect() {
                var self = this;
                let selected_regular = [];

                if(self.ds_regular.hasOwnProperty('selectable')){
                    selected_regular = self.ds_regular.getSelectedValues();
                }

                //Initialize Drag Select in for calendars
                self.ds_regular = new SelectableDS('#regular_journey .ds-button');
                //There are no previous selections
                if(selected_regular.length > 0) {
                    self.ds_regular.setInitialSelections('#regular_journey .ds-button', selected_regular); // Pre select values for an specific resource
                }
            },
            //Submit information to webservice
            submitInfo() {
                let self = this;
                self.adhoc_object.regular = {
                                    time_name: document.querySelector("#regular button.active").id,
                                    hours: self.ds_regular.getSelectedValues(),
                                    duration: document.querySelector("#regular_duration").value
                                };

                let url = '/calendar/resource/adhoc';
                $("#contentLoading").modal("show");
                axios['post'](url, { id: self.resource, hours: self.adhoc_object })
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
            //Watch change of service
            resource: function() {
                $("#contentLoading").modal("show");
                if (typeof this.ds_regular.clear === "function") {
                    this.ds_regular.clear();
                }
            }
        },
        mounted() {
            this.initDragSelect();
        }
    }
</script>