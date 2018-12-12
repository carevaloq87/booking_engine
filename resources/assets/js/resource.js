
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
            let resource_id = document.getElementById('id');
            if(resource_id.value) {
                resource_id = document.getElementById('id').value;
                url = '/services/getByResourceId/'+resource_id;
                if(resource_id.localeCompare('')!=0) {
                    this.initOldServices(resource_id);
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
        initOldServices: function (resource_id) {
            $("#contentLoading").modal("show");
            var self = this;
            let url = '/resources/getServices/'+resource_id;
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