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
        is_interpreter: false,
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
            // Clean the date to clean the fields
            self.$children[1].date = '';
            if(self.service_selected) {
                $("#contentLoading").modal("show");
                let url='/services/getAvailabilitybyService/'+self.service_selected.id;
                axios.get(url)
                    .then(function (response) {
                        self.service_availability = response.data;
                        self.sendAvailabilityToChild();
                        $("#contentLoading").modal("hide");
                    })
                    .catch(function (error) {
                        console.log(error);
                        $("#contentLoading").modal("hide");
                    });
            }
        },
        onChangeInterpreter: function (e) {
            var self = this;
            self.is_interpreter = false;
            self.$children[1].date = '';
            if (parseInt(e.srcElement.value) === 1) {
                self.is_interpreter = true;
            }
            self.getAvailability();
        },
        sendAvailabilityToChild: function () {
            var self = this;
            self.$children[1].availability=self.service_availability;
            self.$children[1].is_interpreter = self.is_interpreter;
            if (self.service_availability.regular && !self.is_interpreter) {
                self.$children[1].dates_regular = Object.keys(self.service_availability.regular);
            }
            if (self.service_availability.interpreter && self.is_interpreter) {
                self.$children[1].dates_interpreter = Object.keys(self.service_availability.interpreter);
            }

        }


    },
    mounted() {
        this.intitServices();
    }

});