<template>
    <div>
        <div class="col-xs-12">
            <h4>Appointments<small> Remember that the information represents the hours that the resource is unavailable</small></h4>
            <div>
                <ul class="adhoc-hours" v-for="(hours, day) in adhocs.regular" :key="day">
                    <p><i class="fa fa-times-circle" @click="deleteAdhoc(day + '||' + 0)"></i> <strong>{{day}}</strong></p>
                    <li v-for="(hour, time) in hours.hours" :key="time">
                        <span>{{ hour }}</span>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</template>
<script>
    export default {
        props:['resource'],
        data() {
            return {
                adhocs: {
                    regular: {}
                }
            }
        },
        methods: {
            deleteAdhoc(value) {
                $("#contentLoading").modal("show");
                var self = this;
                let url = '/calendar/resource/adhoc/delete';

                axios['post'](url, {resource_id: self.resource, adhoc: value})
                    .then(response => {
                        self.getAdhocs();
                        $("#contentLoading").modal("hide");
                    })
                    .catch(error => {
                        $("#contentLoading").modal("hide");
                    });
            },
            getAdhocs() {
                $("#contentLoading").modal("show");
                var self = this;
                let url = '/calendar/resource/adhoc/' + self.resource;

                axios['get'](url, {})
                    .then(response => {
                        self.adhocs = response.data;
                        $("#contentLoading").modal("hide");
                    })
                    .catch(error => {
                        $("#contentLoading").modal("hide");
                    });
            },
        },
        mounted() {
            this.getAdhocs();
        }
    }
</script>