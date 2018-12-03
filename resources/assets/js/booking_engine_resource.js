require('./bootstrap');

Vue.component('resource-days', require('./components/booking_engine/days/resource_days.vue'));
Vue.component('resource-hours', require('./components/booking_engine/hours/resource_hours.vue'));
Vue.component('resource-adhoc', require('./components/booking_engine/adhoc/resource_adhoc.vue'));
Vue.component('selected-adhoc', require('./components/booking_engine/adhoc/selected_resource_adhoc.vue'));

export const data_bus = new Vue();

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
        },
        openSchedule(id) {
            var self = this;
            self.rs_id = id;
            $('#set_hours').modal('show');
        },
        openAdhoc(id) {
            var self = this;
            self.rs_id = id;
            $('#set_adhoc_booking').modal('show');
        }
    },
    mounted() {},
});
