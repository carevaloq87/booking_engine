
import Vue from 'vue';
import Multiselect from 'vue-multiselect';
import axios from 'axios';

    Vue.component('multiselect', Multiselect)

new Vue({
    el: '#service-select',

    data: {
        options : [],
        selected: [],
    },
    methods: {
        intitServices: function () {
            var self = this;
            let url = '/services/getByUserServiceProvider';
            let rs_id = document.getElementById('id');
            if(rs_id) {
                rs_id = document.getElementById('id').value;
                if(rs_id.localeCompare('')!=0) {
                    this.initOldServices(rs_id);
                }
            }
            axios.get(url)
                .then(function (response) {
                    self.options = response.data.data;
                })
                .catch(function (error) {
                    console.log(error);
                });
        },
        initOldServices: function (rs_id) {
            $("#contentLoading").modal("show");
            var self = this;
            let url = '/resources/getServices/'+rs_id;
            axios.get(url)
            .then(function (response) {
                self.selected = response.data;
                $("#contentLoading").modal("hide");
            })
            .catch(function (error) {
                console.log(error);
                $("#contentLoading").modal("hide");
            });
        }

    },
    mounted() {
        this.intitServices();
    }

});