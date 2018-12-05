import Vue from 'vue';
import axios from 'axios';

new Vue({
    el: '#set_sp',

    data: {
        service_provider_id : null,
        name:null
    },
    methods: {
        setServiceProvider: function () {
            var self = this;
            let url = '/service_provider/set_sp';
            if(self.service_provider_id) {
                $("#contentLoading").modal("show");
                axios.post(url , {
                    service_provider_id: self.service_provider_id,
                    name : self.name
                    })
                    .then(function (response) {
                        $("#contentLoading").modal("hide");
                        alert("Your information was updated");
                        window.location.href = "/services";
                    })
                    .catch(function (error) {
                        console.log(error);
                        $("#contentLoading").modal("hide");
                    });
            } else {
                alert("Please select an office/program");
            }
        }
    },
    mounted() {

    }

});