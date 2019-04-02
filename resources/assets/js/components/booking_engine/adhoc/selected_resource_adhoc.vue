<template>
    <div class="justify-content-md-center">
        <div class="col-xs-12">
            <span>Appointments<small> Remember that the information represents the hours that the resource is unavailable</small></span>
            <div>
                <ul class="adhoc-hours" v-for="(hours, day) in adhocs.regular" :key="day">
                    <p><i class="fa fa-times-circle" @click="deleteAdhoc(day + '||' + 0)"></i> <strong>{{day}}</strong>&nbsp;<i id="adhoc_details" class="fa fa-info-circle" :title="hours.options.details" aria-hidden="true" data-container="body"></i></p>
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
                var self = this;
                let url = '/calendar/resource/adhoc/delete';

                self.showLoader();
                axios['post'](url, {resource_id: self.resource, adhoc: value})
                    .then(response => {
                        self.getAdhocs();
                        self.hideLoader();
                    })
                    .catch(error => {
                        self.hideLoader();
                    });
            },
            getAdhocs() {
                var self = this;
                let url = '/calendar/resource/adhoc/' + self.resource;

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
                EventBus.$on('adhoc', (data) => {
                    self.getAdhocs();
                });
            },
            showLoader() {
                EventBus.$emit('SHOW_LOADER', 'selected_resource_adhoc');
            },
            hideLoader() {
                EventBus.$emit('HIDE_LOADER', 'selected_resource_adhoc');
            }
        },
        mounted() {
            this.getAdhocs();
            this.updateListAdhocs();
        }
    }
</script>