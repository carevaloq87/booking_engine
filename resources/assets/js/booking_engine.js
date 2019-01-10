require('./bootstrap');

Vue.component('service-days', require('./components/booking_engine/days/service_days.vue'));
Vue.component('service-hours', require('./components/booking_engine/hours/service_hours.vue'));
Vue.component('service-adhoc', require('./components/booking_engine/adhoc/service_adhoc.vue'));
Vue.component('selected-adhoc', require('./components/booking_engine/adhoc/selected_adhoc.vue'));
Vue.component('Calendar', require('./components/calendar/calendar.vue'));
Vue.component('loading-modal', require('./components/loading/loading-modal.vue'));

import EventBus from './utils/event-bus';
new Vue({
    el: '#booking_engine',

    data: {
        sv_id: 0,
    },
    methods: {
        openCalendar(id) {
            var self = this;
            self.sv_id = id;
            $('#set_days').modal('show');
            EventBus.$emit('FETCH_DAYS');
        },
        openSchedule(id) {
            var self = this;
            self.sv_id = id;
            $('#set_hours').modal('show');
            EventBus.$emit('FETCH_HOURS');
        },
        openAdhoc(id) {
            var self = this;
            self.sv_id = id;
            $('#set_adhoc_booking').modal('show');
        }
    },
    mounted() {
    },
});
