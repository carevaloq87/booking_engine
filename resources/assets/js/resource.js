
import Vue from 'vue';
import Multiselect from 'vue-multiselect';
import axios from 'axios';
import EventBus from './utils/event-bus';

    Vue.component('multiselect', Multiselect)
    Vue.component('loading-modal', require('./components/loading/loading-modal.vue'));

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
            var self = this;
            self.showLoader();
            let url = '/resources/getServices/'+resource_id;
            axios.get(url)
            .then(function (response) {
                self.selected = response.data;
                self.hideLoader();
            })
            .catch(function (error) {
                console.log(error);
                self.hideLoader();
            });
        },
        showLoader() {
            EventBus.$emit('SHOW_LOADER', 'resource');
        },
        hideLoader() {
            EventBus.$emit('HIDE_LOADER', 'resource');
        }

    },
    mounted() {
        this.intitServices();
    }

});