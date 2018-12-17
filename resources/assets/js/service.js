import Vue from 'vue';
import Multiselect from 'vue-multiselect';
import axios from 'axios';

    Vue.component('multiselect', Multiselect)

new Vue({
    el: '#resources-select',

    data: {
        options : [],
        selected: [],
    },
    methods: {
        intitResources: function () {
            var self = this;
            let service_id = document.getElementById('id');
            let url = '/resources/getByUserServiceProvider/';
            if(service_id.value) {
                service_id = document.getElementById('id').value;
                url = '/resources/getByServiceId/'+service_id;
                if(service_id.localeCompare('')!=0) {
                    this.initOldResources(service_id);
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
        initOldResources: function (service_id) {
            $("#contentLoading").modal("show");
            var self = this;
            let url = '/services/getResources/'+service_id;
            axios.get(url)
            .then(function (response) {
                self.selected = response.data;
                $('#contentLoading').on('shown.bs.modal', function (e) {
                    $("#contentLoading").modal('hide');
                })
            })
            .catch(function (error) {
                console.log(error);
                $("#contentLoading").modal("hide");
            });
        },
    },
    mounted() {
        this.intitResources();
    }

});