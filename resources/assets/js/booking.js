import Vue from 'vue';
import Multiselect from 'vue-multiselect';
import axios from 'axios';
import * as uiv from 'uiv';
import moment from 'moment'
import VueMce from 'vue-mce';

Vue.use(uiv);
Vue.use(VueMce);
Vue.component('multiselect', Multiselect);
Vue.component('booking-date-picker', require('./components/booking_engine/booking/date_picker.vue'));

const config = {
    theme: 'modern',
    menubar: false,
    fontsize_formats: "8px 10px 12px 14px 16px 18px 20px",
    plugins: 'lists paste',
    paste_as_text: true,
    toolbar1: 'formatselect fontsizeselect | bold italic strikethrough forecolor backcolor | link | alignleft aligncenter alignright alignjustify  | numlist bullist outdent indent  | removeformat',
    content_css: [
        '//fonts.googleapis.com/css?family=Lato:300,300i,400,400i',
        '//www.tinymce.com/css/codepen.min.css'
    ],
};

new Vue({
    el: '#booking-create',

    data: {
        config,
        comment_value:'',
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

            // First and last date of the month to calculate availability
            let date = new Date();
            let lastDay = new Date(date.getFullYear() + 1 , 12, 0);
            if(self.service_selected) {
                $("#contentLoading").modal("show");
                let url='/services/getAvailabilitybyService/'+self.service_selected.id;
                axios.get(url,{
                        params: {
                            start:  moment(date).format('YYYY-MM-DD'),
                            end: moment(lastDay).format('YYYY-MM-DD'),
                        }
                    })
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