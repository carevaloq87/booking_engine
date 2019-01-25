require('./bootstrap');

Vue.component('resource-days', require('./components/booking_engine/days/resource_days.vue'));
Vue.component('resource-hours', require('./components/booking_engine/hours/resource_hours.vue'));
Vue.component('resource-adhoc', require('./components/booking_engine/adhoc/resource_adhoc.vue'));
Vue.component('selected-adhoc', require('./components/booking_engine/adhoc/selected_resource_adhoc.vue'));
Vue.component('loading-modal', require('./components/loading/loading-modal.vue'));

import EventBus from './utils/event-bus';
new Vue({
    el: '#booking_engine_resource',

    data: {
        rs_id: 0,
    },
    methods: {
        openCalendar(id) {
            var self = this;
            self.rs_id = id;
            $('#set_days').modal('show');
            setTimeout(() => {
                EventBus.$emit('FETCH_RESOURCE_DAYS');
            }, 1000);
        },
        openSchedule(id) {
            var self = this;
            self.rs_id = id;
            $('#set_hours').modal('show');
            setTimeout(() => {
                EventBus.$emit('FETCH_RESOURCE_HOURS');
            }, 1000);
        },
        openAdhoc(id) {
            var self = this;
            self.rs_id = id;
            $('#set_adhoc_booking').modal('show');
        }
    },
    mounted() {
    },
});
