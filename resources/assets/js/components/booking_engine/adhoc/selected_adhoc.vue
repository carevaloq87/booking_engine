<template>
    <div class="row justify-content-md-center">
        <div class="col-sm-5">
            <h6>Regular Appointments</h6>
            <div class="row">
                <ul class="adhoc-hours" v-for="(hours, day) in adhocs.regular" :key="day">
                    <p><i class="fa fa-times-circle" @click="deleteAdhoc(day + '||' + 0)"></i> <strong>{{day}}</strong></p>
                    <li v-for="(hour, time) in hours.hours" :key="time">
                        <span>{{ hour }}</span>
                    </li>
                </ul>
            </div>
        </div>
        <div class="col-sm-5">
            <h6>Interpreter Appointments</h6>
            <div class="row">
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

import { data_bus } from '../../../booking_engine';

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
                        //self.getAdhocs();
                        data_bus.$emit('calendar');
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
            updateListAdhocs() {
                var self = this;
                data_bus.$on('calendar', (data) => {
                    self.getAdhocs();
                });
            },
        },
        mounted() {
            this.getAdhocs();
            this.updateListAdhocs();
            $('#contentLoading').modal('hide');
        }
    }
</script>