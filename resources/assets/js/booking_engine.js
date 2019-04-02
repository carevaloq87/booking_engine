require('./bootstrap');

Vue.component('service-days', require('./components/booking_engine/days/service_days.vue'));
Vue.component('service-hours', require('./components/booking_engine/hours/service_hours.vue'));
Vue.component('service-adhoc', require('./components/booking_engine/adhoc/service_adhoc.vue'));
Vue.component('selected-adhoc', require('./components/booking_engine/adhoc/selected_adhoc.vue'));
Vue.component('Calendar', require('./components/calendar/calendar.vue'));
Vue.component('loading-modal', require('./components/loading/loading-modal.vue'));

import EventBus from './utils/event-bus';
import scroll_to_element from './utils/scroll_to_element';

new Vue({
    el: '#booking_engine',

    data: {
        sv_id: 0,
        interpreter_duration: 0,
        regular_duration : 0
    },
    methods: {
        openCalendar(id) {
            var self = this;
            self.sv_id = id;
            $('#set_days').modal('show');
            setTimeout(() => {
                EventBus.$emit('FETCH_DAYS');
            }, 1000);
        },
        openSchedule(id) {
            var self = this;
            self.sv_id = id;
            $('#set_hours').modal('show');
            setTimeout(() => {
                EventBus.$emit('FETCH_HOURS');
            }, 1000);
        },
        openAdhoc(id, regular_duration, interpreter_duration) {
            var self = this;
            self.sv_id = id;
            self.regular_duration = regular_duration,
            self.interpreter_duration = interpreter_duration
            $('#set_adhoc_booking').modal('show');
        },
        /**
         * Allow users to see the details of the service and then close the accordion
         * This will improve how users interact with the information according to our UX
         */
        closeAccordion() {
            let selector = '#accordion_service_details';
            setTimeout(() => {
                document.querySelector(selector).click();
            }, 500);
            jQuery('[data-tooltip="true"]').tooltip();
        },
        scroll_to_headers() {
            scroll_to_element(".m-accordion__item-head");
        }
    },
    mounted() {
        this.closeAccordion();
        this.scroll_to_headers();
    },
});
