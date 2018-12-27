import Vue from 'vue';
import axios from 'axios';
import Datepicker from 'vuejs-datepicker';


new Vue({
    el: '#holiday_form',
    components : {
        Datepicker
    },
    data: {
        date:null,
        state: {
            disabledDates: {
                to: new Date(),
            }
        },
        selected: [],
    },
    methods: {
        getDate: function () {
            var self = this;
            let holiday_id = document.getElementById('id');
            if(holiday_id.value) {
                $("#contentLoading").modal("show");
                holiday_id = document.getElementById('id').value;
                let url = '/holidays/getDateById/'+ holiday_id;
                axios.get(url)
                .then(function (response) {
                    self.date = response.data;
                    $('#contentLoading').on('shown.bs.modal', function (e) {
                        $("#contentLoading").modal('hide');
                    })
                })
                .catch(function (error) {
                    console.log(error);
                    $('#contentLoading').on('shown.bs.modal', function (e) {
                        $("#contentLoading").modal('hide');
                    })
                });
            }
        },
    },
    mounted() {
        this.getDate();
    }

});