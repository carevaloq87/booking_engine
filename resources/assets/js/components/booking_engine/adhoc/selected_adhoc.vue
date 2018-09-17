<template>
    <div>
        <div class="col-xs-6">
            <h4>Regular Appointments</h4>
            <div>
                <ul class="adhoc-hours" v-for="(hours, day) in adhocs.regular" :key="day">
                    <p><i class="fa fa-times-circle" @click="deleteAdhoc(day + '||' + 0)"></i> <strong>{{day}}</strong></p>
                    <li v-for="(hour, time) in hours.hours" :key="time">
                        <span>{{ hour }}</span>
                    </li>
                </ul>
            </div>
        </div>
        <div class="col-xs-6">
            <h4>Interpreter Appointments</h4>
            <div>
                <ul class="adhoc-hours" v-for="(hours, day) in adhocs.interpreter" :key="day">
                    <p><i class="fa fa-times-circle" @click="deleteAdhoc(day + '||' + 1)"></i> <strong>{{day}}</strong></p>
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
        props:['service'],
        data() {
            return {
                adhocs: {
                    interpreter:{},
                    regular: {}
                }
            }
        },
        methods: {
            deleteAdhoc(value) {
                $("#contentLoading").modal("show");
                var self = this;
                let url = '/calendar/service/adhoc/delete';

                axios['post'](url, {service_id: self.service, adhoc: value})
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
                let url = '/calendar/service/adhoc/' + self.service;

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