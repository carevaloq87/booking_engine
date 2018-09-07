<template>
    <div>
        <div class="tab-content col-xs-12">
            <div id="regular" class="tab-pane fade in active">
                <hours-container v-bind:currentSchedule="schedule.regular" tableClass="current" v-on:reload-ds="initDragSelect"> </hours-container>
            </div>
        </div>

        <div class="form-group margin-top-10">
            <div class="col-sm-12 margin-top-10">
                <button class="btn green pull-left" v-on:click="submitInfo">Submit</button>
            </div>
        </div>
    </div>
</template>

<script>
    import SelectableDS from '../selectableDS';
    Vue.component('hours-container', require('./hours_container.vue'));
    export default {
        props:['resource'],
        data() {
            return {
                ds_regular: {},
                schedule: {},
                rs_id: this.$root.rs_id,
            }
        },
        methods: {
            //Get schedules by resource ID
            getSchedule(rs_id) {
                var self = this;
                let url = '/calendar/resource/hours/' + rs_id;
                axios['get'](url, {})
                    .then(response => {
                        self.schedule = response.data;
                    })
                    .then(() => {
                        self.initDragSelect();
                        $("#contentLoading").modal("hide");
                    })
                    .catch(error => {
                        console.log(error);
                        $("#contentLoading").modal("hide");
                    });
            },
            //Initialize Drage and select object for regular and interpreter elements
            initDragSelect() {
                var self = this;
                //Initialize Drag Select in for calendars
                self.ds_regular = new SelectableDS('#regular .ds-button');
                //Set previous selections
                if(self.schedule.hasOwnProperty('regular') && self.schedule.regular.hasOwnProperty('days')){
                    self.ds_regular.setInitialSelections('#regular .ds-button', self.schedule.regular.days); // Pre select values for an specific resource
                }

            },
            // Submit information to webservice
            submitInfo() {
                let self = this;
                let hours = {
                                regular: {
                                    time_name: document.querySelector("#regular button.active").id,
                                    days: self.ds_regular.getSelection().map( item => item.id )
                                },
                            };

                let url = '/calendar/resource/hours';

                $("#contentLoading").modal("show");
                axios['post'](url, { id: self.resource, hours: hours })
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
                $("#contentLoading").modal("show");
                if (typeof this.ds_regular.clear === "function") {
                    this.ds_regular.clear();
                }
                this.getSchedule(this.resource);
            }
        }
    }
</script>