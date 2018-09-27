import Vue from 'vue';
import Multiselect from 'vue-multiselect';
import axios from 'axios';
import * as uiv from 'uiv'

Vue.use(uiv);
Vue.component('multiselect', Multiselect);
Vue.component('booking-date-picker', require('./components/booking_engine/booking/date_picker.vue'));

new Vue({
    el: '#booking-create',

    data: {
        service_options : [],
        service_selected: [],
        service_availability : [],
    },
    methods: {
        intitServices: function () {
            $("#contentLoading").modal("show");
            var self = this;
            let url = '/services/getByUserServiceProvider';
            axios.get(url)
                .then(function (response) {
                    self.service_options = response.data.data;
                    $("#contentLoading").modal("hide");
                })
                .catch(function (error) {
                    console.log(error);
                    $("#contentLoading").modal("hide");
                });
        },
        getAvailability: function () {
            var self = this;
            if(self.service_selected) {
                $("#contentLoading").modal("show");
                let url='/services/getAvailabilitybyService/'+self.service_selected.id;
                axios.get(url)
                    .then(function (response) {
                        self.service_availability = response.data;
                        self.$children[1].availability=self.service_availability;
                        if (self.service_availability.regular) {
                            self.$children[1].dates_regular = Object.keys(self.service_availability.regular);
                        }
                        if (self.service_availability.interpreter) {
                            self.$children[1].dates_interpreter = Object.keys(self.service_availability.interpreter);
                        }
                        $("#contentLoading").modal("hide");
                    })
                    .catch(function (error) {
                        console.log(error);
                        $("#contentLoading").modal("hide");
                    });
            }
        }


    },
    mounted() {
        this.intitServices();
    }

});