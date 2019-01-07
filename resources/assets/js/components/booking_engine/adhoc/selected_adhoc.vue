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
    import EventBus from '../../../utils/event-bus';

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
                var self = this;
                let url = '/calendar/service/adhoc/delete';

                self.showLoader();
                axios['post'](url, {service_id: self.service, adhoc: value})
                    .then(response => {
                        //self.getAdhocs();
                        EventBus.$emit('calendar');
                        self.hideLoader();
                    })
                    .catch(error => {
                        self.hideLoader();
                    });
            },
            getAdhocs() {
                var self = this;
                let url = '/calendar/service/adhoc/' + self.service;

                self.showLoader();
                axios['get'](url, {})
                    .then(response => {
                        self.adhocs = response.data;
                        self.hideLoader();
                    })
                    .catch(error => {
                        self.hideLoader();
                    });
            },
            updateListAdhocs() {
                var self = this;
                EventBus.$on('calendar', (data) => {
                    self.getAdhocs();
                });
            },
            showLoader() {
                EventBus.$emit('SHOW_LOADER', 'selected_adhoc');
            },
            hideLoader() {
                EventBus.$emit('HIDE_LOADER', 'selected_adhoc');
            }
        },
        mounted() {
            this.getAdhocs();
            this.updateListAdhocs();
        }
    }
</script>