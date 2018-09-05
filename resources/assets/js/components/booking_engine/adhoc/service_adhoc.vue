<template>
    <div>
        <div class="well">
            <span>This will set an adhoc day only, you should provide a range of hours and duration for those appointments. Please include interpreter and regular information when relevant.</span>
        </div>
        <div class="form-group col-xs-12">
            <label for="duration" class="col-md-2 control-label">Choose Day</label>
            <div class="col-sm-6 col-md-4">
                <!-- <input type="text" class="form-control input-medium" id="adhoc_date" name="adhoc_date" placeholder="dd-mm-yyyy"> -->
                <dropdown class="form-group">
                    <div class="input-group">
                        <input class="form-control" type="text" v-model="adhoc_date" name="adhoc_date">
                        <div class="input-group-btn">
                        <btn class="dropdown-toggle"><i class="glyphicon glyphicon-calendar"></i></btn>
                        </div>
                    </div>
                    <template slot="dropdown">
                        <li>
                        <date-picker v-model="adhoc_date" :width="200" :today-btn="false" :clear-btn="false" :limit-from="limit_from"/>
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
                    <journey-container v-bind:currentJourney="journey.regular" tableClass="regular" v-on:reload-ds="initDragSelect"> </journey-container>
                </div>

                <div id="interpreter_journey" class="tab-pane fade">
                    <journey-container v-bind:currentJourney="journey.interpreter" tableClass="interpreter" v-on:reload-ds="initDragSelect"> </journey-container>
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
                adhoc_date: null,
                choice: 'currentActive',
                duration: 60,
                ds_interpreter: {},
                ds_regular: {},
                journey: {},
                limit_from: new Date().toISOString().split('T')[0],
                sv_id: this.$root.sv_id,
            }
        },
        methods: {
            //Initialize Drage and select object for regular and interpreter elements
            initDragSelect() {
                var self = this;
                //Initialize Drag Select in for calendars
                self.ds_regular = new SelectableDS('#regular_journey .ds-button');
                self.ds_interpreter = new SelectableDS('#interpreter_journey .ds-button');
                //There are no previous selections
            },
            //Submit information to webservice
            submitInfo() {
                let self = this;
                let hours = {
                                regular: {
                                    time_name: document.querySelector("#regular button.active").id,
                                    days: self.ds_regular.getSelection().map( item => item.id )
                                },
                                interpreter: {
                                    time_name: document.querySelector("#interpreter button.active").id,
                                    days: self.ds_interpreter.getSelection().map( item => item.id )
                                },
                                date: self.adhoc_date
                            };

                let url = '/calendar/service/adhoc';

                $("#contentLoading").modal("show");
                axios['post'](url, { id: self.service, hours: hours })
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