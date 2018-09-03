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
            let url = '/resources/getByUserServiceProvider';            
            let rs_id = document.getElementById('id');
            if(rs_id) {
                rs_id = document.getElementById('id').value;                
                if(rs_id.localeCompare('')!=0) {                    
                    this.initOldResources(rs_id);
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
        initOldResources: function (rs_id) {
            var self = this;
            let url = '/services/getResources/'+rs_id;            
            axios.get(url)
            .then(function (response) {                 
                self.selected = response.data;                 
            })
            .catch(function (error) {
                console.log(error);
            });
        }
        
    },
    mounted() {        
        this.intitResources();
    }

});